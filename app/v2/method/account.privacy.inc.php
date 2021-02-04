<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $allowShowMyGallery = isset($_POST['allowShowMyGallery']) ? $_POST['allowShowMyGallery'] : 0;
    $allowShowMyGifts = isset($_POST['allowShowMyGifts']) ? $_POST['allowShowMyGifts'] : 0;
    $allowShowMyFriends = isset($_POST['allowShowMyFriends']) ? $_POST['allowShowMyFriends'] : 0;
    $allowShowMyInfo = isset($_POST['allowShowMyInfo']) ? $_POST['allowShowMyInfo'] : 0;

    $allowShowMyGallery = helper::clearInt($allowShowMyGallery);
    $allowShowMyGifts = helper::clearInt($allowShowMyGifts);
    $allowShowMyFriends = helper::clearInt($allowShowMyFriends);
    $allowShowMyInfo = helper::clearInt($allowShowMyInfo);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS);

    $account = new account($dbo, $accountId);

    $account->setPrivacySettings($allowShowMyGallery, $allowShowMyGifts, $allowShowMyFriends, $allowShowMyInfo);

    $result = $account->getPrivacySettings();

    echo json_encode($result);
    exit;
}
