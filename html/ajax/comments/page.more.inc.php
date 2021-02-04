<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!empty($_POST)) {

        $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';
        $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $commentId = isset($_POST['commentId']) ? $_POST['commentId'] : 0;
        $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 2; // ITEM_TYPE_POST = 2

        $itemId = helper::clearInt($itemId);
        $commentId = helper::clearInt($commentId);
        $itemType = helper::clearInt($itemType);

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $auth = new auth($dbo);

        if (!$auth->authorize($accountId, $accessToken)) {

            api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
        }

        $comments = new comments($dbo, $itemType);
        $comments->setLanguage($LANG['lang-code']);
        $comments->setRequestFrom($accountId);

        $result = $comments->get($itemId, $commentId);

        $result['comments'] = array_reverse($result['comments'], false);

        ob_start();

        foreach ($result['comments'] as $key => $value) {

            draw::comment($value, $LANG);
        }

        $result['html'] = ob_get_clean();

        echo json_encode($result);
        exit;
    }

    echo "asd";