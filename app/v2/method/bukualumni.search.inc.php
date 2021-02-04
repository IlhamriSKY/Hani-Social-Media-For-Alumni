<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $query = isset($_POST['query']) ? $_POST['query'] : '';
    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $query = helper::clearText($query);
    $query = helper::escapeText($query);

    $itemId = helper::clearInt($itemId);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $buku_alumni = new bukualumni($dbo);
    $buku_alumni->setRequestFrom($accountId);

    $result = $buku_alumni->query($query, $itemId);

    echo json_encode($result);
    exit;
}
