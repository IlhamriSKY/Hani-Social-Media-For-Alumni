<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class market extends db_connect
{

    private $requestFrom = 0;
    private $language = 'en';

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM market_items WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getMyItemsCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM market_items WHERE removeAt = 0 AND fromUserId = (:fromUserId)");
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getCount($queryText)
    {
        $queryText = "%".$queryText."%";

        $sql = "SELECT count(*) FROM market_items WHERE removeAt = 0 AND (itemTitle LIKE '{$queryText}' OR itemDesc LIKE '{$queryText}' OR itemContent LIKE '{$queryText}')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function lastIndex()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM market_items");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn() + 1;
    }

    public function add($title, $content, $imgUrl, $previewImgUrl, $price = 0, $allowComments = 1, $postArea = "", $postCountry = "", $postCity = "", $postLat = "0.000000", $postLng = "0.000000")
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $spam = new spam($this->db);
        $spam->setRequestFrom($this->getRequestFrom());

        if ($spam->getMarketItemsCount() > 10) {

            return $result;
        }

        unset($spam);

        $description = "";

        if (strlen($title) == 0) {

            return $result;
        }

        if (strlen($imgUrl) == 0) {

            return $result;
        }

        if (strlen($content) == 0) {

            return $result;
        }

        if ($price == 0) {

            return $result;
        }

        $currentTime = time();
        $ip_addr = helper::ip_addr();
        $u_agent = helper::u_agent();

        $stmt = $this->db->prepare("INSERT INTO market_items (allowComments, fromUserId, itemTitle, itemDesc, itemContent, imgUrl, previewImgUrl, price, area, country, city, lat, lng, createAt, ip_addr, u_agent) value (:allowComments, :fromUserId, :itemTitle, :itemDesc, :itemContent, :imgUrl, :previewImgUrl, :price, :area, :country, :city, :lat, :lng, :createAt, :ip_addr, :u_agent)");
        $stmt->bindParam(":allowComments", $allowComments, PDO::PARAM_INT);
        $stmt->bindParam(":fromUserId", $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(":itemTitle", $title, PDO::PARAM_STR);
        $stmt->bindParam(":itemDesc", $description, PDO::PARAM_STR);
        $stmt->bindParam(":itemContent", $content, PDO::PARAM_STR);
        $stmt->bindParam(":imgUrl", $imgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":previewImgUrl", $previewImgUrl, PDO::PARAM_STR);
        $stmt->bindParam(":price", $price, PDO::PARAM_INT);
        $stmt->bindParam(":area", $postArea, PDO::PARAM_STR);
        $stmt->bindParam(":country", $postCountry, PDO::PARAM_STR);
        $stmt->bindParam(":city", $postCity, PDO::PARAM_STR);
        $stmt->bindParam(":lat", $postLat, PDO::PARAM_STR);
        $stmt->bindParam(":lng", $postLng, PDO::PARAM_STR);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);
        $stmt->bindParam(":ip_addr", $ip_addr, PDO::PARAM_STR);
        $stmt->bindParam(":u_agent", $u_agent, PDO::PARAM_STR);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS,
                            "itemId" => $this->db->lastInsertId());
        }

        return $result;
    }

    public function remove($itemId)
    {
        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        $currentTime = time();

        $stmt = $this->db->prepare("UPDATE market_items SET removeAt = (:removeAt) WHERE id = (:itemId)");
        $stmt->bindParam(":itemId", $itemId, PDO::PARAM_INT);
        $stmt->bindParam(":removeAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $result = array("error" => false,
                            "error_code" => ERROR_SUCCESS);
        }

        return $result;
    }

    public function query($queryText = '', $itemId = 0)
    {
        $originQuery = $queryText;

        if ($itemId == 0) {

            $itemId = $this->lastIndex();
        }

        $endSql = " ORDER BY createAt DESC LIMIT 20";

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemCount" => $this->getCount($originQuery),
                        "itemId" => $itemId,
                        "query" => $originQuery,
                        "items" => array());

        $queryText = "%".$queryText."%";

        $sql = "SELECT * FROM market_items WHERE removeAt = 0 AND (itemTitle LIKE '{$queryText}' OR itemDesc LIKE '{$queryText}' OR itemContent LIKE '{$queryText}') AND id < {$itemId}".$endSql;
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    array_push($result['items'], $this->info($row));

                    $result['itemId'] = $row['id'];
                }
            }
        }

        return $result;
    }

    public function preload($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $endSql = " ORDER BY id DESC LIMIT 20";

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemCount" => $this->getCount(""),
                        "itemId" => $itemId,
                        "items" => array());

        $sql = "SELECT * FROM market_items WHERE removeAt = 0 AND id < {$itemId}".$endSql;
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    array_push($result['items'], $this->info($row));

                    $result['itemId'] = $row['id'];
                }
            }
        }

        return $result;
    }

    public function getMyItems($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->lastIndex();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemCount" => $this->getMyItemsCount(),
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM market_items WHERE removeAt = 0 AND id < (:itemId) AND fromUserId = (:fromUserId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    array_push($result['items'], $this->info($row));

                    $result['itemId'] = $row['id'];
                }
            }
        }

        return $result;
    }

    public function info($row)
    {
        $time = new language($this->db, $this->language);

        $profile = new profile($this->db, $row['fromUserId']);
        $profileInfo = $profile->getVeryShort();
        unset($profile);

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "id" => $row['id'],
                        "price" => $row['price'],
                        "fromUserId" => $row['fromUserId'],
                        "fromUserVerified" => $profileInfo['verify'],
                        "fromUserUsername" => $profileInfo['username'],
                        "fromUserFullname" => $profileInfo['fullname'],
                        "fromUserPhoto" => $profileInfo['lowPhotoUrl'],
                        "itemTitle" => htmlspecialchars_decode(stripslashes($row['itemTitle'])),
                        "itemDesc" => htmlspecialchars_decode(stripslashes($row['itemDesc'])),
                        "itemContent" => stripslashes($row['itemContent']),
                        "area" => htmlspecialchars_decode(stripslashes($row['area'])),
                        "country" => htmlspecialchars_decode(stripslashes($row['country'])),
                        "city" => htmlspecialchars_decode(stripslashes($row['city'])),
                        "lat" => $row['lat'],
                        "lng" => $row['lng'],
                        "previewImgUrl" => $row['previewImgUrl'],
                        "imgUrl" => $row['imgUrl'],
                        "allowComments" => $row['allowComments'],
                        "rating" => $row['rating'],
                        "commentsCount" => $row['commentsCount'],
                        "likesCount" => $row['likesCount'],
                        "imagesCount" => $row['imagesCount'],
                        "createAt" => $row['createAt'],
                        "date" => date("Y-m-d H:i:s", $row['createAt']),
                        "timeAgo" => $time->timeAgo($row['createAt']),
                        "removeAt" => $row['removeAt']);

        return $result;
    }

    public function getItem($itemId = 0)
    {
        $result = array(
            "error" => true,
            "error_code" => ERROR_SUCCESS);

        $stmt = $this->db->prepare("SELECT * FROM market_items WHERE removeAt = 0 AND id = (:itemId)");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $result = $this->info($row);
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

