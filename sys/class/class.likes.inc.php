<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class likes extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';
    private $table = 'likes';
    private $itemType = ITEM_TYPE_POST;

	public function __construct($dbo = NULL, $itemType = ITEM_TYPE_POST)
    {
		parent::__construct($dbo);

        $this->setItemType($itemType);

		if ($itemType == ITEM_TYPE_POST) {

		    $this->table = 'likes';

        } else {

            $this->table = 'gallery_likes';
        }
	}

    public function allLikesCount()
    {
        $sql = "SELECT max(id) FROM ".$this->table;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count($itemId)
    {
        $sql = "SELECT count(*) FROM $this->table WHERE itemId = (:itemId) AND removeAt = 0";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function is_like_exists($itemId, $fromUserId)
    {
        $sql = "SELECT id FROM $this->table WHERE fromUserId = (:fromUserId) AND itemId = (:itemId) AND removeAt = 0 LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":fromUserId", $fromUserId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            return true;
        }

        return false;
    }

    public function get($itemId, $elementId = 0)
    {

        if ($elementId == 0) {

            $elementId = 100000;
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "elementId" => $elementId,
            "items" => array()
        );

        $sql = "SELECT * FROM $this->table WHERE itemId = (:itemId) AND id < (:pageId) AND removeAt = 0 ORDER BY id DESC LIMIT 20";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':pageId', $elementId, PDO::PARAM_INT);

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

    public function like($itemId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        if ($this->getItemType() == ITEM_TYPE_POST) {

            $item = new post($this->db);
            $item->setRequestFrom($this->getRequestFrom());

            $gcm_notify_type = GCM_NOTIFY_LIKE;
            $notify_type = NOTIFY_TYPE_LIKE;

        } else {

            $item = new gallery($this->db);
            $item->setRequestFrom($this->getRequestFrom());

            $gcm_notify_type = GCM_NOTIFY_IMAGE_LIKE;
            $notify_type = NOTIFY_TYPE_IMAGE_LIKE;
        }

        $itemInfo = $item->info($itemId);

        if ($itemInfo['error']) {

            return $result;
        }

        if ($itemInfo['removeAt'] != 0) {

            return $result;
        }

        if ($this->is_like_exists($itemId, $this->getRequestFrom())) {

            $removeAt = time();

            $sql = "UPDATE $this->table SET removeAt = (:removeAt) WHERE itemId = (:itemId) AND fromUserId = (:fromUserId) AND removeAt = 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
            $stmt->bindParam(":removeAt", $removeAt, PDO::PARAM_INT);
            $stmt->execute();

            $notify = new notify($this->db);
            $notify->removeNotify($itemInfo['fromUserId'], $this->getRequestFrom(), NOTIFY_TYPE_IMAGE_LIKE, $itemId);
            unset($notify);

            $itemInfo['likesCount'] = $itemInfo['likesCount'] - 1;
            $itemInfo['myLike'] = false;

        } else {

            $createAt = time();
            $ip_addr = helper::ip_addr();

            $sql = "INSERT INTO $this->table (toUserId, fromUserId, itemId, createAt, ip_addr) value (:toUserId, :fromUserId, :itemId, :createAt, :ip_addr)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":toUserId", $itemInfo['fromUserId'], PDO::PARAM_INT);
            $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
            $stmt->bindParam(":createAt", $createAt, PDO::PARAM_INT);
            $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
            $stmt->execute();

            if ($itemInfo['fromUserId'] != $this->getRequestFrom()) {

                $blacklist = new blacklist($this->db);
                $blacklist->setRequestFrom($itemInfo['fromUserId']);

                if (!$blacklist->isExists($this->getRequestFrom())) {

                    $fcm = new fcm($this->db);
                    $fcm->setRequestFrom($this->getRequestFrom());
                    $fcm->setRequestTo($itemInfo['fromUserId']);
                    $fcm->setType($gcm_notify_type);
                    $fcm->setTitle("You have new like");
                    $fcm->setItemId($itemId);
                    $fcm->prepare();
                    $fcm->send();
                    unset($fcm);

                    $notify = new notify($this->db);
                    $notify->createNotify($itemInfo['fromUserId'], $this->getRequestFrom(), $notify_type, $itemId);
                    unset($notify);
                }

                unset($blacklist);
            }

            $itemInfo['likesCount'] = $itemInfo['likesCount'] + 1;
            $itemInfo['myLike'] = true;
        }

        $item->recalculate($itemId);

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "likesCount" => $itemInfo['likesCount'],
            "commentsCount" => $itemInfo['commentsCount'],
            "rePostsCount" => 0,
            "myLike" => $itemInfo['myLike']);

        if ($this->getItemType() == ITEM_TYPE_POST) {

            $result['rePostsCount'] = $itemInfo['rePostsCount'];
        }

        return $result;
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    public function getItemType()
    {
        return $this->itemType;
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
