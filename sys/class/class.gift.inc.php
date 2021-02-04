<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class gift extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM photos");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function db_getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM gifts_data");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM gifts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM gifts WHERE giftTo = (:giftTo) AND removeAt = 0");
        $stmt->bindParam(":giftTo", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function db_add($cost, $category, $imgUrl = "")
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if (strlen($imgUrl) == 0) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO gifts_data (cost, category, imgUrl, createAt) value (:cost, :category, :imgUrl, :createAt)");
        $stmt->bindParam(":cost", $cost, PDO::PARAM_INT);
        $stmt->bindParam(":category", $category, PDO::PARAM_INT);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "giftId" => $this->db->lastInsertId(),
                            "gift" => $this->db_info($this->db->lastInsertId()));
        }

        return $result;
    }

    public function send($giftId, $giftTo, $message, $giftAnonymous = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        if ($giftId == 0) {

            return $result;
        }

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getSendGiftsCount() > 15) {

            return $result;
        }

        unset($spam);

        $giftInfo = $this->db_info($giftId);

        if ($giftInfo['error'] === true) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO gifts (giftId, giftTo, giftFrom, giftAnonymous, message, imgUrl, createAt) value (:giftId, :giftTo, :giftFrom, :giftAnonymous, :message, :imgUrl, :createAt)");
        $stmt->bindParam(":giftId", $giftId, PDO::PARAM_INT);
        $stmt->bindParam(":giftTo", $giftTo, PDO::PARAM_INT);
        $stmt->bindParam(":giftFrom", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":giftAnonymous", $giftAnonymous, PDO::PARAM_INT);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $giftInfo['imgUrl'], PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $giftId = $this->db->lastInsertId();

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "giftId" => $this->db->lastInsertId(),
                            "gift" => $this->info($this->db->lastInsertId()));

            $account = new account($this->db, $giftTo);
            $account->updateCounters();
            unset($account);

            if ($this->requestFrom != $giftTo) {

                $blacklist = new blacklist($this->db);
                $blacklist->setRequestFrom($giftTo);

                if (!$blacklist->isExists($this->requestFrom)) {

                    $fcm = new fcm($this->db);
                    $fcm->setRequestFrom($this->getRequestFrom());
                    $fcm->setRequestTo($giftTo);
                    $fcm->setType(GCM_NOTIFY_GIFT);
                    $fcm->setTitle("You have new gift");
                    $fcm->setItemId(0);
                    $fcm->prepare();
                    $fcm->send();
                    unset($fcm);

                    $notify = new notify($this->db);
                    $notify->createNotify($giftTo, $this->requestFrom, NOTIFY_TYPE_GIFT, $giftId);
                    unset($notify);
                }

                unset($blacklist);
            }
        }

        return $result;
    }

    public function db_remove($giftId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE gifts_data SET removeAt = (:removeAt) WHERE id = (:giftId)");
        $stmt->bindParam(":giftId", $giftId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function remove($giftId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $giftInfo = $this->info($giftId);

        if ($giftInfo['error'] === true) {

            return $result;
        }

        if ($giftInfo['giftTo'] != $this->requestFrom) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE gifts SET removeAt = (:removeAt) WHERE id = (:giftId)");
        $stmt->bindParam(":giftId", $giftId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);

            $account = new account($this->db, $giftInfo['giftTo']);
            $account->updateCounters();
            unset($account);

            $notify = new notify($this->db);
            $notify->removeNotify($giftInfo['giftTo'], $giftInfo['giftFrom'], NOTIFY_TYPE_GIFT, $giftInfo['id']);
            unset($notify);
        }

        return $result;
    }

    public function db_info($giftId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM gifts_data WHERE id = (:giftId) LIMIT 1");
        $stmt->bindParam(":giftId", $giftId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "cost" => $row['cost'],
                                "category" => $row['category'],
                                "imgUrl" => $row['imgUrl'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function info($giftId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM gifts WHERE id = (:giftId) LIMIT 1");
        $stmt->bindParam(":giftId", $giftId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['giftFrom']);
                $profileInfo = $profile->get();
                unset($profile);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "giftId" => $row['giftId'],
                                "giftTo" => $row['giftTo'],
                                "giftFrom" => $row['giftFrom'],
                                "giftFromUserVerify" => $profileInfo['verify'],
                                "giftFromUserVerified" => $profileInfo['verified'],
                                "giftFromUserOnline" => $profileInfo['online'],
                                "giftFromUserUsername" => $profileInfo['username'],
                                "giftFromUserFullname" => $profileInfo['fullname'],
                                "giftFromUserPhoto" => $profileInfo['lowPhotoUrl'],
                                "giftAnonymous" => $row['giftAnonymous'],
                                "message" => htmlspecialchars_decode(stripslashes($row['message'])),
                                "imgUrl" => $row['imgUrl'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function db_get($itemId = 0, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = $this->db_getMaxId();
            $itemId++;
        }

        $items = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemId" => $itemId,
                       "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM gifts_data WHERE removeAt = 0 AND id < (:giftId) ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':giftId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = $this->db_info($row['id']);

                array_push($items['items'], $itemInfo);

                $items['itemId'] = $itemInfo['id'];

                unset($itemInfo);
            }
        }

        return $items;
    }

    public function get($profileId, $itemId = 0, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $items = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemId" => $itemId,
                       "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM gifts WHERE giftTo = (:giftTo) AND removeAt = 0 AND id < (:giftId) ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':giftTo', $profileId, PDO::PARAM_INT);
        $stmt->bindParam(':giftId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = $this->info($row['id']);

                array_push($items['items'], $itemInfo);

                $items['itemId'] = $itemInfo['id'];

                unset($itemInfo);
            }
        }

        return $items;
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
