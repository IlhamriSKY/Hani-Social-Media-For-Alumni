<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $profileId = isset($_POST['profileId']) ? $_POST['profileId'] : 0;
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';

    $accountId = helper::clearInt($accountId);

    $profileId = helper::clearInt($profileId);

    $reason = preg_replace( "/[\r\n]+/", " ", $reason); //replace all new lines to one new line
    $reason  = preg_replace('/\s+/', ' ', $reason);        //replace all white spaces to one space

    $reason = helper::escapeText($reason);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $blacklist = new blacklist($dbo);
    $blacklist->setRequestFrom($accountId);

    $result = $blacklist->add($profileId, $reason);

    echo json_encode($result);
    exit;
}
