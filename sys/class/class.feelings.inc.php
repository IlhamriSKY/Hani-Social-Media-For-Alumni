<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class feelings extends db_connect
{
	private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function db_getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM feelings_data");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function db_add($imgUrl, $title = "")
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if (strlen($imgUrl) == 0) {

            return $result;
        }

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO feelings_data (title, imgUrl, createAt) value (:title, :imgUrl, :createAt)");
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "feelingId" => $this->db->lastInsertId(),
                            "feeling" => $this->db_info($this->db->lastInsertId()));
        }

        return $result;
    }

    public function db_remove($feelingId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE feelings_data SET removeAt = (:removeAt) WHERE id = (:feelingId)");
        $stmt->bindParam(":feelingId", $feelingId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function db_info($feelingId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $stmt = $this->db->prepare("SELECT * FROM feelings_data WHERE id = (:feelingId) LIMIT 1");
        $stmt->bindParam(":feelingId", $feelingId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                $row = $stmt->fetch();

                $time = new language($this->db, $this->language);

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS,
                                "id" => $row['id'],
                                "title" => $row['title'],
                                "imgUrl" => $row['imgUrl'],
                                "createAt" => $row['createAt'],
                                "date" => date("Y-m-d H:i:s", $row['createAt']),
                                "timeAgo" => $time->timeAgo($row['createAt']),
                                "removeAt" => $row['removeAt']);
            }
        }

        return $result;
    }

    public function db_get($itemId = 0, $limit = 40)
    {
        if ($itemId == 0) {

            $itemId = $this->db_getMaxId();
            $itemId++;
        }

        $items = array("error" => false,
                       "error_code" => ERROR_SUCCESS,
                       "itemId" => $itemId,
                       "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM feelings_data WHERE removeAt = 0 AND id < (:feelingId) ORDER BY id ASC LIMIT :limit");
        $stmt->bindParam(':feelingId', $itemId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $itemInfo = array("error" => false,
                                  "error_code" => ERROR_SUCCESS,
                                  "id" => $row['id'],
                                  "title" => $row['title'],
                                  "imgUrl" => $row['imgUrl'],
                                  "createAt" => $row['createAt'],
                                  "date" => date("Y-m-d H:i:s", $row['createAt']),
                                  "removeAt" => $row['removeAt']);

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
