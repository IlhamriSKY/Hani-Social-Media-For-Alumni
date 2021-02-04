<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class payments extends db_connect
{

	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM payments");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function create($paymentAction, $paymentType, $credits, $amount = 0, $currency = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO payments (fromUserId, paymentAction, paymentType, credits, amount, currency, createAt, ip_addr, u_agent) value (:fromUserId, :paymentAction, :paymentType, :credits, :amount, :currency, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":paymentAction", $paymentAction, PDO::PARAM_INT);
        $stmt->bindParam(":paymentType", $paymentType, PDO::PARAM_INT);
        $stmt->bindParam(":credits", $credits, PDO::PARAM_INT);
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
        $stmt->bindParam(":currency", $currency, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );
        }

        return $result;
    }

    public function remove($itemId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE payments SET removeAt = (:removeAt) WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );
        }

        return $result;
    }

    public function clear() {

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE payments SET removeAt = (:removeAt) WHERE fromUserId = (:fromUserId)");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);
    }

    public function info($itemId)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = (:itemId) LIMIT 1");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $result = $this->quickInfo($row);
            }
        }

        return $result;
    }

    public function quickInfo($row, $details = false)
    {
        $time = new language($this->db, $this->language);

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "id" => $row['id'],
            "fromUserId" => $row['fromUserId'],
            "paymentAction" => $row['paymentAction'],
            "paymentType" => $row['paymentType'],
            "credits" => $row['credits'],
            "amount" => $row['amount'],
            "currency" => $row['currency'],
            "date" => date("Y-m-d H:i:s", $row['createAt']),
            "createAt" => $row['createAt'],
            "timeAgo" => $time->timeAgo($row['createAt'])
        );

        if ($details) {

            $profile = new profile($this->db, $row['fromUserId']);
            $profileInfo = $profile->getVeryShort();
            unset($profile);

            $result['owner'] = $profileInfo;
        }

        return $result;
    }

    public function get($itemId, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = 1000000;
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array()
        );

        $stmt = $this->db->prepare("SELECT * FROM payments WHERE fromUserId = (:fromUserId) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = $this->quickInfo($row);

                array_push($result['items'], $itemInfo);

                $result['itemId'] = $itemInfo['id'];

                unset($itemInfo);
            }
        }

        return $result;
    }

    public function stream($itemId, $limit = 20)
    {
        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array()
        );

        $stmt = $this->db->prepare("SELECT * FROM payments WHERE itemId = (:itemId) AND removeAt = 0 ORDER BY id DESC LIMIT :limit");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = $this->quickInfo($row, true);

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
