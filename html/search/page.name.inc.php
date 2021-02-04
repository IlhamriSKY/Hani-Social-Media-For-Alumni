<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!auth::isSession() && !WEB_EXPLORE) {

        header('Location: /');
        exit;
    }

    $query = '';

    $u_online = -1;
    $u_gender = -1;
    $u_photo = -1;

    $search = new search($dbo);
    $search->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (isset($_GET['query'])) {

        $query = isset($_GET['query']) ? $_GET['query'] : '';

        $u_online = isset($_GET['online']) ? $_GET['online'] : 'all';
        $u_gender = isset($_GET['gender']) ? $_GET['gender'] : 'all';
        $u_photo = isset($_GET['photo']) ? $_GET['photo'] : 'all';

        $u_online = helper::clearText($u_online);
        $u_online = helper::escapeText($u_online);

        $u_photo = helper::clearText($u_photo);
        $u_photo = helper::escapeText($u_photo);

        $u_gender = helper::clearText($u_gender);
        $u_gender = helper::escapeText($u_gender);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        if ($u_online === "yes") {

            $u_online = 1;

        } else {

            $u_online = -1;
        }

        if ($u_photo === "yes") {

            $u_photo = 1;

        } else {

            $u_photo = -1;
        }

        if ($u_gender === "male") {

            $u_gender = 1;

        } else if ($u_gender === "female") {

            $u_gender = 2;

        } else {

            $u_gender = -1;
        }
    }

    if (!empty($_POST)) {

        $userId = isset($_POST['userId']) ? $_POST['userId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;
        $query = isset($_POST['query']) ? $_POST['query'] : '';

        $u_online = isset($_POST['online']) ? $_POST['online'] : -1;
        $u_gender = isset($_POST['gender']) ? $_POST['gender'] : -1;
        $u_photo = isset($_POST['photo']) ? $_POST['photo'] : -1;

        $userId = helper::clearInt($userId);
        $loaded = helper::clearInt($loaded);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        if ($u_gender != -1) $u_gender = helper::clearInt($u_gender);
        if ($u_online != -1) $u_online = helper::clearInt($u_online);
        if ($u_photo != -1) $u_photo = helper::clearInt($u_photo);


        if (strlen($query) > 0) {

            $result = $search->query($query, $userId, $u_gender, $u_online, $u_photo);

        } else {

            $result = $search->preload($userId, $u_gender, $u_online, $u_photo);
        }

        $items_loaded = count($result['items']);
        $items_all = $result['itemCount'];


        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Search.more('<?php echo $result['itemId']; ?>', '<?php echo $u_online; ?>', '<?php echo $u_gender; ?>', '<?php echo $u_photo; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-search']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-search">


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

                    <form class="search-container" method="get" action="/search/name">

                        <div class="card">

                            <div class="standard-page page-title-content">
                                <div class="page-title-content-inner">
                                    <?php echo $LANG['tab-search-users']; ?>
                                </div>
                                <div class="page-title-content-bottom-inner">
                                    <?php echo $LANG['tab-search-users-description']; ?>
                                </div>
                            </div>

                            <div class="standard-page search-box">

                                <div class="search-editbox-line">

                                    <input class="search-field" name="query" id="query" autocomplete="off" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">

                                    <button type="submit" class="btn btn-main blue"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                                </div>

                            </div>

                        </div>

                        <div class="card">

                            <div class="card-header">
                                <h3 class="card-title"></i><?php echo $LANG['label-search-filters']; ?></h3>
                            </div>

                            <div class="search-filters">

                                <div class="search-filter-form-line">
                                    <h5 style="margin-top: 0px;"><?php echo $LANG['search-filters-active']; ?></h5>
                                    <label class="search-filter-radio-button" for="online-radio-1">
                                        <input type="radio" name="online" id="online-radio-1" value="all"><?php echo $LANG['search-filters-all']; ?></label>
                                    <label class="search-filter-radio-button" for="online-radio-2">
                                        <input type="radio" name="online" id="online-radio-2" value="yes" <?php if ($u_online != -1) echo "checked" ?>><?php echo $LANG['search-filters-online']; ?></label>
                                </div>

                                <div class="search-filter-form-line">
                                    <h5><?php echo $LANG['search-filters-gender']; ?></h5>
                                    <label class="search-filter-radio-button" for="gender-radio-1">
                                        <input type="radio" name="gender" id="gender-radio-1" value="all"><?php echo $LANG['search-filters-all']; ?></label>
                                    <label class="search-filter-radio-button" for="gender-radio-2">
                                        <input type="radio" name="gender" id="gender-radio-2" value="male" <?php if ($u_gender == 1) echo "checked" ?>><?php echo $LANG['search-filters-male']; ?></label>
                                    <label class="search-filter-radio-button" for="gender-radio-3">
                                        <input type="radio" name="gender" id="gender-radio-3" value="female" <?php if ($u_gender == 2) echo "checked" ?>><?php echo $LANG['search-filters-female']; ?></label>
                                </div>

                                <div class="search-filter-form-line">
                                    <h5><?php echo $LANG['search-filters-photo']; ?></h5>
                                    <label class="search-filter-radio-button" for="photo-radio-1">
                                        <input type="radio" name="photo" id="photo-radio-1" value="all"><?php echo $LANG['search-filters-all']; ?></label>
                                    <label class="search-filter-radio-button" for="photo-radio-2">
                                        <input type="radio" name="photo" id="photo-radio-2" value="yes" <?php if ($u_photo != -1) echo "checked" ?>><?php echo $LANG['search-filters-photo-filter']; ?></label>
                                </div>

                            </div>
                        </div>

                    </form>

                    <div class="content-list-page">

                        <?php

                        if (strlen($query) > 0) {

                            $result = $search->query($query, 0, $u_gender, $u_online, $u_photo);

                        } else {

                            $result = $search->preload(0, $u_gender, $u_online, $u_photo);
                        }

                        $items_all = $result['itemCount'];
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

                            <div class="grid-list row">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::peopleItem($value, $LANG, $helper);
                                }
                                ?>

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card">

                                <header class="top-banner info-banner">

                                    <div class="info">
                                        <?php echo $LANG['label-search-empty']; ?>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }
                        ?>

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="top-banner loading-banner border-0">

                                <div class="prompt">
                                    <button onclick="Search.more('<?php echo $result['itemId']; ?>', '<?php echo $u_online; ?>', '<?php echo $u_gender; ?>', '<?php echo $u_photo; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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
