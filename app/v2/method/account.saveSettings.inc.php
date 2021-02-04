<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $facebookPage = isset($_POST['facebookPage']) ? $_POST['facebookPage'] : '';
    $instagramPage = isset($_POST['instagramPage']) ? $_POST['instagramPage'] : '';
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';

    $sex = isset($_POST['sex']) ? $_POST['sex'] : 0;
    $age = isset($_POST['age']) ? $_POST['age'] : 18;
    $year = isset($_POST['year']) ? $_POST['year'] : 0;
    $month = isset($_POST['month']) ? $_POST['month'] : 0;
    $day = isset($_POST['day']) ? $_POST['day'] : 0;

    $allowShowMyAgeAndGender = isset($_POST['allowShowMyAgeAndGender']) ? $_POST['allowShowMyAgeAndGender'] : 0;

    $accountId = helper::clearInt($accountId);

    $fullname = helper::clearText($fullname);
    $fullname = helper::escapeText($fullname);

    $location = helper::clearText($location);
    $location = helper::escapeText($location);

    $facebookPage = helper::clearText($facebookPage);
    $facebookPage = helper::escapeText($facebookPage);

    $instagramPage = helper::clearText($instagramPage);
    $instagramPage = helper::escapeText($instagramPage);

    $bio = helper::clearText($bio);

    $bio = preg_replace( "/[\r\n]+/", " ", $bio); //replace all new lines to one new line
    $bio  = preg_replace('/\s+/', ' ', $bio);        //replace all white spaces to one space

    $bio = helper::escapeText($bio);

    $sex = helper::clearInt($sex);
    $age = helper::clearInt($age);

    $allowShowMyAgeAndGender = helper::clearInt($allowShowMyAgeAndGender);

    $year = helper::clearInt($year);
    $month = helper::clearInt($month);
    $day = helper::clearInt($day);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $account = new account($dbo, $accountId);
    $account->setLastActive();

    $account->setFullname($fullname);
    $account->setLocation($location);
    $account->setStatus($bio);

    $account->setSex($sex);
    $account->setAge($age);
    $account->setAllowShowMyAgeAndGender($allowShowMyAgeAndGender);
    $account->setBirth($year, $month, $day);

    if (helper::isValidURL($facebookPage)) {

        $account->setFacebookPage($facebookPage);

    } else {

        $account->setFacebookPage("");
    }

    if (helper::isValidURL($instagramPage)) {

        $account->setInstagramPage($instagramPage);

    } else {

        $account->setInstagramPage("");
    }

    $result = $account->get();

    echo json_encode($result);
    exit;
}
