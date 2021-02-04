<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $appType = isset($_POST['appType']) ? $_POST['appType'] : 0; // 0 = APP_TYPE_UNKNOWN
    $fcm_regId = isset($_POST['fcm_regId']) ? $_POST['fcm_regId'] : '';
    $lang = isset($_POST['lang']) ? $_POST['lang'] : '';

    $clientId = helper::clearInt($clientId);

    $username = helper::clearText($username);
    $password = helper::clearText($password);

    $username = helper::escapeText($username);
    $password = helper::escapeText($password);

    $appType = helper::clearInt($appType);

    $lang = helper::clearText($lang);
    $lang = helper::escapeText($lang);

    $fcm_regId = helper::clearText($fcm_regId);
    $fcm_regId = helper::escapeText($fcm_regId);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $access_data = array();

    $account = new account($dbo);
    $access_data = $account->signin($username, $password);

    unset($account);

    if (!$access_data["error"]) {

        $account = new account($dbo, $access_data['accountId']);

        switch ($account->getState()) {

            case ACCOUNT_STATE_BLOCKED: {

                break;
            }

            case ACCOUNT_STATE_DISABLED: {

                break;
            }

            default: {

                $auth = new auth($dbo);
                $access_data = $auth->create($access_data['accountId'], $clientId, $appType, $fcm_regId, $lang);

                if (!$access_data['error']) {

                    $account->setState(ACCOUNT_STATE_ENABLED);
                    $account->setLastActive();
                    $access_data['account'] = array();

                    array_push($access_data['account'], $account->get());
                }

                break;
            }
        }
    }

    echo json_encode($access_data);
    exit;
}
