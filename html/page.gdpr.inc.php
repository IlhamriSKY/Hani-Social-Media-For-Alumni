<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $page_id = "gdpr";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-gdpr']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

    ?>

<body class="about has-bottom-footer">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column">

            <div class="main-content">

                <?php

                    if (file_exists("../html/gdpr/".$LANG['lang-code'].".inc.php")) {

                        include_once("../html/gdpr/".$LANG['lang-code'].".inc.php");

                    } else {

                        include_once("../html/gdpr/en.inc.php");
                    }
                ?>

            </div>

        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>


</body
</html>