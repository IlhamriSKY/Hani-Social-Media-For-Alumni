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

    $chatFromUserId = isset($_POST['chatFromUserId']) ? $_POST['chatFromUserId'] : 0;
    $chatToUserId = isset($_POST['chatToUserId']) ? $_POST['chatToUserId'] : 0;

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;

    $notifyId = isset($_POST['notifyId']) ? $_POST['notifyId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $chatFromUserId = helper::clearInt($chatFromUserId);
    $chatToUserId = helper::clearInt($chatToUserId);

    $chatId = helper::clearInt($chatId);

    $notifyId = helper::clearInt($notifyId);

    $result = array("error" => false,
                    "error_code" => ERROR_UNKNOWN);

    $profileId = $chatFromUserId;

    if ($profileId == $accountId) {

        $fcm = new fcm($dbo);
        $fcm->setRequestFrom($accountId);
        $fcm->setRequestTo($chatToUserId);
        $fcm->setType($notifyId);
        $fcm->setTitle("Seen");
        $fcm->setItemId($chatId);
        $fcm->prepare();
        $fcm->send();
        unset($fcm);

    } else {

        $fcm = new fcm($dbo);
        $fcm->setRequestFrom($accountId);
        $fcm->setRequestTo($chatFromUserId);
        $fcm->setType($notifyId);
        $fcm->setTitle("Seen");
        $fcm->setItemId($chatId);
        $fcm->prepare();
        $fcm->send();
        unset($fcm);
    }

    echo json_encode($result);
    exit;
}
