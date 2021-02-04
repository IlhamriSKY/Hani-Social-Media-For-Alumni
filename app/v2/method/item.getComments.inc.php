<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $posts = new post($dbo);
    $posts->setRequestFrom($accountId);

    $itemInfo = $posts->info($itemId);

    if (!$itemInfo['error'] && $itemInfo['removeAt'] == 0) {

        $comments = new comments($dbo);
        $comments->setRequestFrom($accountId);

        $result = array(
            "error" => false,
            "error_code" => ERROR_SUCCESS,
            "itemId" => $itemId,
            "comments" => $comments->get($itemId, 0, $itemInfo),
            "items" => array()
        );

        array_push($result['items'], $itemInfo);
    }

    echo json_encode($result);
    exit;
}
