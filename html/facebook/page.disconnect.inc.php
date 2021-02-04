<?php

/*! Hani Halo Alumni v1  */

if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

    header('Location: /');
}

if (isset($_GET['access_token'])) {

    $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';

    if (auth::getAccessToken() === $accessToken) {

        $account = new account($dbo, auth::getCurrentUserId());
        $account->setFacebookId(0); //remove connection. set facebook id to 0.

        header("Location: /account/settings/services/?oauth_provider=facebook&status=disconnected");
        exit;
    }
}

header("Location: /account/settings/services");
