<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!admin::isSession()) {

        header('Location: /');
    }

    if (isset($_GET['access_token'])) {

        $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';
        $continue = (isset($_GET['continue'])) ? ($_GET['continue']) : '/';

        if (admin::getAccessToken() === $accessToken) {

            admin::unsetSession();

            header('Location: '.$continue);
            exit;
        }
    }

    header('Location: /');