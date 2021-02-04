<?php

/*! Hani Halo Alumni v1  */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

	$profileId = $helper->getUserId($request[0]);

	$imageExists = true;

	$profile = new profile($dbo, $profileId);

	$profile->setRequestFrom(auth::getCurrentUserId());
	$profileInfo = $profile->get();

	if ($profileInfo['error'] === true) {

		include_once("../html/error.inc.php");
		exit;
	}

	if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

		include_once("../html/stubs/profile.inc.php");
		exit;
	}

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom(auth::getCurrentUserId());

	$itemId = helper::clearInt($request[2]);

	$imageInfo = $gallery->info($itemId);

	if ($imageInfo['error'] === true) {

        // Missing
		$imageExists = false;
	}

	if ($imageExists && $imageInfo['removeAt'] != 0) {

		// Missing
		$imageExists = false;
	}

	if ($imageExists && $profileInfo['id'] != $imageInfo['fromUserId']) {

        // Missing
		$imageExists = false;
    }

	$page_id = "image";

	$css_files = array("main.css", "my.css", "tipsy.css");

	$page_title = $profileInfo['fullname']." | ".APP_HOST."/".$profileInfo['username'];

	include_once("../html/common/header.inc.php");

?>

<body class="">


	<?php
		include_once("../html/common/topbar.inc.php");
	?>


	<div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/images/images_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="content-list-page">

                    <?php

                    if ($imageExists) {

                        ?>

                        <div class="items-list content-list m-0">

                            <?php

                            draw::image($imageInfo, $LANG, $helper, true);

                            ?>

                            <?php

                                require_once ("../html/common/adsense_banner.inc.php");
                            ?>

                        </div>

                        <?php

                    } else {

                        ?>

                        <div class="card information-banner">
                            <div class="card-header">
                                <div class="card-body">
                                    <h5 class="m-0"><?php echo $LANG['label-image-missing']; ?></h5>
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

	<?php

		include_once("../html/common/footer.inc.php");
	?>

	<script type="text/javascript">

		var replyToUserId = 0;

		<?php

            if (auth::getCurrentUserId() == $profileInfo['id']) {

                ?>
					var myPage = true;
				<?php
    		}
		?>

	</script>


</body
</html>