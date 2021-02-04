<?php

/*! Hani Halo Alumni v1  */

if (auth::isSession()) {

    header("Location: /account/wall");
    exit;
}

if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook') {

    header("Location: /signup");
    exit;
}

require_once 'autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// init app with app id and secret
FacebookSession::setDefaultApplication(FACEBOOK_APP_ID, FACEBOOK_APP_SECRET);

// login helper with redirect_uri
$helper2 = new FacebookRedirectLoginHelper(APP_URL.'/facebook/login');

try {

    $session = $helper2->getSessionFromRedirect();

} catch(FacebookRequestException $ex) {

    // When Facebook returns an error
    header("Location: /signup");
    exit;

} catch( Exception $ex ) {

    // When validation fails or other local issues
    header("Location: /signup");
    exit;
}

// see if we have a session
if (isset($session)) {

    // graph api request for user data
    $request = new FacebookRequest( $session, 'GET', '/me' );
    $response = $request->execute();

    // get response
    $graphObject = $response->getGraphObject();
    $fbid = $graphObject->getProperty('id');              // To Get Facebook ID
    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
    $flink = $graphObject->getProperty('link');

    $accountId = $helper->getUserIdByFacebook($fbid);

    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();

    if ($accountInfo['error'] === false) {

        //user with fb id exists in db

        if ($accountInfo['state'] == ACCOUNT_STATE_BLOCKED) {

            header("Location: /");
            exit;

        } else {

            $account->setState(ACCOUNT_STATE_ENABLED);
            $account->setLastActive();

            $clientId = 0; // Desktop version

            $auth = new auth($dbo);
            $access_data = $auth->create($accountId, $clientId, APP_TYPE_WEB, "", "");

            if ($access_data['error'] === false) {

                auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                auth::updateCookie($accountInfo['username'], $access_data['accessToken']);

                header("Location: /account/wall");
            }
        }

    } else {

        //new user
        $_SESSION['oauth'] = 'facebook';
        $_SESSION['oauth_id'] = $fbid;
        $_SESSION['oauth_name'] = $fbfullname;

        if (isset($flink)) {

            $_SESSION['oauth_link'] = $flink;
        }

        $_SESSION['oauth_email'] = "";

        if (isset($femail)) {

            $_SESSION['oauth_email'] = $femail;
        }

        header("Location: /signup");
        exit;
    }

} else {

    $loginUrl = $helper2->getLoginUrl();
    header("Location: ".$loginUrl);
}

