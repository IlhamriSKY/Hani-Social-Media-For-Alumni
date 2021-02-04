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

    $price = isset($_POST['price']) ? $_POST['price'] : 0;
    $allowComments = isset($_POST['allowComments']) ? $_POST['allowComments'] : 0;

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    $imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : '';

    $postArea = isset($_POST['postArea']) ? $_POST['postArea'] : '';
    $postCountry = isset($_POST['postCountry']) ? $_POST['postCountry'] : '';
    $postCity = isset($_POST['postCity']) ? $_POST['postCity'] : '';
    $postLat = isset($_POST['postLat']) ? $_POST['postLat'] : '0.000000';
    $postLng = isset($_POST['postLng']) ? $_POST['postLng'] : '0.000000';

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $price = helper::clearInt($price);
    $allowComments = helper::clearInt($allowComments);

    $title = helper::clearText($title);
    $title = helper::escapeText($title);

    $description = helper::clearText($description);

    $description = preg_replace( "/[\r\n]+/", "<br>", $description); //replace all new lines to one new line
    $description  = preg_replace('/\s+/', ' ', $description);        //replace all white spaces to one space

    $description = helper::escapeText($description);

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

    $market = new market($dbo);
    $market->setRequestFrom($accountId);

    $result = $market->add($title, $description, $imgUrl, $imgUrl, $price, $allowComments, $postArea, $postCountry, $postCity, $postLat, $postLng);

    echo json_encode($result);
    exit;
}
