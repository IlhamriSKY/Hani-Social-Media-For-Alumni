<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

$accountId = auth::getCurrentUserId();
$accessToken = auth::getAccessToken();

if (!$auth->authorize($accountId, $accessToken)) {

    exit;
}

$profile = new profile($dbo, $accountId);
$profileInfo = $profile->get();

$act = '';
$msg = '';

if (isset($_GET['action'])) {

    $act = isset($_GET['action']) ? $_GET['action'] : '';
    $postId = isset($_GET['postId']) ? $_GET['postId'] : 0;

    $postId = helper::clearInt($postId);

    $act = helper::clearText($act);

    ?>

        <div class="box-body">
            <div style="padding-left: 0px; text-align: left" class="prompt_header"><?php echo $LANG['label-share-on-wall-desc']; ?></div>
            <form onsubmit="Post.rePost('<?php echo $profileInfo['username']; ?>'); return false;" class="repost_form" action="/<?php echo $profileInfo['username']; ?>/post" method="post">
                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">
                <input autocomplete="off" type="hidden" name="postImg" value="">
                <input autocomplete="off" type="hidden" name="rePostId" value="<?php echo $postId; ?>">
                <textarea style="width: 420px;resize: none;height: 100px;margin-bottom: 10px;" name="postText" maxlength="400" placeholder="<?php echo $LANG['label-share-add-comment']; ?>"></textarea>
                <div class="choice" style="height: 45px;">
                    <button class="primary_btn" value="repost"><?php echo $LANG['action-share']; ?></button>
                </div>
            </form>
        </div>

    <?php
}
