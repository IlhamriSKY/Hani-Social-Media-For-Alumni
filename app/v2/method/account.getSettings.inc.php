<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $lat = isset($_POST['lat']) ? $_POST['lat'] : '0.000000';
    $lng = isset($_POST['lng']) ? $_POST['lng'] : '0.000000';

    $lat = helper::clearText($lat);
    $lat = helper::escapeText($lat);

    $lng = helper::clearText($lng);
    $lng = helper::escapeText($lng);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!auth::isSession()) {

        if (!$auth->authorize($accountId, $accessToken)) {

            api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
        }
    }

    $result = array(
        "error" => false,
        "error_code" => ERROR_SUCCESS
    );

    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();
    unset($account);

    $messages_count = 0;
    $notifications_count = 0;
    $guests_count = 0;
    $friends_count = 0;

    // Get new messages count

    if (APP_MESSAGES_COUNTERS) {

        $msg = new msg($dbo);
        $msg->setRequestFrom($accountId);

        $messages_count = $msg->getNewMessagesCount();

        unset($msg);
    }

    // Get notifications count

    $notifications = new notify($dbo);
    $notifications->setRequestFrom($accountId);

    $notifications_count = $notifications->getNewCount($accountInfo['lastNotifyView']);

    unset($notifications);


    // Get new guests count

    $guests = new guests($dbo, $accountId);
    $guests->setRequestFrom($accountId);

    $guests_count = $guests->getNewCount($accountInfo['lastGuestsView']);

    unset($guests);

    // Get friends count

    $friends = new friends($dbo);
    $friends->setRequestFrom($accountId);

    $friends_count = $friends->getNewCount($accountInfo['lastFriendsView']);

    unset($friends);

    //

    $result['messagesCount'] = $messages_count;
    $result['notificationsCount'] = $notifications_count;
    $result['guestsCount'] = $guests_count;
    $result['friendsCount'] = $friends_count;

    echo json_encode($result);
    exit;
}
