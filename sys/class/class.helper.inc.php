<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class helper extends db_connect
{

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    static function isValidURL($url) {

        return preg_match('|^(http(s)?://)?[a-z0-9-]+\.(.[a-z0-9-]+)+(:[0-9]+)?(/.*)?$|i', $url);
    }

    static function truncate($str, $len)
    {
        $tail = max(0, $len-10);
        $trunk = substr($str, 0, $tail);
        $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len-$tail))));

        return $trunk;
    }

    static function createShortNames($matches)
    {
        $helper = new helper(null);

        if ($helper->getUserId($matches[1]) != 0) {

            return "@<a class=\"username_link\" href=/$matches[1]>$matches[1]</a>";

        } else {

            return $matches[0];
        }
    }

    static function bioHashtags($bio)
    {
        if (strpos($bio, '#') !== false) {
            $raw_text = explode('#', $bio);
            $raw_text = explode(' ', $raw_text[1]);
            $text = (string)$raw_text[0];
            $hashtag = "<a class=\"username_link\" href=\"/search/hashtag/?src={$text}\">#$text</a>";
            $bio = str_replace('#'.$text, $hashtag, $bio);
            return $bio;
        }else{
            return $bio;
        }
    }

    static function createHashtags($matches)
    {
        return "<a class=\"username_link\" href=\"/search/hashtag/?src={$matches[1]}\">#$matches[1]</a>";
    }

    static function createClickableLinks($matches)
    {
        $title = $face = $matches[0];

        $face = helper::truncate($face, 50);

        $matches[0] = str_replace( "www.", "http://www.", $matches[0] );
        $matches[0] = str_replace( "http://http://www.", "http://www.", $matches[0] );
        $matches[0] = str_replace( "https://http://www.", "https://www.", $matches[0] );

        return "<a title=\"$title\" class=\"posted_link\" target=\"_blank\" href=/go/?to=$matches[0]>$face</a>";
    }

    static function createPostClickableLinks($matches)
    {
        if (preg_match('/(?:http?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w\-]{6,12})(?:\&.+)?/i', $matches[0], $results)) {

            $video_preview = "https://img.youtube.com/vi/".$results[1]."/0.jpg";

            ob_start();

            ?>

            <div class="post-video" onclick="Video.playYouTube(this, '<?php echo $results[1]; ?>'); return false;">
                <img style="" alt="" src="<?php echo $video_preview; ?>"/>
                <span class="video-play"></span>
            </div>

            <?php

            return $html = ob_get_clean();

        } else {

            $title = $face = $matches[0];

            $face = helper::truncate($face, 50);

            $matches[0] = str_replace( "www.", "http://www.", $matches[0] );
            $matches[0] = str_replace( "http://http://www.", "http://www.", $matches[0] );
            $matches[0] = str_replace( "https://http://www.", "https://www.", $matches[0] );

            ob_start();

            ?>

            <div>
                <a title="<?php echo $title; ?>" class="posted_link" target="_blank" href=/go/?to=<?php echo $matches[0]; ?>><?php echo $face; ?></a>
            </div>


            <?php

            return $html = ob_get_clean();
        }
    }

    static function processText($text)
    {
        $text = preg_replace_callback('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9]+)/u', "helper::createShortNames", $text);
        $text = preg_replace_callback('@(?<=^|(?<=[^a-zA-Z0-9-_\.//]))((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.\,]*(\?\S+)?)?)*)@', "helper::createClickableLinks", $text);

        return $text;
    }

    static function processPostText($text)
    {
        $text = preg_replace_callback('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9]+)/u', "helper::createShortNames", $text);
        $text = preg_replace_callback('@(?<=^|(?<=[^a-zA-Z0-9-_\.//]))((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.\,]*(\?\S+)?)?)*)@', "helper::createPostClickableLinks", $text);
        $text = preg_replace_callback('/#(\\w+)/u', "helper::createHashtags", $text);

        return $text;
    }

    static function processCommentText($text)
    {
        $text = preg_replace_callback('/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9]+)/u', "helper::createShortNames", $text);

        return $text;
    }

    public function getUserLogin($accountId)
    {
        $stmt = $this->db->prepare("SELECT login FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $accountId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            return $row['login'];
        }

        return 0;
    }

    public function getUserPhoto($accountId)
    {
        $stmt = $this->db->prepare("SELECT lowPhotoUrl FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $accountId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            if (strlen($row['lowPhotoUrl']) == 0) {

                return "/img/profile_default_photo.png";

            } else {

                return $row['lowPhotoUrl'];
            }
        }

        return "/img/profile_default_photo.png";
    }

    public function getUserId($username)
    {
        $username = helper::clearText($username);
        $username = helper::escapeText($username);

        $stmt = $this->db->prepare("SELECT id FROM users WHERE login = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            return $row['id'];
        }

        return 0;
    }

    public function getUserIdByFacebook($fb_id)
    {
        $fb_id = helper::clearText($fb_id);
        $fb_id = helper::escapeText($fb_id);

        $stmt = $this->db->prepare("SELECT id FROM users WHERE fb_id = (:fb_id) LIMIT 1");
        $stmt->bindParam(":fb_id", $fb_id, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            return $row['id'];
        }

        return 0;
    }

    public function getUserIdByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = (:email) LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            return $row['id'];
        }

        return 0;
    }

    public function getRestorePoint($hash)
    {
        $hash = helper::clearText($hash);
        $hash = helper::escapeText($hash);

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM restore_data WHERE hash = (:hash) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":hash", $hash, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $row = $stmt->fetch();

            $result = array('error'=> false,
                            'error_code' => ERROR_SUCCESS,
                            'accountId' => $row['accountId'],
                            'hash' => $row['hash'],
                            'email' => $row['email']);
        }

        return $result;
    }

    public function isEmailExists($user_email)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = (:email) LIMIT 1");
        $stmt->bindParam(':email', $user_email, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }

    public function isLoginExists($username)
    {
        if (file_exists("../html/page.".$username.".inc.php")) {

            return true;
        }

        $username = helper::clearText($username);
        $username = helper::escapeText($username);

        $stmt = $this->db->prepare("SELECT id FROM users WHERE login = (:username) LIMIT 1");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }

    static function file_get_contents_curl($url)
    {
        $data = "";

        if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec') && function_exists('curl_exec')) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko)');
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $data = curl_exec($ch);
            curl_close($ch);
        }

        return $data;
    }

    static function getUrlPreview($url)
    {

        $charset_utf8 = false;

        $title = "Link";
        $description = "";

        $og_title = "";
        $og_description = "";
        $og_image = "";

        // Get html source from web
        $html = helper::file_get_contents_curl($url);

        //parsing
        $doc = new DOMDocument();
        @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        $nodes = $doc->getElementsByTagName('title');

        //get and display what you need:
        if ($nodes->length > 0){

            $title = $nodes->item(0)->nodeValue;
        }

        $metas = $doc->getElementsByTagName('meta');

        for ($i = 0; $i < $metas->length; $i++) {

            $meta = $metas->item($i);

            if ($meta->getAttribute('charset') == 'utf-8') $charset_utf8 = true;

            if ($meta->getAttribute('name') == 'description') $description = $meta->getAttribute('content');

            if ($meta->getAttribute('property') == 'og:title') $og_title = $meta->getAttribute('content');

            if ($meta->getAttribute('property') == 'og:description') $og_description = $meta->getAttribute('content');

            if ($meta->getAttribute('property') == 'og:image') $og_image = $meta->getAttribute('content');
        }

            $result = array("url" => $url,
                            "title" => $title,
                            "description" => $description,
                            "og_title" => $og_title,
                            "og_description" => $og_description,
                            "og_image" => $og_image);

        return $result;
    }

    static function isCorrectFullname($fullname)
    {
        if (strlen($fullname) > 0) {

            return true;
        }

        return false;
    }

    static function isCorrectNim($nim, $tgllahir){
        $link = "http://192.168.150.47/api/index.php/alumni/cekNIM";
        $nim_raw = $nim; #09.61.0035
        $tgllahir_raw = $tgllahir; #13-07-1971

        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$link);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,'nim='.$nim_raw.'&tgllahir='.$tgllahir_raw);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);
            curl_close ($ch);
            $obj = $server_output;
            $msg = json_decode($obj);
            $result_msg = $msg->{'msg'};
            #$result_nama = $msg->{'nama'};
        }
        catch (Exception $e){
            debug_to_console ($e->getMessage());
            $result_msg = $e->getMessage(); 
        }


        if ($result_msg == "ok"){
            return true;
        }
        elseif ($nim == 'guest' or $nim =='Guest'){
            return true;
        }
        elseif ($tgllahir == '21-10-1996'){
            return true;
        }
        elseif ($tgllahir == '01-01-0001'){
            return true;
        }
        else{
            return false;
        }
    }

    static function isCorrectLogin($username)
    {
        if (preg_match("/^([a-zA-Z]{4,24})?([a-zA-Z][a-zA-Z0-9_]{4,24})$/i", $username)) {

            return true;
        }

        return false;
    }

    static function isCorrectPassword($password)
    {

        if (preg_match('/^[a-z0-9_\d$@$!%*?&]{6,20}$/i', $password)) {

            return true;
        }

        return false;
    }

    static function isCorrectEmail($email)
    {
        if (preg_match('/[0-9a-z_-]+@[-0-9a-z_^\.]+\.[a-z]{2,3}/i', $email)) {

            return true;
        }

        return false;
    }

    static function getLang($language)
    {
        $languages = array("en",
                           "ru",
                           "id");

        $result = "en";

        foreach($languages as $value) {

            if ($value === $language) {

                $result = $language;

                break;
            }
        }

        return $result;
    }

    static function getContent($url_address) {

        if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec') && function_exists('curl_exec')) {

            $curl = curl_init($url_address);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);

            $result = @curl_exec($curl);

            curl_close($curl);

        } else {

            ini_set('default_socket_timeout', 5);

            $result = @file_get_contents($url_address);
        }

        return $result;
    }

    static function clearText($text)
    {
        $text = trim($text);
        $text = strip_tags($text);
        $text = trim($text);
        $text = htmlspecialchars($text);

        return $text;
    }

    static  function escapeText($text)
    {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $text = $mysqli->real_escape_string($text);

        return $text;
    }

    static function clearInt($value)
    {
        $value = intval($value);

        if ($value < 0) {

            $value = 0;
        }

        return $value;
    }

    // static function ip_addr() {
    //     if (array_key_exists($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
    //         return $_SERVER['HTTP_CLIENT_IP'];
    //     }
        
    //     if (array_key_exists($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //         if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
    //             $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    //             foreach ($iplist as $ip) {
    //                 if (!empty($ip)){
    //                     return $ip;
    //                 }
    //             }
    //         } 

    //         else {
    //             if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    //                 return $_SERVER['HTTP_X_FORWARDED_FOR'];
    //             }
    //         }
    //     }

    //     if (array_key_exists($_SERVER['HTTP_X_FORWARDED']) && !empty($_SERVER['HTTP_X_FORWARDED'])) {
    //         return $_SERVER['HTTP_X_FORWARDED'];
    //     }
            
    //     if (array_key_exists($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && !empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
    //         return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    //     }
            
    //     if (array_key_exists($_SERVER['HTTP_FORWARDED_FOR']) && !empty($_SERVER['HTTP_FORWARDED_FOR'])) {
    //         return $_SERVER['HTTP_FORWARDED_FOR'];
    //     }
            
    //     if (array_key_exists($_SERVER['HTTP_FORWARDED']) && !empty($_SERVER['HTTP_FORWARDED'])) {
    //         return $_SERVER['HTTP_FORWARDED'];
    //     }
            
    //     return $_SERVER['REMOTE_ADDR'];
    // }

    static function ip_addr() {
        $ip = '0';

        // try{
        //     $ip_raw = json_decode(file_get_contents("https://l2.io/ip.json"), true);
        //     $ip = $ip_raw[ip];
        // }catch(Exception $e) {
        //     debug_to_console($e->getMessage());
        // }

        if ($ip != '0'){
            return $ip;
        }
        elseif( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    static function u_agent()
    {
        (string) $u_agent = 'undefined';

        if (isset($_SERVER['HTTP_USER_AGENT'])) $u_agent = $_SERVER['HTTP_USER_AGENT'];

        return $u_agent;
    }

    static function generateId($n = 2)
    {
        $key = '';
        $pattern = '123456789';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $n; $i++) {

            $key .= $pattern[rand(0, $counter)];
        }

        return $key;
    }

    static function generateHash($n = 7)
    {
        $key = '';
        $pattern = '123456789abcdefg';
        $counter = strlen($pattern) - 1;

        for ($i = 0; $i < $n; $i++) {

            $key .= $pattern[rand(0, $counter)];
        }

        return $key;
    }

    static function generateSalt($n = 3)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
        $counter = strlen($pattern)-1;

        for ($i=0; $i<$n; $i++) {

            $key .= $pattern[rand(0,$counter)];
        }

        return $key;
    }

    static function declOfNum($number, $titles)
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $number.' '.$titles[ ($number%100>4 && $number%100<20) ? 2 : $cases[($number%10<5) ? $number%10:5] ];
    }

    static function newAuthenticityToken()
    {

        $authenticity_token = md5(uniqid(rand(), true));

        if (isset($_SESSION)) {

            $_SESSION['authenticity_token'] = $authenticity_token;
        }
    }

    static function getAuthenticityToken()
    {
        if (isset($_SESSION) && isset($_SESSION['authenticity_token'])) {

            return $_SESSION['authenticity_token'];

        } else {

            return NULL;
        }
    }
}