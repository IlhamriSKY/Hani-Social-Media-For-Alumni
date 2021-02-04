<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $friends_loaded = 0;

    $friends = new friends($dbo, auth::getCurrentUserId());

    $page_id = "friends_online";

    $css_files = array("main.css", "tipsy.css", "my.css");
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

                include_once("../html/account/friends/friends_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-friends']; ?>
                        </div>

                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['label-friends-online-sub-title']; ?>
                        </div>

                        <div class="page-title-content-extra">
                            <a class="extra-button button blue" href="/search/name"><?php echo$LANG['label-friends-search-sub-title']; ?></a>
                        </div>

                    </div>

                    <div class=" content-list-page mt-3">

                        <?php

                        $result = $friends->getOnline();

                        $friends_loaded = count($result['items']);

                        if ($friends_loaded != 0) {

                            ?>

                            <div class="content-list px-0 border-0  row ">

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


    </script>


</body
</html>
