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
    $userId = isset($_POST['userId']) ? $_POST['userId'] : 0;

    $gender = isset($_POST['gender']) ? $_POST['gender'] : -1;
    $online = isset($_POST['online']) ? $_POST['online'] : -1;

    $photo = isset($_POST['photo']) ? $_POST['photo'] : -1;

    $query = helper::clearText($query);
    $query = helper::escapeText($query);

    $userId = helper::clearInt($userId);

    if ($gender != -1) $gender = helper::clearInt($gender);
    if ($online != -1) $online = helper::clearInt($online);
    if ($photo != -1) $photo = helper::clearInt($photo);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $search = new search($dbo);
    $search->setRequestFrom($accountId);

    $result = $search->query($query, $userId, $gender, $online, $photo);

    echo json_encode($result);
    exit;
}
