<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class profile extends db_connect
{

    private $id = 0;
    private $requestFrom = 0;

    public function __construct($dbo = NULL, $profileId)
    {

        parent::__construct($dbo);

        $this->setId($profileId);
    }

    public function lastIndex()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn() + 1;
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

                // test to blocked
                $blocked = false;

                if ($this->requestFrom != 0) {

                    $blacklist = new blacklist($this->db);
                    $blacklist->setRequestFrom($this->requestFrom);

                    if ($blacklist->isExists($this->id)) {

                        $blocked = true;
                    }

                    unset($blacklist);
                }

                // test to friend
                $friend = false;

                if ($this->getRequestFrom() != 0 && $this->getRequestFrom() != $this->getId()) {

                    if ($this->is_friend_exists($this->requestFrom)) {

                        $friend = true;
                    }
                }

                // test to follow
                $follow = false;

                // test to my follower
                $follower = false;


                if (!$friend && $this->getRequestFrom() != $this->getId()) {

                    // test to follow
                    // $follow = false;

                    if ($this->getRequestFrom() != 0) {

                        if ($this->is_follower_exists($this->requestFrom)) {

                            $follow = true;
                        }
                    }

                    // test to my follower
                    // $follower = false;

                    if ($this->getRequestFrom() != 0) {

                        $myProfile = new profile($this->db, $this->requestFrom);

                        if ($myProfile->is_follower_exists($this->getId())) {

                            $follower = true;
                        }

                        unset($myProfile);
                    }
                }

                // is my profile exists in blacklist
                $inBlackList = false;

                if ($this->requestFrom != 0) {

                    $blacklist = new blacklist($this->db);
                    $blacklist->setRequestFrom($this->getId());

                    if ($blacklist->isExists($this->getRequestFrom())) {

                        $inBlackList = true;
                    }

                    unset($blacklist);
                }

                $online = false;

                $current_time = time();

                if ($row['last_authorize'] != 0 && $row['last_authorize'] > ($current_time - 15 * 60)) {

                    $online = true;
                }

                $time = new language($this->db);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "state" => $row['state'],
                                "accountType" => $row['account_type'],
                                "accountCategory" => $row['account_category'],
                                "accountAuthor" => $row['account_author'],
                                "mood" => $row['mood'],
                                "pro" => $row['pro'],
                                "pro_create_at" => $row['pro_create_at'],
                                "sex" => $row['sex'],
                                "age" => $row['age'],
                                "year" => $row['bYear'],
                                "month" => $row['bMonth'],
                                "day" => $row['bDay'],
                                "username" => $row['login'],
                                "fullname" => htmlspecialchars_decode(stripslashes($row['fullname'])),
                                "nim" => strtoupper($row['nim']),
                                "location" => stripcslashes($row['country']),
                                "status" => stripcslashes($row['status']),
                                "fb_page" => stripcslashes($row['fb_page']),
                                "instagram_page" => stripcslashes($row['my_page']),
                                "my_page" => stripcslashes($row['my_page']),
                                "verify" => $row['verify'],
                                "verified" => $row['verify'],
                                "botaccount" => $row['botaccount'],
                                "staffaccount" => $row['staffaccount'],
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "lowPhotoUrl" => $row['lowPhotoUrl'],
                                "bigPhotoUrl" => $row['bigPhotoUrl'],
                                "normalPhotoUrl" => $row['normalPhotoUrl'],
                                "normalCoverUrl" => $row['normalCoverUrl'],
                                "originCoverUrl" => $row['originCoverUrl'],
                                "coverPosition" => $row['coverPosition'],
                                "allowComments" => $row['allowComments'],
                                "allowPhotosComments" => $row['allowPhotosComments'],
                                "allowVideoComments" => $row['allowVideoComments'],
                                "allowGalleryComments" => $row['allowGalleryComments'],
                                "allowMessages" => $row['allowMessages'],
                                "referralsCount" => $row['referrals_count'],
                                "postsCount" => $row['posts_count'],
                                "followersCount" => $row['followers_count'],
                                "likesCount" => $row['likes_count'],
                                "photosCount" => $row['photos_count'],
                                "galleryItemsCount" => $row['gallery_items_count'],
                                "videosCount" => $row['videos_count'],
                                "giftsCount" => $row['gifts_count'],
                                "friendsCount" => $row['friends_count'],
                                "allowShowMyInfo" => $row['allowShowMyInfo'],
                                "allowShowMyVideos" => $row['allowShowMyVideos'],
                                "allowShowMyFriends" => $row['allowShowMyFriends'],
                                "allowShowMyPhotos" => $row['allowShowMyPhotos'],
                                "allowShowMyGallery" => $row['allowShowMyGallery'],
                                "allowShowMyGifts" => $row['allowShowMyGifts'],
                                "allowShowMyAgeAndGender" => $row['allowShowMyAgeAndGender'],
                                "follower" => $follower,
                                "inBlackList" => $inBlackList,
                                "follow" => $follow,
                                "friend" => $friend,
                                "blocked" => $blocked,
                                "lastAuthorize" => $row['last_authorize'],
                                "lastAuthorizeDate" => date("Y-m-d H:i:s", $row['last_authorize']),
                                "lastAuthorizeTimeAgo" => $time->timeAgo($row['last_authorize']),
                                "online" => $online,
                                "photoModerateAt" => $row['photoModerateAt'],
                                "photoModerateUrl" => $row['photoModerateUrl'],
                                "photoPostModerateAt" => $row['photoPostModerateAt'],
                                "coverModerateAt" => $row['coverModerateAt'],
                                "coverModerateUrl" => $row['coverModerateUrl'],
                                "coverPostModerateAt" => $row['coverPostModerateAt']);
            }
        }

        return $result;
    }

    public function getShort()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                // is my profile exists in blacklist
                $inBlackList = false;

                if ($this->requestFrom != 0) {

                    $blacklist = new blacklist($this->db);
                    $blacklist->setRequestFrom($this->getId());

                    if ($blacklist->isExists($this->getRequestFrom())) {

                        $inBlackList = true;
                    }

                    unset($blacklist);
                }

                $online = false;

                $current_time = time();

                if ($row['last_authorize'] != 0 && $row['last_authorize'] > ($current_time - 15 * 60)) {

                    $online = true;
                }

                $time = new language($this->db);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "rating" => $row['rating'],
                                "state" => $row['state'],
                                "mood" => $row['mood'],
                                "pro" => $row['pro'],
                                "pro_create_at" => $row['pro_create_at'],
                                "sex" => $row['sex'],
                                "age" => $row['age'],
                                "year" => $row['bYear'],
                                "month" => $row['bMonth'],
                                "day" => $row['bDay'],
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "username" => $row['login'],
                                "fullname" => htmlspecialchars_decode(stripslashes($row['fullname'])),
                                "location" => stripcslashes($row['country']),
                                "status" => stripcslashes($row['status']),
                                "fb_page" => stripcslashes($row['fb_page']),
                                "instagram_page" => stripcslashes($row['my_page']),
                                "verify" => $row['verify'],
                                "verified" => $row['verify'],
                                "botaccount" => $row['botaccount'],
                                "staffaccount" => $row['staffaccount'],
                                "lowPhotoUrl" => $row['lowPhotoUrl'],
                                "bigPhotoUrl" => $row['bigPhotoUrl'],
                                "normalPhotoUrl" => $row['normalPhotoUrl'],
                                "normalCoverUrl" => $row['normalCoverUrl'],
                                "originCoverUrl" => $row['originCoverUrl'],
                                "coverPosition" => $row['coverPosition'],
                                "allowComments" => $row['allowComments'],
                                "allowPhotosComments" => $row['allowPhotosComments'],
                                "allowVideoComments" => $row['allowVideoComments'],
                                "allowGalleryComments" => $row['allowGalleryComments'],
                                "allowMessages" => $row['allowMessages'],
                                "referralsCount" => $row['referrals_count'],
                                "postsCount" => $row['posts_count'],
                                "followersCount" => $row['followers_count'],
                                "likesCount" => $row['likes_count'],
                                "photosCount" => $row['photos_count'],
                                "galleryItemsCount" => $row['gallery_items_count'],
                                "videosCount" => $row['videos_count'],
                                "giftsCount" => $row['gifts_count'],
                                "friendsCount" => $row['friends_count'],
