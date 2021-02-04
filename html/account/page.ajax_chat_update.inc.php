<?php

/*! Hani Halo Alumni v1  */

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $chat_id = 0;
    $user_id = 0;

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $chat_id = isset($_POST['chat_id']) ? $_POST['chat_id'] : 0;
        $message_id = isset($_POST['message_id']) ? $_POST['message_id'] : 0;

        $user_id = helper::clearInt($user_id);
        $chat_id = helper::clearInt($chat_id);
        $message_id = helper::clearInt($message_id);

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if ($access_token != auth::getAccessToken()) {

            api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
        }

        $messages = new messages($dbo);
        $messages->setRequestFrom(auth::getCurrentUserId());

        $result = $messages->getNextMessages($chat_id, $message_id);

        ob_start();

        foreach ($result['messages'] as $key => $value) {

            draw::messageItem($value, $LANG, $helper);
        }

        $result['html'] = ob_get_clean();
        $result['items_all'] = $messages->messagesCountByChat($chat_id);

        echo json_encode($result);
        exit;
    }