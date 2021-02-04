<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!auth::isSession() && !WEB_EXPLORE) {

        header('Location: /');
        exit;
    }

    $query = '';

    $search = new search($dbo);
    $search->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (isset($_GET['query'])) {

        $query = isset($_GET['query']) ? $_GET['query'] : '';

        $query = helper::clearText($query);
        $query = helper::escapeText($query);
    }

    if (!empty($_POST)) {

        $userId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;
        $query = isset($_POST['query']) ? $_POST['query'] : '';

        $userId = helper::clearInt($userId);
        $loaded = helper::clearInt($loaded);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        $result = $search->communitiesQuery($query, $userId);

        $items_loaded = count($result['items']);
        $items_all = $result['itemsCount'];


        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::communityItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Search.communitiesMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search_groups";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-search-communities']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-search-groups">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/search/search_nav.inc.php");
            ?>

            <div class="row col-lg-7 col-md-12 sn-content" id="content">

                <div class="main-content">

                    <div class="card">

                        <div class="standard-page page-title-content">
                            <div class="page-title-content-inner">
                                <?php echo $LANG['page-search-communities']; ?>
                            </div>
                            <div class="page-title-content-bottom-inner">
                                <?php echo $LANG['tab-search-communities-description']; ?>
                            </div>
                        </div>

                        <div class="standard-page search-box ">
                            <form class="search-container" method="get" action="/search/groups">
                                <div class="search-editbox-line">
                                    <input class="search-field" name="query" id="query" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" autocomplete="off" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">
                                    <button type="submit" class="btn btn-main blue"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="content-list-page mt-3">

                        <?php

                        if (strlen($query) > 0) {

                            $result = $search->communitiesQuery($query, 0);

                        } else {

                            $result = $search->communitiesPreload(0);
                        }

                        $items_all = $result['itemsCount'];
                        $items_loaded = count($result['items']);

                        if (strlen($query) > 0) {

                            ?>

                            <div class="card">

                                <header class="top-banner">

                                    <div class="info">
                                        <h1><?php echo $LANG['label-search-result']; ?> (<?php echo $items_all; ?>)</h1>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }

                        if ($items_loaded != 0) {

                            ?>

                            <div class="grid-list row" style="margin-right: -10px; margin-left: -10px;">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::communityItem($value, $LANG, $helper);
                                }

                                ?>
                            </div>

                            <?php
                        }
                        ?>

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Search.communitiesMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

        var items_all = <?php echo $items_all; ?>;
        var items_loaded = <?php echo $items_loaded; ?>;
        var query = "<?php echo $query; ?>";

    </script>


</body
</html>
