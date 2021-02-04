<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class guests extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';
    private $profileId = 0;

	public function __construct($dbo = NULL, $profileId = 0)
    {
		parent::__construct($dbo);

        $this->setProfileId($profileId);
	}

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM guests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM guests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM guests WHERE guestTo = (:guestTo) AND removeAt = 0");
        $stmt->bindParam(":guestTo", $this->profileId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function add($guestId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getGuestsCount() > 20) {

            return $result;
        }

        unset($spam);

        $this->delete($guestId);

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO guests (guestId, guestTo, lastVisitAt, createAt, ip_addr, u_agent) value (:guestId, :guestTo, :lastVisitAt, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":guestId", $guestId, PDO::PARAM_INT);
        $stmt->bindParam(":guestTo", $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(":lastVisitAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "itemId" => $this->db->lastInsertId(),
                            "guest" => $this->info($this->db->lastInsertId()));
        }

        return $result;
    }

    public function clear()
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE guests SET removeAt = (:removeAt) WHERE guestTo = (:guestTo) AND removeAt = 0");
        $stmt->bindParam(":guestTo", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function remove($itemId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $itemInfo = $this->info($itemId);

        if ($itemInfo['error'] === true) {

            return $result;
        }

        if ($itemInfo['guestTo'] != $this->requestFrom) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE guests SET removeAt = (:removeAt) WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    private function delete($guestId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE guests SET removeAt = (:removeAt) WHERE guestTo = (:guestTo) AND guestId = (:guestId) AND removeAt = 0");
        $stmt->bindParam(":guestTo", $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(":guestId", $guestId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function info($itemId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM guests WHERE id = (:itemId) LIMIT 1");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['guestId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "guestUserId" => $row['guestId'],
                                "guestUserVerify" => $profileInfo['verify'],
                                "guestUserUsername" => $profileInfo['username'],
                                "guestUserFullname" => $profileInfo['fullname'],
                                "guestUserPhoto" => $profileInfo['lowPhotoUrl'],
                                "guestUserOnline" => $profileInfo['online'],
                                "guestTo" => $row['guestTo'],
                                "times" => $row['times'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "lastVisitAt" => $row['lastVisitAt'],
                                "timeAgo" => $time->timeAgo($row['lastVisitAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function get($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $guests = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM guests WHERE guestTo = (:guestTo) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':guestTo', $this->profileId, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['guestId']);
                $profileInfo = $profile->getVeryShort();
                unset($profile);

                $guestInfo = array("error" => false,
                                   "error_code" => ERROR_SUCCESS,
                                   "id" => $row['id'],
                                   "guestUserId" => $row['guestId'],
                                   "guestUserVerify" => $profileInfo['verify'],
                                   "guestUserUsername" => $profileInfo['username'],
                                   "guestUserFullname" => $profileInfo['fullname'],
                                   "guestUserPhoto" => $profileInfo['lowPhotoUrl'],
                                   "guestUserOnline" => $profileInfo['online'],
                                   "guestTo" => $row['guestTo'],
                                   "times" => $row['times'],
                                   "createAt" => $row['createAt'],
                                   "date" => date("Y-m-d H:i:s", $row['createAt']),
                                   "lastVisitAt" => $row['lastVisitAt'],
                                   "timeAgo" => $time->timeAgo($row['lastVisitAt']),
                                   "removeAt" => $row['removeAt']);

                array_push($guests['items'], $guestInfo);

                $guests['itemId'] = $guestInfo['id'];

                unset($guestInfo);
            }
        }

        return $guests;
    }

    public function getNewCount($lastGuestsView)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM guests WHERE guestTo = (:guestTo) AND createAt > (:lastGuestsView) AND removeAt = 0");
        $stmt->bindParam(":guestTo", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":lastGuestsView", $lastGuestsView, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
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
