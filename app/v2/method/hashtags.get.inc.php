<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $language = isset($_POST['language']) ? $_POST['language'] : 'en';
    $hashtag = isset($_POST['hashtag']) ? $_POST['hashtag'] : '';
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $language = helper::clearText($language);
    $language = helper::escapeText($language);

    $hashtag = helper::clearText($hashtag);
    $hashtag = helper::escapeText($hashtag);

    $itemId = helper::clearInt($itemId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $hashtags = new hashtag($dbo);
    $hashtags->setRequestFrom($accountId);
//    $hashtags->setLanguage($LANG['lang-code']);

    $result = $hashtags->search($hashtag, $itemId);

    echo json_encode($result);
    exit;
}
