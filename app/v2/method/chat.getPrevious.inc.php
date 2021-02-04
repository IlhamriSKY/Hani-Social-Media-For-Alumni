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

    $profileId = isset($_POST['profileId']) ? $_POST['profileId'] : '';

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;
    $msgId = isset($_POST['msgId']) ? $_POST['msgId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $profileId = helper::clearInt($profileId);

    $chatId = helper::clearInt($chatId);
    $msgId = helper::clearInt($msgId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $messages = new msg($dbo);
    $messages->setRequestFrom($accountId);

    if ($chatId == 0) {

        $chatId = $messages->getChatId($accountId, $profileId);
    }

    if ($chatId != 0) {

        $result = $messages->getPreviousMessages($chatId, $msgId);
    }

    echo json_encode($result);
    exit;
}
