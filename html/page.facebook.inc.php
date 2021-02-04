<?php

/*! Hani Halo Alumni v1  */

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (auth::isSession()) {

    header("Location: /account/wall");
    exit;
}

if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook') {

    unset($_SESSION['oauth']);
    unset($_SESSION['oauth_id']);
    unset($_SESSION['oauth_name']);
    unset($_SESSION['oauth_email']);
    unset($_SESSION['oauth_link']);

    header("Location: /signup");
    exit;
}

header("Location: /");