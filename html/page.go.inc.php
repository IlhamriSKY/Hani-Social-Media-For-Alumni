<?php

/*! Hani Halo Alumni v1  */
    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $error = false;
    $error_msg = "";

    if (isset($_GET['to'])) {

        $url = (isset($_GET['to'])) ? $_GET['to'] : '';

        $url = helper::clearText($url);
        $url = helper::escapeText($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {

            header("Location: /");
            exit;

        } else {

            // add url to db

//            $stats = new stats($dbo);
//            $stats->setRequestFrom(auth::getCurrentUserId());
//            $stats->add($url);
//
//            unset($stats);

            header("Location: ".$url);
        }

    } else {

        header("Location: /");
        exit;
    }
