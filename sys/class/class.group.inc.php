<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class group extends db_connect
{

    private $id = 0;
    private $requestFrom = 0;

    public function __construct($dbo = NULL, $groupId = 0)
    {

        parent::__construct($dbo);

        $this->setId($groupId);
    }

    public function create($group_name, $group_fullname, $group_category, $group_desc, $group_site, $group_location, $year, $month, $day, $group_allow_posts, $group_allow_comments)
    {

        $result = array("error" => true);

        $helper = new helper($this->db);

        if (!helper::isCorrectLogin($group_name)) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 0,
                            "error_description" => "Incorrect Group Name (Login)");

            return $result;
        }

        if ($helper->isLoginExists($group_name)) {

            $result = array("error" => true,
                            "error_code" => ERROR_LOGIN_TAKEN,
                            "error_type" => 1,
                            "error_description" => "Login (Group Name) already taken");

            return $result;
        }

        if (strlen($group_fullname) == 0) {

            $result = array("error" => true,
                            "error_code" => ERROR_UNKNOWN,
                            "error_type" => 3,
                            "error_description" => "Empty group full name");

            return $result;
        }

        $currentTime = time();

        $ip_addr = helper::ip_addr();

        $accountState = ACCOUNT_STATE_ENABLED;
        $accountType = ACCOUNT_TYPE_GROUP;

        $stmt = $this->db->prepare("INSERT INTO users (state, login, fullname, account_author, account_type, account_category, status, country, my_page, allowComments, allowPosts, bYear, bMonth, bDay, regtime, ip_addr) value (:state, :username, :fullname, :account_author, :account_type, :account_category, :status, :country, :my_page, :allowComments, :allowPosts, :bYear, :bMonth, :bDay, :createAt, :ip_addr)");
        $stmt->bindParam(":state", $accountState, PDO::PARAM_INT);
        $stmt->bindParam(":username", $group_name, PDO::PARAM_STR);
        $stmt->bindParam(":fullname", $group_fullname, PDO::PARAM_STR);
        $stmt->bindParam(":account_author", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":account_type", $accountType, PDO::PARAM_INT);
        $stmt->bindParam(":account_category", $group_category, PDO::PARAM_INT);
        $stmt->bindParam(":status", $group_desc, PDO::PARAM_STR);
        $stmt->bindParam(":country", $group_location, PDO::PARAM_STR);
        $stmt->bindParam(":my_page", $group_site, PDO::PARAM_STR);
        $stmt->bindParam(":allowComments", $group_allow_comments, PDO::PARAM_INT);
        $stmt->bindParam(":allowPosts", $group_allow_posts, PDO::PARAM_INT);
        $stmt->bindParam(":bYear", $year, PDO::PARAM_INT);
        $stmt->bindParam(":bMonth", $month, PDO::PARAM_INT);
        $stmt->bindParam(":bDay", $day, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $this->setId($this->db->lastInsertId());

            $this->setLanguage("en");

            $result = array("error" => false,
                            'groupId' => $this->id,
                            'username' => $group_name,
                            'fullname' => $group_fullname,
                            'error_code' => ERROR_SUCCESS,
                            'error_description' => 'Group Create Success!');

            return $result;
        }

        return $result;
    }

    public function addFollower($follower_id, $follow_type = 1)
    {
        if ($this->is_follower_exists($follower_id)) {

            $stmt = $this->db->prepare("DELETE FROM profile_followers WHERE follower = (:follower) AND follow_to = (:follow_to)");
            $stmt->bindParam(":follower", $follower_id, PDO::PARAM_INT);
            $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "follow" => false,
                            "followersCount" => $this->getFollowersCount());

            $notify = new notify($this->db);
            $notify->removeNotify($this->id, $follower_id, NOTIFY_TYPE_FOLLOWER, 0);
            unset($notify);

        } else {

            $create_at = time();

            $stmt = $this->db->prepare("INSERT INTO profile_followers (follower, follow_to, follow_type, create_at) value (:follower, :follow_to, :follow_type, :create_at)");
            $stmt->bindParam(":follower", $follower_id, PDO::PARAM_INT);
            $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":follow_type", $follow_type, PDO::PARAM_INT);
            $stmt->bindParam(":create_at", $create_at, PDO::PARAM_INT);

            $stmt->execute();

            $blacklist = new blacklist($this->db);
            $blacklist->setRequestFrom($this->id);

            if (!$blacklist->isExists($follower_id) && $follow_type == 0) {

                $notify = new notify($this->db);
                $notify->createNotify($this->id, $follower_id, NOTIFY_TYPE_FOLLOWER, 0);
                unset($notify);
            }

            unset($blacklist);

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "follow" => true,
                            "followersCount" => $this->getFollowersCount());
        }

        $this->updateCounters();

        return $result;
    }

    public function is_follower_exists($follower_id)
    {

        $stmt = $this->db->prepare("SELECT id FROM profile_followers WHERE follower = (:follower) AND follow_to = (:follow_to) LIMIT 1");
        $stmt->bindParam(":follower", $follower_id, PDO::PARAM_INT);
        $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            return true;
        }

        return false;
    }

    public function getFollowersCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follow_to = (:follow_to)");
        $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
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

    public function setWebPage($my_page)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET my_page = (:my_page) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":my_page", $my_page, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getWebPage()
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
        $stmt = $this->db->prepare("UPDATE users SET verify = (:verify) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":verify", $verify, PDO::PARAM_INT);
        $stmt->execute();
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

    public function setAllowPosts($allowPosts)
    {
        $stmt = $this->db->prepare("UPDATE users SET allowPosts = (:allowPosts) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":allowPosts", $allowPosts, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAllowPosts()
    {
        $stmt = $this->db->prepare("SELECT allowPosts FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['allowPosts'];
        }

        return 0;
    }

    public function setCategory($accountCategory)
    {

        $stmt = $this->db->prepare("UPDATE users SET account_category = (:accountCategory) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":accountCategory", $accountCategory, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getCategory()
    {
        $stmt = $this->db->prepare("SELECT account_category FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $row = $stmt->fetch();

            return $row['account_category'];
        }

        return 0;
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

    public function get()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $profile = new profile($this->db, $row['id']);

                $time = new language($this->db);

                $follow = false;

                if ($this->is_follower_exists($this->requestFrom)) {

                    $follow = true;
                }

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "accountType" => $row['account_type'],
                                "accountCategory" => $row['account_category'],
                                "accountAuthor" => $row['account_author'],
                                "state" => $row['state'],
                                "username" => $row['login'],
                                "fullname" => stripcslashes($row['fullname']),
                                "location" => stripcslashes($row['country']),
                                "status" => stripcslashes($row['status']),
                                "my_page" => stripcslashes($row['my_page']),
                                "verify" => $row['verify'],
                                "email" => $row['email'],
                                "year" => $row['bYear'],
                                "month" => $row['bMonth'],
                                "day" => $row['bDay'],
                                "myPage" => $row['my_page'],
                                "postsCount" => $row['posts_count'],
                                "followersCount" => $row['followers_count'],
                                "language" => $row['language'],
                                "lowPhotoUrl" => $row['lowPhotoUrl'],
                                "normalPhotoUrl" => $row['normalPhotoUrl'],
                                "bigPhotoUrl" => $row['normalPhotoUrl'],
                                "allowComments" => $row['allowComments'],
                                "allowPosts" => $row['allowPosts'],
                                "follow" => $follow);

                unset($profile);
                unset($time);
            }
        }

        return $result;
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

    public function setPhoto($array_data)
    {
        $stmt = $this->db->prepare("UPDATE users SET originPhotoUrl = (:originPhotoUrl), normalPhotoUrl = (:normalPhotoUrl), bigPhotoUrl = (:bigPhotoUrl), lowPhotoUrl = (:lowPhotoUrl) WHERE id = (:account_id)");
        $stmt->bindParam(":account_id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":originPhotoUrl", $array_data['originPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":normalPhotoUrl", $array_data['normalPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":bigPhotoUrl", $array_data['bigPhotoUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":lowPhotoUrl", $array_data['lowPhotoUrl'], PDO::PARAM_STR);

        $stmt->execute();
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

    private function getMaxIdFollowers()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM profile_followers");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getFollowers($itemId = 0, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxIdFollowers();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follow_to = (:follow_to) AND id < (:id) ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':follow_to', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['follower']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $profile->get());

                    $result['itemId'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $result;
    }

    private function getMaxIdPosts()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getPostsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE groupId = (:groupId) AND removeAt = 0");
        $stmt->bindParam(':groupId', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function updateCounters()
    {
        $postsCount = $this->getPostsCount();
        $followersCount = $this->getFollowersCount();

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET posts_count = (:posts_count), followers_count = (:followers_count) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":posts_count", $postsCount, PDO::PARAM_INT);
        $stmt->bindParam(":followers_count", $followersCount, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array('error' => false,
                            'error_code' => ERROR_SUCCESS);
        }

        return $result;
    }

    public function getPosts($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxIdPosts();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM posts WHERE groupId = (:groupId) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':groupId', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $post = new post($this->db);
                $post->setRequestFrom($this->requestFrom);

                $postInfo = $post->info($row['id']);

                array_push($result['items'], $postInfo);

                $result['itemId'] = $row['id'];

                unset($postInfo);
                unset($post);
            }
        }

        return $result;
    }

    public function setId($profileId)
    {
        $this->id = $profileId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }
}

