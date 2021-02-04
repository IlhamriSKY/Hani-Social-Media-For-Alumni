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

    $messageCreateAt = isset($_POST['messageCreateAt']) ? $_POST['messageCreateAt'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $messageCreateAt = helper::clearInt($messageCreateAt);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

//    $auth = new auth($dbo);
//
//    if (!$auth->authorize($accountId, $accessToken)) {
//
//        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
//    }

    $messages = new msg($dbo);
    $messages->setRequestFrom($accountId);

    $result = $messages->getDialogs_new($messageCreateAt);

    echo json_encode($result);
    exit;
}
