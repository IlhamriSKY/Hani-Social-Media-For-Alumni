<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class refsys extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';

    private $bonus = 0;

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function setReferrer($referrerId = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $profile = new profile($this->db, $referrerId);

        $profileInfo = $profile->getVeryShort();

        if (!$profileInfo['error']) {

            $stmt = $this->db->prepare("UPDATE users SET referrer = (:referrer) WHERE id = (:referral)");
            $stmt->bindParam(":referral", $this->requestFrom, PDO::PARAM_INT);
            $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);

            if ($stmt->execute()) {

                $profileInfo['referralsCount'] = $profileInfo['referralsCount'] + 1;

                $this->setReferralsCount($referrerId, $profileInfo['referralsCount']);

                $this->addBonus($referrerId);

                $result = array(
                    "error" => false,
                    "error_code" => ERROR_SUCCESS);
            }
        }

        unset($profile);
        unset($profileInfo);

        return $result;
    }

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM guests");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getReferralsCount($referrerId)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE referrer = (:referrer) AND state = 0");
        $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function setReferralsCount($referrerId, $referralsCount = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $stmt = $this->db->prepare("UPDATE users SET referrals_count = (:referrals_count) WHERE id = (:referrer)");
        $stmt->bindParam(":referrals_count", $referralsCount, PDO::PARAM_INT);
        $stmt->bindParam(":referrer", $referrerId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );
        }

        return $result;
    }

    public function addBonus($referrer)
    {
        if ($this->getBonus() != 0) {

            $account = new account($this->db, $referrer);
            $account->setBalance($account->getBalance() + $this->getBonus());
            unset($account);

            $payments = new payments($this->db);
            $payments->setRequestFrom($referrer);
            $payments->create(PA_BUY_REFERRAL_BONUS, PT_BONUS, $this->getBonus());
            unset($payments);
        }
    }

    public function count()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE referrer = (:referrer) AND state = 0");
        $stmt->bindParam(":referrer", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }


    public function getReferrals($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM users WHERE referrer = (:referrer) AND state = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':referrer', $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $profile = new profile($this->db, $row['id']);

                $referralInfo = $profile->get();

                array_push($result['items'], $referralInfo);

                $result['itemId'] = $referralInfo['id'];

                unset($profile);
                unset($referralInfo);
            }
        }

        return $result;
    }

    public function getNewCount($lastReferralsView)
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE referrer = (:referrer) AND createAt > (:lastReferralsView) AND state = 0");
        $stmt->bindParam(":referrer", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":lastReferralsView", $lastReferralsView, PDO::PARAM_INT);
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

    public function setBonus($bonus)
    {
        $this->bonus = $bonus;
    }

    public function getBonus()
    {
        return $this->bonus;
    }
}
