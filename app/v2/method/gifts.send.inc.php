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

    $giftId = isset($_POST['giftId']) ? $_POST['giftId'] : 0;
    $giftAnonymous = isset($_POST['giftAnonymous']) ? $_POST['giftAnonymous'] : 0;
    $giftTo = isset($_POST['giftTo']) ? $_POST['giftTo'] : 0;

    $message = isset($_POST['message']) ? $_POST['message'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $giftId = helper::clearInt($giftId);
    $giftAnonymous = helper::clearInt($giftAnonymous);
    $giftTo = helper::clearInt($giftTo);

    $message = helper::clearText($message);

    $message = preg_replace( "/[\r\n]+/", "<br>", $message); //replace all new lines to one new line
    $message = preg_replace('/\s+/', ' ', $message);        //replace all white spaces to one space

    $message = helper::escapeText($message);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gift = new gift($dbo);
    $gift->setRequestFrom($accountId);

    $giftInfo = $gift->db_info($giftId);

    if ($giftInfo['error'] === false && $giftInfo['removeAt'] == 0) {

        $account = new account($dbo, $accountId);

        $balance = $account->getBalance();

        if ($balance == $giftInfo['cost'] || $balance > $giftInfo['cost']) {

            $result = $gift->send($giftId, $giftTo, $message, $giftAnonymous);

            if ($result['error'] === false) {

                $account->setBalance($balance - $giftInfo['cost']);

                $result['balance'] = $balance - $giftInfo['cost'];

                $payments = new payments($dbo);
                $payments->setRequestFrom($accountId);
                $payments->create(PA_BUY_GIFT, PT_CREDITS, $giftInfo['cost']);
                unset($payments);
            }
        }
    }

    echo json_encode($result);
    exit;
}
