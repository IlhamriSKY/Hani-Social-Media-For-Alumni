<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $hash = isset($_POST['hash']) ? $_POST['hash'] : '';

    $appType = isset($_POST['appType']) ? $_POST['appType'] : 0; // 0 = APP_TYPE_UNKNOWN
    $fcm_regId = isset($_POST['fcm_regId']) ? $_POST['fcm_regId'] : '';
    $lang = isset($_POST['lang']) ? $_POST['lang'] : '';

    $facebookId = isset($_POST['facebookId']) ? $_POST['facebookId'] : '';

    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $tgllahir = isset($_POST['tgllahir']) ? $_POST['tgllahir'] : '';
    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;

    $photoUrl = isset($_POST['photo']) ? $_POST['photo'] : '';
    $u_gender = isset($_POST['gender']) ? $_POST['gender'] : 0;
    $u_age = isset($_POST['age']) ? $_POST['age'] : 18;

    $language = isset($_POST['language']) ? $_POST['language'] : '';

    $clientId = helper::clearInt($clientId);

    $referrer = helper::clearInt($referrer);

    $facebookId = helper::clearText($facebookId);

    $username = helper::clearText($username);
    $nim = helper::clearText($nim);
    $tgllahir = helper::clearText($tgllahir);
    $fullname = helper::clearText($fullname);
    $password = helper::clearText($password);
    $email = helper::clearText($email);
    $language = helper::clearText($language);

    $facebookId = helper::escapeText($facebookId);

    $username = helper::escapeText($username);
    $nim = helper::escapeText($nim);
    $tgllahir = helper::escapeText($tgllahir);
    $fullname = helper::escapeText($fullname);
    $password = helper::escapeText($password);
    $email = helper::escapeText($email);
    $language = helper::escapeText($language);

    $u_age = helper::clearInt($u_age);
    $u_gender = helper::clearInt($u_gender);
    $photoUrl = helper::clearText($photoUrl);
    $photoUrl = helper::escapeText($photoUrl);

    $appType = helper::clearInt($appType);

    $fcm_regId = helper::clearText($fcm_regId);
    $fcm_regId = helper::escapeText($fcm_regId);

    $lang = helper::clearText($lang);
    $lang = helper::escapeText($lang);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    if (APP_USE_CLIENT_SECRET) {

        if ($hash !== md5(md5($username).CLIENT_SECRET)) {

            api::printError(ERROR_CLIENT_SECRET, "Error hash.");
        }
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $account = new account($dbo);
    $result = $account->signup($username, $nim, $tgllahir, $fullname, $password, $email, $language);
    unset($account);

    if ($result['error'] === false) {

        $account = new account($dbo);
        $account->setState(ACCOUNT_STATE_ENABLED);
        $account->setLastActive();
        $result = $account->signin($username, $password);
        unset($account);

        if ($result['error'] === false) {

            $auth = new auth($dbo);
            $result = $auth->create($result['accountId'], $clientId, $appType, $fcm_regId, $lang);

            if (!$result['error']) {

                $account = new account($dbo, $result['accountId']);

                $account->setSex($u_gender);
                $account->setAge($u_age);

                // refsys

                if ($referrer != 0) {

                    $ref = new refsys($dbo);
                    $ref->setRequestFrom($account->getId());
                    $ref->setBonus(BONUS_REFERRAL);
                    $ref->setReferrer($referrer);

                    unset($ref);
                }

                if (strlen($photoUrl) != 0) {

                    $photos = array("error" => false,
                                    "originPhotoUrl" => $photoUrl,
                                    "normalPhotoUrl" => $photoUrl,
                                    "bigPhotoUrl" => $photoUrl,
                                    "lowPhotoUrl" => $photoUrl);

                    $account->setPhoto($photos);

                    unset($photos);
                }

                // Facebook

                if (strlen($facebookId) != 0) {

                    $helper = new helper($dbo);

                    if ($helper->getUserIdByFacebook($facebookId) == 0) {

                        $account->setFacebookId($facebookId);
                    }

                } else {

                    $account->setFacebookId("");
                }

                $result['account'] = array();

                array_push($result['account'], $account->get());
            }
        }
    }

    echo json_encode($result);
    exit;
}
