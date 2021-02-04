<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class stream extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    public function getAllCount()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE removeAt = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function getAllCountByType()
    {
        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE removeAt = 0 AND postType = 0");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getLikeMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM likes");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    private function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function count($language = 'en')
    {
        $count = 0;

        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE accessMode = 0 AND removeAt = 0");

        if ($stmt->execute()) {

            $count = $stmt->fetchColumn();
        }

        return $count;
    }

    public function getFavoritesCount()
    {
        $count = 0;

        $stmt = $this->db->prepare("SELECT count(*) FROM likes WHERE fromUserId = (:fromUserId) AND removeAt = 0");
        $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $count = $stmt->fetchColumn();
        }

        return $count;
    }

    public function getFavorites($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getLikeMaxId();
            $itemId++;
        }

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "itemId" => $itemId,
                        "items" => array());

        $stmt = $this->db->prepare("SELECT id, itemId FROM likes WHERE removeAt = 0 AND id < (:itemId) AND fromUserId = (:fromUserId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $post = new post($this->db);
                    $post->setRequestFrom($this->requestFrom);
                    $postInfo = $post->info($row['itemId']);
                    unset($post);

                    array_push($result['items'], $postInfo);

                    $result['itemId'] = $row['id'];

                    unset($postInfo);
                }
            }
        }

        return $result;
    }

    public function get($itemId = 0, $language = 'en')
    {
        if ($itemId == 0) {

            $itemId = 100000;
            $itemId++;
        }

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "items" => array()
        );

        $stmt = $this->db->prepare("SELECT * FROM posts WHERE accessMode = 0 AND postType = 0 AND groupId = 0 AND removeAt = 0 AND id < (:itemId) ORDER BY id DESC LIMIT 20");
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $post = new post($this->db);
                    $post->setRequestFrom($this->getRequestFrom());
                    $postInfo = $post->quikInfo($row);
                    unset($post);

                    array_push($result['items'], $postInfo);

                    $result['itemId'] = $postInfo['id'];

                    unset($postInfo);
                }
            }
        }

        return $result;
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

