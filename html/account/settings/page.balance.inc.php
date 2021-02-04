<?php

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    require('../html/stripe/init.php');

    \Stripe\Stripe::setVerifySslCerts(false);
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

    if (!empty($_POST)) {

        $packageId = isset($_POST['packageId']) ? $_POST['packageId'] : 0;

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        if ($packageId < count($payments_packages)) {

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'name' => $payments_packages[$packageId]['name'],
                    'amount' => $payments_packages[$packageId]['amount'],
                    'currency' => 'usd',
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'capture_method' => 'automatic',
                ],
                'success_url' => APP_URL.'/account/settings/balance?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => APP_URL.'/account/settings/balance',
            ]);

            $stripeSession = array($session);
            $sessId = ($stripeSession[0]['id']);

            $_SESSION['sessId'] = $sessId;
            $_SESSION['packageId'] = $packageId;

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "sessId" => $sessId
            );
        }

        echo json_encode($result);
        exit;
    }

    $payment = false;

    if (isset($_GET['session_id'])) {

        try {

            $response = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

            if (isset($_SESSION['sessId'])) {

                $paymentIntent = \Stripe\PaymentIntent::retrieve($response['payment_intent']);

                if ($paymentIntent['amount_received'] == $response['display_items'][0]['amount'] && $_GET['session_id'] == $_SESSION['sessId']) {

                    $account = new account($dbo, auth::getCurrentUserId());
                    $account->setBalance($account->getBalance() + $payments_packages[$_SESSION['packageId']]['credits']);

                    $payments = new payments($dbo);
                    $payments->setRequestFrom(auth::getCurrentUserId());
                    $payments->create(PA_BUY_CREDITS, PT_CARD, $payments_packages[$_SESSION['packageId']]['credits'], $payments_packages[$_SESSION['packageId']]['amount']);
                    unset($payments);

                    unset($_SESSION['sessId']);
                    unset($_SESSION['packageId']);

                    $payment = true;
                }
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {

            $payment = false;
        }
    }

    $account = new account($dbo, auth::getCurrentUserId());

    $page_id = "settings_balance";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-balance']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="settings-page">

<?php

    include_once("../html/common/topbar.inc.php");
?>

<div class="wrap content-page">
    <div class="main-column row">

        <?php

            include_once("../html/common/sidenav.inc.php");
        ?>

        <?php

            include_once("../html/account/settings/settings_nav.inc.php");
        ?>

        <div class="row col sn-content" id="content">

            <div class="main-content">

                <div class="standard-page profile-content">

                    <?php

                    if ($payment) {

                        ?>

                        <div class="success-container" style="margin-top: 15px;">
                            <ul class="m-0">
                                <b><?php echo $LANG['label-thanks']; ?></b>
                                <br>
                                <?php echo $LANG['label-payments-success_added']; ?>
                            </ul>
                        </div>

                        <?php
                    }
                    ?>

                    <h1 class="title"><?php echo $LANG['page-balance']; ?></h1>
                    <p><?php echo $LANG['page-balance-desc'] ?></p>
                    <p><?php echo $LANG['action-get-explanation'] ?></p>
                    <p><?php echo $LANG['label-balance']; ?> <b><?php echo $account->getBalance(); ?> <?php echo $LANG['label-credits']; ?></b></p>

                    <header class="top-banner px-0 pb-0">
                        <div class="info">
                            <h1><?php echo $LANG['label-payments-history']; ?></h1>
                        </div>
                    </header>

                    <div class="listview">
                        <table class="bordered data-tables responsive-table">
                            <tbody>
                            <tr class="listview-header">
                                <th class="text-center"><?php echo $LANG['label-payments-credits']; ?></th>
                                <th class="text-center"><?php echo $LANG['label-payments-amount']; ?></th>
                                <th class="text-right"><?php echo $LANG['label-payments-description']; ?></th>
                                <th class="text-right"><?php echo $LANG['label-payments-date']; ?></th>
                            </tr>

                            <?php

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());

                            $result = $payments->get(0, 30);

                            if (count($result['items']) == 0) {

                                ?>

                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="card information-banner border-0 shadow-none m-0">
                                            <div class="card-header border-0">
                                                <div class="card-body">
                                                    <h5 class="m-0"><?php echo $LANG['label-empty-list']; ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php

                            } else {

                                foreach ($result['items'] as $key => $value) {

                                    ?>

                                    <tr>
                                        <td class="text-center">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                case PA_BUY_REGISTRATION_BONUS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                case PA_BUY_REFERRAL_BONUS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                default: {

                                                    echo "<span class=\"red\">-".$value['credits']."</span>";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    if ($value['amount'] > 0) {

                                                        echo "$".$value['amount'] / 100;
                                                        break;
                                                    }
                                                }

                                                default: {

                                                    echo "";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right" style="word-break: break-word">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    switch ($value['paymentType']) {

                                                        case PT_CARD: {

                                                            echo $LANG['label-payments-credits-stripe'];
                                                            break;
                                                        }

                                                        case PT_GOOGLE_PURCHASE: {

                                                            echo $LANG['label-payments-credits-android'];
                                                            break;
                                                        }

                                                        case PT_APPLE_PURCHASE: {

                                                            echo $LANG['label-payments-credits-ios'];
                                                            break;
                                                        }

                                                        case PT_ADMOB_REWARDED_ADS: {

                                                            echo $LANG['label-payments-credits-admob'];
                                                            break;
                                                        }
                                                    }

                                                    break;
                                                }

                                                case PA_BUY_GIFT: {

                                                    echo $LANG['label-payments-send-gift'];
                                                    break;
                                                }

                                                case PA_BUY_VERIFIED_BADGE: {

                                                    echo $LANG['label-payments-verified-badge'];
                                                    break;
                                                }

                                                case PA_BUY_GHOST_MODE: {

                                                    echo $LANG['label-payments-ghost-mode'];
                                                    break;
                                                }

                                                case PA_BUY_DISABLE_ADS: {

                                                    echo $LANG['label-payments-off-admob'];
                                                    break;
                                                }

                                                case PA_BUY_REGISTRATION_BONUS: {

                                                    echo $LANG['label-payments-registration-bonus'];
                                                    break;
                                                }

                                                case PA_BUY_REFERRAL_BONUS: {

                                                    echo $LANG['label-payments-referral-bonus'];
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right"><?php echo $value['date']; ?></td>
                                    </tr>

                                    <?php
                                }
                            }

                            ?>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>


    <script src="https://js.stripe.com/v3/"></script>

<?php

include_once("../html/common/footer.inc.php");
?>

<script>

    //set your publishable key
    var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

    window.Payments || ( window.Payments = {} );

    Payments.new = function (package_id) {

        $.ajax({
            type: 'POST',
            url: '/account/settings/balance',
            data: "packageId=" + package_id,
            dataType: 'json',
            timeout: 30000,
            success: function(response){

                if (response.hasOwnProperty('error')) {

                    if (response.error === false) {

                        if (response.hasOwnProperty('sessId')) {

                            stripe.redirectToCheckout({
                                // Make the id field from the Checkout Session creation API response
                                // available to this file, so you can provide it as parameter here
                                // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                                sessionId: response.sessId

                            }).then(function (result) {
                                // If `redirectToCheckout` fails due to a browser or network
                                // error, display the localized error message to your customer
                                // using `result.error.message`.
                            });
                        }
                    }
                }
            },
            error: function(xhr, type){


            }
        });
    };

    $('.btn-stripe').click(function() {

        Payments.new(1);
    });

</script>

</body>
</html>