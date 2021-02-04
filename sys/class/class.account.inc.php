<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class account extends db_connect
{

    private $id = 0;
    private $allow_multi_accounts = true;

    public function __construct($dbo = NULL, $accountId = 0)
    {

        parent::__construct($dbo);

        $this->setId($accountId);
    }

    public function signup($username, $nim, $tgllahir, $fullname, $password, $email, $language = 'en')
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $helper = new helper($this->db);

        if (!helper::isCorrectLogin($username)) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 0,
                            "error_description" => "Incorrect login");

            return $result;
        }

        if ($helper->isLoginExists($username)) {

            $result = array("error" => true,
                            "error_code" => ERROR_LOGIN_TAKEN,
                            "error_type" => 0,
                            "error_description" => "Login already taken");

            return $result;
        }

        if (empty($fullname)) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 3,
                            "error_description" => "Empty user full name");

            return $result;
        }

        if (!helper::isCorrectPassword($password)) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 1,
                            "error_description" => "Incorrect password");

            return $result;
        }

        if (!helper::isCorrectEmail($email)) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 2,
                            "error_description" => "Wrong email");

            return $result;
        }

        if ($helper->isEmailExists($email)) {

            $result = array("error" => true,
                            "error_code" => ERROR_EMAIL_TAKEN,
                            "error_type" => 2,
                            "error_description" => "User with this email is already registered");

            return $result;
        }

        // Birthday
        $byear = date('Y', strtotime($tgllahir));
        $bmonth = date('m', strtotime($tgllahir));
        $bday = date('d', strtotime($tgllahir));

        $salt = helper::generateSalt(3);
        $passw_hash = md5(md5($password).$salt);
        $currentTime = time();

        $ip_addr = helper::ip_addr();

        $settings = new settings($this->db);
        $app_settings = $settings->get();
        unset($settings);

        if ($app_settings['allowMultiAccountsFunction']['intValue'] != 1) {

            if ($this->checkMultiAccountsByIp($ip_addr)) {

                $result = array(
                    "error" => true,
                    "error_code" => 500,
                    "error_type" => 4,
                    "error_description" => "User with this ip is already registered");

                return $result;
            }
        }

        $accountState = ACCOUNT_STATE_ENABLED;

        $admob = $app_settings['admob']['intValue'];
        $default_allow_messages = $app_settings['defaultAllowMessages']['intValue'];

        $stmt = $this->db->prepare("INSERT INTO users (state, login, fullname, nim, tgllahir, passw, email, salt, regtime, admob, last_authorize, language, allowMessages, ip_addr, bYear, bMonth, bDay) value (:state, :username, :fullname, :nim, :tgllahir, :password, :email, :salt, :createAt, :admob, :last_authorize, :language, :allowMessages, :ip_addr, :bYear, :bMonth, :bDay)");
        $stmt->bindParam(":state", $accountState, PDO::PARAM_INT);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":nim", $nim, PDO::PARAM_STR);
        $stmt->bindParam(":tgllahir", $tgllahir, PDO::PARAM_STR);
        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
        $stmt->bindParam(":password", $passw_hash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":salt", $salt, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":admob", $admob, PDO::PARAM_INT);
        $stmt->bindParam(":last_authorize", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":language", $language, PDO::PARAM_STR);
        $stmt->bindParam(":allowMessages", $default_allow_messages, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":bYear", $byear, PDO::PARAM_INT);
        $stmt->bindParam(":bMonth", $bmonth, PDO::PARAM_INT);
        $stmt->bindParam(":bDay", $bday, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $this->setId($this->db->lastInsertId());

            if (BONUS_SIGNUP != 0) {

                $this->setBalance(BONUS_SIGNUP);

                $payments = new payments($this->db);
                $payments->setRequestFrom($this->getId());
                $payments->create(PA_BUY_REGISTRATION_BONUS, PT_BONUS, BONUS_SIGNUP);
                unset($payments);
            }

            $result = array("error" => false,
                            'accountId' => $this->id,
                            'username' => $username,
                            'password' => $password,
                            'error_code' => ERROR_SUCCESS,
                            'error_description' => 'SignUp Success!');

            return $result;
        }

        return $result;
    }

    public function signin($username, $password)
    {
        $access_data = array('error' => true);

        $username = helper::clearText($username);
        $password = helper::clearText($password);

        $stmt = $this->db->prepare("SELECT salt FROM users WHERE login = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();
            $passw_hash = md5(md5($password).$row['salt']);

            $stmt2 = $this->db->prepare("SELECT id, state FROM users WHERE login = (:username) AND passw = (:password) LIMIT 1");
            $stmt2->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt2->bindParam(":password", $passw_hash, PDO::PARAM_STR);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0) {

                $row2 = $stmt2->fetch();

                $access_data = array("error" => false,
                                     "error_code" => ERROR_SUCCESS,
                                     "accountId" => $row2['id']);
            }
        }

        return $access_data;
    }

    public function logout($accountId, $accessToken)
    {
        $auth = new auth($this->db);
        $auth->remove($accountId, $accessToken);
    }

    public function checkMultiAccountsByIp($ip_addr)
    {

        if (!$this->allow_multi_accounts) {

            $createAt = time() - 2 * 3600; // 2 hours

            $stmt = $this->db->prepare("SELECT id FROM users WHERE ip_addr = (:ip_addr) AND regtime > (:regtime) LIMIT 1");
            $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
            $stmt->bindParam(":regtime", $createAt, PDO::PARAM_INT);

            if ($stmt->execute()) {

                if ($stmt->rowCount() > 0) {

                    return true;
                }
            }
        }

        return false;
    }

    public function setPassword($password, $newPassword)
    {
        $result = array('error' => true,
                        'error_code' => ERROR_UNKNOWN);

        if (!helper::isCorrectPassword($password)) {

            return $result;
        }

        if (!helper::isCorrectPassword($newPassword)) {

            return $result;
        }

        $stmt = $this->db->prepare("SELECT salt FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();
            $passw_hash = md5(md5($password).$row['salt']);

            $stmt2 = $this->db->prepare("SELECT id FROM users WHERE id = (:accountId) AND passw = (:password) LIMIT 1");
            $stmt2->bindParam(":accountId", $this->id, PDO::PARAM_INT);
            $stmt2->bindParam(":password", $passw_hash, PDO::PARAM_STR);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0) {

                $this->newPassword($newPassword);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS);
            }
        }

        return $result;
    }

    public function newPassword($password)
    {
        $newSalt = helper::generateSalt(3);
        $newHash = md5(md5($password).$newSalt);

        $stmt = $this->db->prepare("UPDATE users SET passw = (:newHash), salt = (:newSalt) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":newHash", $newHash, PDO::PARAM_STR);
        $stmt->bindParam(":newSalt", $newSalt, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function setSex($sex)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET sex = (:sex) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":sex", $sex, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getSex()
    {
        $stmt = $this->db->prepare("SELECT sex FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['sex'];
        }

        return 0;
    }

    public function setAge($age)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET age = (:age) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":age", $age, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                'error' => false,
                'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getAge()
    {
        $stmt = $this->db->prepare("SELECT age FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['age'];
        }

        return 18;
    }

    public function setAllowShowMyAgeAndGender($allowShowMyAgeAndGender)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET allowShowMyAgeAndGender = (:allowShowMyAgeAndGender) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowShowMyAgeAndGender", $allowShowMyAgeAndGender, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                'error' => false,
                'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getAllowShowMyAgeAndGender()
    {
        $stmt = $this->db->prepare("SELECT age FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['allowShowMyAgeAndGender'];
        }

        return 0;
    }

    public function setBirth($year, $month, $day)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET bYear = (:bYear), bMonth = (:bMonth), bDay = (:bDay) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":bYear", $year, PDO::PARAM_INT);
        $stmt->bindParam(":bMonth", $month, PDO::PARAM_INT);
        $stmt->bindParam(":bDay", $day, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setAdmob($admob)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET admob = (:mode) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":mode", $admob, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getAdmob()
    {
        $stmt = $this->db->prepare("SELECT admob FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['admob'];
        }

        return 0;
    }

    public function setGhost($ghost)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET ghost = (:ghost) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":ghost", $ghost, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getGhost()
    {
        $stmt = $this->db->prepare("SELECT ghost FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['ghost'];
        }

        return 0;
    }

    public function setPro($pro)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $pro_create_at = 0;

        if ($pro != 0) {

            $pro_create_at = time();
        }

        $stmt = $this->db->prepare("UPDATE users SET pro = (:pro), pro_create_at = (:pro_create_at) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":pro", $pro, PDO::PARAM_INT);
        $stmt->bindParam(":pro_create_at", $pro_create_at, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getPro()
    {
        $stmt = $this->db->prepare("SELECT pro FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['pro'];
        }

        return 0;
    }

    public function setFacebookId($fb_id)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET fb_id = (:fb_id) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":fb_id", $fb_id, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getFacebookId()
    {
        $stmt = $this->db->prepare("SELECT fb_id FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['fb_id'];
        }

        return 0;
    }

    public function setFacebookPage($fb_page)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET fb_page = (:fb_page) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":fb_page", $fb_page, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getFacebookPage()
    {
        $stmt = $this->db->prepare("SELECT fb_page FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['fb_page'];
        }

        return '';
    }

    public function setInstagramPage($instagram_page)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET my_page = (:my_page) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":my_page", $instagram_page, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getInstagramPage()
    {
        $stmt = $this->db->prepare("SELECT my_page FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['my_page'];
        }

        return '';
    }

    public function setEmail($email)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $helper = new helper($this->db);

        if (!helper::isCorrectEmail($email)) {

            return $result;
        }

        if ($helper->isEmailExists($email)) {

            return $result;
        }

        $stmt = $this->db->prepare("UPDATE users SET email = (:email) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getEmail()
    {
        $stmt = $this->db->prepare("SELECT email FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['email'];
        }

        return '';
    }

    public function setUsername($username)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $helper = new helper($this->db);

        if (!helper::isCorrectLogin($username)) {

            return $result;
        }

        if ($helper->isLoginExists($username)) {

            return $result;
        }

        $stmt = $this->db->prepare("UPDATE users SET login = (:login) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":login", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getUsername()
    {
        $stmt = $this->db->prepare("SELECT login FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['login'];
        }

        return '';
    }

    public function setLocation($location)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET country = (:country) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":country", $location, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getLocation()
    {
        $stmt = $this->db->prepare("SELECT country FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['country'];
        }

        return '';
    }

    public function setGeoLocation($lat, $lng)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET lat = (:lat), lng = (:lng) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":lat", $lat, PDO::PARAM_STR);
        $stmt->bindParam(":lng", $lng, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getGeoLocation()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT lat, lng FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS,
                            'lat' => $row['lat'],
                            'lng' => $row['lng']);
        }

        return $result;
    }

    public function setStatus($status)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET status = (:status) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setBukuAlumni($buku_alumni)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET buku_alumni = (:buku_alumni) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":buku_alumni", $buku_alumni, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getStatus()
    {
        $stmt = $this->db->prepare("SELECT status FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['status'];
        }

        return '';
    }

    public function restorePointCreate($email, $clientId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $restorePointInfo = $this->restorePointInfo();

        if ($restorePointInfo['error'] === false) {

            return $restorePointInfo;
        }

        $currentTime = time();	// Current time

        $u_agent = helper::u_agent();
        $ip_addr = helper::ip_addr();

        $hash = md5(uniqid(rand(), true));

        $stmt = $this->db->prepare("INSERT INTO restore_data (accountId, hash, email, clientId, createAt, u_agent, ip_addr) value (:accountId, :hash, :email, :clientId, :createAt, :u_agent, :ip_addr)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":hash", $hash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":clientId", $clientId, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $this->id,
                            'hash' => $hash,
                            'email' => $email);
        }

        return $result;
    }

    public function restorePointInfo()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM restore_data WHERE accountId = (:accountId) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $row['accountId'],
                            'hash' => $row['hash'],
                            'email' => $row['email']);
        }

        return $result;
    }

    public function restorePointRemove()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $removeAt = time();

        $stmt = $this->db->prepare("UPDATE restore_data SET removeAt = (:removeAt) WHERE accountId = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $removeAt, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function deactivation($password)
    {

        $result = array('error' => true,
                        'error_code' => ERROR_UNKNOWN);

        if (!helper::isCorrectPassword($password)) {

            return $result;
        }

        $stmt = $this->db->prepare("SELECT salt FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();
            $passw_hash = md5(md5($password) . $row['salt']);

            $stmt2 = $this->db->prepare("SELECT id FROM users WHERE id = (:accountId) AND passw = (:password) LIMIT 1");
            $stmt2->bindParam(":accountId", $this->id, PDO::PARAM_INT);
            $stmt2->bindParam(":password", $passw_hash, PDO::PARAM_STR);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0) {

                $this->setState(ACCOUNT_STATE_DISABLED);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS);
            }
        }

        return $result;
    }

    public function setLanguage($language)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET language = (:language) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":language", $language, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getLanguage()
    {
        $stmt = $this->db->prepare("SELECT language FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['language'];
        }

        return 'en';
    }

    public function setVerify($verify)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET verify = (:verify) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":verify", $verify, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setFullname($fullname)
    {
        if (strlen($fullname) == 0) {

            return;
        }

        $stmt = $this->db->prepare("UPDATE users SET fullname = (:fullname) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function setBalance($balance)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET balance = (:balance) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":balance", $balance, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getBalance()
    {
        $stmt = $this->db->prepare("SELECT balance FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['balance'];
        }

        return 0;
    }

    public function setAllowMessages($allowMessages)
    {
        $stmt = $this->db->prepare("UPDATE users SET allowMessages = (:allowMessages) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowMessages", $allowMessages, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllowMessages()
    {
        $stmt = $this->db->prepare("SELECT allowMessages FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['allowMessages'];
        }

        return 0;
    }

    public function setAllowComments($allowComments)
    {
        $stmt = $this->db->prepare("UPDATE users SET allowComments = (:allowComments) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowComments", $allowComments, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllowComments()
    {
        $stmt = $this->db->prepare("SELECT allowComments FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['allowComments'];
        }

        return 0;
    }

    public function setAllowGalleryComments($allowGalleryComments)
    {
        $stmt = $this->db->prepare("UPDATE users SET allowGalleryComments = (:allowGalleryComments) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowGalleryComments", $allowGalleryComments, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllowGalleryComments()
    {
        $stmt = $this->db->prepare("SELECT allowGalleryComments FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['allowGalleryComments'];
        }

        return 0;
    }

    public function setPrivacySettings($allowShowMyGallery, $allowShowMyGifts, $allowShowMyFriends, $allowShowMyInfo)
    {
        $stmt = $this->db->prepare("UPDATE users SET allowShowMyGallery = (:allowShowMyGallery), allowShowMyGifts = (:allowShowMyGifts), allowShowMyFriends = (:allowShowMyFriends), allowShowMyInfo = (:allowShowMyInfo)  WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowShowMyGallery", $allowShowMyGallery, PDO::PARAM_INT);
        $stmt->bindParam(":allowShowMyGifts", $allowShowMyGifts, PDO::PARAM_INT);
        $stmt->bindParam(":allowShowMyFriends", $allowShowMyFriends, PDO::PARAM_INT);
        $stmt->bindParam(":allowShowMyInfo", $allowShowMyInfo, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getPrivacySettings()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT allowShowMyGallery, allowShowMyGifts, allowShowMyFriends, allowShowMyInfo FROM users WHERE id = (:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "allowShowMyGallery" => $row['allowShowMyGallery'],
                            "allowShowMyGifts" => $row['allowShowMyGifts'],
                            "allowShowMyFriends" => $row['allowShowMyFriends'],
                            "allowShowMyInfo" => $row['allowShowMyInfo']);
        }

        return $result;
    }

    public function setState($accountState)
    {

        $stmt = $this->db->prepare("UPDATE users SET state = (:accountState) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":accountState", $accountState, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getState()
    {
        $stmt = $this->db->prepare("SELECT state FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['state'];
        }

        return 0;
    }

    public function setMood($mood)
    {
        $result = array('error' => false,
                        'error_code' => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET mood = (:mood) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":mood", $mood, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function setLastActive()
    {
        $time = time();

        $stmt = $this->db->prepare("UPDATE users SET last_authorize = (:last_authorize) WHERE id = (:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":last_authorize", $time, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function get()
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_ACCOUNT_ID
        );

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $notifications_count = 0;
                $messages_count = 0;
                $guests_count = 0;

                $online = false;

                $current_time = time();

                if ($row['last_authorize'] != 0 && $row['last_authorize'] > ($current_time - 15 * 60)) {

                    $online = true;
                }

                $time = new language($this->db);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "access_level" => $row['access_level'],
                                "account_type" => $row['account_type'],
                                "mood" => $row['mood'],
                                "admob" => $row['admob'],
                                "ghost" => $row['ghost'],
                                "pro" => $row['pro'],
                                "pro_create_at" => $row['pro_create_at'],
                                "gcm" => $row['gcm'],
                                "balance" => $row['balance'],
                                "fb_id" => $row['fb_id'],
                                "state" => $row['state'],
                                "regtime" => $row['regtime'],
                                "ip_addr" => $row['ip_addr'],
                                "username" => $row['login'],
                                "fullname" => stripcslashes($row['fullname']),
                                "location" => stripcslashes($row['country']),
                                "status" => stripcslashes($row['status']),
                                "buku_alumni" => stripcslashes($row['buku_alumni']),
                                "fb_page" => stripcslashes($row['fb_page']),
                                "instagram_page" => stripcslashes($row['my_page']),
                                "verify" => $row['verify'],
                                "verified" => $row['verify'],
                                "email" => $row['email'],
                                "sex" => $row['sex'],
                                "age" => $row['age'],
                                "year" => $row['bYear'],
                                "month" => $row['bMonth'],
                                "day" => $row['bDay'],
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "language" => $row['language'],
                                "lowPhotoUrl" => $row['lowPhotoUrl'],
                                "normalPhotoUrl" => $row['normalPhotoUrl'],
                                "bigPhotoUrl" => $row['normalPhotoUrl'],
                                "coverUrl" => $row['normalCoverUrl'],
                                "originCoverUrl" => $row['originCoverUrl'],
                                "allowPhotosComments" => $row['allowPhotosComments'],
                                "allowVideoComments" => $row['allowVideoComments'],
                                "allowGalleryComments" => $row['allowGalleryComments'],
                                "allowComments" => $row['allowComments'],
                                "allowMessages" => $row['allowMessages'],
                                "allowLikesGCM" => $row['allowLikesGCM'],
                                "allowCommentsGCM" => $row['allowCommentsGCM'],
                                "allowFollowersGCM" => $row['allowFollowersGCM'],
                                "allowGiftsGCM" => $row['allowGiftsGCM'],
                                "allowMessagesGCM" => $row['allowMessagesGCM'],
                                "allowCommentReplyGCM" => $row['allowCommentReplyGCM'],
                                "allowShowMyInfo" => $row['allowShowMyInfo'],
                                "allowShowMyVideos" => $row['allowShowMyVideos'],
                                "allowShowMyFriends" => $row['allowShowMyFriends'],
                                "allowShowMyPhotos" => $row['allowShowMyPhotos'],
                                "allowShowMyGallery" => $row['allowShowMyGallery'],
                                "allowShowMyGifts" => $row['allowShowMyGifts'],
                                "allowShowMyAgeAndGender" => $row['allowShowMyAgeAndGender'],
                                "lastNotifyView" => $row['last_notify_view'],
                                "lastGuestsView" => $row['last_guests_view'],
                                "lastFriendsView" => $row['last_friends_view'],
                                "lastFeedView" => $row['last_feed_view'],
                                "lastAuthorize" => $row['last_authorize'],
                                "lastAuthorizeDate" => date("Y-m-d H:i:s", $row['last_authorize']),
                                "lastAuthorizeTimeAgo" => $time->timeAgo($row['last_authorize']),
                                "online" => $online,
                                "postsCount" => $row['posts_count'],
                                "friendsCount" => $row['friends_count'],
                                "guestsCount" => $guests_count,
                                "notificationsCount" => $notifications_count,
                                "messagesCount" => $messages_count,
                                "photoModerateAt" => $row['photoModerateAt'],
                                "photoModerateUrl" => $row['photoModerateUrl'],
                                "photoPostModerateAt" => $row['photoPostModerateAt'],
                                "coverModerateAt" => $row['coverModerateAt'],
                                "coverModerateUrl" => $row['coverModerateUrl'],
                                "coverPostModerateAt" => $row['coverPostModerateAt']);
                unset($time);
            }
        }

        return $result;
    }

    public function updateCounters()
    {

        $galleryItemsCount = $this->getGalleryItemsCount();
        $postsCount = $this->getPostsCount();
        $giftsCount = $this->getGiftsCount();
        $likesCount = $this->getLikesCount();
        $followingCount = $this->getFollowingCount();
        $followersCount = $this->getFollowersCount();
        $friendsCount = $this->getFriendsCount();

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $stmt = $this->db->prepare("UPDATE users SET posts_count = (:posts_count), gallery_items_count = (:gallery_items_count), gifts_count = (:gifts_count), likes_count = (:likes_count), friends_count = (:friends_count), following_count = (:following_count), followers_count = (:followers_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":posts_count", $postsCount, PDO::PARAM_INT);
        $stmt->bindParam(":gallery_items_count", $galleryItemsCount, PDO::PARAM_INT);
        $stmt->bindParam(":gifts_count", $giftsCount, PDO::PARAM_INT);
        $stmt->bindParam(":likes_count", $likesCount, PDO::PARAM_INT);
        $stmt->bindParam(":friends_count", $friendsCount, PDO::PARAM_INT);
        $stmt->bindParam(":following_count", $followingCount, PDO::PARAM_INT);
        $stmt->bindParam(":followers_count", $followersCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                'error' => false,
                'error_code' => ERROR_SUCCESS
            );
        }

        return $result;
    }

    public function getFriendsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM friends WHERE friendTo = (:profileId) AND removeAt = 0");
        $stmt->bindParam(":profileId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setFriendsCount($friendsCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET friends_count = (:friends_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":friends_count", $friendsCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getGalleryItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gallery WHERE fromUserId = (:fromUserId) AND removeAt = 0");
        $stmt->bindParam(":fromUserId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setGiftsCount($giftsCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET gifts_count = (:gifts_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":gifts_count", $giftsCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getGiftsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gifts WHERE giftTo = (:accountId) AND removeAt = 0");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setFollowingCount($followingCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET following_count = (:following_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":following_count", $followingCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getFollowingCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follower = (:followerId) AND follow_type = 0");
        $stmt->bindParam(":followerId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setFollowersCount($followersCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET followers_count = (:followers_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":followers_count", $followersCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getFollowersCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follow_to = (:follow_to)");
        $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setPostsCount($postsCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET posts_count = (:posts_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":posts_count", $postsCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getPostsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND groupId = 0 AND removeAt = 0");
        $stmt->bindParam(":fromUserId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function setLikesCount($likesCount)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET likes_count = (:likes_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":likes_count", $likesCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getLikesCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM likes WHERE toUserId = (:toUserId) AND removeAt = 0");
        $stmt->bindParam(":toUserId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setLastGuestsView()
    {
        $time = time();

        $stmt = $this->db->prepare("UPDATE users SET last_guests_view = (:last_guests_view) WHERE id = (:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":last_guests_view", $time, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getLastGuestsView()
    {
        $stmt = $this->db->prepare("SELECT last_guests_view FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                return $row['last_guests_view'];
            }
        }

        $time = time();

        return $time;
    }

    public function setLastFriendsView()
    {
        $time = time();

        $stmt = $this->db->prepare("UPDATE users SET last_friends_view = (:last_friends_view) WHERE id = (:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":last_friends_view", $time, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getLastFriendsView()
    {
        $stmt = $this->db->prepare("SELECT last_friends_view FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                return $row['last_friends_view'];
            }
        }

        $time = time();

        return $time;
    }

    public function edit($fullname)
    {
        $result = array("error" => true);

        $stmt = $this->db->prepare("UPDATE users SET fullname = (:fullname) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false);
        }

        return $result;
    }

    public function setType($type)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET account_type = (:account_type) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":account_type", $type, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getType()
    {
        $stmt = $this->db->prepare("SELECT account_type FROM users WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['account_type'];
        }

        return 0;
    }

    public function setPhoto($array_data)
    {
        $stmt = $this->db->prepare("UPDATE users SET originPhotoUrl = (:originPhotoUrl), normalPhotoUrl = (:normalPhotoUrl), bigPhotoUrl = (:bigPhotoUrl), lowPhotoUrl = (:lowPhotoUrl), photoModerateUrl = '' WHERE id = (:account_id)");
        $stmt->bindParam(":account_id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":originPhotoUrl", $array_data['originPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":normalPhotoUrl", $array_data['normalPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":bigPhotoUrl", $array_data['bigPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":lowPhotoUrl", $array_data['lowPhotoUrl'], PDO::PARAM_STR);

        $stmt->execute();
    }

    public function setCover($array_data)
    {
        $stmt = $this->db->prepare("UPDATE users SET originCoverUrl = (:originCoverUrl), normalCoverUrl = (:normalCoverUrl), coverModerateUrl = '' WHERE id = (:account_id)");
        $stmt->bindParam(":account_id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":originCoverUrl", $array_data['originCoverUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":normalCoverUrl", $array_data['normalCoverUrl'], PDO::PARAM_STR);

        $stmt->execute();
    }

    public function setCoverPosition($position)
    {
        $stmt = $this->db->prepare("UPDATE users SET coverPosition = (:coverPosition) WHERE id = (:account_id)");
        $stmt->bindParam(":account_id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":coverPosition", $position, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function setBackgroundUrl($url)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET originCoverUrl = (:originCoverUrl), normalCoverUrl = (:normalCoverUrl) WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":originCoverUrl", $url, PDO::PARAM_STR);
        $stmt->bindParam(":normalCoverUrl", $url, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getBackgroundUrl()
    {
        $stmt = $this->db->prepare("SELECT originCoverUrl FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                return $row['originCoverUrl'];
            }
        }

        return '';
    }

    public function getAccessLevel($user_id)
    {
        $stmt = $this->db->prepare("SELECT access_level FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                return $row['access_level'];
            }
        }

        return 0;
    }

    public function setAccessLevel($access_level)
    {
        $stmt = $this->db->prepare("UPDATE users SET access_level = (:access_level) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":access_level", $access_level, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function setId($accountId)
    {
        $this->id = $accountId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAnonymousQuestions($anonymousQuestions)
    {
        $result = array("error" => true);

        $stmt = $this->db->prepare("UPDATE users SET anonymousQuestions = (:anonymousQuestions) WHERE id = (:accountId) LIMIT 1");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":anonymousQuestions", $anonymousQuestions, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->execute()) {

            $result = array("error" => false);
        }

        return $result;
    }
}

