<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $groupId = isset($_POST['groupId']) ? $_POST['groupId'] : 0;
    $postMode = isset($_POST['postMode']) ? $_POST['postMode'] : 0;

    $rePostId = isset($_POST['rePostId']) ? $_POST['rePostId'] : 0;

    $postText = isset($_POST['postText']) ? $_POST['postText'] : '';
    $postImg = isset($_POST['postImg']) ? $_POST['postImg'] : '';

    $postArea = isset($_POST['postArea']) ? $_POST['postArea'] : '';
    $postCountry = isset($_POST['postCountry']) ? $_POST['postCountry'] : '';
    $postCity = isset($_POST['postCity']) ? $_POST['postCity'] : '';
    $postLat = isset($_POST['postLat']) ? $_POST['postLat'] : '0.000000';
    $postLng = isset($_POST['postLng']) ? $_POST['postLng'] : '0.000000';

    $videoImgUrl = isset($_POST['videoImgUrl']) ? $_POST['videoImgUrl'] : '';
    $videoUrl = isset($_POST['videoUrl']) ? $_POST['videoUrl'] : '';

    $feeling = isset($_POST['feeling']) ? $_POST['feeling'] : 0;

    $imagesArray = isset($_POST['images']) ? $_POST['images'] : array();

    $feeling = helper::clearInt($feeling);

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $groupId = helper::clearInt($groupId);
    $postMode = helper::clearInt($postMode);

    $rePostId = helper::clearInt($rePostId);

    $postText = helper::clearText($postText);

    $postText = preg_replace( "/[\r\n]+/", "<br>", $postText); //replace all new lines to one new line
    $postText  = preg_replace('/\s+/', ' ', $postText);        //replace all white spaces to one space

    $postText = helper::escapeText($postText);

    $postImg = helper::clearText($postImg);
    $postImg = helper::escapeText($postImg);

    $postArea = helper::clearText($postArea);
    $postArea = helper::escapeText($postArea);

    $postCountry = helper::clearText($postCountry);
    $postCountry = helper::escapeText($postCountry);

    $postCity = helper::clearText($postCity);
    $postCity = helper::escapeText($postCity);

    $postLat = helper::clearText($postLat);
    $postLat = helper::escapeText($postLat);

    $postLng = helper::clearText($postLng);
    $postLng = helper::escapeText($postLng);

    $videoImgUrl = helper::clearText($videoImgUrl);
    $videoImgUrl = helper::escapeText($videoImgUrl);

    $videoUrl = helper::clearText($videoUrl);
    $videoUrl = helper::escapeText($videoUrl);

    if (strpos($postImg, APP_URL."/".POST_PHOTO_PATH) === false) {

        $postImg = "";
    }

    if (strpos($videoImgUrl, APP_URL."/".VIDEO_IMAGE_PATH) === false) {

        $videoImgUrl = "";
    }

    if (strpos($videoUrl, APP_URL."/".VIDEO_PATH) === false) {

        $videoUrl = "";
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $fromUserId = $accountId;

    if ($groupId != 0) {

        $m = new profile($dbo, $groupId);
        $m->setRequestFrom(auth::getCurrentUserId());

        $mInfo = $m->get();

        if ($mInfo['accountType'] == ACCOUNT_TYPE_GROUP || $mInfo['accountType'] == ACCOUNT_TYPE_PAGE) {

            $groupId = $mInfo['id'];
            $postMode = 0;

            if ($mInfo['accountAuthor'] == $accountId) {

                $fromUserId = $mInfo['id'];

            } else {

                $fromUserId = $accountId;
            }

        } else {

            $groupId = 0;
            $fromUserId = $accountId;
        }
    }

    $posts = new post($dbo);
    $posts->setRequestFrom($fromUserId);

    $result = $posts->add($postMode, $postText, $postImg, $rePostId, $groupId, $postArea, $postCountry, $postCity, $postLat, $postLng, $videoImgUrl, $videoUrl, 0, $feeling);

    if (count($imagesArray) <= POST_MAX_IMAGES_COUNT) {

        if (count($imagesArray) > 0) {

            $postimg2 = new postimg($dbo);
            $postimg2->setRequestFrom($accountId);

            for ($i = 0; $i < count($imagesArray); $i++) {

                if (strpos($imagesArray[$i], APP_URL."/".POST_PHOTO_PATH) !== false) {

                    $postimg2->add($result['postId'], $imagesArray[$i], $imagesArray[$i], $imagesArray[$i]);
                }
            }

            $posts->setImagesCount($result['postId'], count($imagesArray));
        }
    }

    echo json_encode($result);
    exit;
}
