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

    $profileId = isset($_POST['profileId']) ? $_POST['profileId'] : 0;
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
    $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : -1;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $profileId = helper::clearInt($profileId);
    $itemId = helper::clearInt($itemId);

    if ($itemType != -1) {

        $itemType = helper::clearInt($itemType);
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom($accountId);

    $result = $gallery->get($profileId, $itemType, $itemId);

    echo json_encode($result);
    exit;
}
