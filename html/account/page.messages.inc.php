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

    $messages = new messages($dbo);
    $messages->setRequestFrom(auth::getCurrentUserId());

    $account = new account($dbo, auth::getCurrentUserId());
    $account->setLastActive();
    unset($account);

    $chats_all = $messages->myActiveChatsCount();
    $chats_loaded = 0;

    if (!empty($_POST)) {

        $messageCreateAt = isset($_POST['messageCreateAt']) ? $_POST['messageCreateAt'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $messageCreateAt = helper::clearInt($messageCreateAt);
        $loaded = helper::clearInt($loaded);

        $result = $messages->getChats($messageCreateAt);

        $chats_loaded = count($result['chats']);

        $result['chats_loaded'] = $chats_loaded + $loaded;
        $result['chats_all'] = $chats_all;

        if ($chats_loaded != 0) {

            ob_start();

            foreach ($result['chats'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['chats_loaded'] < $chats_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Chats.more('<?php echo $result['messageCreateAt']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "messages";

    $css_files = array("main.css", "tipsy.css");
    $page_title = $LANG['page-messages']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-messages">


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

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['page-messages']; ?></h3>
                            <h5 class="card-description"><?php echo $LANG['label-messages-sub-title']; ?></h5>
                        </div>
                    </div>

                    <div class="content-list-page">

                        <?php

                        $result = $messages->getChats(0);

                        $chats_loaded = count($result['chats']);

                        if ($chats_loaded != 0) {

                            ?>

                            <div class="card cards-list content-list">

                                <?php

                                foreach ($result['chats'] as $key => $value) {

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

                        if ($chats_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Chats.more('<?php echo $result['messageCreateAt']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

    <script type="text/javascript">

        var chats_all = <?php echo $chats_all; ?>;
        var chats_loaded = <?php echo $chats_loaded; ?>;

    </script>


</body>
</html>

<?php

    function draw($chat, $LANG, $helper)
    {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($chat['withUserPhotoUrl']) != 0) {

            $profilePhotoUrl = $chat['withUserPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="card-item classic-item default-item chat-item" data-id="<?php echo $chat['id']; ?>">
            <div class="card-body">
                    <span class="card-header px-0 pt-0 border-0">
                        <a href="/<?php echo $chat['withUserUsername']; ?>">
                            <img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/>
                        </a>

                        <?php if ($chat['withUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>

                        <div class="card-content">
                            <span class="card-title">
                                <a href="/account/chat/?chat_id=<?php echo $chat['id']; ?>&user_id=<?php echo $chat['withUserId']; ?>"><?php echo $chat['withUserFullname']; ?></a>
                                        <!-- Hani -->
                                        <!-- Bedge -->
                                        <?php
                                        if ($chat['withUserVerify'] == 1) {

                                            ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                            <?php
                                        }

                                        if ($chat['fromUserStaff']) {
                                            if ($chat['fromGender'] == 1) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                                <?php
                                            }elseif ($chat['fromGender'] == 2) { 
                                                ?>
                                                    <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-satff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                                                <?php
                                            }else{
                                                ?>
                                                <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                                            <?php
                                            }
                                        }

                                        if ($chat['fromUserBot'] == 1) {

                                            ?>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                                            <?php
                                        }
                                        ?>
                                        <!-- End of bedge -->
                            </span>

                            <span class="card-date"><?php echo $time->timeAgo($chat['lastMessageCreateAt']); ?></span>

                            <span class="card-status-text">

                                <?php

                                if (strlen($chat['lastMessage']) == 0) {

                                    echo "Image";

                                } else {

                                    echo $chat['lastMessage'];
                                }
                                ?>

                            </span>

                            <?php

                            if ($chat['newMessagesCount'] != 0) {

                                ?>
                                <span class="card-counter red"><?php echo $chat['newMessagesCount']; ?></span>
                                <?php
                            }
                            ?>

                            <span class="card-action">
                                <a href="javascript:void(0)" onclick="Messages.removeChat('<?php echo $chat['id']; ?>', '<?php echo $chat['withUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="card-act negative"><?php echo $LANG['action-remove']; ?></a>
                                <a href="/account/chat/?chat_id=<?php echo $chat['id']; ?>&user_id=<?php echo $chat['withUserId']; ?>" class="card-act active"><?php echo $LANG['action-go-to-chat']; ?></a>
                            </span>
                        </div>
                    </span>
            </div>
        </li>

        <?php
    }

?>