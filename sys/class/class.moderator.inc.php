<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class moderator extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function postPhoto($accountId, $imageName = "")
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $photoModerateUrl = $imageName;
        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE users SET photoModerateAt = 0, photoPostModerateAt = (:photoPostModerateAt), photoModerateUrl = (:photoModerateUrl) WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->bindParam(":photoPostModerateAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":photoModerateUrl", $photoModerateUrl, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);

            $notify = new notify($this->db);
            $notify->removeNotify($accountId, 0, NOTIFY_TYPE_PROFILE_PHOTO_APPROVE, $accountId);
            $notify->removeNotify($accountId, 0, NOTIFY_TYPE_PROFILE_PHOTO_REJECT, $accountId);
            unset($notify);
        }

        return $result;
    }

    public function approvePhoto($accountId, $notification = false)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE users SET photoModerateAt = (:photoModerateAt), photoPostModerateAt = 0, photoModerateUrl = '' WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->bindParam(":photoModerateAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($notification) {

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($accountId);
                $fcm->setType(GCM_NOTIFY_PROFILE_PHOTO_APPROVE);
                $fcm->setTitle("You profile photo is approved.");
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

                $notify = new notify($this->db);
                $notify->createNotify($accountId, 0, NOTIFY_TYPE_PROFILE_PHOTO_APPROVE, $accountId);
                unset($notify);
            }

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function rejectPhoto($accountId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("UPDATE users SET originPhotoUrl = '', normalPhotoUrl = '', bigPhotoUrl = '', lowPhotoUrl = '', photoModerateUrl = '', photoPostModerateAt = 0, photoModerateAt = 0 WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);

            $fcm = new fcm($this->db);
            $fcm->setRequestFrom($this->getRequestFrom());
            $fcm->setRequestTo($accountId);
            $fcm->setType(GCM_NOTIFY_PROFILE_PHOTO_REJECT);
            $fcm->setTitle("You profile photo is rejected.");
            $fcm->prepare();
            $fcm->send();
            unset($fcm);

            $notify = new notify($this->db);
            $notify->createNotify($accountId, 0, NOTIFY_TYPE_PROFILE_PHOTO_REJECT, $accountId);
            unset($notify);
        }

        return $result;
    }

    public function getNotModeratedPhotos($itemId = 0, $language = 'en')
    {
        if ($itemId == 0) {

            $itemId = time();
        }

        $result = array("error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array());

        $stmt = $this->db->prepare("SELECT id, photoPostModerateAt FROM users WHERE photoModerateUrl <> '' AND photoPostModerateAt < (:photoPostModerateAt) ORDER BY photoPostModerateAt DESC LIMIT 20");
        $stmt->bindParam(':photoPostModerateAt', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['id']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $profile->getVeryShort());

                    $result['itemId'] = $row['photoPostModerateAt'];

                    unset($profile);
                }
            }
        }

        return $result;
    }

    public function postCover($accountId, $imageName = "")
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $coverModerateUrl = $imageName;
        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE users SET coverPostModerateAt = (:coverPostModerateAt), coverModerateUrl = (:coverModerateUrl), coverModerateAt = 0 WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->bindParam(":coverPostModerateAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":coverModerateUrl", $coverModerateUrl, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);

            $notify = new notify($this->db);
            $notify->removeNotify($accountId, 0, NOTIFY_TYPE_PROFILE_COVER_APPROVE, $accountId);
            $notify->removeNotify($accountId, 0, NOTIFY_TYPE_PROFILE_COVER_REJECT, $accountId);
            unset($notify);
        }

        return $result;
    }

    public function approveCover($accountId, $notification = false)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $account = new account($this->db, $accountId);
        $accountInfo = $account->get();
        unset($account);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE users SET coverModerateAt = (:coverModerateAt), coverPostModerateAt = 0, coverModerateUrl = '' WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);
        $stmt->bindParam(":coverModerateAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($notification) {

                $fcm = new fcm($this->db);
                $fcm->setRequestFrom($this->getRequestFrom());
                $fcm->setRequestTo($accountId);
                $fcm->setType(GCM_NOTIFY_PROFILE_COVER_APPROVE);
                $fcm->setTitle("You profile cover is approved.");
                $fcm->prepare();
                $fcm->send();
                unset($fcm);

                $notify = new notify($this->db);
                $notify->createNotify($accountId, 0, NOTIFY_TYPE_PROFILE_COVER_APPROVE, $accountId);
                unset($notify);
            }

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function rejectCover($accountId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN);

        $account = new account($this->db, $accountId);
        $accountInfo = $account->get();
        unset($account);

        $stmt = $this->db->prepare("UPDATE users SET originCoverUrl = '', normalCoverUrl = '', coverModerateUrl = '', coverPostModerateAt = 0, coverModerateAt = 0 WHERE id = (:accountId)");
        $stmt->bindParam(":accountId", $accountId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS);

            $fcm = new fcm($this->db);
            $fcm->setRequestFrom($this->getRequestFrom());
            $fcm->setRequestTo($accountId);
            $fcm->setType(GCM_NOTIFY_PROFILE_COVER_REJECT);
            $fcm->setTitle("You profile cover is rejected.");
            $fcm->prepare();
            $fcm->send();
            unset($fcm);

            $notify = new notify($this->db);
            $notify->createNotify($accountId, 0, NOTIFY_TYPE_PROFILE_COVER_REJECT, $accountId);
            unset($notify);

            unlink(TEMP_PATH.basename($accountInfo['coverModerateUrl']));
        }

        return $result;
    }

    public function getNotModeratedCovers($itemId = 0, $language = 'en')
    {
        if ($itemId == 0) {

            $itemId = time();
        }

        $result = array("error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array());

        $stmt = $this->db->prepare("SELECT id, coverPostModerateAt FROM users WHERE coverModerateUrl <> '' AND coverPostModerateAt < (:coverPostModerateAt) ORDER BY coverPostModerateAt DESC LIMIT 20");
        $stmt->bindParam(':coverPostModerateAt', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['id']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $profile->getVeryShort());

                    $result['itemId'] = $row['coverPostModerateAt'];

                    unset($profile);
                }
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
