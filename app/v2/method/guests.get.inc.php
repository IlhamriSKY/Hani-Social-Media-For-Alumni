<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $itemId = helper::clearInt($itemId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if ($itemId == 0) {

        $account = new account($dbo, $accountId);
        $account->setLastGuestsView();
        unset($account);
    }

    $guests = new guests($dbo, $accountId);
    $guests->setRequestFrom($accountId);
    $result = $guests->get($itemId);

    echo json_encode($result);
    exit;
}
