<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class feed extends db_connect
{
	private $requestFrom = 0;

	public function __construct($dbo = NULL)
    {
		parent::__construct($dbo);
	}

    public function count()
    {
        $count = 0;

        // My communities posts

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follower = (:followerId) AND follow_type = 1 ORDER BY create_at DESC");
        $stmt->bindParam(':followerId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $stmt2 = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND removeAt = 0 ORDER BY createAt DESC");
                $stmt2->bindParam(':fromUserId', $row['follow_to'], PDO::PARAM_INT);
                $stmt2->execute();

                $count = $count + $stmt2->fetchColumn();
            }
        }

        // My friends posts

        $stmt3 = $this->db->prepare("SELECT id, friend FROM friends WHERE friendTo = (:friendTo) AND removeAt = 0 ORDER BY createAt DESC");
        $stmt3->bindParam(':friendTo', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt3->execute()) {

            while ($row = $stmt3->fetch()) {

                $stmt4 = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND removeAt = 0 ORDER BY createAt DESC");
                $stmt4->bindParam(':fromUserId', $row['friend'], PDO::PARAM_INT);
                $stmt4->execute();

                $count = $count + $stmt4->fetchColumn();
            }
        }

        // My posts

        $stmt5 = $this->db->prepare("SELECT count(*) FROM posts WHERE fromUserId = (:fromUserId) AND removeAt = 0 ORDER BY id DESC");
        $stmt5->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt5->execute();

        $count = $count + $stmt5->fetchColumn();

        return $count;
    }

    public function getMaxId()
    {
        $stmt = $this->db->prepare("SELECT MAX(id) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

    public function get($itemId = 0)
    {
        if ($itemId == 0) {

            $itemId = $this->getMaxId();
            $itemId++;
        }

        $items = array();

        $feed = array("error" => false,
                      "error_code" => ERROR_SUCCESS,
                      "itemId" => $itemId,
                      "items" => array());

        $stmt = $this->db->prepare("SELECT * FROM profile_followers WHERE follower = (:followerId) AND follow_type = 1 ORDER BY create_at DESC");
        $stmt->bindParam(':followerId', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $stmt2 = $this->db->prepare("SELECT id FROM posts WHERE fromUserId = (:fromUserId) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC");
                $stmt2->bindParam(':fromUserId', $row['follow_to'], PDO::PARAM_INT);
                $stmt2->bindParam(':itemId', $itemId, PDO::PARAM_INT);
                $stmt2->execute();

                while ($row2 = $stmt2->fetch())  {

                    $items[] = array("id" => $row2['id'], "itemId" => $row2['id']);
                }
            }
        }

        $stmt3 = $this->db->prepare("SELECT id, friend FROM friends WHERE friendTo = (:friendTo) AND removeAt = 0 ORDER BY createAt DESC");
        $stmt3->bindParam(':friendTo', $this->requestFrom, PDO::PARAM_INT);

        if ($stmt3->execute()) {

            while ($row = $stmt3->fetch()) {

                $stmt4 = $this->db->prepare("SELECT id FROM posts WHERE fromUserId = (:fromUserId) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC");
                $stmt4->bindParam(':fromUserId', $row['friend'], PDO::PARAM_INT);
                $stmt4->bindParam(':itemId', $itemId, PDO::PARAM_INT);
                $stmt4->execute();

                while ($row2 = $stmt4->fetch())  {

                    $items[] = array("id" => $row2['id'], "itemId" => $row2['id']);
                }
            }
        }

        $stmt5 = $this->db->prepare("SELECT id FROM posts WHERE fromUserId = (:fromUserId) AND id < (:itemId) AND removeAt = 0 ORDER BY id DESC");
        $stmt5->bindParam(':fromUserId', $this->requestFrom, PDO::PARAM_INT);
        $stmt5->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt5->execute();

        while ($row3 = $stmt5->fetch())  {

            $items[] = array("id" => $row3['id'], "itemId" => $row3['id']);
        }

        $currentItem = 0;
        $maxItem = 20;

        if (count($items) != 0) {

            arsort($items);

            foreach ($items as $key => $value) {

                if ($currentItem < $maxItem) {

                    $currentItem++;

                    $item = new post($this->db);
                    $item->setRequestFrom($this->requestFrom);

                    $itemInfo = $item->info($value['itemId']);

                    array_push($feed['items'], $itemInfo);

                    $feed['itemId'] = $itemInfo['id'];

                    unset($itemInfo);
                    unset($item);
                }
            }
        }

        return $feed;
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
