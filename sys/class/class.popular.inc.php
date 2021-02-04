<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class popular extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
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

        $stmt = $this->db->prepare("SELECT count(*) FROM posts WHERE accessMode = 0 AND fromUserId <> (:fromUserId) AND removeAt = 0 AND rating > 0");
        $stmt->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            $count = $stmt->fetchColumn();
        }

        return $count;
    }

    public function get($rating = 0, $category = 0)
    {
        if ($rating == 0) {

            $rating = 1000000;
        }

        $timeAt = 0;

        switch ($category) {

            case 0: {

                $timeAt = time();

                break;
            }

            default: {

                $timeAt = time();

                break;
            }
        }
        $result = array("error" => false,
                         "error_code" => ERROR_SUCCESS,
                         "rating" => $rating,
                         "category" => $category,
                         "items" => array());

        $stmt = $this->db->prepare("SELECT id FROM posts WHERE accessMode = 0 AND createAt < (:timeAt) AND removeAt = 0 AND rating > 0 AND rating < (:rating) ORDER BY rating DESC LIMIT 50");
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':timeAt', $timeAt, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $post = new post($this->db);
                    $post->setRequestFrom($this->requestFrom);
                    $postInfo = $post->info($row['id']);
                    unset($post);

                    array_push($result['items'], $postInfo);

                    $result['rating'] = $postInfo['rating'];

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

