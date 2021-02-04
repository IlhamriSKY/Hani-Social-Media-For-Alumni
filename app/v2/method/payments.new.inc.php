<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $credits = isset($_POST['credits']) ? $_POST['credits'] : 0;
    $paymentType = isset($_POST['paymentType']) ? $_POST['paymentType'] : 0;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

    $credits = helper::clearInt($credits);
    $paymentType = helper::clearInt($paymentType);
    $amount = helper::clearInt($amount);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $account = new account($dbo, $accountId);
    $result = $account->setBalance($account->getBalance() + $credits);

    if (!$result['error']) {

        $result['balance'] = $account->getBalance();

        $payments = new payments($dbo);
        $payments->setRequestFrom($accountId);
        $payments->create(PA_BUY_CREDITS, $paymentType, $credits, $amount);
        unset($payments);
    }

    echo json_encode($result);
    exit;
}
