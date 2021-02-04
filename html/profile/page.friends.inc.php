<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $profileId = $helper->getUserId($request[0]);

    $user = new profile($dbo, $profileId);

    $user->setRequestFrom(auth::getCurrentUserId());
    $profileInfo = $user->get();

    $friends = new friends($dbo, $profileId);

    if ($profileInfo['error'] === true) {

        include_once("../html/error.inc.php");
        exit;
    }

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("../html/stubs/profile.inc.php");
        exit;
    }

    $friends_all = $profileInfo['friendsCount'];
    $friends_loaded = 0;

    if (!empty($_POST)) {

        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $id = helper::clearInt($id);
        $loaded = helper::clearInt($loaded);

        $result = $friends->get($id);

        $friends_loaded = count($result['items']);

        $result['friends_loaded'] = $friends_loaded + $loaded;
        $result['friends_all'] = $friends_all;

        if ($friends_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();



            if ($result['friends_loaded'] < $friends_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Friends.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "friends";

    $css_files = array("main.css", "my.css", "tipsy.css");
    $page_title = $LANG['page-friends']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="cards-page">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/profile/profile_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-friends']; ?>
                        </div>
                    </div>

                    <div class="content-list-page mt-3">

                        <?php

                        if ($profileInfo['id'] != auth::getCurrentUserId() && !$profileInfo['friend'] && $profileInfo['allowShowMyFriends'] == 1) {

                            ?>
                            <header class="top-banner info-banner">

                                <div class="info">
                                    <h1><?php echo $LANG['label-error-permission']; ?></h1>
                                </div>

                            </header>
                            <?php

                        } else {

                            $result = $friends->get(0);

                            $friends_loaded = count($result['items']);

                            if ($friends_loaded != 0) {

                                ?>

                                <div class="content-list px-0 border-0 row">

                                    <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw::peopleItem($value, $LANG, $helper);
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

                            if ($friends_all > 20) {

                                ?>

                                <header class="top-banner loading-banner">

                                    <div class="prompt">
                                        <button onclick="Friends.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }
                            ?>
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

    <script type="text/javascript" src="/js/friends.js"></script>

    <script type="text/javascript">

        var friends_all = <?php echo $friends_all; ?>;
        var friends_loaded = <?php echo $friends_loaded; ?>;

    </script>


</body
</html>
