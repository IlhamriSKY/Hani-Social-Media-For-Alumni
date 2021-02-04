<?php
    error_reporting(0);
    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $stream = new stream($dbo);
    $stream->setRequestFrom(auth::getCurrentUserId());


    // Hani Server
    try {
        $url = 'https://haniapi.mooo.com/metamorfosa'; // path to your JSON file
        $data = file_get_contents($url); // put the contents of the file into a variable
        $characters = json_decode($data); // decode the JSON feed
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }



    if (!empty($_POST)) {
        if ($items_loaded != 0) {
            ob_start();
            if ($result['items_loaded'] < $items_all) {
                ob_start();
                ?>
                <header class="top-banner loading-banner">
                    <div class="prompt">
                        <button onclick="Items.more('/account/news/metamorfosa', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>
                </header>
                <?php
                $result['banner'] = ob_get_clean();
            }
        }
        echo json_encode($result);
        exit;
    }
    $page_id = "metamorfosa";
    $css_files = array("main.css");
    $page_title = $LANG['page-metamorfosa']." | ".APP_TITLE;
    include_once("../html/common/header.inc.php");
?>

<body class="page-favorites">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">
        <div class="main-column row">
            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>
            <div class="row col sn-content sn-content-sidebar-block" id="content">
                <div class="main-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['page-metamorfosa']; ?></h3>
                            <h5 class="card-description"><?php echo $LANG['page-metamorfosa-description']; ?></h5>
                        </div>
                    </div>
                    <div class=" content-list-page posts-list-page posts-list-page-bordered">
                        <?php
                            if ($characters->url != 0) {
                            // var_dump($result)
                        ?>
                            <div class="items-list content-list">
                                <?php foreach ($characters->url as $urls => $index): ?>
                                <div class="card">
                                    <li class="custom-list-item post-item">
                                        <div class="mb-2 item-header">
                                            <a href="/vanika" class="item-logo" style="background-image:url('/img/vanikaprofile.jpg')"></a>
                                            <span title="Online" class="item-logo-online"></span>
                                            <a href="/vanika" class="custom-item-link post-item-fullname">Vanika</a>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                                            <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="Staff account"><i class="iconfont icofont-businesswoman"></i></span>
                                            <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="Bot account"><i class="iconfont icofont-robot"></i></span>
                                             <span class="post-item-time">
                                                <?php echo $characters->date[$urls]; ?>
                                                <?php
                                                    echo "<b title=\"{$LANG['hint-item-android-version']}\" class=\"android-version-icon ml-1\"></b>";
                                                ?>
                                            </span>
                                        </div>
                                        <div class="item-meta post-item-content">
                                            <div class="post-text mx-2"><?php echo $characters->judul[$urls]; ?></div>
                                            <img class="post-img" data-id="" data-href="<?php echo $characters->images[$urls]; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $characters->images[$urls]; ?>">
                                            <div class="post-text mx-2"><?php echo $characters->desc[$urls]; ?></div>
                                        </div>
                                    </li>
                                     <div class="item-footer">
                                        <div class="item-footer-container">
                                            <span class="item-footer-button">
                                                <a href="<?php echo $characters->url[$urls]; ?>" target="_blank">
                                                    <?php echo $LANG['action-readmore']; ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach ?> 
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

            <?php

                include_once("../html/common/sidebar.inc.php");
            ?>

        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        var inbox_all = <?php echo $items_all; ?>;
        var inbox_loaded = <?php echo $items_loaded; ?>;

    </script>


</body>
</html>