//                                "allowShowMyBirthday" => $row['allowShowMyBirthday'],
//                                "friendsCount" => $row['friends_count'],
                                "allowShowMyInfo" => $row['allowShowMyInfo'],
                                "allowShowMyVideos" => $row['allowShowMyVideos'],
                                "allowShowMyFriends" => $row['allowShowMyFriends'],
                                "allowShowMyPhotos" => $row['allowShowMyPhotos'],
                                "allowShowMyGallery" => $row['allowShowMyGallery'],
                                "allowShowMyGifts" => $row['allowShowMyGifts'],
                                "allowShowMyAgeAndGender" => $row['allowShowMyAgeAndGender'],
                                "inBlackList" => $inBlackList,
                                "createAt" => $row['regtime'],
                                "createDate" => date("Y-m-d", $row['regtime']),
                                "lastAuthorize" => $row['last_authorize'],
                                "lastAuthorizeDate" => date("Y-m-d H:i:s", $row['last_authorize']),
                                "lastAuthorizeTimeAgo" => $time->timeAgo($row['last_authorize']),
                                "online" => $online,
                                "photoModerateAt" => $row['photoModerateAt'],
                                "photoModerateUrl" => $row['photoModerateUrl'],
                                "photoPostModerateAt" => $row['photoPostModerateAt'],
                                "coverModerateAt" => $row['coverModerateAt'],
                                "coverModerateUrl" => $row['coverModerateUrl'],
                                "coverPostModerateAt" => $row['coverPostModerateAt']);
            }
        }

        return $result;
    }

    public function getVeryShort()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_ACCOUNT_ID);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $online = false;

                $current_time = time();

                if ($row['last_authorize'] != 0 && $row['last_authorize'] > ($current_time - 15 * 60)) {

                    $online = true;
                }

                $time = new language($this->db);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "rating" => $row['rating'],
                                "state" => $row['state'],
                                "accountType" => $row['account_type'],
                                "mood" => $row['mood'],
                                "pro" => $row['pro'],
                                "pro_create_at" => $row['pro_create_at'],
                                "sex" => $row['sex'],
                                "age" => $row['age'],
                                "year" => $row['bYear'],
                                "month" => $row['bMonth'],
                                "day" => $row['bDay'],
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "username" => $row['login'],
                                "fullname" => htmlspecialchars_decode(stripslashes($row['fullname'])),
                                 "nim" => strtoupper($row['nim']),
                                "location" => stripcslashes($row['country']),
                                "status" => stripcslashes($row['status']),
                                "verify" => $row['verify'],
                                "verified" => $row['verify'],
                                "botaccount" => $row['botaccount'],
                                "staffaccount" => $row['staffaccount'],
                                "fb_page" => stripcslashes($row['fb_page']),
                                "instagram_page" => stripcslashes($row['my_page']),
                                "lowPhotoUrl" => $row['lowPhotoUrl'],
                                "bigPhotoUrl" => $row['bigPhotoUrl'],
                                "normalPhotoUrl" => $row['normalPhotoUrl'],
                                "normalCoverUrl" => $row['normalCoverUrl'],
                                "originCoverUrl" => $row['originCoverUrl'],
                                "allowComments" => $row['allowComments'],
                                "allowPhotosComments" => $row['allowPhotosComments'],
                                "allowVideoComments" => $row['allowVideoComments'],
                                "allowGalleryComments" => $row['allowGalleryComments'],
                                "allowMessages" => $row['allowMessages'],
                                "referralsCount" => $row['referrals_count'],
                                "postsCount" => $row['posts_count'],
                                "followersCount" => $row['followers_count'],
                                "likesCount" => $row['likes_count'],
                                "photosCount" => $row['photos_count'],
                                "galleryItemsCount" => $row['gallery_items_count'],
                                "videosCount" => $row['videos_count'],
                                "giftsCount" => $row['gifts_count'],
                                "friendsCount" => $row['friends_count'],
                                "allowShowMyInfo" => $row['allowShowMyInfo'],
                                "allowShowMyVideos" => $row['allowShowMyVideos'],
                                "allowShowMyFriends" => $row['allowShowMyFriends'],
                                "allowShowMyPhotos" => $row['allowShowMyPhotos'],
                                "allowShowMyGallery" => $row['allowShowMyGallery'],
                                "allowShowMyGifts" => $row['allowShowMyGifts'],
                                "allowShowMyAgeAndGender" => $row['allowShowMyAgeAndGender'],
                                "createAt" => $row['regtime'],
                                "createDate" => date("Y-m-d", $row['regtime']),
                                "lastAuthorize" => $row['last_authorize'],
                                "lastAuthorizeDate" => date("Y-m-d H:i:s", $row['last_authorize']),
                                "lastAuthorizeTimeAgo" => $time->timeAgo($row['last_authorize']),
                                "online" => $online,
                                "photoModerateAt" => $row['photoModerateAt'],
                                "photoModerateUrl" => $row['photoModerateUrl'],
                                "photoPostModerateAt" => $row['photoPostModerateAt'],
                                "coverModerateAt" => $row['coverModerateAt'],
                                "coverModerateUrl" => $row['coverModerateUrl'],
                                "coverPostModerateAt" => $row['coverPostModerateAt']);
            }
        }

        return $result;
    }

    public function addFollower($follower_id)
    {

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "follow" => false,
            "followersCount" => 0
        );

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getSendFriendRequestsCount() > 20) {

            return $result;
        }

        unset($spam);

        $account = new account($this->db, $follower_id);
        $account->setLastActive();
        unset($account);

        // test to friend
        $friend = false;

        if ($this->getRequestFrom() != 0 && $this->getRequestFrom() != $this->getId()) {

            if ($this->is_friend_exists($follower_id)) {

                $friend = true;
            }
        }

        if (!$friend) {

            if ($this->is_follower_exists($follower_id)) {

                $stmt = $this->db->prepare("DELETE FROM profile_followers WHERE follower = (:follower) AND follow_to = (:follow_to)");
                $stmt->bindParam(":follower", $follower_id, PDO::PARAM_INT);
                $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);

                $stmt->execute();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "follow" => false,
                                "followersCount" => 0);

                $notify = new notify($this->db);
                $notify->removeNotify($this->id, $follower_id, NOTIFY_TYPE_FOLLOWER, 0);
                unset($notify);

            } else {

                $create_at = time();

                $stmt = $this->db->prepare("INSERT INTO profile_followers (follower, follow_to, create_at) value (:follower, :follow_to, :create_at)");
                $stmt->bindParam(":follower", $follower_id, PDO::PARAM_INT);
                $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
                $stmt->bindParam(":create_at", $create_at, PDO::PARAM_INT);

                $stmt->execute();

                $blacklist = new blacklist($this->db);
                $blacklist->setRequestFrom($this->id);

                if (!$blacklist->isExists($follower_id)) {

                    $fcm = new fcm($this->db);
                    $fcm->setRequestFrom($this->getRequestFrom());
                    $fcm->setRequestTo($this->id);
                    $fcm->setType(GCM_NOTIFY_FOLLOWER);
                    $fcm->setTitle("You have new friend request");
                    $fcm->setItemId(0);
                    $fcm->prepare();
                    $fcm->send();
                    unset($fcm);

                    $notify = new notify($this->db);
                    $notify->createNotify($this->id, $follower_id, NOTIFY_TYPE_FOLLOWER, 0);
                    unset($notify);
                }

                unset($blacklist);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "follow" => true,
                                "followersCount" => 0);
            }
        }

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

    public function is_friend_exists($friend_id)
    {

        $stmt = $this->db->prepare("SELECT id FROM friends WHERE friend = (:friend) AND friendTo = (:friendTo) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":friend", $friend_id, PDO::PARAM_INT);
        $stmt->bindParam(":friendTo", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            return true;
        }

        return false;
    }

    public function reportAbuse($abuseId)
    {
        $result = array("error" => true);

        $create_at = time();
        $ip_addr = helper::ip_addr();

        $stmt = $this->db->prepare("INSERT INTO profile_abuse_reports (abuseFromUserId, abuseToUserId, abuseId, createAt, ip_addr) value (:abuseFromUserId, :abuseToUserId, :abuseId, :createAt, :ip_addr)");
        $stmt->bindParam(":abuseFromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":abuseToUserId", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":abuseId", $abuseId, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $create_at, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false);
        };

        return $result;
    }

    public function getPostsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND groupId = 0 AND removeAt = 0");
        $stmt->bindParam(":fromUserId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getFriendsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follower = (:followerId) AND follow_type = 0");
        $stmt->bindParam(":followerId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMyGroupsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follower = (:followerId) AND follow_type = 1");
        $stmt->bindParam(":followerId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getManagedGroupsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE account_author = (:account_author) AND state = 0");
        $stmt->bindParam(":account_author", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getLikesCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM likes WHERE toUserId = (:toUserId) AND removeAt = 0");
        $stmt->bindParam(":toUserId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getFollowingsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follower = (:followerId) AND follow_type = 0");
        $stmt->bindParam(":followerId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getFollowersCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_followers WHERE follow_to = (:follow_to)");
        $stmt->bindParam(":follow_to", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setLastNotifyView()
    {
        $time = time();

        $stmt = $this->db->prepare("UPDATE users SET last_notify_view = (:last_notify_view) WHERE id = (:id)");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":last_notify_view", $time, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getLastNotifyView()
    {
        $stmt = $this->db->prepare("SELECT last_notify_view FROM users WHERE id = (:id) LIMIT 1");
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                return $row['last_notify_view'];
            }
        }

        $time = time();

        return $time;
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

    public function getState()
    {
        $stmt = $this->db->prepare("SELECT state FROM users WHERE id = (:profileId) LIMIT 1");
        $stmt->bindParam(":profileId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row['state'];
    }

    public function getFullname()
    {
        $stmt = $this->db->prepare("SELECT login, fullname FROM users WHERE id = (:profileId) LIMIT 1");
        $stmt->bindParam(":profileId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        $fullname = stripslashes($row['fullname']);

        if (strlen($fullname) < 1) {

            $fullname = $row['login'];
        }

        return $fullname;
    }

    public function getUsername()
    {
        $stmt = $this->db->prepare("SELECT login FROM users WHERE id = (:profileId) LIMIT 1");
        $stmt->bindParam(":profileId", $this->id , PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row['login'];
    }

    public function getAnonymousQuestions()
    {
        $stmt = $this->db->prepare("SELECT anonymousQuestions FROM users WHERE id = (:profileId) LIMIT 1");
        $stmt->bindParam(":profileId", $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();

        return $row['anonymousQuestions'];
    }

    private function getMaxIdFollowers()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM profile_followers");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getFollowings($itemId = 0) {

        return $this->getFriends($itemId);
    }

    public function getFriends($id = 0)
    {
        if ($id == 0) {

            $id = $this->getMaxIdFollowers();
            $id++;
        }

        $friends = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "id" => $id,
                        "friends" => array());

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follower = (:follower) AND id < (:id) AND follow_type = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':follower', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['follow_to']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($friends['friends'], $profile->get());

                    $friends['id'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $friends;
    }

    public function getFollowers($id = 0)
    {
        if ($id == 0) {

            $id = $this->getMaxIdFollowers();
            $id++;
        }

        $friends = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "id" => $id,
                        "friends" => array());

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follow_to = (:follow_to) AND id < (:id) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':follow_to', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['follower']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($friends['friends'], $profile->get());

                    $friends['id'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $friends;
    }

    public function getMyGroups($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxIdFollowers();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follower = (:follower) AND id < (:id) AND follow_type = 1 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':follower', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $group = new group($this->db, $row['follow_to']);
                    $group->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $group->get());

                    $result['itemId'] = $row['id'];

                    unset($group);
                }
            }
        }

        return $result;
    }

    public function getManagedGroups($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM users WHERE account_author = (:account_author) AND id < (:id) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':account_author', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $group = new group($this->db, $row['id']);
                    $group->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $group->get());

                    $result['itemId'] = $row['id'];

                    unset($group);
                }
            }
        }

        return $result;
    }
}

