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

    $buku_alumni = new bukualumni($dbo);
    $buku_alumni->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (isset($_GET['query'])) {

        $query = isset($_GET['query']) ? $_GET['query'] : '';

        $query = helper::clearText($query);
        $query = helper::escapeText($query);
    }

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;
        $query = isset($_POST['query']) ? $_POST['query'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        $result = $buku_alumni->query($query, $itemId);

        $items_loaded = count($result['items']);
        $items_all = $result['itemCount'];


        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::bukualumniItemPreview($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Search.bukualumniMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search_buku_alumni";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-buku-alumni']." | ".APP_TITLE;

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
                                <?php echo $LANG['page-buku-alumni']; ?>
                            </div>
                            <div class="page-title-content-bottom-inner">
                                <?php echo $LANG['page-buku-alumni-sub-title']; ?>
                            </div>
                            <br>
                            <div class="page-title-content-bottom-inner">
                                <?php echo $LANG['page-buku-alumni-search-guide']; ?>
                            </div>
                        </div>

                        <div class="standard-page search-box ">
                            <form class="search-container" method="get" action="/search/bukualumni">
                                <div class="search-editbox-line">
                                    <input class="search-field" name="query" id="query" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" autocomplete="off" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">
                                    <button type="submit" class="btn btn-main blue"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php

                        if (auth::isSession()) {

                            ?>
                                <div class="card border-0">
                                    <div class="card-header row mx-0 d-flex">
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                            <div class="upgrades-feature-container">
                                                <h3 class="card-title">
                                                    <?php echo $LANG['buku-alumni-ku-title']; ?>
                                                </h3>
                                                <h5 class="card-description"><?php echo $LANG['buku-alumni-ku-desc']; ?></h5>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                            <button type="button" data-toggle="modal" data-target="#newItemModal" class="action-button button green p-2"><i class="icofont icofont-plus"></i> <?php echo $LANG['buku-alumni-new-item-button-title']; ?></button>

                                        </div>

                                    </div>
                                </div>
                            <?php
                        }
                    ?>

                    <div class="content-list-page">

                        <?php

                        if (strlen($query) > 0) {

                            $result = $buku_alumni->query($query, 0);

                        } else {

                            $result = $buku_alumni->query("");
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

                            <div class="grid-list row" style="margin-right: -10px; margin-left: -10px;">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::bukualumniItemPreview($value, $LANG, $helper);
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
                                    <button onclick="Search.bukualumniMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

    <div class="modal" id="newItemModal" tabindex="-1" role="dialog" aria-labelledby="newItemModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newItemModalTitle"><?php echo $LANG['market-new-item-dlg-title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" class="new-bukualumni-item">
                        <input autocomplete="off" type="hidden" name="accessToken" value="<?php echo auth::getAccessToken(); ?>">
                        <input autocomplete="off" type="hidden" name="accountId" value="<?php echo auth::getCurrentUserId(); ?>">
                        <div class="form-group">
                            <label for="market-item-title" class="col-form-label"><?php echo $LANG['buku-alumni-new-item-title']; ?>:</label>
                            <input placeholder="<?php echo $LANG['buku-alumni-new-item-title-placeholder']; ?>" type="text" class="form-control" id="market-item-title" name="title">
                        </div>

                        <div class="form-group">
                            <label for="market-item-desc" class="col-form-label"><?php echo $LANG['buku-alumni-new-item-desc']; ?>:</label>
                            <textarea placeholder="<?php echo $LANG['market-new-item-ad-desc-placeholder']; ?>" style="min-height: 100px" class="form-control" id="market-item-desc" name="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="buku-alumni-shareto" class="col-form-label"><?php echo $LANG['buku-alumni-new-item-shareto']; ?></label>
                            <br>
                            <select id="buku-alumni-shareto" name="shareto">
                                <option value="1"><?php echo $LANG['for-public']; ?></option>
                                <option value="2"><?php echo $LANG['friends-only']; ?></option>
                            </select>
                        </div>

                        <div class="form-group">

                            <div class="item-image-progress hidden">
                                <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="market-upload-button-container" style="height: 35px">
                                <div class="btn btn-secondary item-image-action-button item-add-image" style="right: auto">
                                    <input type="file" id="bukualumni-item-image-upload" name="uploaded_file">
                                    <i class="iconfont icofont-ui-image mr-1"></i>
                                    <?php echo $LANG['action-add-img']; ?>
                                </div>
                            </div>

                            <div class="img_container">

                                <div class="img-items-list-page">

                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $LANG['action-cancel']; ?></button>
                    <button onclick="Bukualumni.newItem()" type="button" class="btn blue"><?php echo $LANG['action-post']; ?></button>
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
        $(document).ready(function() {
            $('#newItemModal').on('show.bs.modal', function (event) {
                var modal = $(this)
                modal.find('#market-item-desc').val('');
                modal.find('#market-item-title').val('');
                modal.find('#buku-alumni-shareto').val('1');
                modal.find('div.img-items-list-page').html("");
                modal.find('div.market-upload-button-container').removeClass('hidden');
            })
        });
    </script>


</body>
</html>
