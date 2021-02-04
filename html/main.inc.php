<?php
    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (auth::isSession()) {

        header("Location: /account/wall");
    }

    // Sign in
    $user_username = '';
    $error = false;
    $error_message = '';

    // Sign up
    $user_username_signup = '';
    $user_nim = '';
    $user_tgllahir = '';
    $user_email = '';
    $user_fullname = '';
    $user_referrer = 0;

    $error_signup = false;
    $error_signup_message = array();

    if (!empty($_POST['signin'])) {

        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_username = helper::clearText($user_username);
        $user_password = helper::clearText($user_password);

        $user_username = helper::escapeText($user_username);
        $user_password = helper::escapeText($user_password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            $access_data = array();

            $account = new account($dbo);

            $access_data = $account->signin($user_username, $user_password);

            unset($account);

            if (!$access_data['error']) {

                $account = new account($dbo, $access_data['accountId']);
                $accountInfo = $account->get();

                //print_r($accountInfo);

                switch ($accountInfo['state']) {

                    case ACCOUNT_STATE_BLOCKED: {

                        break;
                    }

                    default: {

                        $account->setState(ACCOUNT_STATE_ENABLED);

                        $clientId = 0; // Desktop version

                        $auth = new auth($dbo);
                        $access_data = $auth->create($accountInfo['id'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);

                        if (!$access_data['error']) {

                            auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                            auth::updateCookie($user_username, $access_data['accessToken']);

                            unset($_SESSION['oauth']);
                            unset($_SESSION['oauth_id']);
                            unset($_SESSION['oauth_name']);
                            unset($_SESSION['oauth_email']);
                            unset($_SESSION['oauth_link']);

                            $account->setLastActive();

                            header("Location: /");
                        }
                    }
                }

            } else {

                $error = true;
            }
        }
    }

    if (!empty($_POST['signup'])) {
        $error_signup = false;
        // Sign up
        $user_username_signup = isset($_POST['username']) ? $_POST['username'] : '';
        $user_nim = isset($_POST['nim']) ? $_POST['nim'] : '';
        $user_tgllahir_raw = isset($_POST['tgllahir']) ? $_POST['tgllahir'] : '';
        $user_fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $user_password_signup = isset($_POST['password']) ? $_POST['password'] : '';
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $user_referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;
        $token_signup = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_referrer = helper::clearInt($user_referrer);

        // Format date
        $user_tgllahir = date("d-m-Y", strtotime($user_tgllahir_raw));  

        $user_username_signup = helper::clearText($user_username_signup);
        $user_nim = helper::clearText($user_nim);
        $user_tgllahir = helper::clearText($user_tgllahir);
        $user_fullname = helper::clearText($user_fullname);
        $user_password_signup = helper::clearText($user_password_signup);
        $user_email = helper::clearText($user_email);

        $user_username_signup = helper::escapeText($user_username_signup);
        $user_nim = helper::escapeText($user_nim);
        $user_tgllahir = helper::escapeText($user_tgllahir);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password_signup = helper::escapeText($user_password_signup);
        $user_email = helper::escapeText($user_email);      

        if (auth::getAuthenticityToken() !== $token_signup) {
            $error_signup = true;
            $error_signup_token = true;
            $error_signup_message[] = $LANG['msg-error-unknown'];
        }

        if (!helper::isCorrectLogin($user_username_signup)) {
            $error_signup = true;
            $error_signup_username = true;
            $error_signup_message[] = $LANG['msg-login-incorrect'];
        }

        if (!helper::isCorrectNim($user_nim, $user_tgllahir)) {
            $error_signup = true;
            $error_signup_nim = true;
            $error_signup_message[] = $LANG['msg-nim-incorrect'];
        }

        if ($helper->isLoginExists($user_username_signup)) {
            $error_signup = true;
            $error_signup_username = true;
            $error_signup_message[] = $LANG['msg-login-taken'];
        }

        if (!helper::isCorrectFullname($user_fullname)) {
            $error_signup = true;
            $error_signup_fullname = true;
            $error_signup_message[] = $LANG['msg-fullname-incorrect'];
        }

        if (!helper::isCorrectPassword($user_password_signup)) {
            $error_signup = true;
            $error_signup_password = true;
            $error_signup_message[] = $LANG['msg-password-incorrect'];
        }

        if (!helper::isCorrectEmail($user_email)) {
            $error_signup = true;
            $error_signup_email = true;
            $error_signup_message[] = $LANG['msg-email-incorrect'];
        }

        if ($helper->isEmailExists($user_email)) {
            $error_signup = true;
            $error_signup_email = true;
            $error_signup_message[] = $LANG['msg-email-taken'];
        }

        if (!$error_signup) {
            $account = new account($dbo);

            $result = array();
            $result = $account->signup($user_username_signup, $user_nim, $user_tgllahir, $user_fullname, $user_password_signup, $user_email, $LANG['lang-code']);

            if ($result['error'] === false) {

                $clientId = 0; // Desktop version

                $auth = new auth($dbo);
                $access_data = $auth->create($result['accountId'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);

                $account = new account($dbo, $access_data['accountId']);
                $accountInfo = $account->get();

                if ($access_data['error'] === false) {

                    auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                    auth::updateCookie($user_username_signup, $access_data['accessToken']);

                    $language = $account->getLanguage();

                    $account->setState(ACCOUNT_STATE_ENABLED);

                    $account->setLastActive();

                    // refsys

                    if ($user_referrer != 0) {

                        $ref = new refsys($dbo);
                        $ref->setRequestFrom($account->getId());
                        $ref->setBonus(BONUS_REFERRAL);
                        $ref->setReferrer($user_referrer);

                        unset($ref);
                    }

                    //Facebook connect

                    if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook' && $helper->getUserIdByFacebook($_SESSION['oauth_id']) == 0) {

                        $account->setFacebookId($_SESSION['oauth_id']);

                        $time = time();
                        $fb_id = $_SESSION['oauth_id'];

                        $img = @file_get_contents('https://graph.facebook.com/'.$fb_id.'/picture?type=large');
                        $file =  TEMP_PATH.$time.".jpg";
                        @file_put_contents($file, $img);

                        $imglib = new imglib($dbo);
                        $response = $imglib->createPhoto($file, $file);
                        unset($imglib);

                        if ($response['error'] === false) {

                            $account->setPhoto($response);
                        }

                        unset($_SESSION['oauth']);
                        unset($_SESSION['oauth_id']);
                        unset($_SESSION['oauth_name']);
                        unset($_SESSION['oauth_email']);
                        unset($_SESSION['oauth_link']);

                    } else {

                        $account->setFacebookId("");
                    }

                    $_SESSION['welcome_hash'] = helper::generateHash(5);

                    header("Location: /account/welcome");
                    exit;
                }

            } else {

                $error_signup = true;
                $error_signup_message[] = "You can not create multi-accounts!";
            }
        }
    }


    auth::newAuthenticityToken();

    $page_id = "main";

    $css_files = array("landing.css", "my.css");
    $page_title = APP_TITLE;
?>
<!DOCTYPE html>
<html lang="<?php echo $LANG['lang-code']; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $page_title; ?></title>
    <meta name="google-site-verification" content="" />
    <meta name='yandex-verification' content='' />
    <meta name="msvalidate.01" content="" />
    <meta property="og:site_name" content="<?php echo APP_TITLE; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="utf-8">
    <meta name="description" content="">
    <link href="/img/favicon.png" rel="shortcut icon" type="image/x-icon">
    <link rel="manifest" href="/js/manifest.json">
    <link rel="stylesheet" href="/css/all.min.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/normalize.css">
</head>
<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">

                    <form accept-charset="UTF-8" action="/" class="sign-in-form" id="login-form" method="post">
                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">
                        <h2 class="title"><?php echo $LANG['page-login']; ?></h2>
                        <?php
                        if (FACEBOOK_AUTHORIZATION) {
                            ?>
                            <p>
                                <a class="fb-icon-btn fb-btn-large btn-facebook" href="/facebook/login">
                                    <span class="icon-container">
                                        <i class="icon icon-facebook"></i>
                                    </span>
                                    <span><?php echo $LANG['action-login-with']." ".$LANG['label-facebook']; ?></span>
                                </a>
                            </p>
                            <?php
                        }
                        ?>
                        <div class="errors-container" style="<?php if (!$error) echo "display: none"; ?>">
                            <p class="title"><?php echo $LANG['label-errors-title']; ?></p>
                            <ul>
                                <li><?php echo $LANG['msg-error-authorize']; ?></li>
                            </ul>
                        </div>
                        <div class="input-field">
                          <i class="fas fa-user"></i>
                          <input id="username" name="user_username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>">
                        </div>

                        <div class="input-field">
                          <i class="fas fa-lock"></i>
                          <input id="password" name="user_password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">
                        </div>

                            <input class="btn solid" name="signin" type="submit" value="<?php echo $LANG['action-login']; ?>">
                            <p>
                              <a href="/remind" class="help"><?php echo $LANG['action-forgot-password']; ?></a>
                            </p>
                    </form>

                    <form accept-charset="UTF-8" action="/signup" class="sign-up-form" id="signup-form" method="post">
                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                        <?php
                        if (isset($_SESSION['oauth'])) {
                            ?>

                            <?php

                        } else {
                            if (FACEBOOK_AUTHORIZATION) {
                                ?>
                                <p>
                                    <a class="fb-icon-btn fb-btn-large btn-facebook" href="/facebook/signup">
                                        <span class="icon-container">
                                            <i class="icon icon-facebook"></i>
                                        </span>
                                        <span><?php echo $LANG['action-signup-with'] . " " . $LANG['label-facebook']; ?></span>
                                    </a>
                                </p>
                                <?php
                            }
                        }
                        ?>

                        <div class="errors-container" style="<?php if (!$error_signup) echo "display: none"; ?>">
                            <p class="title"><?php echo $LANG['label-errors-title']; ?></p>
                            <ul>
                                <?php
                                foreach ($error_signup_message as $key => $value) {
                                    echo "<li>{$value}</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="input-field">
                        <i class="fas fa-at"></i>
                        <input id="username" name="username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username_signup; ?>">
                        </div>
                        
                        <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-fullname']; ?>" required="required" size="30" type="text" value="<?php echo $user_fullname; ?>">
                        </div>
                        
                        <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input id="password" name="password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">
                        </div>
                        
                        <div class="input-field">
                        <i class="fas fa-envelope-open-text"></i>
                        <input id="email" name="email" placeholder="<?php echo $LANG['label-email']; ?>" required="required" size="48" type="text" value="<?php echo $user_email; ?>">
                        </div>

                        <div class="input-field">
                        <i class="fas fa-book-user"></i>
                        <input id="nim" name="nim" placeholder="<?php echo $LANG['label-nim']; ?>" required="required" size="30" type="text" value="<?php echo $user_nim; ?>">
                        </div>

                        <div class="opt-in">
                            <label for="user_receive_digest">
                                <?php echo $TEXT['label-tgllahir-problem']; ?>
                            </label>
                        </div>

                        <div class="input-field">
                        <i class="fas fa-calendar-week"></i>
                        <input id="tgllahir" name="tgllahir" placeholder="<?php echo $LANG['label-tgllahir']; ?>" required="required" size="30" type="text" value="<?php echo $user_tgllahir; ?>">
                        </div>

                        <div class="opt-in">
                            <label for="user_receive_digest">
                                <?php echo $LANG['label-signup-invite']; ?>
                            </label>
                        </div>

                        <div class="input-field">
                        <i class="fas fa-sparkles"></i>
                        <input id="referrer" name="referrer" placeholder="<?php echo $LANG['label-user-id']; ?>" size="8" type="number" value="<?php echo $user_referrer; ?>">
                        </div>

                        <!--<div class="opt-in">
                            <label for="user_receive_digest">
                                <b><?php echo $LANG['label-signup-confirm']; ?></b>
                                <a style="font-size: 0.8rem;" href="/terms"><?php echo $LANG['page-terms']; ?></a>
                            </label>
                        </div>-->

                        <input class="btn solid" name="signup" type="submit" value="<?php echo $LANG['action-signup']; ?>">
                    </form>
      </div>
    </div>

    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3><?php echo $LANG['dont-have-account']; ?></h3>
          <p>
            <?php echo $LANG['label-signup-sub-title'] ?>
          </p>
          <button class="btn transparent" id="sign-up-btn">
            <?php echo $LANG['topbar-signup'] ?>
          </button>
          <?php
            if (WEB_EXPLORE) {
                ?>
                <button class="btn explore">
                  <a href="/explore"><?php echo $LANG['action-explore']; ?>  <i class="fa fa-arrow-right ml-1"></i></a>
                </button>
                <?php
            }
            ?>
        </div>
        <img src="/img/flatboy.png" class="image" alt="" />
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3><?php echo $LANG['already-have-account'] ?></h3>
          <p>
          <?php echo $LANG['login-now'] ?>
          </p>
          <button class="btn transparent" id="sign-in-btn">
            <?php echo $LANG['topbar-signin'] ?>
          </button>
        </div>
        <img src="/img/flatgirl.png" class="image" alt="" />
      </div>
    </div>
  </div>
  </div>
<!-- partial -->
  <script  src="/js/login.js"></script>
  <script src="/js/app.js"></script>
</body>
</html>