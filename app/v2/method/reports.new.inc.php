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

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
    $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 0;
    $abuseId = isset($_POST['abuseId']) ? $_POST['abuseId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);
    $itemType = helper::clearInt($itemType);
    $abuseId = helper::clearInt($abuseId);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $reports = new reports($dbo);
    $reports->setRequestFrom($accountId);

    $result = $reports->add($itemType, $itemId, $abuseId);

    echo json_encode($result);
    exit;
}
