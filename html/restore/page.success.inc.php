<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (auth::isSession()) {

        header("Location: /account/wall");
        exit;
    }

    $page_id = "restore_success";

    $css_files = array("main.css", "my.css");
    $page_title = APP_TITLE;

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

                    <h1><?php echo $LANG['label-success']; ?>!</h1>

                    <div class="opt-in">
                        <label for="user_receive_digest">
                            <b><?php echo $LANG['label-password-reset-success']; ?></span></b>
                        </label>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body>
</html>