<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class blacklist extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function activeItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_blacklist WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function myActiveItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM profile_blacklist WHERE blockedByUserId = (:blockedByUserId) AND removeAt = 0");
        $stmt->bindParam(":blockedByUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxIdBlackList()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM profile_blacklist");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function add($userId, $reason = "")
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO profile_blacklist (blockedByUserId, blockedUserId, reason, createAt, ip_addr, u_agent) value (:blockedByUserId, :blockedUserId, :reason, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":blockedByUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":blockedUserId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function remove($userId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE profile_blacklist SET removeAt = (:removeAt) WHERE blockedUserId = (:blockedUserId) AND blockedByUserId = (:blockedByUserId)");
        $stmt->bindParam(":blockedByUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":blockedUserId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function isExists($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM profile_blacklist WHERE blockedByUserId = (:blockedByUserId) AND blockedUserId = (:blockedUserId) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":blockedByUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":blockedUserId", $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                return true;
            }
        }

        return false;
    }

    private function itemInfo($itemId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM profile_blacklist WHERE id = (:itemId) LIMIT 1");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $profile = new profile($this->db, $row['blockedUserId']);
                $blockedUserId = $profile->get();
                unset($profile);

                $lowPhotoUrl = "/img/profile_default_photo.png";

                if (strlen($blockedUserId['lowPhotoUrl']) != 0) {

                    $lowPhotoUrl = $blockedUserId['lowPhotoUrl'];
                }

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "blockedUserId" => $row['blockedUserId'],
                                "blockedUserState" => $blockedUserId['state'],
                                "blockedUserVerify" => $blockedUserId['verify'],
                                "blockedUserUsername" => $blockedUserId['username'],
                                "blockedUserFullname" => $blockedUserId['fullname'],
                                "blockedUserPhotoUrl" => $lowPhotoUrl,
                                "blockedUserOnline" => $blockedUserId['online'],
                                "reason" => htmlspecialchars_decode(stripslashes($row['reason'])),
                                "createAt" => $row['createAt'],
                                "removeAt" => $row['removeAt'],
                                "timeAgo" => $time->timeAgo($row['createAt']));
            }
        }

        return $result;
    }

    public function get($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxIdBlackList();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM profile_blacklist WHERE blockedByUserId = (:blockedByUserId) AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(":blockedByUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = $this->itemInfo($row['id']);

                array_push($result['items'], $itemInfo);

                $result['itemId'] = $itemInfo['id'];

                unset($itemInfo);
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

