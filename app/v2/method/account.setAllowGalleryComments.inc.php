<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $allowGalleryComments = isset($_POST['allowGalleryComments']) ? $_POST['allowGalleryComments'] : 0;

    $allowGalleryComments = helper::clearInt($allowGalleryComments);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS);

    $account = new account($dbo, $accountId);

    $account->setAllowGalleryComments($allowGalleryComments);

    $result['allowGalleryComments'] = $account->getAllowGalleryComments();

    echo json_encode($result);
    exit;
}
