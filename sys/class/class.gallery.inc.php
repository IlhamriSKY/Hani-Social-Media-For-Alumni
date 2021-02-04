<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class gallery extends db_connect
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
        $stmt = $this->db->prepare("SELECT count(*) FROM gallery");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gallery WHERE fromUserId = (:fromUserId) AND removeAt = 0");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function add($comment, $originImgUrl = "", $previewImgUrl = "", $imgUrl = "", $previewVideoImgUrl = "", $videoUrl = "", $photoArea = "", $photoCountry = "", $photoCity = "", $photoLat = "0.00000", $photoLng = "0.00000")
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getGalleryItemsCount() > 15) {

            return $result;
        }

        unset($spam);

        if (strlen($originImgUrl) == 0 && strlen($previewImgUrl) == 0 && strlen($imgUrl) == 0 && strlen($videoUrl) == 0) {

            return $result;
        }

        if (strlen($comment) != 0) {

            $comment = $comment." ";
        }

        $itemType = GALLERY_ITEM_TYPE_IMAGE;

        if (strlen($videoUrl) != 0) {

            $itemType = GALLERY_ITEM_TYPE_VIDEO;
        }

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO gallery (fromUserId, itemType, comment, originImgUrl, previewImgUrl, imgUrl, previewVideoImgUrl, videoUrl, area, country, city, lat, lng, createAt, ip_addr, u_agent) value (:fromUserId, :itemType, :comment, :originImgUrl, :previewImgUrl, :imgUrl, :previewVideoImgUrl, :videoUrl, :area, :country, :city, :lat, :lng, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":itemType", $itemType, PDO::PARAM_INT);
        $stmt->bindParam(":comment", $comment, PDO::PARAM_STR);
        $stmt->bindParam(":originImgUrl", $originImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":previewImgUrl", $previewImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":previewVideoImgUrl", $previewVideoImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":videoUrl", $videoUrl, PDO::PARAM_STR);
        $stmt->bindParam(":area", $photoArea, PDO::PARAM_STR);
        $stmt->bindParam(":country", $photoCountry, PDO::PARAM_STR);
        $stmt->bindParam(":city", $photoCity, PDO::PARAM_STR);
        $stmt->bindParam(":lat", $photoLat, PDO::PARAM_STR);
        $stmt->bindParam(":lng", $photoLng, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "itemId" => $this->db->lastInsertId(),
                            "item" => $this->info($this->db->lastInsertId()));

            $this->update();
        }

        return $result;
    }

    private function update() {

        $account = new account($this->db, $this->getRequestFrom());
        $account->updateCounters();
        unset($account);
    }

    public function remove($itemId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_SUCCESS);

        $itemInfo = $this->info($itemId);

        if ($itemInfo['error']) {

            return $result;
        }

        if ($itemInfo['fromUserId'] != $this->getRequestFrom()) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE gallery SET removeAt = (:removeAt) WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            //remove all reports to

            $reports = new reports($this->db);
            $reports->remove(REPORT_TYPE_GALLERY_ITEM, $itemId);
            unset($reports);

            //

            $stmt2 = $this->db->prepare("DELETE FROM notifications WHERE postId = (:postId) AND notifyType > 6");
            $stmt2->bindParam(":postId", $itemId, PDO::PARAM_INT);
            $stmt2->execute();

            //remove all comments to item

            $stmt3 = $this->db->prepare("UPDATE gallery_comments SET removeAt = (:removeAt) WHERE itemId = (:itemId)");
            $stmt3->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
            $stmt3->bindParam(":itemId", $itemId, PDO::PARAM_INT);
            $stmt3->execute();

            //remove all likes to item

            $stmt4 = $this->db->prepare("UPDATE gallery_likes SET removeAt = (:removeAt) WHERE itemId = (:itemId) AND removeAt = 0");
            $stmt4->bindParam(":itemId", $itemId, PDO::PARAM_INT);
            $stmt4->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
            $stmt4->execute();

            $result = array(
                "error" => false
            );

            $this->update();
        }

        return $result;
    }

    public function restore($itemId)
    {
        $result = array(
            "error" => true
        );

        $itemInfo = $this->info($itemId);

        if ($itemInfo['error']) {

            return $result;
        }

        $stmt = $this->db->prepare("UPDATE gallery SET removeAt = 0 WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false
            );
        }

        return $result;
    }

    public function recalculate($itemId) {

        $comments_count = 0;
        $likes_count = 0;
        $rating = 0;

        $comments = new comments($this->db, ITEM_TYPE_GALLERY);
        $comments->setRequestFrom($this->getRequestFrom());
        $comments_count = $comments->count($itemId);
        unset($comments);

        $likes = new likes($this->db, ITEM_TYPE_GALLERY);
        $likes->setRequestFrom($this->getRequestFrom());
        $likes_count = $likes->count($itemId);
        unset($likes);

        $rating = $likes_count + $comments_count;

        $stmt = $this->db->prepare("UPDATE gallery SET likesCount = (:likesCount), commentsCount = (:commentsCount), rating = (:rating) WHERE id = (:itemId)");
        $stmt->bindParam(":likesCount", $likes_count, PDO::PARAM_INT);
        $stmt->bindParam(":commentsCount", $comments_count, PDO::PARAM_INT);
        $stmt->bindParam(":rating", $rating, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        $account = new account($this->db, $this->requestFrom);
        $account->updateCounters();
        unset($account);
    }

    public function quickInfo($row) {

        $time = new language($this->db, $this->getLanguage());

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "id" => $row['id'],
            "itemType" => $row['itemType'],
            "fromUserId" => $row['fromUserId'],
            "comment" => htmlspecialchars_decode(stripslashes($row['comment'])),
            "area" => htmlspecialchars_decode(stripslashes($row['area'])),
            "country" => htmlspecialchars_decode(stripslashes($row['country'])),
            "city" => htmlspecialchars_decode(stripslashes($row['city'])),
            "lat" => $row['lat'],
            "lng" => $row['lng'],
            "imgUrl" => $row['imgUrl'],
            "previewImgUrl" => $row['previewImgUrl'],
            "originImgUrl" => $row['originImgUrl'],
            "previewVideoImgUrl" => $row['previewVideoImgUrl'],
            "videoUrl" => $row['videoUrl'],
            "rating" => $row['rating'],
            "commentsCount" => $row['commentsCount'],
            "likesCount" => $row['likesCount'],
            "myLike" => false,
            "createAt" => $row['createAt'],
            "date" => date("Y-m-d H:i:s", $row['createAt']),
            "timeAgo" => $time->timeAgo($row['createAt']),
            "removeAt" => $row['removeAt']
        );

        return $result;
    }

    public function info($itemId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $stmt = $this->db->prepare("SELECT * FROM gallery WHERE id = (:itemId) LIMIT 1");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = $this->quickInfo($row);

                $myLike = false;

                if ($this->getRequestFrom() != 0) {

                    $likes = new likes($this->db, ITEM_TYPE_GALLERY);
                    $likes->setRequestFrom($this->getRequestFrom());

                    if ($likes->is_like_exists($itemId, $this->getRequestFrom())) {

                        $myLike = true;
                    }

                    unset($likes);
                }

                $result['myLike'] = $myLike;

                $profile = new profile($this->db, $row['fromUserId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $result['fromUserAllowGalleryComments'] = $profileInfo['allowGalleryComments'];

                $result['owner'] = array();

                $result['owner'] = $profileInfo;
            }
        }

        return $result;
    }

    public function get($profileId, $itemType = -1, $itemId = 0, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = 32000;
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array());

        if ($itemType == -1) {

            $stmt = $this->db->prepare("SELECT * FROM gallery WHERE fromUserId = (:fromUserId) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT :limit");
            $stmt->bindParam(':fromUserId', $profileId, PDO::PARAM_INT);
            $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        } else {

            $stmt = $this->db->prepare("SELECT * FROM gallery WHERE fromUserId = (:fromUserId) AND itemType = (:itemType) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT :limit");
            $stmt->bindParam(':fromUserId', $profileId, PDO::PARAM_INT);
            $stmt->bindParam(':itemType', $itemType, PDO::PARAM_INT);
            $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                array_push($result['items'], $this->quickInfo($row));

                $result['itemId'] = $row['id'];
            }
        }

        return $result;
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
}
