<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $accountId = auth::getCurrentUserId();

    $error = false;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $password = isset($_POST['pswd']) ? $_POST['pswd'] : '';

        $password = helper::clearText($password);
        $password = helper::escapeText($password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if ( !$error ) {

            $account = new account($dbo, $accountId);

            $result = array();

            $result = $account->deactivation($password);

            if ($result['error'] === false) {

                header("Location: /logout/?access_token=".auth::getAccessToken());
                exit;
            }
        }

        header("Location: /account/settings/deactivation/?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_deactivation";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-profile-deactivation']." | ".APP_TITLE;

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

                        <h1 class="title"><?php echo $LANG['page-profile-deactivation']; ?></h1>

                        <form accept-charset="UTF-8" action="/account/settings/deactivation" autocomplete="off" class="edit_user" id="settings-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                            <div class="tabbed-content">

                                <div class="warning-container">
                                    <ul>
                                        <?php echo $LANG['page-profile-deactivation-sub-title']; ?>
                                    </ul>
                                </div>

                                <?php

                                if ( isset($_GET['error']) ) {

                                    ?>

                                    <div class="errors-container" style="margin-top: 15px;">
                                        <ul>
                                            <?php echo $LANG['msg-error-deactivation']; ?>
                                        </ul>
                                    </div>

                                    <?php
                                }
                                ?>

                                <div class="tab-pane active form-table">

                                    <div class="profile-basics form-row">
                                        <div class="form-cell left">
                                            <p class="info"><?php echo $LANG['label-settings-deactivation-sub-title']; ?></p>
                                        </div>

                                        <div class="form-cell">
                                            <input id="pswd" name="pswd" placeholder="<?php echo $LANG['label-password']; ?>" maxlength="32" type="password" value="">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <input style="margin-top: 25px" name="commit" class="button blue" type="submit" value="<?php echo $LANG['action-deactivation-profile']; ?>">

                        </form>
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