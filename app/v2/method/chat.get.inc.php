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

    $chatFromUserId = isset($_POST['chatFromUserId']) ? $_POST['chatFromUserId'] : 0;
    $chatToUserId = isset($_POST['chatToUserId']) ? $_POST['chatToUserId'] : 0;

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;
    $msgId = isset($_POST['msgId']) ? $_POST['msgId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $profileId = helper::clearInt($profileId);

    $chatFromUserId = helper::clearInt($chatFromUserId);
    $chatToUserId = helper::clearInt($chatToUserId);

    $chatId = helper::clearInt($chatId);
    $msgId = helper::clearInt($msgId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $msg = new msg($dbo);
    $msg->setRequestFrom($accountId);

    if ($chatId == 0) {

        $chatId = $msg->getChatId($accountId, $profileId);
    }

    if ($chatId != 0) {

        $response = $msg->get($chatId, $msgId, $chatFromUserId, $chatToUserId);

        if ($response['chatFromUserId'] == $accountId || $response['chatToUserId'] == $accountId) {

            echo json_encode($response);

        } else {

            echo json_encode($result);
        }

    } else {

        echo json_encode($result);
    }


    exit;
}
