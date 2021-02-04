<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class hashtag extends db_connect {

    private $requestFrom = 0;
    private $language = 'en';

	public function __construct($dbo = NULL)
	{
		parent::__construct($dbo);
	}

    public function storeHashtags($matches) {

        $hashtags = new hashtags($this->db);
        $hashtags->setRequestFrom($this->getRequestFrom());

        $numberofclick = 0;

        $currentTime = time();

        $stmt = $this->db->prepare("INSERT INTO hashtags (hashtags, numberOfClicks, createAt) value (:hashtags, :numberOfClicks, :createAt)");
        $stmt->bindParam(":hashtags", $matches, PDO::PARAM_STR);
        $stmt->bindParam(":numberOfClicks", $numberofclick, PDO::PARAM_INT);
        $stmt->bindParam(":createAt", $currentTime, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $chatId = $this->db->lastInsertId();
        }

        return $chatId;
    }

    public function postsCount()
    {
        $stmt = $this->db->prepare("SELECT max(id) FROM posts");
        $stmt->execute();

        return $number_of_rows = $stmt->fetchColumn();
    }

	public function count($hashtag)
	{
        $hashtag = str_replace('#', '', $hashtag);
		$search_explode = explode(' ',trim($hashtag, ' '));

		$sql = "SELECT count(*) FROM posts WHERE (post LIKE '%#{$search_explode[0]} %' OR post LIKE '#{$search_explode[0]}' OR post LIKE '% #{$search_explode[0]} %' OR post LIKE '% #{$search_explode[0]},%' OR post LIKE '% #{$search_explode[0]}!%' OR post LIKE '% #{$search_explode[0]}.%' OR post LIKE '%,#{$search_explode[0]},%' OR post LIKE '#{$search_explode[0]},%') AND removeAt = 0";

		$stmt = $this->db->prepare($sql);
		$stmt->execute();

		return $number_of_rows = $stmt->fetchColumn();
	}

	public function search($hashtag, $postId = 0)
    {
        $originQuery = $hashtag;

        if ($postId == 0) {

            $postId = $this->postsCount();
            $postId++;
        }

        $posts = array("error" => false,
                         "error_code" => ERROR_SUCCESS,
                         "postId" => $postId,
                         "query" => $originQuery,
                         "posts" => array());

        $hashtag = str_replace('#', '', $hashtag);
        $search_explode = explode(' ', trim($hashtag, ' '));

        $sql = "SELECT id FROM posts WHERE (post LIKE '%#{$search_explode[0]} %' OR post LIKE '#{$search_explode[0]}' OR post LIKE '% #{$search_explode[0]} %' OR post LIKE '% #{$search_explode[0]},%' OR post LIKE '% #{$search_explode[0]}!%' OR post LIKE '% #{$search_explode[0]}.%' OR post LIKE '%,#{$search_explode[0]},%' OR post LIKE '#{$search_explode[0]},%') AND removeAt = 0 AND id < (:postId) ORDER BY id DESC LIMIT 20";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {

                while ($row = $stmt->fetch()) {

                    $post = new post($this->db);
                    $post->setRequestFrom($this->getRequestFrom());

                    $postInfo = $post->info($row['id']);

                    array_push($posts['posts'], $postInfo);

                    $posts['postId'] = $postInfo['id'];

                    unset($post);
                    unset($postInfo);
                }
            }
        }

        return $posts;
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