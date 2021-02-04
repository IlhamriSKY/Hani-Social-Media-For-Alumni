<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

	$error = false;
    $error_message = '';

    $account = new account($dbo, auth::getCurrentUserId());
    $fb_id = $account->getFacebookId();

    if (!empty($_POST)) {

    }

	$page_id = "settings_services";

	$css_files = array("main.css", "my.css");


    $page_title = $LANG['page-services']." | ".APP_TITLE;

	include_once("../html/common/header.inc.php");
?>

<body class="settings-page">

    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/account/settings/settings_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="profile-content standard-page">

                        <h1 class="title"><?php echo $LANG['page-services']; ?></h1>

                        <?php

                        $msg = $LANG['page-services-sub-title'];

                        if (isset($_GET['status'])) {

                            switch($_GET['status']) {

                                case "connected": {

                                    $msg = $LANG['label-services-facebook-connected'];
                                    break;
                                }

                                case "error": {

                                    $msg = $LANG['label-services-facebook-error'];
                                    break;
                                }

                                case "disconnected": {

                                    $msg = $LANG['label-services-facebook-disconnected'];
                                    break;
                                }

                                default: {

                                    $msg = $LANG['page-services-sub-title'];
                                    break;
                                }
                            }
                        }
                        ?>

                        <div class="warning-container">
                            <ul>
                                <?php echo $msg; ?>
                            </ul>
                        </div>

                        <header class="top-banner" style="padding: 0">

                            <div class="info">
                                <h1>Facebook</h1>

                                <?php

                                if ($fb_id != 0) {

                                    ?>
                                    <p><?php echo $LANG['label-connected-with-facebook']; ?></p>
                                    <?php
                                }
                                ?>

                            </div>

                            <div class="prompt">

                                <?php

                                if ($fb_id == 0) {

                                    ?>
                                    <a class="button green " href="/facebook/connect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-connect-facebook']; ?></a>
                                    <?php

                                } else {

                                    ?>
                                    <a class="button green" href="/facebook/disconnect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-disconnect']; ?></a>
                                    <?php
                                }
                                ?>

                            </div>

                        </header>

                    </div>


                </div>
            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body
</html>