<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $page_id = "about";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-about'];

    include_once("../html/common/header.inc.php");

    ?>

<body class="about has-bottom-footer">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column">

            <div class="main-content">

                <section class="standard-page">
                    <h1><?php echo $LANG['page-about']; ?></h1>
                    <p><?php echo APP_TITLE." (web version) © ".APP_YEAR; ?></p>
                    <p><?php echo $LANG['page-about-version']." ".APP_VERSION; ?></p>
                </section>

                <section class="standard-page">
                    <h1>About</h1>

                    <h3>Hani - Halo Alumni</h3>

                    <p>Hani or Halo Alumni is a social media specially developed for alumni of Soegijapranata Catolic University. developed and maintained by SSCC bureau.</p>

                </section>

            </div>

        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>


</body>
</html>