<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $page_id = "support";

    $error = false;
    $send_status = false;
    $email = "";
    $subject = "";
    $about = "";

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
        $about = isset($_POST['about']) ? $_POST['about'] : '';

        $subject = helper::clearText($subject);
        $about = helper::clearText($about);
        $email = helper::clearText($email);

        $subject = helper::escapeText($subject);
        $about = helper::escapeText($about);
        $email = helper::escapeText($email);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!helper::isCorrectEmail($email)) {

            $error = true;
        }

        if (empty($about)) {

            $error = true;
        }

        if (empty($subject)) {

            $error = true;
        }

        if (!$error) {

            $accountId = auth::getCurrentUserId();
            $clientId = 0; //Desktop version;

            $support = new support($dbo);
            $support->createTicket($accountId, $email, $subject, $about, $clientId);

            $send_status = true;
        }
    }

    auth::newAuthenticityToken();

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-support']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="remind-page has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <?php

                    if ($send_status) {

                        ?>

                        <h1><?php echo $LANG['page-support']; ?></h1>

                        <div class="opt-in">
                            <label for="user_receive_digest">
                                <b><?php echo $LANG['ticket-send-success']; ?></b>
                            </label>
                        </div>

                        <?php

                    } else {

                        ?>

                        <h1><?php echo $LANG['page-support']; ?></h1>
                        <p><?php echo $LANG['label-support-sub-title']; ?></p>

                        <form accept-charset="UTF-8" action="/support" class="custom-form" id="remind-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                            <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">
                                <p class="title"><?php echo $LANG['ticket-send-error']; ?></p>
                            </div>

                            <p><label for="email"><?php echo $LANG['label-email']; ?></label></p>
                            <input id="email" name="email" placeholder="" required="required" size="30" type="text" value="<?php echo $email; ?>">

                            <p><label for="subject"><?php echo $LANG['label-subject']; ?></label></p>
                            <input id="subject" name="subject" maxlength="164" placeholder="" required="required" size="30" type="text" value="<?php echo $subject; ?>">

                            <p><label for="about"><?php echo $LANG['label-support-message']; ?></label></p>
                            <textarea id="about" name="about" required="required" maxlength="800"><?php echo $about; ?></textarea>

                            <div class="login-button">
                                <input name="commit" class="primary_btn blue" type="submit" value="<?php echo $LANG['action-send']; ?>">
                            </div>

                        </form>

                        <?php

                    }
                    ?>
                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body>
</html>