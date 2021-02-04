<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
    $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 0;
    $elementId = isset($_POST['elementId']) ? $_POST['elementId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);
    $itemType = helper::clearInt($itemType);
    $elementId = helper::clearInt($elementId);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $likes = new likes($dbo, $itemType);
    $likes->setRequestFrom($accountId);

    $result = $likes->get($itemId, $elementId);

    echo json_encode($result);
    exit;
}
