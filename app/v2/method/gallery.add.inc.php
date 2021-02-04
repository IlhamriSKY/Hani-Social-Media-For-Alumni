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

    $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
    $originImgUrl = isset($_POST['originImgUrl']) ? $_POST['originImgUrl'] : "";
    $previewImgUrl = isset($_POST['previewImgUrl']) ? $_POST['previewImgUrl'] : "";
    $imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : "";

    $previewVideoImgUrl = isset($_POST['previewVideoImgUrl']) ? $_POST['previewVideoImgUrl'] : "";
    $videoUrl = isset($_POST['videoUrl']) ? $_POST['videoUrl'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $comment = helper::clearText($comment);

    $comment = preg_replace( "/[\r\n]+/", "<br>", $comment); //replace all new lines to one new line
    $comment  = preg_replace('/\s+/', ' ', $comment);        //replace all white spaces to one space

    $comment = helper::escapeText($comment);

    $originImgUrl = helper::clearText($originImgUrl);
    $originImgUrl = helper::escapeText($originImgUrl);

    $previewImgUrl = helper::clearText($previewImgUrl);
    $previewImgUrl = helper::escapeText($previewImgUrl);

    $imgUrl = helper::clearText($imgUrl);
    $imgUrl = helper::escapeText($imgUrl);

    $previewVideoImgUrl = helper::clearText($previewVideoImgUrl);
    $previewVideoImgUrl = helper::escapeText($previewVideoImgUrl);

    $videoUrl = helper::clearText($videoUrl);
    $videoUrl = helper::escapeText($videoUrl);

    if (strpos($originImgUrl, APP_URL."/".GALLERY_PATH) === false) {

        $originImgUrl = "";
    }

    if (strpos($previewImgUrl, APP_URL."/".GALLERY_PATH) === false) {

        $previewImgUrl = "";
    }

    if (strpos($imgUrl, APP_URL."/".GALLERY_PATH) === false) {

        $imgUrl = "";
    }

    if (strpos($previewVideoImgUrl, APP_URL."/".VIDEO_IMAGE_PATH) === false) {

        $previewVideoImgUrl = "";
    }

    if (strpos($videoUrl, APP_URL."/".VIDEO_PATH) === false) {

        $videoUrl = "";
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom($accountId);

    $result = $gallery->add($comment, $originImgUrl, $previewImgUrl, $imgUrl, $previewVideoImgUrl, $videoUrl);

    echo json_encode($result);
    exit;
}
