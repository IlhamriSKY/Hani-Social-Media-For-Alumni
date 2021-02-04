<?php
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class post extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';
    private $profileId = 0;

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxIdLikes()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM likes");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxIdPosts()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND removeAt = 0");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function autoPost($postText, $postImgUrl, $postType)
    {
        $this->add(0, $postText, $postImgUrl, 0, 0, "", "", "", "0.00000", "0.00000", "", "", $postType);
    }

    private function getLinkInfo($post_text)
    {

        $result = array(
            "title" => "",
            "description" => "",
            "image" => "",
            "link" => "");

        if (preg_match('@(?<=^|(?<=[^a-zA-Z0-9-_\.//]))((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.\,]*(\?\S+)?)?)*)@', htmlspecialchars_decode(stripslashes($post_text)), $results)) {

            if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec')) {

                $page = $results[0];

                $page = $this->addScheme($page);

                $result['link'] = $page;

                $urlPreviewResult = helper::getUrlPreview($page);

                if (strlen($urlPreviewResult['title']) > 0) {

                    $result['title'] = $urlPreviewResult['title'];
                }

                if (strlen($urlPreviewResult['description']) > 0) {

                    $result['description'] = $urlPreviewResult['description'];
                }

                if (strlen($urlPreviewResult['og_title']) > 0) {

                    $result['title'] = $urlPreviewResult['og_title'];
                }

                if (strlen($urlPreviewResult['og_description']) > 0) {

                    $result['description'] = $urlPreviewResult['og_description'];
                }

                if (strlen($urlPreviewResult['og_image']) > 0) {

                    $result['image'] = $urlPreviewResult['og_image'];
                }

                $result['title'] = helper::clearText($result['title']);
                $result['title'] = helper::escapeText($result['title']);

                $result['description'] = helper::clearText($result['description']);
                $result['description'] = helper::escapeText($result['description']);
            }
        }

        return $result;
    }

    public function add($mode, $postText, $postImage = "", $rePostId = 0, $groupId = 0, $postArea = "", $postCountry = "", $postCity = "", $postLat = "0.00000", $postLng = "0.00000", $videoImgUrl = "", $videoUrl = "", $postType = 0, $feeling = 0)
    {

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $ip_addr = helper::ip_addr();

        $stoplist = new stoplist($this->db);

        if ($stoplist->isExists($ip_addr)) {

            return $result;
        }

        unset($stoplist);

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getItemsCount() > 10) {

            return $result;
        }

        unset($spam);

        $account = new account($this->db, $this->getRequestFrom());
        $account->setLastActive();
        unset($account);

        if (strlen($postText) == 0 && strlen($postImage) == 0 && $rePostId == 0 && strlen($videoUrl) == 0) {

            return $result;
        }

        if ($rePostId != 0) {

            $rePostInfo = $this->info($rePostId);

            if ($rePostInfo['error'] === true || $rePostInfo['removeAt'] != 0 || $rePostInfo['fromUserId'] == $this->requestFrom) {

                return $result;
            }
        }

        if (strlen($postText) != 0) {

            $postText = $postText . " ";
        }

        $url_info = $this->getLinkInfo($postText);

        $currentTime = time();
        $u_agent = helper::u_agent();

        $deviceType = 0;

        if (strpos($u_agent, "Android") !== false) {

            $deviceType = 1;

        } else if (strpos($u_agent, "Darwin") !== false) {

            $deviceType = 2;

        } else {

            $deviceType = 0;
        }

        $stmt = $this->db->prepare("INSERT INTO posts (fromUserId, accessMode, rePostId, groupId, post, urlPreviewTitle, urlPreviewImage, urlPreviewDescription, urlPreviewLink, area, country, city, lat, lng, imgUrl, previewVideoImgUrl, videoUrl, createAt, ip_addr, u_agent, deviceType, postType, feeling) value (:fromUserId, :accessMode, :rePostId, :groupId, :post, :urlPreviewTitle, :urlPreviewImage, :urlPreviewDescription, :urlPreviewLink, :area, :country, :city, :lat, :lng, :imgUrl, :videoImgUrl, :videoUrl, :createAt, :ip_addr, :u_agent, :deviceType, :postType, :feeling)");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":accessMode", $mode, PDO::PARAM_INT);
        $stmt->bindParam(":rePostId", $rePostId, PDO::PARAM_INT);
        $stmt->bindParam(":groupId", $groupId, PDO::PARAM_INT);
        $stmt->bindParam(":post", $postText, PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewTitle", $url_info['title'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewImage", $url_info['image'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewDescription", $url_info['description'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewLink", $url_info['link'], PDO::PARAM_STR);
        $stmt->bindParam(":area", $postArea, PDO::PARAM_STR);
        $stmt->bindParam(":country", $postCountry, PDO::PARAM_STR);
        $stmt->bindParam(":city", $postCity, PDO::PARAM_STR);
        $stmt->bindParam(":lat", $postLat, PDO::PARAM_STR);
        $stmt->bindParam(":lng", $postLng, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $postImage, PDO::PARAM_STR);
        $stmt->bindParam(":videoImgUrl", $videoImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":videoUrl", $videoUrl, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);
        $stmt->bindParam(":deviceType", $deviceType, PDO::PARAM_INT);
        $stmt->bindParam(":postType", $postType, PDO::PARAM_INT);
        $stmt->bindParam(":feeling", $feeling, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "postId" => $this->db->lastInsertId(),
                            "itemId" => $this->db->lastInsertId(),
                            "post" => $this->info($this->db->lastInsertId()));

            if ($rePostId != 0) {

                $this->recalculate($rePostId);
            }

            if ($groupId != 0) {

                $group = new group($this->db, $groupId);
                $group->updateCounters();
                unset($group);

            } else {

                $account = new account($this->db, $this->requestFrom);
                $account->updateCounters();
                unset($account);
            }
        }

        return $result;
    }

    public function edit($postId, $postMode, $repostId, $postText, $postImage = "", $postArea = "", $postCountry = "", $postCity = "", $postLat = "0.00000", $postLng = "0.00000", $videoImgUrl = "", $videoUrl = "", $feeling = 0)
    {
        $url_info = $this->getLinkInfo($postText);

        $result = array("error" => true);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE posts SET accessMode = (:accessMode), rePostId = (:rePostId), area = (:area), country = (:country), city = (:city), lat = (:lat), lng = (:lng), previewVideoImgUrl = (:videoImgUrl), videoUrl = (:videoUrl), feeling = (:feeling), post = (:postText), imgUrl = (:imgUrl), urlPreviewTitle = (:urlPreviewTitle), urlPreviewImage = (:urlPreviewImage), urlPreviewDescription = (:urlPreviewDescription), urlPreviewLink = (:urlPreviewLink), createAt = (:createAt) WHERE id = (:postId)");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->bindParam(":accessMode", $postMode, PDO::PARAM_INT);
        $stmt->bindParam(":rePostId", $repostId, PDO::PARAM_INT);
        $stmt->bindParam(":imgUrl", $postImage, PDO::PARAM_STR);
        $stmt->bindParam(":postText", $postText, PDO::PARAM_STR);
        $stmt->bindParam(":area", $postArea, PDO::PARAM_STR);
        $stmt->bindParam(":country", $postCountry, PDO::PARAM_STR);
        $stmt->bindParam(":city", $postCity, PDO::PARAM_STR);
        $stmt->bindParam(":lat", $postLat, PDO::PARAM_STR);
        $stmt->bindParam(":lng", $postLng, PDO::PARAM_STR);
        $stmt->bindParam(":videoImgUrl", $videoImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":videoUrl", $videoUrl, PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewTitle", $url_info['title'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewImage", $url_info['image'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewDescription", $url_info['description'], PDO::PARAM_STR);
        $stmt->bindParam(":urlPreviewLink", $url_info['link'], PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":feeling", $feeling, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false);
        }

        return $result;
    }

    public function removeByIp($ip_addr)
    {

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN,
                        "count" => 0);

        $stmt = $this->db->prepare("SELECT id FROM posts WHERE ip_addr = (:ip_addr) AND removeAt = 0");
        $stmt->bindParam(':ip_addr', $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $this->remove($row['id']);
            }

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "count" => $stmt->rowCount());
        }

        return $result;
    }

    public function remove($postId)
    {
        $result = array(
            "error" => true
        );

        $postInfo = $this->info($postId);

        if ($postInfo['error']) {

            return $result;
        }

        if ($postInfo['fromUserId'] != $this->requestFrom) {

            $error = true;

            if ($postInfo['groupId'] != 0) {

                $group = new group($this->db, $postInfo['groupId']);
                $group->setRequestFrom($this->requestFrom);

                $groupInfo = $group->get();

                if ($groupInfo['accountAuthor'] == $this->requestFrom) {

                    $error = false;
                }

                unset($group);
            }

            if ($error) return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE posts SET removeAt = (:removeAt) WHERE id = (:postId)");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            //remove all reports

            $reports = new reports($this->db);
            $reports->remove(REPORT_TYPE_ITEM, $postId);
            unset($reports);

            // remove all notifications by likes and comments

            $stmt2 = $this->db->prepare("DELETE FROM notifications WHERE postId = (:postId) AND notifyType < 7");
            $stmt2->bindParam(":postId", $postId, PDO::PARAM_INT);
            $stmt2->execute();

            //remove all comments to post

            $stmt3 = $this->db->prepare("UPDATE comments SET removeAt = (:removeAt) WHERE itemId = (:itemId)");
            $stmt3->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
            $stmt3->bindParam(":itemId", $postId, PDO::PARAM_INT);
            $stmt3->execute();

            //remove all likes to post

            $stmt4 = $this->db->prepare("UPDATE likes SET removeAt = (:removeAt) WHERE itemId = (:itemId) AND removeAt = 0");
            $stmt4->bindParam(":itemId", $postId, PDO::PARAM_INT);
            $stmt4->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
            $stmt4->execute();

            $result = array(
                "error" => false
            );
        }

        if ($postInfo['groupId'] != 0) {

            $group = new group($this->db, $postInfo['groupId']);
            $group->setRequestFrom($this->getRequestFrom());

            $group->updateCounters();

            unset($group);

        } else {

            $this->recalculate($postId);
        }

        if ($postInfo['rePostId'] != 0) {

            $this->recalculate($postInfo['rePostId']);
        }

        return $result;
    }

    public function restore($postId)
    {
        $result = array(
            "error" => true
        );

        $postInfo = $this->info($postId);

        if ($postInfo['error']) {

            return $result;
        }

        $stmt = $this->db->prepare("UPDATE posts SET removeAt = 0 WHERE id = (:postId)");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false
            );
        }

        return $result;
    }

    private function is_repost_exists($postId, $fromUserId)
    {
        $stmt = $this->db->prepare("SELECT id FROM posts WHERE fromUserId = (:fromUserId) AND rePostId = (:rePostId) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":fromUserId", $fromUserId, PDO::PARAM_INT);
        $stmt->bindParam(":rePostId", $postId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            return true;
        }

        return false;
    }

    private function getRePostsCount($postId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE rePostId = (:rePostId) AND removeAt = 0");
        $stmt->bindParam(":rePostId", $postId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function recalculate($postId) {

        $comments_count = 0;
        $likes_count = 0;
        $reposts_count = 0;
        $rating = 0;

        $likes = new likes($this->db, ITEM_TYPE_POST);
        $likes->setRequestFrom($this->getRequestFrom());
        $likes_count = $likes->count($postId);
        unset($likes);

        $reposts_count = $this->getRePostsCount($postId);

        $comments = new comments($this->db);
        $comments_count = $comments->count($postId);
        unset($comments);

        $rating = $likes_count + $comments_count + $reposts_count;

        $stmt = $this->db->prepare("UPDATE posts SET likesCount = (:likesCount), commentsCount = (:commentsCount), rePostsCount = (:rePostsCount), rating = (:rating) WHERE id = (:postId)");
        $stmt->bindParam(":likesCount", $likes_count, PDO::PARAM_INT);
        $stmt->bindParam(":commentsCount", $comments_count, PDO::PARAM_INT);
        $stmt->bindParam(":rePostsCount", $reposts_count, PDO::PARAM_INT);
        $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);
        $stmt->execute();

        $account = new account($this->db, $this->getRequestFrom());
        $account->updateCounters();
        unset($account);
    }

    public function setImagesCount($itemId, $images_count = 0)
    {
        $stmt = $this->db->prepare("UPDATE posts SET imagesCount = (:imagesCount) WHERE id = (:itemId)");
        $stmt->bindParam(":imagesCount", $images_count, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function quikInfo($row)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $time = new language($this->db, $this->language);

        $myLike = false;
        $myRePost = false;

        if ($this->getRequestFrom() != 0) {

            if ($row['likesCount'] != 0) {

                $likes = new likes($this->db, ITEM_TYPE_POST);
                $likes->setRequestFrom($this->getRequestFrom());

                if ($likes->is_like_exists($row['id'], $this->getRequestFrom())) {

                    $myLike = true;
                }

                unset($likes);
            }

            if ($row['rePostsCount'] != 0) {

                if ($this->is_repost_exists($row['id'], $this->getRequestFrom())) {

                    $myRePost = true;
                }
            }
        }

        $groupAllowComments = 0;
        $groupAuthor = 0;

        if ($row['groupId'] != 0) {

            $group = new group($this->db, $row['groupId']);
            $group->setRequestFrom($this->getRequestFrom());

            $groupInfo = $group->get();

            $groupAllowComments = $groupInfo['allowComments'];
            $groupAuthor = $groupInfo['accountAuthor'];

            unset($group);
        }

        $profile = new profile($this->db, $row['fromUserId']);
        $profileInfo = $profile->getVeryShort();
        unset($profile);

        $you_tube_video_img = "";
        $you_tube_video_code = "";
        $you_tube_video_url = "";

        if (preg_match('/(?:http?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w\-]{6,12})(?:\&.+)?/i', htmlspecialchars_decode(stripslashes($row['post'])), $results)) {

            $you_tube_video_img = "http://img.youtube.com/vi/".$results[1]."/0.jpg";
            $you_tube_video_url = "http://www.youtube.com/v/".$results[1];
            $you_tube_video_code = $results[1];
        }

        $result = array("error" => false,
            "error_code" => ERROR_SUCCESS,
            "id" => $row['id'],
            "postType" => $row['postType'],
            "feeling" => $row['feeling'],
            "accessMode" => $row['accessMode'],
            "deviceType" => $row['deviceType'],
            "rePostId" => $row['rePostId'],
            "groupId" => $row['groupId'],
            "fromUserId" => $row['fromUserId'],
            "fromGender" => $profileInfo['sex'],
            "fromUserVerify" => $profileInfo['verify'],
            "fromUserBot" => $profileInfo['botaccount'],
            "fromUserStaff" => $profileInfo['staffaccount'],
            "fromUserOnline" => $profileInfo['online'],
            "fromUserUsername" => $profileInfo['username'],
            "fromUserFullname" => $profileInfo['fullname'],
            "fromUserPhoto" => $profileInfo['lowPhotoUrl'],
            "post" => htmlspecialchars_decode(stripslashes($row['post'])),
            "area" => htmlspecialchars_decode(stripslashes($row['area'])),
            "country" => htmlspecialchars_decode(stripslashes($row['country'])),
            "city" => htmlspecialchars_decode(stripslashes($row['city'])),
            "YouTubeVideoImg" => $you_tube_video_img,
            "YouTubeVideoCode" => $you_tube_video_code,
            "YouTubeVideoUrl" => $you_tube_video_url,
            "urlPreviewTitle" => htmlspecialchars_decode(stripslashes($row['urlPreviewTitle'])),
            "urlPreviewImage" => $row['urlPreviewImage'],
            "urlPreviewLink" => $row['urlPreviewLink'],
            "urlPreviewDescription" => htmlspecialchars_decode(stripslashes($row['urlPreviewDescription'])),
            "previewVideoImgUrl" => $row['previewVideoImgUrl'],
            "videoUrl" => $row['videoUrl'],
            "lat" => $row['lat'],
            "lng" => $row['lng'],
            "imgUrl" => $row['imgUrl'],
            "allowComments" => $profileInfo['allowComments'],
            "groupAllowComments" => $groupAllowComments,
            "groupAuthor" => $groupAuthor,
            "rating" => $row['rating'],
            "commentsCount" => $row['commentsCount'],
            "likesCount" => $row['likesCount'],
            "viewsCount" => $row['viewsCount'],
            "imagesCount" => $row['imagesCount'],
            "rePostsCount" => $row['rePostsCount'],
            "myLike" => $myLike,
            "myRePost" => $myRePost,
            "moderateAt" => $row['moderateAt'],
            "createAt" => $row['createAt'],
            "date" => date("Y-m-d H:i:s", $row['createAt']),
            "timeAgo" => $time->timeAgo($row['createAt']),
            "removeAt" => $row['removeAt'],
            "ip_addr" => $row['ip_addr'],
            "rePost" => array(),
            "owner" => $profileInfo);

        if ($row['rePostId'] != 0) {

            array_push($result['rePost'], $this->repost_info($row['rePostId']));

        } else {

            array_push($result['rePost'], array(
                "error" => true,
                "error_code" => ERROR_UNKNOWN)
            );
        }

        return $result;
    }

    public function info($postId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = (:postId) LIMIT 1");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $myLike = false;
                $myRePost = false;

                if ($this->getRequestFrom() != 0) {

                    if ($row['likesCount'] != 0) {

                        $likes = new likes($this->db, ITEM_TYPE_POST);
                        $likes->setRequestFrom($this->getRequestFrom());

                        if ($likes->is_like_exists($postId, $this->getRequestFrom())) {

                            $myLike = true;
                        }

                        unset($likes);
                    }

                    if ($row['rePostsCount'] != 0) {

                        if ($this->is_repost_exists($postId, $this->getRequestFrom())) {

                            $myRePost = true;
                        }
                    }
                }

                $groupAllowComments = 0;
                $groupAuthor = 0;

                if ($row['groupId'] != 0) {

                    $group = new group($this->db, $row['groupId']);
                    $group->setRequestFrom($this->getRequestFrom());

                    $groupInfo = $group->get();

                    $groupAllowComments = $groupInfo['allowComments'];
                    $groupAuthor = $groupInfo['accountAuthor'];

                    unset($group);
                }

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $you_tube_video_img = "";
                $you_tube_video_code = "";
                $you_tube_video_url = "";

                if (preg_match('/(?:http?:\/\/)?(?:www\.)?youtu(?:\.be|be\.com)\/(?:watch\?v=)?([\w\-]{6,12})(?:\&.+)?/i', htmlspecialchars_decode(stripslashes($row['post'])), $results)) {

                    $you_tube_video_img = "http://img.youtube.com/vi/".$results[1]."/0.jpg";
                    $you_tube_video_url = "http://www.youtube.com/v/".$results[1];
                    $you_tube_video_code = $results[1];
                }

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "postType" => $row['postType'],
                                "feeling" => $row['feeling'],
                                "accessMode" => $row['accessMode'],
                                "deviceType" => $row['deviceType'],
                                "rePostId" => $row['rePostId'],
                                "groupId" => $row['groupId'],
                                "fromUserId" => $row['fromUserId'],
                                "fromGender" => $profileInfo['sex'],
                                "fromUserVerify" => $profileInfo['verify'],
                                "fromUserBot" => $profileInfo['botaccount'],
                                "fromUserStaff" => $profileInfo['staffaccount'],
                                "fromUserOnline" => $profileInfo['online'],
                                "fromUserUsername" => $profileInfo['username'],
                                "fromUserFullname" => $profileInfo['fullname'],
                                "fromUserPhoto" => $profileInfo['lowPhotoUrl'],
                                "post" => htmlspecialchars_decode(stripslashes($row['post'])),
                                "area" => htmlspecialchars_decode(stripslashes($row['area'])),
                                "country" => htmlspecialchars_decode(stripslashes($row['country'])),
                                "city" => htmlspecialchars_decode(stripslashes($row['city'])),
                                "YouTubeVideoImg" => $you_tube_video_img,
                                "YouTubeVideoCode" => $you_tube_video_code,
                                "YouTubeVideoUrl" => $you_tube_video_url,
                                "urlPreviewTitle" => htmlspecialchars_decode(stripslashes($row['urlPreviewTitle'])),
                                "urlPreviewImage" => $row['urlPreviewImage'],
                                "urlPreviewLink" => $row['urlPreviewLink'],
                                "urlPreviewDescription" => htmlspecialchars_decode(stripslashes($row['urlPreviewDescription'])),
                                "previewVideoImgUrl" => $row['previewVideoImgUrl'],
                                "videoUrl" => $row['videoUrl'],
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "imgUrl" => $row['imgUrl'],
                                "allowComments" => $profileInfo['allowComments'],
                                "groupAllowComments" => $groupAllowComments,
                                "groupAuthor" => $groupAuthor,
                                "rating" => $row['rating'],
                                "commentsCount" => $row['commentsCount'],
                                "likesCount" => $row['likesCount'],
                                "viewsCount" => $row['viewsCount'],
                                "imagesCount" => $row['imagesCount'],
                                "rePostsCount" => $row['rePostsCount'],
                                "myLike" => $myLike,
                                "myRePost" => $myRePost,
                                "moderateAt" => $row['moderateAt'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt'],
                                "ip_addr" => $row['ip_addr'],
                                "rePost" => array(),
                                "owner" => $profileInfo);

                if ($row['rePostId'] != 0) {

                    array_push($result['rePost'], $this->repost_info($row['rePostId']));

                } else {

                    array_push($result['rePost'], array("error" => true,
                                                        "error_code" => ERROR_UNKNOWN));
                }
            }
        }

        return $result;
    }

    public function repost_info($postId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM posts WHERE id = (:postId) LIMIT 1");
        $stmt->bindParam(":postId", $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $myLike = false;

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->get();
                unset($profile);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "postType" => $row['postType'],
                                "accessMode" => $row['accessMode'],
                                "fromUserId" => $row['fromUserId'],
                                "fromGender" => $profileInfo['sex'],
                                "fromUserVerify" => $profileInfo['verify'],
                                "fromUserBot" => $profileInfo['botaccount'],
                                "fromUserStaff" => $profileInfo['staffaccount'],
                                "fromUserOnline" => $profileInfo['online'],
                                "fromUserUsername" => $profileInfo['username'],
                                "fromUserFullname" => $profileInfo['fullname'],
                                "fromUserPhoto" => $profileInfo['lowPhotoUrl'],
                                "post" => htmlspecialchars_decode(stripslashes($row['post'])),
                                "area" => htmlspecialchars_decode(stripslashes($row['area'])),
                                "country" => htmlspecialchars_decode(stripslashes($row['country'])),
                                "city" => htmlspecialchars_decode(stripslashes($row['city'])),
                                "lat" => $row['lat'],
                                "lng" => $row['lng'],
                                "imgUrl" => $row['imgUrl'],
                                "previewVideoImgUrl" => $row['previewVideoImgUrl'],
                                "videoUrl" => $row['videoUrl'],
                                "allowComments" => $profileInfo['allowComments'],
                                "rating" => $row['rating'],
                                "commentsCount" => $row['commentsCount'],
                                "likesCount" => $row['likesCount'],
                                "imagesCount" => $row['imagesCount'],
                                "rePostsCount" => $row['rePostsCount'],
                                "myLike" => $myLike,
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function get($profileId, $postId = 0, $accessMode = 0)
    {
        if ($postId == 0) {

            $postId = $this->getMaxIdPosts();
            $postId++;
        }

        $posts = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "postId" => $postId,
                       "posts" => array());

        if ($accessMode == 0) {

            $stmt = $this->db->prepare("SELECT id FROM posts WHERE accessMode = 0 AND fromUserId = (:fromUserId) AND groupId = 0 AND removeAt = 0 AND id < (:postId) ORDER BY id DESC LIMIT 20");
            $stmt->bindParam(':fromUserId', $profileId, PDO::PARAM_INT);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);

        } else {

            $stmt = $this->db->prepare("SELECT id FROM posts WHERE fromUserId = (:fromUserId) AND groupId = 0 AND removeAt = 0 AND id < (:postId) ORDER BY id DESC LIMIT 20");
            $stmt->bindParam(':fromUserId', $profileId, PDO::PARAM_INT);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $postInfo = $this->info($row['id']);

                array_push($posts['posts'], $postInfo);

                $posts['postId'] = $postInfo['id'];

                unset($postInfo);
            }
        }

        return $posts;
    }

    public function getLikers($itemId, $elementId = 0)
    {

        if ($elementId == 0) {

            $elementId = 50000;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "elementId" => $elementId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM likes WHERE itemId = (:itemId) AND id < (:elementId) AND removeAt = 0 ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':elementId', $elementId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['fromUserId']);
                    $profile->setRequestFrom($this->getRequestFrom());
                    $profileInfo = $profile->getVeryShort();
                    unset($profile);

                    array_push($result['items'], $profileInfo);

                    $result['elementId'] = $row['id'];
                }
            }
        }

        return $result;
    }

    public function query($queryText = '', $addedToListAt = 0)
    {
        $originQuery = $queryText;

        if ($addedToListAt == 0) {

            $addedToListAt = time();
        }

        $questions = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "addedToListAt" => $addedToListAt,
                        "query" => $originQuery,
                        "questions" => array());

        $queryText = "%".$queryText."%";

        $stmt = $this->db->prepare("SELECT id FROM qa WHERE question LIKE (:query) AND replyAt = 0 AND removeAt = 0 AND addedToListAt < (:addedToListAt) ORDER BY addedToListAt DESC LIMIT 50");
        $stmt->bindParam(':query', $queryText, PDO::PARAM_STR);
        $stmt->bindParam(':addedToListAt', $addedToListAt, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $questionInfo = $this->info($row['id']);

                    array_push($questions['questions'], $questionInfo);

                    $questions['addedToListAt'] = $questionInfo['addedToListAt'];
                }
            }
        }

        return $questions;
    }

    private function addScheme($url, $scheme = 'http://')
    {
        return parse_url($url, PHP_URL_SCHEME) === null ? $scheme . $url : $url;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }

    public function getProfileId()
    {
        return $this->profileId;
    }
}
