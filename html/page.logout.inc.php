<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (isset($_GET['access_token'])) {

        $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';
        $continue = (isset($_GET['continue'])) ? ($_GET['continue']) : '/';

        if (auth::getAccessToken() === $accessToken) {

            $account = new account($dbo);
            $account->logout(auth::getCurrentUserId(), auth::getAccessToken());
            $account->setLastActive();

            auth::unsetSession();
            auth::clearCookie();

            header('Location: '.$continue);
            exit;
        }
    }

    header('Location: /');

?>