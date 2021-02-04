<?php
    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (auth::isSession()) {

        header("Location: /account/wall");
    }

    $user_username = '';

    $error = false;
    $error_message = '';

    if (!empty($_POST)) {

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

    auth::newAuthenticityToken();

    $page_id = "main";

    $css_files = array("landing.css", "my.css");
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");

?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <script type="text/javascript" src="/js/buttonbubble1.js"></script> -->


<style>
a:visited{
    color: #ffffff;
}

.confetti-button {
  font-family: 'Helvetica', 'Arial', sans-serif;
  display: inline-block;
  font-size: 1em;
  padding: 1em 2em;
  margin-top: 20px;
  margin-bottom: 20px;
  background-color: #ff0081;
  color: #fff;
  border-radius: 4px;
  border: none;
  cursor: pointer;
  position: relative;
  transition: transform ease-in 0.1s, box-shadow ease-in 0.25s;
  box-shadow: 0 2px 25px rgba(255, 0, 130, 0.5);
}

.confetti-button:focus { 
    color: #fff;
    outline: 0;
}

.confetti-button:before, .confetti-button:after {
  color: #fff;
  position: absolute;
  content: '';
  display: block;
  width: 110%;
  height: 100%;
  left: -20%;
  z-index: -1000;
  transition: all ease-in-out 0.5s;
  background-repeat: no-repeat;
}

.confetti-button:before {
    color: #fff;
  display: none;
  top: -100%;
  background-image: radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, transparent 20%, #ffffff 20%, transparent 30%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, transparent 10%, #ffffff 15%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%);
  background-size: 10% 10%, 20% 20%, 15% 15%, 20% 20%, 18% 18%, 10% 10%, 15% 15%, 10% 10%, 18% 18%;
}

.confetti-button:after {
    color: #fff;
  display: none;
  bottom: -100%;
  background-image: radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, transparent 10%, #ffffff 15%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%), radial-gradient(circle, #ffffff 20%, transparent 20%);
  background-size: 15% 15%, 20% 20%, 18% 18%, 20% 20%, 15% 15%, 10% 10%, 20% 20%;
}

.confetti-button:hover {
  transform: scale(0.9);
  background-color: #e60074;
  color: #fff;
  box-shadow: 0 2px 25px rgba(255, 0, 130, 0.2);
}

.confetti-button.animate:before {
  display: block;
  animation: topBubbles ease-in-out 0.75s forwards;
}

.confetti-button.animate:after {
  display: block;
  animation: bottomBubbles ease-in-out 0.75s forwards;
}
 @keyframes
topBubbles {  0% {
 background-position: 5% 90%, 10% 90%, 10% 90%, 15% 90%, 25% 90%, 25% 90%, 40% 90%, 55% 90%, 70% 90%;
}
 50% {
 background-position: 0% 80%, 0% 20%, 10% 40%, 20% 0%, 30% 30%, 22% 50%, 50% 50%, 65% 20%, 90% 30%;
}
 100% {
 background-position: 0% 70%, 0% 10%, 10% 30%, 20% -10%, 30% 20%, 22% 40%, 50% 40%, 65% 10%, 90% 20%;
 background-size: 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%;
}
}
@keyframes
bottomBubbles {  0% {
 background-position: 10% -10%, 30% 10%, 55% -10%, 70% -10%, 85% -10%, 70% -10%, 70% 0%;
}
 50% {
 background-position: 0% 80%, 20% 80%, 45% 60%, 60% 100%, 75% 70%, 95% 60%, 105% 0%;
}
 100% {
 background-position: 0% 90%, 20% 90%, 45% 70%, 60% 110%, 75% 80%, 95% 70%, 110% 10%;
 background-size: 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%, 0% 0%;
}
}
</style>

<body class="home has-bottom-footer main-page">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>

    <div class="content-page">
        <div class="limiter">

            <?php

            if (WEB_EXPLORE) {

                ?>
                <div class="wrap-landing-info-container explore-promo">

                    <div class="wrap-landing-info">
                        <?php echo sprintf($LANG['main-page-promo-explore'], APP_TITLE); ?>
                            <a href="/explore" class="confetti-button"><?php echo $LANG['action-explore']; ?>  <i class="fa fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="container-login100">
                <div class="wrap-login100">

                    <form accept-charset="UTF-8" action="/" class="custom-form login100-form" id="login-form" method="post">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                        <span class="login100-form-title "><?php echo $LANG['page-login']; ?></span>

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

                        <input id="username" name="user_username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>">
                        <input id="password" name="user_password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">
                        <!-- <input type="checkbox" onclick="myFunction()">Show Password -->

                        <div class="login-button">
                            <input style="margin-right: 10px" class="submit-button button blue" name="commit" type="submit" value="<?php echo $LANG['action-login']; ?>">
                            <a href="/remind" class="help"><?php echo $LANG['action-forgot-password']; ?></a>
                        </div>
                    </form>

                    <div class="login100-more">
                        <div class="login100_content">
                            <h1 class="mb-10">Welcome to</h1>
                            <h1><?php echo APP_NAME; ?></h1>
                            <p><?php echo sprintf($LANG['main-page-promo-login'], APP_TITLE); ?></p>
                        </div>
                    </div>

                </div>

            </div>

            <?php

                if (strlen(GOOGLE_PLAY_LINK) != 0) {

                    ?>
                        <div class="wrap-landing-info-container">

                            <div class="wrap-landing-info">
                                <?php echo sprintf($LANG['main-page-promo-google-app'], APP_TITLE, APP_TITLE); ?>
                                <a href="<?php echo GOOGLE_PLAY_LINK; ?>" target="_blank" rel="nofollow">
                                    <img class="mt-4" width="170" src="/img/google_play.png">
                                </a>
                            </div>
                        </div>
                    <?php
                }
            ?>

            <?php

                include_once("../html/common/footer.inc.php");
            ?>

        </div>
    </div>


<script>
var animateButton = function(e) {

  e.preventDefault;
  //reset animation
  e.target.classList.remove('animate');

  e.target.classList.add('animate');
  setTimeout(function(){
    e.target.classList.remove('animate');
  },700);
};

var classname = document.getElementsByClassName("confetti-button");

for (var i = 0; i < classname.length; i++) {
  classname[i].addEventListener('click', animateButton, false);
}
</script>
<script type="text/javascript">
function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
</body>
</html>