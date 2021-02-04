<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $distance = isset($_POST['distance']) ? $_POST['distance'] : 30;
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $sex = isset($_POST['sex']) ? $_POST['sex'] : 3;

    $lat = isset($_POST['lat']) ? $_POST['lat'] : '0.000000';
    $lng = isset($_POST['lng']) ? $_POST['lng'] : '0.000000';

    $distance = helper::clearInt($distance);
    $itemId = helper::clearInt($itemId);
    $sex = helper::clearInt($sex);

    $lat = helper::clearText($lat);
    $lat = helper::escapeText($lat);

    $lng = helper::clearText($lng);
    $lng = helper::escapeText($lng);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    // Update geolocation in db

    $account = new account($dbo, $accountId);

    if (strlen($lat) > 0 && strlen($lng) > 0 && $itemId > 0) {

        $result = $account->setGeoLocation($lat, $lng);
    }

    unset($account);

    // Get People List

    $geo = new geo($dbo);
    $geo->setRequestFrom($accountId);

    $result = $geo->getPeopleNearby($itemId, $lat, $lng, $distance, $sex);

    echo json_encode($result);
    exit;
}
