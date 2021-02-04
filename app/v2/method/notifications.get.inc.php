<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $notifyId = isset($_POST['notifyId']) ? $_POST['notifyId'] : 0;

    $notifyId = helper::clearInt($notifyId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new profile($dbo, $accountId);
    $account->setLastNotifyView();

    $notify = new notify($dbo);
    $notify->setRequestFrom($accountId);
    $result = $notify->getAll($notifyId);

    echo json_encode($result);
    exit;
}
