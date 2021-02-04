<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class comments extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';
    private $table = 'comments';
    private $itemType = ITEM_TYPE_POST;

	public function __construct($dbo = NULL, $itemType = ITEM_TYPE_POST)
    {
		parent::__construct($dbo);

		$this->setItemType($itemType);

        if ($itemType == ITEM_TYPE_POST) {

            $this->table = 'comments';

        } else {

            $this->table = 'gallery_comments';
        }
	}

    public function allCommentsCount()
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

    public function create($itemId, $text, $notifyId = 0, $replyToUserId = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($this->getItemType() == ITEM_TYPE_POST) {

            if ($spam->getItemCommentsCount() > 30) {

                return $result;
            }

        } else {

            if ($spam->getGalleryCommentsCount() > 30) {

                return $result;
            }
        }

        unset($spam);

        if (strlen($text) == 0) {

            return $result;
        }

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $sql = "INSERT INTO $this->table (fromUserId, replyToUserId, itemId, comment, createAt, notifyId, ip_addr, u_agent) value (:fromUserId, :replyToUserId, :itemId, :comment, :createAt, :notifyId, :ip_addr, :u_agent)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":replyToUserId", $replyToUserId, PDO::PARAM_INT);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":comment", $text, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":notifyId", $notifyId, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "commentId" => $this->db->lastInsertId(),
                "comment" => $this->info($this->db->lastInsertId())
            );

            if ($this->getItemType() == ITEM_TYPE_POST) {

                $item = new post($this->db);
                $item->setRequestFrom($this->getRequestFrom());
                $itemInfo = $item->info($itemId);

                $gcm_notify_comment = GCM_NOTIFY_COMMENT;
                $gcm_notify_comment_reply = GCM_NOTIFY_COMMENT_REPLY;
                $notify_type_comment = NOTIFY_TYPE_COMMENT;
                $notify_type_comment_reply = NOTIFY_TYPE_COMMENT_REPLY;

            } else {

                $item = new gallery($this->db);
                $item->setRequestFrom($this->getRequestFrom());
                $itemInfo = $item->info($itemId);

                $gcm_notify_comment = GCM_NOTIFY_IMAGE_COMMENT;
                $gcm_notify_comment_reply = GCM_NOTIFY_IMAGE_COMMENT_REPLY;
                $notify_type_comment = NOTIFY_TYPE_IMAGE_COMMENT;
                $notify_type_comment_reply = NOTIFY_TYPE_IMAGE_COMMENT_REPLY;
            }

            if (($this->getRequestFrom() != $itemInfo['fromUserId']) && ($replyToUserId != $itemInfo['fromUserId'])) {

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($itemInfo['fromUserId']);
                $fcm->setType($gcm_notify_comment);
                $fcm->setTitle("You have a new comment.");
                $fcm->setItemId($itemId);
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

                $notify = new notify($this->db);
                $notifyId = $notify->createNotify($itemInfo['fromUserId'], $this->getRequestFrom(), $notify_type_comment, $itemInfo['id']);
                unset($notify);

                $this->setNotifyId($result['commentId'], $notifyId);
            }

            if ($replyToUserId != $this->getRequestFrom() && $replyToUserId != 0) {

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($replyToUserId);
                $fcm->setType($gcm_notify_comment_reply);
                $fcm->setTitle("You have a new reply to comment.");
                $fcm->setItemId($itemId);
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

                $notify = new notify($this->db);
                $notifyId = $notify->createNotify($replyToUserId, $this->getRequestFrom(), $notify_type_comment_reply, $itemInfo['id']);
                unset($notify);
            }

            $item->recalculate($itemId);
        }

        unset($item);

        return $result;
    }

    private function setNotifyId($commentId, $notifyId)
    {
        $sql = "UPDATE $this->table SET notifyId = (:notifyId) WHERE id = (:commentId)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $stmt->bindParam(":notifyId", $notifyId, PDO::PARAM_INT);

        $stmt->execute();
    }

    public function remove($commentId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $commentInfo = $this->info($commentId);

        if ($commentInfo['error']) {

            return $result;
        }

        $currentTime = time();

        $sql = "UPDATE $this->table SET removeAt = (:removeAt) WHERE id = (:commentId)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":commentId", $commentId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $notify = new notify($this->db);
            $notify->remove($commentInfo['notifyId']);
            unset($notify);

            if ($this->getItemType() == ITEM_TYPE_POST) {

                $item = new post($this->db);
                $item->setRequestFrom($this->getRequestFrom());

            } else {

                $item = new gallery($this->db);
                $item->setRequestFrom($this->getRequestFrom());
            }

            $item->recalculate($commentInfo['itemId']);

            unset($item);

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );
        }

        return $result;
    }

    public function removeAll($itemId) {

        $currentTime = time();

        $sql = "UPDATE $this->table SET removeAt = (:removeAt) WHERE itemId = (:itemId)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
    }

    public function info($commentId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $sql = "SELECT * FROM $this->table WHERE id = (:commentId) LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":commentId", $commentId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = $this->quickInfo($row);
            }
        }

        return $result;
    }

    public function quickInfo($row, $itemFromUserId = 0)
    {
        $time = new language($this->db, $this->language);

        $profile = new profile($this->db, $row['fromUserId']);
        $fromUserId = $profile->getVeryShort();
        unset($profile);

        $replyToUserId = $row['replyToUserId'];
        $replyToUserUsername = "";
        $replyToFullname = "";

        if ($replyToUserId != 0) {

            $profile = new profile($this->db, $row['replyToUserId']);
            $replyToUser = $profile->getVeryShort();
            unset($profile);

            $replyToUserUsername = $replyToUser['username'];
            $replyToFullname = $replyToUser['fullname'];
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "id" => $row['id'],
            "owner" => $fromUserId,
            "comment" => htmlspecialchars_decode(stripslashes($row['comment'])),
            "fromUserId" => $row['fromUserId'],
            "replyToUserId" => $replyToUserId,
            "replyToUserUsername" => $replyToUserUsername,
            "replyToFullname" => $replyToFullname,
            "itemId" => $row['itemId'],
            "itemFromUserId" => $itemFromUserId,
            "itemType" => $this->getItemType(),
            "createAt" => $row['createAt'],
            "notifyId" => $row['notifyId'],
            "timeAgo" => $time->timeAgo($row['createAt']));

        if ($itemFromUserId == 0) {

            if ($this->getItemType() == ITEM_TYPE_POST) {

                $item = new post($this->db);
                $item->setRequestFrom($this->getRequestFrom());
                $itemInfo = $item->info($row['itemId']);

                $result['itemFromUserId'] = $itemInfo['fromUserId'];
                $result['itemAllowComments'] = $itemInfo['allowComments'];

            } else {

                $item = new gallery($this->db);
                $item->setRequestFrom($this->getRequestFrom());
                $itemInfo = $item->info($row['itemId']);

                $result['itemFromUserId'] = $itemInfo['fromUserId'];
                $result['itemAllowComments'] = $itemInfo['fromUserAllowGalleryComments'];
            }
        }

        return $result;
    }

    public function get($itemId, $commentId = 0, $itemInfo = array())
    {
        if ($commentId == 0) {

            $commentId = 100000;
        }

        if (empty($itemInfo)) {

            if ($this->getItemType() == ITEM_TYPE_POST) {

                $item = new post($this->db);
                $item->setRequestFrom($this->getRequestFrom());

            } else {

                $item = new gallery($this->db);
                $item->setRequestFrom($this->getRequestFrom());
            }

            $itemInfo = $item->info($itemId);
        }

        $comments = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "commentId" => $commentId,
            "itemId" => $itemId,
            "comments" => array()
        );

        $sql = "SELECT * FROM $this->table WHERE itemId = (:itemId) AND id < (:commentId) AND removeAt = 0 ORDER BY id DESC LIMIT 70";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $commentInfo = $this->quickInfo($row, $itemInfo['fromUserId']);

                array_push($comments['comments'], $commentInfo);

                $comments['commentId'] = $commentInfo['id'];

                unset($commentInfo);
            }
        }

        return $comments;
    }

    public function getPreview($itemId)
    {
        $comments = array("error" => false,
                          "error_code" => ERROR_SUCCESS,
                          "itemId" => $itemId,
                          "count" => $this->count($itemId),
                          "comments" => array());

        $sql = "SELECT * FROM $this->table WHERE itemId = (:itemId) AND removeAt = 0 ORDER BY id DESC LIMIT 3";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $commentInfo = $this->quickInfo($row);

                array_push($comments['comments'], $commentInfo);

                $comments['commentId'] = $commentInfo['id'];

                unset($commentInfo);
            }
        }

        return $comments;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setItemType($itemType)
    {
        $this->itemType = $itemType;
    }

    public function getItemType()
    {
        return $this->itemType;
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
