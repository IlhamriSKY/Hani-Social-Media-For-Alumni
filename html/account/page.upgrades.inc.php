<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $error = false;

    if (isset($_SESSION['upgrades-error'])) {

        $error = true;

        unset($_SESSION['upgrades-error']);
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $accountInfo = $account->get();

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $act = isset($_POST['act']) ? $_POST['act'] : '';

        if (auth::getAccessToken() === $token) {

            switch ($act) {

                case "ghost-mode": {

                    if ($accountInfo['ghost'] == 0 && $accountInfo['balance'] >= UPGRADES_GHOST_MODE_COST) {

                        $account->setBalance($account->getBalance() - UPGRADES_GHOST_MODE_COST);

                        $result = $account->setGhost(1);

                        if (!$result['error']) {

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_GHOST_MODE, PT_CREDITS, UPGRADES_GHOST_MODE_COST);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "verified-badge": {

                    if ($accountInfo['verify'] == 0 && $accountInfo['balance'] >= UPGRADES_VERIFIED_BADGE_COST) {

                        $account->setBalance($account->getBalance() - UPGRADES_VERIFIED_BADGE_COST);

                        $result = $account->setVerify(1);

                        if (!$result['error']) {

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_VERIFIED_BADGE, PT_CREDITS, UPGRADES_VERIFIED_BADGE_COST);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }

                case "admob-feature": {

                    if ($accountInfo['admob'] == 1 && $accountInfo['balance'] >= UPGRADES_DISABLE_ADS_COST) {

                        $account->setBalance($account->getBalance() - UPGRADES_DISABLE_ADS_COST);

                        $result = $account->setAdmob(0);

                        if (!$result['error']) {

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());
                            $payments->create(PA_BUY_DISABLE_ADS, PT_CREDITS, UPGRADES_DISABLE_ADS_COST);
                            unset($payments);
                        }

                    } else {

                        $_SESSION['upgrades-error'] = true;
                    }

                    break;
                }
            }
        }

        header("Location: /account/upgrades");
        exit;
    }

    $page_id = "upgrades";

    $css_files = array("main.css");
    $page_title = $LANG['page-upgrades']." | ".APP_TITLE;

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

            <div class="row col sn-content sn-content-wide-block d-block" id="content">

                <div class="main-content">

                    <div class="upgrades-intro-header">

                        <span class="upgrades-icon bg_blue">
                            <i class="fa fa-star"></i>
                        </span>

                        <div class="upgrades-intro-header-main">
                            <h1 class="upgrades-title"><?php echo $LANG['page-upgrades']; ?></h1>
                            <p class="upgrades-sub-title"><?php echo $LANG['page-upgrades-desc']; ?></p>
                        </div>

                        <a class="action-button button green" href="/account/settings/balance">
                            <span><b><?php echo $accountInfo['balance']; ?> <?php echo $LANG['label-credits']; ?></b></span><?php echo $LANG['action-buy-credits']; ?>
                        </a>

                    </div>
                </div>

                <div class="main-content" style="background: #fff">

                    <?php

                    if ($error) {

                        ?>
                        <div class="standard-page p-4">
                            <div class="errors-container">
                                <ul>
                                    <i class="icofont icofont-exclamation-circle"></i> <?php echo $LANG['label-balance-not-enough']; ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="upgrades-intro-header">

                        <span class="upgrades-icon bg_light_blue">
                            <i class="fa fa-check"></i>
                        </span>

                        <form id="verified-badge-form" action="/account/upgrades" method="post">
                            <input type="hidden" name="act" value="verified-badge">
                            <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">
                            <div class="upgrades-intro-header-main">

                                <h1 class="upgrades-title"><?php echo $LANG['label-upgrades-verified-badge']; ?></h1>
                                <span id="verified-badge"> -
                                    <?php

                                        if ($accountInfo['verify'] != 1) {

                                            echo UPGRADES_VERIFIED_BADGE_COST." ".$LANG['label-payments-credits'];
                                        }
                                    ?>
                                    </span>
                                <p class="upgrades-sub-title"><?php echo $LANG['label-upgrades-verified-badge-desc']; ?></p>
                            </div>

                            <?php

                            if ($accountInfo['verify'] == 0) {

                                ?>
                                    <button type="submit" class="action-button button blue"><i class="fa fa-check"></i> <?php echo $LANG['action-activate']; ?></button>
                                <?php

                            } else {

                                ?>
                                    <button disabled type="submit" class="action-button button secondary p-2"><i class="fa fa-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                <?php
                            }
                            ?>
                        </form>

                    </div>

                    <div class="upgrades-intro-header">

                        <span class="upgrades-icon bg_gray">
                            <i class="fa fa-user-secret"></i>
                        </span>

                        <form id="ghost-mode-form" action="/account/upgrades" method="post">
                            <input type="hidden" name="act" value="ghost-mode">
                            <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">
                            <div class="upgrades-intro-header-main">
                                <h1 class="upgrades-title"><?php echo $LANG['label-upgrades-ghost-mode']; ?></h1>
                                <span id="ghost-mode"> -
                                    <?php

                                        if ($accountInfo['ghost'] != 1) {

                                            echo UPGRADES_GHOST_MODE_COST." ".$LANG['label-payments-credits'];
                                        }
                                    ?>
                                    </span>
                                <p class="upgrades-sub-title"><?php echo $LANG['label-upgrades-ghost-mode-desc']; ?></p>
                            </div>

                            <?php

                                if ($accountInfo['ghost'] == 0) {

                                    ?>

                                        <button type="submit" class="action-button button blue"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['action-activate']; ?></button>
                                    <?php

                                } else {

                                    ?>
                                        <button disabled type="submit" class="action-button button secondary p-2"><i class="fa fa-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                    <?php
                                }
                            ?>

                        </form>
                    </div>

                    <div class="upgrades-intro-header">

                        <span class="upgrades-icon bg_red">
                            <i class="fa fa-ad"></i>
                        </span>

                        <form id="admob-feature-form" action="/account/upgrades" method="post">
                            <input type="hidden" name="act" value="admob-feature">
                            <input type="hidden" name="authenticity_token" value="<?php echo auth::getAccessToken(); ?>">
                            <div class="upgrades-intro-header-main">
                                <h1 class="upgrades-title"><?php echo $LANG['label-upgrades-off-admob']; ?></h1>
                                <span id="admob-feature"> -
                                    <?php

                                        if ($accountInfo['admob'] != 0) {

                                            echo UPGRADES_DISABLE_ADS_COST." ".$LANG['label-payments-credits'];
                                        }
                                    ?>
                                    </span>
                                <p class="upgrades-sub-title"><?php echo $LANG['label-upgrades-off-admob-desc']; ?></p>
                            </div>

                            <?php

                                if ($accountInfo['admob'] != 0) {

                                    ?>

                                        <button type="submit" class="action-button button blue"><i class="icofont icofont-verification-check"></i> <?php echo $LANG['action-activate']; ?></button>
                                    <?php

                                } else {

                                    ?>
                                        <button disabled type="submit" class="action-button button secondary p-2"><i class="fa fa-check"></i> <?php echo $LANG['label-activated']; ?></button>
                                    <?php
                                }
                            ?>
                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

        <script type="text/javascript">


        </script>


</body
</html>
