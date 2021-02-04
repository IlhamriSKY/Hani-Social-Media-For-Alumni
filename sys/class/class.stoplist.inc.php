<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class stoplist extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function activeItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM stoplist WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM stoplist");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function add($ip_addr, $byAdminId = 0, $reason = "")
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO stoplist (byAdminId, reason, createAt, ip_addr) value (:byAdminId, :reason, :createAt, :ip_addr)");
        $stmt->bindParam(":byAdminId", $byAdminId, PDO::PARAM_INT);
        $stmt->bindParam(":reason", $reason, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function remove($ip_addr)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE stoplist SET removeAt = (:removeAt) WHERE ip_addr = (:ip_addr)");
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function isExists($ip_addr)
    {
        $stmt = $this->db->prepare("SELECT * FROM stoplist WHERE ip_addr = (:ip_addr) AND removeAt = 0 LIMIT 1");
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

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

        $stmt = $this->db->prepare("SELECT * FROM stoplist WHERE id = (:itemId) LIMIT 1");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "byAdminId" => $row['byAdminId'],
                                "ip_addr" => $row['ip_addr'],
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

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM stoplist WHERE removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
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

