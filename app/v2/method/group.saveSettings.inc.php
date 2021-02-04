<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $groupId = isset($_POST['group_id']) ? $_POST['group_id'] : 0;

    $group_name = isset($_POST['group_name']) ? $_POST['group_name'] : '';
    $group_category = isset($_POST['group_category']) ? $_POST['group_category'] : 0;
    $group_desc = isset($_POST['group_desc']) ? $_POST['group_desc'] : '';
    $group_site = isset($_POST['group_site']) ? $_POST['group_site'] : '';
    $group_location = isset($_POST['group_location']) ? $_POST['group_location'] : '';

    $year = isset($_POST['year']) ? $_POST['year'] : 0;
    $month = isset($_POST['month']) ? $_POST['month'] : 0;
    $day = isset($_POST['day']) ? $_POST['day'] : 0;

    $group_allow_posts = isset($_POST['group_allow_posts']) ? $_POST['group_allow_posts'] : 0;
    $group_allow_comments = isset($_POST['group_allow_comments']) ? $_POST['group_allow_comments'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $groupId = helper::clearInt($groupId);

    $group_category = helper::clearInt($group_category);

    $year = helper::clearInt($year);
    $month = helper::clearInt($month);
    $day = helper::clearInt($day);

    $group_allow_posts = helper::clearInt($group_allow_posts);
    $group_allow_comments = helper::clearInt($group_allow_comments);

    $group_name = helper::clearText($group_name);
    $group_name = helper::escapeText($group_name);

    $group_category = helper::clearText($group_category);
    $group_category = helper::escapeText($group_category);

    $group_desc = helper::clearText($group_desc);
    $group_desc = helper::escapeText($group_desc);

    $group_site = helper::clearText($group_site);
    $group_site = helper::escapeText($group_site);

    $group_location = helper::clearText($group_location);
    $group_location = helper::escapeText($group_location);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $group = new group($dbo, $groupId);
    $group->setRequestFrom($accountId);

    $group->setAllowPosts($group_allow_posts);
    $group->setAllowComments($group_allow_comments);

    $group->edit($group_name);

    $group->setBirth($year, $month, $day);
    $group->setStatus($group_desc);
    $group->setLocation($group_location);
    $group->setCategory($group_category);

    if (helper::isValidURL($group_site)) {

        $group->setWebPage($group_site);

    } else {

        $group->setWebPage("");
    }

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS);

    echo json_encode($result);
    exit;
}
