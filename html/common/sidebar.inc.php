<?php
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

?>

<div class="col sidebar-block pl-lg-0 pr-lg-3" style="">

    <?php

        if (auth::getCurrentUserId() != 0) {

            ?>

            <div class="card preview-block" id="preview-people-block">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="icofont icofont-ui-user mr-2"></i><span class="counter-button-title"><?php echo $LANG['tab-search-users']; ?></span></h3>
                    <span class="action-link">
                        <a href="/search/name"><?php echo $LANG['action-show-all']; ?></a>
                    </span>
                </div>

                <div class="card-body p-2">
                    <div class="grid-list row">

                        <?php

                        $search = new search($dbo);

                        $result = $search->preload(0, -1, -1, 1, 6);

                        foreach ($result['items'] as $key => $value) {

                            draw::previewPeopleItem($value, $LANG, $helper);
                        }

                        unset($search);
                        ?>

                    </div>
                </div>
            </div>

            <?php

                if (isset($page_id) && $page_id != "search_groups" && $page_id != "my_groups" && $page_id != "managed_groups") {

                    ?>
                        <div class="card preview-block" id="preview-groups-block">
                            <div class="card-header border-0">
                                <h3 class="card-title"><i class="icofont icofont-group mr-2"></i><span class="counter-button-title"><?php echo $LANG['page-groups']; ?></span></h3>
                                <span class="action-link">
                                    <a href="/search/groups"><?php echo $LANG['action-show-all']; ?></a>
                                </span>
                            </div>

                            <div class="card-body p-2">
                                <div class="grid-list row">

                                    <?php

                                    $search = new search($dbo);

                                    $result = $search->communitiesPreload(0, 4);

                                    foreach ($result['items'] as $key => $value) {

                                        draw::communityItemPreview($value, $LANG, $helper);
                                    }

                                    unset($search);
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>

            <?php

                $f_adsense_vertical_block = "../html/common/adsense_vertical.inc.php";

                if (file_exists($f_adsense_vertical_block)) {

                    ?>
                        <div class="card ad-block border-0 shadow-none" id="ad-block" style="background: transparent">

                            <div class="card-header p-0 border-0">

                                <?php
                                    require_once($f_adsense_vertical_block);
                                ?>

                            </div>

                        </div>
                    <?php

                } else {

                    ?>
<!--                     <a href="https://codecanyon.net/user/qascript/portfolio?ref=qascript" target="_blank">
                        <img width="100%" src="/img/ad_banner.png">
                    </a> -->
                    <?php
                }
            ?>

            <?php

        } else {

            ?>

            <div class="card preview-block" id="preview-people-block">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="icofont icofont-ui-user mr-2"></i><span class="counter-button-title"><?php echo $LANG['tab-search-users']; ?></span></h3>
                    <span class="action-link">
                        <a href="/search/name"><?php echo $LANG['action-show-all']; ?></a>
                    </span>
                </div>

                <div class="card-body p-2">
                    <div class="grid-list row">

                        <?php

                        $search = new search($dbo);

                        $result = $search->preload(0, -1, -1, 1, 6);

                        foreach ($result['items'] as $key => $value) {

                            draw::previewPeopleItem($value, $LANG, $helper);
                        }

                        unset($search);
                        ?>

                    </div>
                </div>
            </div>

            <div class="card preview-block border-0 shadow-none" id="ad-block" style="background: transparent">

                <div class="card-header p-0 border-0">

                    <?php

                        $f_adsense_vertical_block = "../html/common/adsense_vertical.inc.php";

                        if (file_exists($f_adsense_vertical_block)) {

                            ?>
                                <div class="card ad-block border-0 shadow-none" id="ad-block" style="background: transparent">

                                    <div class="card-header p-0 border-0">

                                        <?php
                                            require_once($f_adsense_vertical_block);
                                        ?>

                                    </div>

                                </div>
                            <?php

                        } else {

                            ?>
<!--                              <a href="https://codecanyon.net/user/qascript/portfolio?ref=qascript" target="_blank">
                                 <img width="100%" src="/img/ad_banner.png">
                             </a> -->
                            <?php
                        }
                    ?>

                </div>

            </div>


            <?php
        }
    ?>

</div>