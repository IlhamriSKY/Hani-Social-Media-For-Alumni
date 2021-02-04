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

    $allowComments = isset($_POST['allowComments']) ? $_POST['allowComments'] : 0;

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $content = isset($_POST['description']) ? $_POST['description'] : '';
    $shareto = isset($_POST['shareto']) ? $_POST['shareto'] : '';
    $imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : '';

    $postArea = isset($_POST['postArea']) ? $_POST['postArea'] : '';
    $postCountry = isset($_POST['postCountry']) ? $_POST['postCountry'] : '';
    $postCity = isset($_POST['postCity']) ? $_POST['postCity'] : '';
    $postLat = isset($_POST['postLat']) ? $_POST['postLat'] : '0.000000';
    $postLng = isset($_POST['postLng']) ? $_POST['postLng'] : '0.000000';

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $allowComments = helper::clearInt($allowComments);

    $title = helper::clearText($title);
    $title = helper::escapeText($title);

    $content = helper::clearText($content);

    $content = preg_replace( "/[\r\n]+/", '<p class="post-text mx-2" style="text-align: justify; text-indent: 0.5in;">', $content); //replace all new lines to one new line
    $content  = preg_replace('/\s+/', ' ', $content);        //replace all white spaces to one space

    $content = helper::escapeText($content);

    $imgUrl = helper::clearText($imgUrl);
    $imgUrl = helper::escapeText($imgUrl);

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

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $buku_alumni = new bukualumni($dbo);
    $buku_alumni->setRequestFrom($accountId);

    $result = $buku_alumni->add($title, $content, $imgUrl, $imgUrl, $allowComments, $postArea, $postCountry, $postCity, $postLat, $postLng, $shareto);

    echo json_encode($result);
    exit;
}
