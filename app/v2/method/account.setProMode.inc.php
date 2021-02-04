<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $cost = isset($_POST['cost']) ? $_POST['cost'] : 0;

    $cost = helper::clearInt($cost);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $account = new account($dbo, $accountId);

    $balance = $account->getBalance();

    if ($balance >= $cost) {

        $account->setBalance($account->getBalance() - $cost);

        $result = $account->setPro(1);
    }

    echo json_encode($result);
    exit;
}
