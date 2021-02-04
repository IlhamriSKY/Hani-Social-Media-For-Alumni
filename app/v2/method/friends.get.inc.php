<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $profileId = isset($_POST['profileId']) ? $_POST['profileId'] : 0;
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $profileId = helper::clearInt($profileId);
    $itemId = helper::clearInt($itemId);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $friends = new friends($dbo, $profileId);
    $friends->setRequestFrom($accountId);

    if ($profileId == $accountId && $itemId == 0) {

        $account = new account($dbo, $accountId);
        $account->setLastFriendsView();
        unset($account);
    }

    $result = $friends->get($itemId);

    echo json_encode($result);
    exit;
}
