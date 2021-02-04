<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $profile = new profile($dbo, auth::getCurrentUserId());

    $profile->setLastNotifyView();

    $notifications = new notify($dbo);
    $notifications->setRequestFrom(auth::getCurrentUserId());

    $notifications_all = $notifications->getAllCount();
    $notifications_loaded = 0;

    if (!empty($_POST)) {

        $notifyId = isset($_POST['notifyId']) ? $_POST['notifyId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $act = isset($_POST['act']) ? $_POST['act'] : '';
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        if ($act === 'clear' && $access_token === auth::getAccessToken()) {

            $notifications->clear();

            exit;
        }

        $notifyId = helper::clearInt($notifyId);
        $loaded = helper::clearInt($loaded);

        $result = $notifications->getAll($notifyId);

        $notifications_loaded = count($result['notifications']);

        $result['notifications_loaded'] = $notifications_loaded + $loaded;
        $result['answers_all'] = $notifications_all;

        if ($notifications_loaded != 0) {

            ob_start();

            foreach ($result['notifications'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['notifications_loaded'] < $notifications_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Notifications.moreAll('<?php echo $result['notifyId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "notifications";

    $css_files = array("main.css", "tipsy.css");
    $page_title = $LANG['page-notifications']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-notifications">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <div class="row col sn-content sn-content-wide-block" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-notifications']; ?>
                        </div>
                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['page-notifications-description']; ?>
                        </div>

                        <?php

                        if ($notifications_all > 0) {

                            ?>
                            <div class="page-title-content-extra">
                                <a class="extra-button button blue" href="javascript:void(0)" onclick="Notifications.clear('<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo$LANG['action-clear-all']; ?></a>
                            </div>
                            <?php
                        }

                        ?>
                    </div>

                    <div class="content-list-page mt-3">

                        <?php

                        $result = $notifications->getAll(0);

                        $notifications_loaded = count($result['notifications']);

                        if ($notifications_loaded != 0) {

                            ?>

                            <div class="card cards-list content-list">

                                <?php

                                foreach ($result['notifications'] as $key => $value) {

                                    draw($value, $LANG, $helper);
                                }
                                ?>

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card information-banner">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="m-0"><?php echo $LANG['label-empty-list']; ?></h5>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                        <?php

                        if ($notifications_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Notifications.moreAll('<?php echo $result['notifyId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                </div>

                            </header>

                            <?php
                        }
                        ?>


                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript" src="/js/friends.js?x=1"></script>

    <script type="text/javascript">

        var notifications_all = <?php echo $notifications_all; ?>;
        var notifications_loaded = <?php echo $notifications_loaded; ?>;

    </script>


</body>
</html>

<?php

    function draw($notify, $LANG, $helper)
    {
        $time = new language(NULL, $LANG['lang-code']);
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($notify['fromUserPhotoUrl']) != 0) {

            $profilePhotoUrl = $notify['fromUserPhotoUrl'];
        }

        switch ($notify['type']) {

            case NOTIFY_TYPE_LIKE: {

                $post = new post(NULL);
                $post->setRequestFrom(auth::getCurrentUserId());

                $post = $post->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon like"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        
                                        <span class="sub-title"><?php echo $LANG['label-likes-your-post']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-post']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_FOLLOWER: {

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon friend-request"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-notify-request-to-friends']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a class="card-act negative" href="javascript:void(0)" onclick="Friends.rejectRequest('<?php echo $notify['id']; ?>', '<?php echo $notify['fromUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo $LANG['action-reject']; ?></a>
                                        <a class="card-act active" href="javascript:void(0)" onclick="Friends.acceptRequest('<?php echo $notify['id']; ?>', '<?php echo $notify['fromUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo $LANG['action-accept']; ?></a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_COMMENT: {

                $post = new post(NULL);
                $post->setRequestFrom(auth::getCurrentUserId());

                $post = $post->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon comment"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-new-comment']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-post']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_COMMENT_REPLY: {

                $post = new post(NULL);
                $post->setRequestFrom(auth::getCurrentUserId());

                $post = $post->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body border-0">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon reply"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-new-reply-to-comment']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-post']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_GIFT: {

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body border-0">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon gift"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-new-gift']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a  href="/<?php echo auth::getCurrentUserLogin(); ?>/gifts" class="card-act active"><?php echo $LANG['action-view']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_IMAGE_COMMENT: {

                $gallery = new gallery(NULL);
                $gallery->setRequestFrom(auth::getCurrentUserId());

                $photoInfo = $gallery->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body border-0">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon comment"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-new-comment']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $photoInfo['owner']['username']; ?>/image/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-photo']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_IMAGE_COMMENT_REPLY: {

                $gallery = new gallery(NULL);
                $gallery->setRequestFrom(auth::getCurrentUserId());

                $photoInfo = $gallery->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body border-0">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon reply"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-new-reply-to-comment']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $photoInfo['owner']['username']; ?>/image/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-photo']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>
                <?php

                break;
            }

            case NOTIFY_TYPE_IMAGE_LIKE: {

                $gallery = new gallery(NULL);
                $gallery->setRequestFrom(auth::getCurrentUserId());

                $photoInfo = $gallery->info($notify['postId']);

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body border-0">
                            <span class="card-header px-0 pt-0 border-0">
                                <a href="/<?php echo $notify['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                                <span title="" class="card-notify-icon like"></span>
                                <?php if ($notify['fromUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                <div class="card-content">
                                    <span class="card-title">
                                        <a href="/<?php echo $notify['fromUserUsername']; ?>"><?php echo  $notify['fromUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($notify['fromUserVerified'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($notify['fromUserStaff']) {
                                            if ($notify['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($notify['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($notify['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                                        <span class="sub-title"><?php echo $LANG['label-likes-your-photo']; ?></span>
                                    </span>
                                    <span class="card-username">@<?php echo  $notify['fromUserUsername']; ?></span>
                                    <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    <span class="card-action">
                                        <a href="/<?php echo $photoInfo['owner']['username']; ?>/image/<?php echo $notify['postId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-photo']; ?> »</a>
                                    </span>
                                </div>
                            </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_PROFILE_PHOTO_REJECT: {

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body">
                                <span class="card-header px-0 pt-0 border-0">
                                    <a href="javascript:void(0)"><img class="card-icon" src="/img/def_photo.png"/></a>
                                    <span title="" class="card-notify-icon rejected"></span>
                                    <div class="card-content">
                                        <span class="card-title">
                                            <a href="javascript:void(0)"><?php echo APP_NAME; ?></a>
                                            <span class="sub-title"><?php echo $LANG['label-notify-profile-photo-reject']; ?></span>
                                            <br><span class="sub-title"><?php echo $LANG['label-notify-profile-photo-reject-subtitle']; ?></span>
                                        </span>
                                        <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    </div>
                                </span>
                    </div>
                </li>

                <?php

                break;
            }

            case NOTIFY_TYPE_PROFILE_COVER_REJECT: {

                ?>

                <li class="card-item classic-item default-item" data-id="<?php echo $notify['id']; ?>">
                    <div class="card-body">
                                <span class="card-header px-0 pt-0 border-0">
                                    <a href="javascript:void(0)"><img class="card-icon" src="/img/def_photo.png"/></a>
                                    <span title="" class="card-notify-icon rejected"></span>
                                    <div class="card-content">
                                        <span class="card-title">
                                            <a href="javascript:void(0)"><?php echo APP_NAME; ?></a>
                                            <span class="sub-title"><?php echo $LANG['label-notify-profile-cover-reject']; ?></span>
                                            <br><span class="sub-title"><?php echo $LANG['label-notify-profile-cover-reject-subtitle']; ?></span>
                                        </span>
                                        <span class="card-counter black"><?php echo $time->timeAgo($notify['createAt']); ?></span>
                                    </div>
                                </span>
                    </div>
                </li>

                <?php

                break;
            }
            
            default: {


                break;
            }
        }
    }

?>