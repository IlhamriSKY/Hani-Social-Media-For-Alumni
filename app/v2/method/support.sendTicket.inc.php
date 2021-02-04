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

    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $subject = isset($_POST['subject']) ? $_POST['subject'] : "";
    $detail = isset($_POST['detail']) ? $_POST['detail'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $email = helper::clearText($email);
    $email = helper::escapeText($email);

    $subject = helper::clearText($subject);
    $subject = helper::escapeText($subject);

    $detail = helper::clearText($detail);
    $detail = helper::escapeText($detail);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $support = new support($dbo);
    $support->setRequestFrom($accountId);

    $result = $support->createTicket($accountId, $email, $subject, $detail, $clientId);

    echo json_encode($result);
    exit;
}
