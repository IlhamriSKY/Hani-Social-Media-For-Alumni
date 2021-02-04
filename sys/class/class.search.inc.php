<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class search extends db_connect
{

    private $requestFrom = 0;
    private $language = 'en';

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    private function getCommunitiesCount($queryText)
    {
        $queryText = "%".$queryText."%";

        $stmt = $this->db->prepare("SELECT count(*) FROM users WHERE state = 0 AND account_type > 0 AND (login LIKE (:query) OR fullname LIKE (:query))");
        $stmt->bindParam(':query', $queryText, PDO::PARAM_STR);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getCount($queryText, $gender = -1, $online = -1, $photo = -1)
    {
        $queryText = "%".$queryText."%";

        $genderSql = "";

        if ($gender != -1) {

            $genderSql = " AND sex = {$gender}";
        }

        $onlineSql = "";

        if ($online != -1) {

            $current_time = time() - (15 * 60);

            $onlineSql = " AND last_authorize > {$current_time}";
        }

        $photoSql = "";

        if ($photo != -1) {

            $photoSql = " AND lowPhotoUrl <> ''";
        }

        $sql = "SELECT count(*) FROM users WHERE state = 0 AND account_type = 0 AND (login LIKE '{$queryText}' OR fullname LIKE '{$queryText}' OR email LIKE '{$queryText}' OR country LIKE '{$queryText}')".$genderSql.$onlineSql.$photoSql;

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function lastIndex()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM users");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn() + 1;
    }

    public function query($queryText = '', $userId = 0, $gender = -1, $online = -1, $photo = -1)
    {
        $originQuery = $queryText;

        if ($userId == 0) {

            $userId = $this->lastIndex();
        }

        $endSql = " ORDER BY regtime DESC LIMIT 20";

        $genderSql = "";

        if ($gender != -1) {

            $genderSql = " AND sex = {$gender}";
        }

        $onlineSql = "";

        if ($online != -1) {

            $current_time = time() - (15 * 60);

            $onlineSql = " AND last_authorize > {$current_time}";
        }

        $photoSql = "";

        if ($photo != -1) {

            $photoSql = " AND lowPhotoUrl <> ''";
        }

        $users = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemCount" => $this->getCount($originQuery, $gender, $online, $photo),
                       "itemId" => $userId,
                       "query" => $originQuery,
                       "items" => array());

        $queryText = "%".$queryText."%";

        $sql = "SELECT id, regtime FROM users WHERE state = 0 AND account_type = 0 AND (login LIKE '{$queryText}' OR fullname LIKE '{$queryText}' OR email LIKE '{$queryText}' OR country LIKE '{$queryText}') AND id < {$userId}".$genderSql.$onlineSql.$photoSql.$endSql;
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['id']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($users['items'], $profile->get());

                    $users['itemId'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $users;
    }

    public function preload($itemId = 0, $gender = -1, $online = -1, $photo = -1, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $endSql = " ORDER BY regtime DESC LIMIT {$limit}";

        $genderSql = "";

        if ($gender != -1) {

            $genderSql = " AND sex = {$gender}";
        }

        $onlineSql = "";

        if ($online != -1) {

            $current_time = time() - (15 * 60);

            $onlineSql = " AND last_authorize > {$current_time}";
        }

        $photoSql = "";

        if ($photo != -1) {

            $photoSql = " AND lowPhotoUrl <> ''";
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemCount" => $this->getCount("", $gender, $online, $photo),
                        "itemId" => $itemId,
                        "items" => array());

        $sql = "SELECT id, regtime FROM users WHERE state = 0 AND account_type = 0 AND id < {$itemId}".$genderSql.$onlineSql.$photoSql.$endSql;
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $profile = new profile($this->db, $row['id']);
                    $profile->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $profile->get());

                    $result['itemId'] = $row['id'];

                    unset($profile);
                }
            }
        }

        return $result;
    }

    public function communitiesPreload($itemId = 0, $limit = 20)
    {
        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemsCount" => $this->getCommunitiesCount(""),
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id, regtime FROM users WHERE state = 0 AND account_type > 0 AND id < (:itemId) ORDER BY regtime DESC LIMIT :limit");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $group = new group($this->db, $row['id']);
                    $group->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $group->get());

                    $result['itemId'] = $row['id'];

                    unset($group);
                }
            }
        }

        return $result;
    }

    public function communitiesQuery($queryText = '', $itemId = 0)
    {
        $originQuery = $queryText;

        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemsCount" => $this->getCommunitiesCount($originQuery),
                        "itemId" => $itemId,
                        "query" => $originQuery,
                        "items" => array());

        $queryText = "%".$queryText."%";

        $stmt = $this->db->prepare("SELECT id, regtime FROM users WHERE state = 0 AND account_type > 0 AND (login LIKE (:query) OR fullname LIKE (:query)) AND id < (:itemId) ORDER BY regtime DESC LIMIT 20");
        $stmt->bindParam(':query', $queryText, PDO::PARAM_STR);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $group = new group($this->db, $row['id']);
                    $group->setRequestFrom($this->requestFrom);

                    array_push($result['items'], $group->get());

                    $result['itemId'] = $row['id'];

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

