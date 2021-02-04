<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

?>

<?php

    $f_adsense_wide_block = "../html/common/adsense_wide.inc.php";
    $f_adsense_square_block = "../html/common/adsense_square.inc.php";

    if (file_exists($f_adsense_wide_block)) {

        if (isset($page_id)) {

            ?>
                <div class="card ad-block border-0 shadow-none" id="ad-block" style="background: transparent">

                    <div class="card-header p-0 border-0">

                        <?php

                            require_once($f_adsense_wide_block);

                        ?>
                    </div>
                </div>
            <?php
        }
    }