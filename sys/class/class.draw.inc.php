<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class draw extends db_connect
{
    private $requestFrom = 0;

    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);
    }

    static function comment($comment, $LANG = array()) {

        $comment['comment'] = helper::processCommentText($comment['comment']);

        $fromUserPhoto = "/img/profile_default_photo.png";

        if (strlen($comment['owner']['lowPhotoUrl']) != 0) {

            $fromUserPhoto = $comment['owner']['lowPhotoUrl'];
        }

        ?>

        <div class="media media-comment" data-id="<?php echo $comment['id']; ?>">
            <a href="/<?php echo $comment['owner']['username']; ?>" class="avatar-container">
                <div alt="<?php echo $comment['owner']['fullname']; ?>" title="<?php echo $comment['owner']['fullname']; ?>" class="rounded-circle shadow mr-4 avatar" style="background-image:url(<?php echo $fromUserPhoto; ?>)"></div>
                <?php if ($comment['owner']['online']) echo "<span title=\"Online\" class=\"online\"></span>"; ?>
            </a>
            <div class="media-body">
                <div class="comment-bubble left-top">
                    <h6 class="mt-0"><a href="/<?php echo $comment['owner']['username']; ?>"><?php echo $comment['owner']['fullname']; ?></a></h6>

                    <?php

                        if ($comment['owner']['verified'] == 1) {

                            ?>
                                <span class="user-badge user-verified-badge ml-1 p-0" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                            <?php
                        }
                    ?>

                    <span class="media-time p-0"><?php echo $comment['timeAgo']; ?></span>
                    <p class="media-content"><?php echo $comment['comment']; ?></p>
                    <div class="icon-actions">

                        <?php

                        if (auth::getCurrentUserId() != 0) {

                            if ($comment['itemFromUserId'] == auth::getCurrentUserId() || auth::getCurrentUserId() == $comment['fromUserId']) {

                                ?>

                                <a href="javascript:void(0)" onclick="Comments.remove('<?php echo $comment['id']; ?>', '<?php echo $comment['itemType']; ?>'); return false;" class="">
                                    <i class="iconfont icofont-ui-delete"></i>
                                    <span class="text-muted"> <?php echo $LANG['action-remove']; ?></span>
                                </a>

                                <?php
                            }
                        }

                        if ((auth::getCurrentUserId() != 0) && ($comment['fromUserId'] != auth::getCurrentUserId()) && ($comment['owner']['allowComments'] != 0) ) {

                            ?>

                            <a href="javascript:void(0)" onclick="Comments.reply('<?php echo $comment['fromUserId']; ?>', '<?php echo $comment['owner']['username']; ?>', '<?php echo $comment['owner']['fullname']; ?>'); return false;" class="">
                                <i class="iconfont icofont-ui-reply"></i>
                                <span class="text-muted"> <?php echo $LANG['action-reply']; ?></span>
                            </a>

                            <?php
                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    static function galleryItem($photo, $profileInfo, $LANG, $helper, $preview = false)
    {

        ?>

        <div class="gallery-item" data-id="<?php echo $photo['id']; ?>">

            <div class="item-inner">

                <a href="/<?php echo $profileInfo['username']; ?>/image/<?php echo $photo['id']; ?>">

                    <?php

                        $previewImg = $photo['previewImgUrl'];

                        if (strlen($photo['previewVideoImgUrl']) != 0) {

                            $previewImg = $photo['previewVideoImgUrl'];
                        }
                    ?>
                    <div class="gallery-item-preview" style="background-image:url(<?php echo $previewImg; ?>)">

                        <?php

                            if (strlen($photo['videoUrl']) != 0) {

                                ?>
                                    <span class="video-play"></span>
                                <?php
                            }
                        ?>

                        <?php

                            if (!$preview) {

                                if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $photo['fromUserId']) {

                                    ?>

                                    <span title="<?php echo $LANG['action-remove']; ?>" class="remove" onclick="Gallery.remove('<?php echo $photo['id']; ?>'); return false;">×</span>

                                    <?php

                                }

                                ?>
                                <span class="info-badge black"><?php echo $photo['timeAgo']; ?></span>
                                <?php
                            }

                        ?>

                    </div>
                </a>

            </div>
        </div>

        <?php
    }

    static function post($post, $LANG, $helper = null, $showComments = false)
    {
        $time = new language(NULL, $LANG['lang-code']);

        $post['post'] = helper::processPostText($post['post']);

        $fromUserPhoto = "/img/profile_default_photo.png";

        if (strlen($post['fromUserPhoto']) != 0) {

            $fromUserPhoto = $post['fromUserPhoto'];
        }

        if ($post['groupId'] != 0) {

            $group = new group(null, $post['groupId']);
            $group->setRequestFrom(auth::getCurrentUserId());

            $groupInfo = $group->get();

            if ($groupInfo['accountAuthor'] == $post['fromUserId']) {

                if (strlen($groupInfo['lowPhotoUrl'])) {

                    $fromUserPhoto = $groupInfo['lowPhotoUrl'];
                }
            }
        }

        ?>

        <div class="card " data-id="<?php echo $post['id']; ?>">

        <li class="custom-list-item post-item" data-id="<?php echo $post['id']; ?>">

            <div class="mb-2 item-header">

                <a href="/<?php echo $post['fromUserUsername']; ?>" class="item-logo" style="background-image:url(<?php echo $fromUserPhoto; ?>)"></a>

                <div class="dropdown">
                    <a class="mb-sm-0 item-menu" data-toggle="dropdown">
                        <i class="fa fa-ellipsis-h"></i>
                    </a>

                    <div class="dropdown-menu">

                        <?php

                        if ((auth::isSession() && $post['fromUserId'] == auth::getCurrentUserId()) || (isset($groupInfo) && $groupInfo['accountAuthor'] == auth::getCurrentUserId())) {

                            ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="Post.remove('<?php echo $post['id']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo $LANG['action-remove']; ?></a>
                            <?php

                        } else {

                            ?>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="Report.showDialog('<?php echo $post['id']; ?>', '<?php echo REPORT_TYPE_ITEM; ?>'); return false;"><?php echo $LANG['action-report']; ?></a>
                            <?php
                        }

                        ?>

                    </div>
                </div>

                <?php

                if ($post['groupId'] != 0 && isset($groupInfo)) {

                    if ($groupInfo['id'] != $post['fromUserId']) {

                        if ($post['fromUserOnline']) echo "<span title=\"Online\" class=\"item-logo-online\"></span>";
                    }

                } else {

                    if ($post['fromUserOnline']) echo "<span title=\"Online\" class=\"item-logo-online\"></span>";
                }

                ?>

                <a href="/<?php echo $post['fromUserUsername']; ?>" class="custom-item-link post-item-fullname"><?php echo $post['fromUserFullname']; ?></a>

                <!-- Hani -->
                <!-- Bedge -->
                <?php
                    if ($post['fromUserVerify'] == 1) {
                        ?>
                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                        <?php
                    }
                    ?>

                    <?php if ($post['fromUserStaff'] == 1){
                        if ($post['fromGender'] == 1){
                            ?>
                            <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                        <?php
                        }
                        elseif ($post['fromGender'] == 2){
                        ?>
                            <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businesswoman"></i></span>
                        <?php
                        }
                        else{
                        ?>
                            <span class="user-badge user-staff-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-staff']; ?>"><i class="iconfont icofont-businessman"></i></span>
                        <?php
                        }
                    }
                    ?>

                    <?php
                    if ($post['fromUserBot'] == 1) {
                        ?>
                        <span class="user-badge user-bot-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-bot']; ?>"><i class="iconfont icofont-robot"></i></span>
                        <?php
                    }

                ?>

                <?php

                if ( $post['feeling'] != 0) {

                    ?>
                    <span><?php echo $LANG['label-is-feeling']; ?></span> <b title="is feeling" class="feeling" style="background-image: url('/feelings/<?php echo $post['feeling']; ?>.png')"></b>
                    <?php
                }
                ?>

                <span class="post-item-time">
                <a href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $post['id']; ?>"><?php echo $time->timeAgo($post['createAt']); ?></a>
                <?php if ( $post['deviceType'] == 1) echo "<b title=\"{$LANG['hint-item-android-version']}\" class=\"android-version-icon ml-1\"></b>"; ?>
                    <?php if ( $post['deviceType'] == 2) echo "<b title=\"{$LANG['hint-item-ios-version']}\" class=\"ios-version-icon ml-1\"></b>"; ?>
            </span>

            </div>

            <div class="item-meta post-item-content">

                <?php

                    if ($post['postType'] == 0) {

                        ?>
                            <div class="post-text mx-2"><?php echo $post['post']; ?></div>
                        <?php

                    } else if ($post['postType'] == 1) {

                        ?>
                            <p class="post-text"><b><?php echo $post['fromUserFullname']." - ".$LANG['label-updated-profile-photo']; ?></b></p>
                        <?php

                    } else {

                        ?>
                            <p class="post-text"><b><?php echo $post['fromUserFullname']." - ".$LANG['label-updated-cover-photo']; ?><b></p>
                        <?php

                    }
                ?>

                <?php

                    if (strlen($post['imgUrl'])) {

                        ?>
                            <img class="post-img" data-id="<?php echo $post['id']; ?>" data-href="<?php echo $post['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $post['imgUrl']; ?>">
                        <?php
                    }

                    if ($post['imagesCount'] != 0) {

                        $height = 150;

                        if ($post['imagesCount'] > 3) {

                            $height = 100;
                        }

                        if ($post['imagesCount'] > 5) {

                            $height = 80;
                        }

                        ?>
                        <div class="d-flex">
                        <?php

                        $postimg = new postimg(null);
                        $postimg->setRequestFrom($post['fromUserId']);

                        $result = $postimg->get($post['id']);

                        foreach ($result['items'] as $key => $value) {

                            ?>


                                <div class="gallery-item" style="height: <?php echo $height; ?>px; padding: 5px">

                                    <div class="item-inner">

                                        <div data-href="<?php echo $value['originImgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" class="gallery-item-preview" style="background-image:url(<?php echo $value['previewImgUrl'] ?>)"></div>

                                    </div>
                                </div>

                            <?php
                        }

                        ?>

                        </div>
                        <?php
                    }
                ?>

                <?php

                    if (strlen($post['videoUrl']) > 0) {

                        ?>

                        <video width = "100%" height = "auto" style="max-height: 300px" controls>
                            <source src="<?php echo $post['videoUrl']; ?>" type="video/mp4">
                        </video>

                        <?php
                    }
                ?>

                <?php

                    if (strlen($post['urlPreviewLink']) > 0) {

                        if (strlen($post['urlPreviewImage']) == 0) $post['urlPreviewImage'] = "/img/img_link.png";
                        if (strlen($post['urlPreviewTitle']) == 0) $post['urlPreviewTitle'] = "Link";

                        ?>

                        <ul class="items-list link-preview-list">
                            <li class="custom-list-item link-preview-item" data-id="<?php echo $post['id']; ?>">
                                <a href="<?php echo $post['urlPreviewLink']; ?>">
                                    <span class="link-img" style="background-image:url(<?php echo $post['urlPreviewImage']; ?>)"></span>
                                    <span class="link-title"><?php echo $post['urlPreviewTitle']; ?></span>

                                    <div class="item-meta">
                                        <span class="link-description"><?php echo $post['urlPreviewDescription']; ?></span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <?php
                    }
                ?>

                <?php

                    $rePost = $post['rePost'];
                    $rePost = $rePost[0];

                    if ($post['rePostId'] != 0 && $rePost['error'] === false) {

                        if ($rePost['removeAt'] != 0) {

                            ?>

                            <div class="post post_item" data-id="<?php echo $rePost['id']; ?>" style="width: 100%;display: inline-block; border-left: 1px solid #DAE1E8; border-bottom: 0px; padding-left: 5px; margin-top: 10px; margin-bottom: 10px;">

                                <div class="post_content">
                                    <div class="post_data">
                                        <?php echo $LANG['label-repost-error']; ?>
                                    </div>
                                </div>
                            </div>

                            <?php

                        }  else {


                            $rePost['post'] = helper::processPostText($rePost['post']);

                            $rePostFromUserPhoto = "/img/profile_default_photo.png";

                            if (strlen($rePost['fromUserPhoto']) != 0) {

                                $rePostFromUserPhoto = $rePost['fromUserPhoto'];
                            }

                            ?>

                                <ul class="items-list repost-list">

                                    <li class="custom-list-item repost-item post-item" data-id="<?php echo $rePost['id']; ?>">

                                        <div class="mb-2 item-header">

                                            <a href="/<?php echo $rePost['fromUserUsername']; ?>/post/<?php echo $rePost['id'] ?>" class="item-logo" style="background-image:url(<?php echo $rePostFromUserPhoto; ?>)"></a>

                                            <a href="/<?php echo $rePost['fromUserUsername']; ?>/post/<?php echo $rePost['id'] ?>" class="custom-item-link post-item-fullname"><?php echo $rePost['fromUserFullname']; ?></a>

                                            <span class="post-item-time"><?php echo $time->timeAgo($rePost['createAt']); ?></span>

                                        </div>

                                        <div class="item-meta post-item-content">

                                            <?php

                                                if ($rePost['postType'] == 0) {

                                                    ?>
                                                        <p class="post-text mx-2"><?php echo $rePost['post']; ?></p>
                                                    <?php

                                                } else if ($rePost['postType'] == 1) {

                                                    ?>
                                                        <p class="post-text"><b><?php echo $rePost['fromUserFullname']." - ".$LANG['label-updated-profile-photo']; ?></b></p>
                                                    <?php

                                                } else {

                                                    ?>
                                                        <p class="post-text"><b><?php echo $rePost['fromUserFullname']." - ".$LANG['label-updated-cover-photo']; ?><b></p>
                                                    <?php

                                                }
                                            ?>

                                            <?php

                                                if (strlen($rePost['imgUrl'])) {

                                                    ?>


                                                    <img class="post-img" data-id="<?php echo $rePost['id']; ?>" data-href="<?php echo $rePost['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" src="<?php echo $rePost['imgUrl']; ?>">
                                                    <?php
                                                }
                                            ?>

                                        </div>
                                    </li>

                                </ul>

                            <?php
                        }
                    }

                ?>

                <div class="item-counters <?php if ($post['likesCount'] == 0 && $post['commentsCount'] == 0 && $post['rePostsCount'] == 0) echo 'gone' ?>" data-id="<?php echo $post['id']; ?>">
                    <a class="item-likes-count <?php if ($post['likesCount'] == 0) echo 'gone'; ?>" data-id="<?php echo $post['id']; ?>" href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $post['id']; ?>/people"><?php echo $LANG['label-likes']; ?>: <span class="likes-count" data-id="<?php echo $post['id']; ?>"><?php echo $post['likesCount']; ?></span></a>
                    <a class="item-comments-count <?php if ($post['commentsCount'] == 0) echo 'gone'; ?>" data-id="<?php echo $post['id']; ?>" href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $post['id']; ?>"><?php echo $LANG['label-comments']; ?>: <span class="comments-count" data-id="<?php echo $post['id']; ?>"><?php echo $post['commentsCount']; ?></span></a>
                    <span class="item-reposts-count <?php if ($post['rePostsCount'] == 0) echo 'gone'; ?>" data-id="<?php echo $post['id']; ?>"><?php echo $LANG['label-reposts']; ?>: <span class="reposts-count" data-id="<?php echo $post['id']; ?>"><?php echo $post['rePostsCount']; ?></span></span>
                </div>

                <div class="item-footer">
                    <div class="item-footer-container">
                        <span class="item-footer-button">
                            <a class="item-like-button item-footer-button <?php if ($post['myLike']) echo "active"; ?>" onclick="Item.like('<?php echo $post['id']; ?>', '<?php echo ITEM_TYPE_POST; ?>'); return false;" data-id="<?php echo $post['id']; ?>">
                                <i class="iconfont icofont-heart mr-1"></i>
                                <?php echo $LANG['action-like']; ?>
                            </a>
                        </span>

                        <?php

                            if (!$showComments) {

                                ?>
                                    <span class="item-footer-button">
                                        <a class="item-footer-button" href="/<?php echo $post['fromUserUsername']; ?>/post/<?php echo $post['id']; ?>">
                                            <i class="iconfont icofont-comment mr-1"></i>
                                            <?php echo $LANG['action-comment']; ?>
                                        </a>
                                    </span>
                                <?php
                            }
                        ?>

                        <?php

                        if (auth::isSession() && $post['fromUserId'] != auth::getCurrentUserId()) {

                            $re_post_id = $post['id'];

                            if ($post['rePostId'] != 0) {

                                $re_post_id = $post['rePostId'];
                            }

                            ?>

                            <span class="item-footer-button">
                                <a class="item-repost-button item-footer-button" onclick="Post.getRepostBox('<?php echo $post['fromUserUsername']; ?>', '<?php echo $re_post_id; ?>', '<?php echo $LANG['action-share-post']; ?>', '<?php echo $post['myRePost']; ?>'); return false;" data-id="<?php echo $post['id']; ?>">
                                    <i class="iconfont icofont-redo mr-1"></i>
                                    <?php echo $LANG['action-share']; ?>
                                </a>
                            </span>

                            <?php
                        }
                        ?>
                    </div>
                </div>

                <?php

                if ($showComments) {

                    ?>

                    <div class="items-list comments-list" data-id="<?php echo $post['id']; ?>">

                        <?php

                        $comments = new comments();
                        $comments->setLanguage($LANG['lang-code']);
                        $comments->setRequestFrom(auth::getCurrentUserId());

                        $data = $comments->getPreview($post['id']);

                        $commentsCount = $data['count'];

                        if ($commentsCount > 3) {

                            ?>
                            <a data-id="<?php echo $post['id']; ?>" onclick="Comments.more('<?php echo $post['id']; ?>', '2', '<?php echo $data['commentId']; ?>'); return false;" class="get_comments_header comment-loader">
                                <?php echo $LANG['action-show-all']; ?> (<?php echo $commentsCount - 3; ?>)
                            </a>
                            <?php
                        }

                        $data['comments'] = array_reverse($data['comments'], false);

                        foreach ($data['comments'] as $key => $value) {

                            draw::comment($value, $LANG);
                        }

                        ?>

                    </div>

                    <?php

                        if (auth::getCurrentUserId() != 0) {

                            if ($post['allowComments'] != 0) {

                                ?>

                                <div class="media media-comment mb-2">

                                    <a href="/<?php echo auth::getCurrentUserLogin(); ?>" class="avatar-container form-avatar-container">
                                        <div alt="<?php echo auth::getCurrentUserFullname(); ?>" title="<?php echo auth::getCurrentUserFullname(); ?>" class="rounded-circle shadow avatar" style="background-image:url(<?php echo auth::getCurrentUserPhotoUrl(); ?>)"></div>
                                        <span title="Online" class="online"></span>
                                    </a>

                                    <div class="media-body">
                                        <form class="rounded-lg border" onsubmit="Comments.create('<?php echo $post['id'] ?>', '<?php echo ITEM_TYPE_POST; ?>'); return false;">
                                            <input type="hidden" name="message_image" value="">
                                            <div class="input-group input-group-lg input-group-merge">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 px-2">
                                                        <i class="icofont icofont-ui-edit"></i>
                                                    </span>
                                                </div>
                                                <input data-id="<?php echo $post['id']; ?>" maxlength="140" type="text" name="comment_text" class="form-control border-0 px-1" placeholder="<?Php echo $LANG['label-placeholder-comment']; ?>">
                                                <div class="input-group-append">
                                                    <span class="input-group-text border-0 py-0 pl-2 pr-0">

                                                        <div class="dropdown emoji-dropdown dropup" style="display: block">

                                                            <span class="smile-button btn-emoji-picker flat_btn" data-toggle="dropdown">
                                                                <i class="btn-emoji-picker-icon iconfont icofont-slightly-smile"></i>
                                                            </span>

                                                            <div class="dropdown-menu dropdown-menu-right mt-2">
                                                                <?php include_once("../html/common/emojis.inc.php"); ?>
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-sm  blue comment_send"><?Php echo $LANG['action-send']; ?></button>
                                                    </span>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <?php

                            } else {

                                ?>

                                <header class="top-banner info-banner" style="border: 0">

                                    <div class="info">
                                        <p style="white-space: normal; border: 0; text-align: center;"><?php echo $LANG['label-comments-disallow']; ?></p>
                                    </div>

                                </header>

                                <?php
                            }

                        } else {

                            ?>

                            <header class="top-banner info-banner" style="border: 0">

                                <div class="info">
                                    <p style="white-space: normal; border: 0; text-align: center;"><?php echo $LANG['label-comments-prompt']; ?></p>
                                </div>

                            </header>

                            <?php
                        }
                        ?>

                    <?php
                }
                ?>

            </div>

        </li>

        </div>

        <?php
    }

    static function previewPeopleItem($profile, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        ?>

        <div class="col-6 grid-item preview-people-item" data-id="<?php echo $profile['id']; ?>>">
            <div class="card" data-id="14178">
                <a href="/<?php echo $profile['username']; ?>" class="photo">
                    <div class="card-img-top-wrapper d-flex justify-content-center">
                        <div class="loader"></div>
                        <div class="card-img-top" style="background-image: url(<?php echo $profilePhotoUrl; ?>);"></div>
                    </div>
                </a>
                <div class="card-body d-flex flex-column">

                    <div class="d-flex flex-row w-100">

                        <div class="w-100">

                            <h4 class="d-flex justify-content-start align-items-center">
                                <a href="/<?php echo $profile['username']; ?>" class="w-100 grid-title">
                                    <span class="user-badge user-verified-badge mr-1 <?php if (!$profile['verified']) echo 'hidden'; ?>" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                    <span class="display-name" title="<?php echo $profile['fullname']; ?>"><?php echo $profile['fullname']; ?></span>
                                </a>
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <?php
    }

    static function peopleItem($profile, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        ?>

        <div class="col-6 col-sm-6 col-md-4 col-lg-4 grid-item" data-id="<?php echo $profile['id']; ?>>">
            <div class="card" data-id="14178">
                <a href="/<?php echo $profile['username']; ?>" class="photo">
                    <div class="card-img-top-wrapper d-flex justify-content-center">
                        <div class="loader"></div>
                        <div class="card-img-top" style="background-image: url(<?php echo $profilePhotoUrl; ?>);"></div>
                    </div>

                    <?php

                        if ($profile['online']) {

                            ?>

                                <div style="" class="grid-badge bg-green">Online</div>

                            <?php

                        } else {

                            ?>

                                <div style="" title="<?php echo $LANG['label-last-seen']; ?>" class="grid-badge bg-gray"><?php echo $profile['lastAuthorizeTimeAgo']; ?></div>

                            <?php
                        }
                    ?>

                </a>
                <div class="card-body d-flex flex-column">

                    <div class="d-flex flex-row w-100">

                        <div class="w-100">

                            <h4 class="d-flex justify-content-start align-items-center">
                                <a href="/<?php echo $profile['username']; ?>" class="w-100 grid-title">
                                    <span class="user-badge user-verified-badge mr-1 <?php if (!$profile['verified']) echo 'hidden'; ?>" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                    <span class="display-name" title="<?php echo $profile['fullname']; ?>"><?php echo $profile['fullname']; ?></span>
                                </a>
                            </h4>

                        </div>

                    </div>

                    <?php

                        if (strlen($profile['location']) > 0) {

                            ?>
                                <div class="grid-location">
                                    <h5><i class="iconfont icofont-location-pin"></i> <?php echo $profile['location']; ?></h5>
                                </div>
                            <?php
                        }
                    ?>

                </div>
            </div>
        </div>

        <?php
    }

    static function guestItem($profile, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['guestUserPhoto']) != 0) {

            $profilePhotoUrl = $profile['guestUserPhoto'];
        }

        ?>

        <div class="col-6 col-sm-6 col-md-4 col-lg-4 grid-item" data-id="<?php echo $profile['guestUserId']; ?>">
            <div class="card" data-id="14178">
                <a href="/<?php echo $profile['guestUserUsername']; ?>" class="photo">
                    <div class="card-img-top-wrapper d-flex justify-content-center">
                        <div class="loader"></div>
                        <div class="card-img-top" style="background-image: url(<?php echo $profilePhotoUrl; ?>);"></div>
                    </div>

                    <div style="" title="<?php echo $LANG['label-last-visit']; ?>" class="grid-badge bg-gray"><?php echo $profile['timeAgo']; ?></div>

                </a>
                <div class="card-body d-flex flex-column">

                    <div class="d-flex flex-row w-100">

                        <div class="w-100">

                            <h4 class="d-flex justify-content-start align-items-center">
                                <a href="/<?php echo $profile['guestUserUsername']; ?>" class="w-100 grid-title">
                                    <span class="user-badge user-verified-badge mr-1 <?php if (!$profile['guestUserVerify']) echo 'hidden'; ?>" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                    <span class="display-name" title="<?php echo $profile['guestUserFullname']; ?>"><?php echo $profile['guestUserFullname']; ?></span>
                                </a>
                            </h4>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <?php
    }

    static function blackListItem($profile, $LANG, $helper = null)
    {
        ?>

        <li class="card-item classic-item" data-id="<?php echo $profile['blockedUserId']; ?>">
            <a href="/<?php echo $profile['blockedUserUsername']; ?>" class="card-body">
                <span class="card-header px-0 pt-0 border-0">
                    <img class="card-icon" src="<?php echo $profile['blockedUserPhotoUrl']; ?>"/>
                    <div class="card-content">
                        <span class="card-title"><?php echo $profile['blockedUserFullname']; ?>

                            <?php

                            if ($profile['blockedUserVerify']) {

                                ?>
                                    <b original-title="<?php echo $LANG['label-account-verified']; ?>" class="verified"></b>
                                <?php
                            }
                            ?>
                        </span>
                        <span class="card-username">@<?php echo $profile['blockedUserUsername']; ?></span>

                        <?php

                        if ($profile['blockedUserOnline']) {

                            ?>
                                <span class="card-date">Online</span>
                            <?php
                        }
                        ?>

                        <span class="card-action">
                            <span class="card-act negative" onclick="Profile.unBlock('<?php echo $profile['blockedUserId']; ?>'); return false;"><?php echo $LANG['action-unblock']; ?></span>
                        </span>

                        <span class="card-counter blue"><?php echo $profile['timeAgo']; ?></span>
                    </div>
                </span>
            </a>
        </li>

        <?php
    }

    static function outboxFriendRequestItem($profile, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="card-item classic-item default-item" data-id="<?php echo $profile['id']; ?>">
            <div class="card-body">
                <span class="card-header border-0 px-0 pt-0">
                    <a href="/<?php echo $profile['username']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                    <?php if ($profile['online']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                    <div class="card-content">
                        <span class="card-title">
                            <a href="/<?php echo $profile['username']; ?>"><?php echo  $profile['fullname']; ?></a>
                            <?php

                                if ($profile['verify'] == 1) {

                                    ?>
                                        <b original-title="<?php echo $LANG['label-account-verified']; ?>" class="verified"></b>
                                    <?php
                                }
                            ?>
                        </span>
                        <span class="card-username">@<?php echo  $profile['username']; ?></span>
                        <span class="card-counter black" title="<?php echo $LANG['label-create-at']; ?>"><?php echo $time->timeAgo($profile['create_at']);  ?></span>
                        <span class="card-action">
                            <a class="card-act negative" href="javascript:void(0)" onclick="Friends.cancelRequest('<?php echo $profile['id']; ?>'); return false;"><?php echo $LANG['action-cancel']; ?></a>
                        </span>
                    </div>
                </span>
            </div>
        </li>

        <?php
    }

    static function inboxFriendRequestItem($profile, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($profile['lowPhotoUrl']) != 0) {

            $profilePhotoUrl = $profile['lowPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="card-item classic-item default-item" data-id="<?php echo $profile['id']; ?>">
            <div class="card-body">
                <span class="card-header border-0 px-0 pt-0">
                    <a href="/<?php echo $profile['username']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                    <?php if ($profile['online']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                    <div class="card-content">
                        <span class="card-title">
                            <a href="/<?php echo $profile['username']; ?>"><?php echo  $profile['fullname']; ?></a>
                            <?php

                                if ($profile['verify'] == 1) {

                                    ?>
                                        <b original-title="<?php echo $LANG['label-account-verified']; ?>" class="verified"></b>
                                    <?php
                                }
                            ?>
                        </span>
                        <span class="card-username">@<?php echo  $profile['username']; ?></span>
                        <span class="card-counter black" title="<?php echo $LANG['label-create-at']; ?>"><?php echo $time->timeAgo($profile['create_at']);  ?></span>
                        <span class="card-action">
                            <a class="card-act negative" href="javascript:void(0)" onclick="Friends.rejectRequest('<?php echo $profile['id']; ?>', '<?php echo $profile['id']; ?>'); return false;"><?php echo $LANG['action-reject']; ?></a>
                            <a class="card-act active" href="javascript:void(0)" onclick="Friends.acceptRequest('<?php echo $profile['id']; ?>', '<?php echo $profile['id']; ?>'); return false;"><?php echo $LANG['action-accept']; ?></a>
                        </span>
                    </div>
                </span>
            </div>
        </li>

        <?php
    }

    static function communityItem($item, $LANG, $helper = null)
    {
        $itemPhotoUrl = "/img/profile_default_photo.png";

        if (strlen($item['lowPhotoUrl']) != 0) {

            $itemPhotoUrl = $item['lowPhotoUrl'];
        }

        ?>

        <div class="community-item" data-id="<?php echo $item['id']; ?>">

            <div class="item-inner">

                <a href="/<?php echo $item['username']; ?>">

                    <div class="community-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">

                        <?php

                        if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['accountAuthor']) {

                            ?>

                            <span onclick='window.location="/<?php echo $item['username']; ?>/settings"; return false;' title="<?php echo $LANG['action-remove']; ?>" class="settings"><i class="icofont icofont-gear-alt"></i></span>

                            <?php

                        }
                        ?>
                        <span class="info-badge black">
                            <span class="title"><?php echo $item['fullname']; ?></span>
                            <span class="sub-title"><?php echo $item['followersCount']." ".$LANG['label-community-followers']; ?></span>
                        </span>
                    </div>
                </a>

            </div>
        </div>

        <?php
    }

    static function communityItemPreview($item, $LANG, $helper = null)
    {
        $itemPhotoUrl = "/img/profile_default_photo.png";

        if (strlen($item['lowPhotoUrl']) != 0) {

            $itemPhotoUrl = $item['lowPhotoUrl'];
        }

        ?>

        <div class="col-12 grid-item community-item"" data-id="<?php echo $item['id']; ?>>">

            <div class="item-inner">

                <a href="/<?php echo $item['username']; ?>">

                    <div class="community-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">

                        <span class="info-badge black">
                            <span class="title"><?php echo $item['fullname']; ?></span>
                            <span class="sub-title"><?php echo $item['followersCount']." ".$LANG['label-community-followers']; ?></span>
                        </span>
                    </div>
                </a>

            </div>
        </div>

        <?php
    }

    static function messageItem($message, $LANG, $helper = null)
    {
        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($message['fromUserPhotoUrl']) != 0) {

            $profilePhotoUrl = $message['fromUserPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        $seen = false;

        if ($message['fromUserId'] == auth::getCurrentUserId() && $message['seenAt'] != 0 ) {

            $seen = true;
        }

        ?>

        <li class="card-item default-item message-item <?php if ($message['fromUserId'] == auth::getCurrentUserId()) echo "message-item-right"; ?>" data-id="<?php echo $message['id']; ?>">
            <div class="card-body">
                <span class="card-header">
                    <a href="/<?php echo $message['fromUserUsername']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>
                    <?php if ($message['fromUserOnline'] && $message['fromUserId'] != auth::getCurrentUserId()) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                    <div class="card-content">

                        <?php

                        if ($message['stickerId'] != 0) {

                            ?>
                                <img class="sticker-img" style="" alt="sticker-img" src="<?php echo $message['stickerImgUrl']; ?>">
                            <?php

                        } else {

                            ?>
                            <span class="card-status-text">

                                    <?php

                                    if (strlen($message['message']) > 0) {

                                        ?>
                                            <span class="card-status-text-message">
                                                <?php echo $message['message']; ?>
                                            </span>
                                        <?php
                                    }

                                    if (strlen($message['imgUrl']) > 0) {

                                        ?>
                                            <img class="post-img" data-href="<?php echo $message['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $message['imgUrl']; ?>">
                                        <?php
                                    }

                                    ?>

                                    </span>
                            <?php
                        }
                        ?>

                        <span class="card-date">
                            <?php echo $time->timeAgo($message['createAt']); ?>
                            <span class="time green" style="<?php if (!$seen) echo 'display: none'; ?>" data-my-id="<?php echo $LANG['label-seen']; ?>"><?php echo $LANG['label-seen']; ?></span>
                        </span>

                    </div>
                </span>
            </div>
        </li>

        <?php
    }

    static function image($post, $LANG, $helper = null, $showComments = false)
    {
        $post['comment'] = helper::processPostText($post['comment']);

        $fromUserPhoto = "/img/profile_default_photo.png";

        if (strlen($post['owner']['lowPhotoUrl']) != 0) {

            $fromUserPhoto = $post['owner']['lowPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <div class="card " data-id="<?php echo $post['id']; ?>">

        <li class="custom-list-item post-item" data-id="<?php echo $post['id']; ?>">

            <div class="mb-2 item-header">

                <a href="/<?php echo $post['owner']['username']; ?>" class="item-logo" style="background-image:url(<?php echo $fromUserPhoto; ?>)"></a>

                <div class="dropdown">
                    <a class="mb-sm-0 item-menu" data-toggle="dropdown">
                        <i class="iconfont icofont-curved-down"></i>
                    </a>

                    <div class="dropdown-menu">

                        <?php

                        if ((auth::isSession() && $post['fromUserId'] == auth::getCurrentUserId())) {

                            ?>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="Gallery.remove('<?php echo $post['id']; ?>'); return false;"><?php echo $LANG['action-remove']; ?></a>
                            <?php

                        } else {

                            ?>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="Report.showDialog('<?php echo $post['id']; ?>', '<?php echo ITEM_TYPE_GALLERY; ?>'); return false;"><?php echo $LANG['action-report']; ?></a>
                            <?php
                        }

                        ?>

                    </div>
                </div>

                <?php

                if ($post['owner']['online']) echo "<span title=\"Online\" class=\"item-logo-online\"></span>";

                ?>

                <a href="/<?php echo $post['owner']['username']; ?>" class="custom-item-link post-item-fullname"><?php echo $post['owner']['fullname']; ?></a>
                <?php

                if ($post['owner']['verified'] == 1) {

                    ?>
                    <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                    <?php


                }
                ?>

                <span class="post-item-time"><a href="/<?php echo $post['owner']['username']; ?>/image/<?php echo $post['id']; ?>"><?php echo $time->timeAgo($post['createAt']); ?></a></span>

            </div>

            <div class="item-meta post-item-content">

                <p class="post-text mx-2"><?php echo $post['comment']; ?></p>

                <?php

                if (strlen($post['imgUrl'])) {

                    ?>
                    <img class="post-img" data-href="<?php echo $post['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $post['imgUrl']; ?>">
                    <?php

                } else {

                    if (strlen($post['videoUrl']) > 0) {

                        ?>

                        <video width = "100%" height = "auto" style="max-height: 300px" controls>
                            <source src="<?php echo $post['videoUrl']; ?>" type="video/mp4">
                        </video>

                        <?php
                    }
                }
                ?>

                <div class="item-counters <?php if ($post['likesCount'] == 0 && $post['commentsCount'] == 0) echo 'gone' ?>" data-id="<?php echo $post['id']; ?>">
                    <a class="item-likes-count <?php if ($post['likesCount'] == 0) echo 'gone'; ?>" data-id="<?php echo $post['id']; ?>" href="/<?php echo $post['owner']['username']; ?>/image/<?php echo $post['id']; ?>/people"><?php echo $LANG['label-likes']; ?>: <span class="likes-count" data-id="<?php echo $post['id']; ?>"><?php echo $post['likesCount']; ?></span></a>
                    <a class="item-comments-count <?php if ($post['commentsCount'] == 0) echo 'gone'; ?>" data-id="<?php echo $post['id']; ?>" href="/<?php echo $post['owner']['fullname']; ?>/image/<?php echo $post['id']; ?>"><?php echo $LANG['label-comments']; ?>: <span class="comments-count" data-id="<?php echo $post['id']; ?>"><?php echo $post['commentsCount']; ?></span></a>
                </div>

                <div class="item-footer">
                    <div class="item-footer-container">
                        <span class="item-footer-button">
                            <a class="item-like-button item-footer-button <?php if ($post['myLike']) echo "active"; ?>" onclick="Item.like('<?php echo $post['id']; ?>', '<?php echo ITEM_TYPE_GALLERY; ?>'); return false;" data-id="<?php echo $post['id']; ?>">
                                <i class="iconfont icofont-heart mr-1"></i>
                                <?php echo $LANG['action-like']; ?>
                            </a>
                        </span>

                        <?php

                        if (!$showComments) {

                            ?>
                            <span class="item-footer-button">
                                        <a class="item-footer-button" href="/<?php echo $post['owner']['username']; ?>/image/<?php echo $post['id']; ?>">
                                            <i class="iconfont icofont-comment mr-1"></i>
                                            <?php echo $LANG['action-comment']; ?>
                                        </a>
                                    </span>
                            <?php
                        }
                        ?>

                    </div>
                </div>

                <?php

                if ($showComments) {

                    ?>

                    <ul class="items-list comments-list" data-id="<?php echo $post['id']; ?>">

                        <?php

                        $gallery = new gallery();
                        $gallery->setLanguage($LANG['lang-code']);
                        $gallery->setRequestFrom(auth::getCurrentUserId());

                        $comments = new comments(NULL, ITEM_TYPE_GALLERY);

                        $data = $comments->get($post['id'], 0, $post);

                        $commentsCount = count($data['comments']);

                        $data['comments'] = array_reverse($data['comments'], false);

                        foreach ($data['comments'] as $key => $value) {

                            draw::comment($value, $LANG);
                        }

                        ?>

                    </ul>

                    <?php

                    if (auth::getCurrentUserId() != 0) {

                        if ($post['owner']['allowGalleryComments'] != 0) {

                            ?>

                            <div class="media media-comment mb-2">

                                <a href="/<?php echo auth::getCurrentUserLogin(); ?>" class="avatar-container form-avatar-container">
                                    <div alt="<?php echo auth::getCurrentUserFullname(); ?>" title="<?php echo auth::getCurrentUserFullname(); ?>" class="rounded-circle shadow avatar" style="background-image:url(<?php echo auth::getCurrentUserPhotoUrl(); ?>)"></div>
                                    <span title="Online" class="online"></span>
                                </a>

                                <div class="media-body">
                                    <form class="rounded-lg border" onsubmit="Comments.create('<?php echo $post['id'] ?>', '<?php echo ITEM_TYPE_GALLERY; ?>'); return false;">
                                        <input type="hidden" name="message_image" value="">
                                        <div class="input-group input-group-lg input-group-merge">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text border-0 px-2">
                                                        <i class="icofont icofont-ui-edit"></i>
                                                    </span>
                                            </div>

                                            <input data-id="<?php echo $post['id']; ?>" maxlength="140" type="text" name="comment_text" class="form-control border-0 px-1" placeholder="<?Php echo $LANG['label-placeholder-comment']; ?>">

                                            <div class="input-group-append">

                                                    <span class="input-group-text border-0 py-0 pl-2 pr-0">

                                                        <div class="dropdown emoji-dropdown dropup" style="display: block">

                                                            <span class="smile-button btn-emoji-picker flat_btn" data-toggle="dropdown">
                                                                <i class="btn-emoji-picker-icon iconfont icofont-slightly-smile"></i>
                                                            </span>

                                                            <div class="dropdown-menu dropdown-menu-right mt-2">
                                                                <div class="emoji-items">
                                                                    <div class="emoji-item">😀</div>
                                                                    <div class="emoji-item">😁</div>
                                                                    <div class="emoji-item">😂</div>
                                                                    <div class="emoji-item">😃</div>
                                                                    <div class="emoji-item">😄</div>
                                                                    <div class="emoji-item">😅</div>
                                                                    <div class="emoji-item">😆</div>
                                                                    <div class="emoji-item">😉</div>
                                                                    <div class="emoji-item">😊</div>
                                                                    <div class="emoji-item">😋</div>
                                                                    <div class="emoji-item">😎</div>
                                                                    <div class="emoji-item">😍</div>
                                                                    <div class="emoji-item">😘</div>
                                                                    <div class="emoji-item">🤗</div>
                                                                    <div class="emoji-item">🤩</div>
                                                                    <div class="emoji-item">🤔</div>
                                                                    <div class="emoji-item">🤨</div>
                                                                    <div class="emoji-item">😐</div>
                                                                    <div class="emoji-item">🙄</div>
                                                                    <div class="emoji-item">😏</div>
                                                                    <div class="emoji-item">😣</div>
                                                                    <div class="emoji-item">😥</div>
                                                                    <div class="emoji-item">😮</div>
                                                                    <div class="emoji-item">🤐</div>
                                                                    <div class="emoji-item">😯</div>
                                                                    <div class="emoji-item">😪</div>
                                                                    <div class="emoji-item">😫</div>
                                                                    <div class="emoji-item">😴</div>
                                                                    <div class="emoji-item">😌</div>
                                                                    <div class="emoji-item">😜</div>
                                                                    <div class="emoji-item">🤤</div>
                                                                    <div class="emoji-item">😓</div>
                                                                    <div class="emoji-item">😔</div>
                                                                    <div class="emoji-item">🤑</div>
                                                                    <div class="emoji-item">😲</div>
                                                                    <div class="emoji-item">🙁</div>
                                                                    <div class="emoji-item">😖</div>
                                                                    <div class="emoji-item">😞</div>
                                                                    <div class="emoji-item">😟</div>
                                                                    <div class="emoji-item">😤</div>
                                                                    <div class="emoji-item">😢</div>
                                                                    <div class="emoji-item">😭</div>
                                                                    <div class="emoji-item">😦</div>
                                                                    <div class="emoji-item">😧</div>
                                                                    <div class="emoji-item">😨</div>
                                                                    <div class="emoji-item">😩</div>
                                                                    <div class="emoji-item">😰</div>
                                                                    <div class="emoji-item">😱</div>
                                                                    <div class="emoji-item">😳</div>
                                                                    <div class="emoji-item">🤪</div>
                                                                    <div class="emoji-item">😵</div>
                                                                    <div class="emoji-item">😡</div>
                                                                    <div class="emoji-item">😠</div>
                                                                    <div class="emoji-item">🤬</div>
                                                                    <div class="emoji-item">😷</div>
                                                                    <div class="emoji-item">🤒</div>
                                                                    <div class="emoji-item">🤕</div>
                                                                    <div class="emoji-item">🤢</div>
                                                                    <div class="emoji-item">🤮</div>
                                                                    <div class="emoji-item">🤧</div>
                                                                    <div class="emoji-item">😇</div>
                                                                    <div class="emoji-item">🤠</div>
                                                                    <div class="emoji-item">🤡</div>
                                                                    <div class="emoji-item">🤥</div>
                                                                    <div class="emoji-item">🤫</div>
                                                                    <div class="emoji-item">🤭</div>
                                                                    <div class="emoji-item">🧐</div>
                                                                    <div class="emoji-item">🤓</div>
                                                                    <div class="emoji-item">😈</div>
                                                                    <div class="emoji-item">👿</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-sm  blue comment_send"><?Php echo $LANG['action-send']; ?></button>
                                                    </span>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                            <?php

                        } else {

                            ?>

                            <header class="top-banner info-banner" style="border: 0">

                                <div class="info">
                                    <p style="white-space: normal; border: 0; text-align: center;"><?php echo $LANG['label-comments-disallow']; ?></p>
                                </div>

                            </header>

                            <?php
                        }

                    } else {

                        ?>

                        <header class="top-banner info-banner" style="border: 0">

                            <div class="info">
                                <p style="white-space: normal; border: 0; text-align: center;"><?php echo $LANG['label-comments-prompt']; ?></p>
                            </div>

                        </header>

                        <?php
                    }
                    ?>

                    <?php
                }
                ?>

            </div>

        </li>

        </div>

        <?php
    }

    static function previewGiftItem($item, $profileInfo)
    {

        ?>

        <div class="col-6 col-sm-6 col-md-6 col-lg-6 gallery-item">

            <div class="item-inner">

                <a href="/<?php echo $profileInfo['username']; ?>/gifts">

                    <div class="gallery-item-preview" style="background-image:url(<?php echo $item['imgUrl']; ?>);">

                    </div>
                </a>

            </div>
        </div>

        <?php
    }

    static function marketItemPreview($item, $LANG, $helper = null)
    {
        $itemPhotoUrl = "/img/market_default_img.png";

        if (strlen($item['previewImgUrl']) != 0) {

            $itemPhotoUrl = $item['previewImgUrl'];
        }

        ?>

        <div class="market-item" data-id="<?php echo $item['id']; ?>">

            <div class="item-inner">

                <a href="/<?php echo $item['fromUserUsername']; ?>/market/<?php echo $item['id']; ?>">

                    <div class="market-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">

                        <?php

                        if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {

                            ?>

                            <span onclick="Market.deleteItem('<?php echo $item['id']; ?>'); return false;" title="<?php echo $LANG['action-remove']; ?>" class="settings"><i class="icofont icofont-ui-delete"></i></span>

                            <?php

                        }
                        ?>
                        <span class="info-badge black">
                            <span class="title"><?php echo $item['itemTitle']; ?></span>
                            <span class="sub-title"><?php echo $item['price']." $"; ?></span>
                        </span>
                    </div>
                </a>

            </div>
        </div>

        <?php
    }

    static function marketItem($post, $LANG, $helper = null)
    {
        $post['itemDesc'] = helper::processPostText($post['itemDesc']);

        $fromUserPhoto = "/img/market_default_img.png";

        if (strlen($post['previewImgUrl']) != 0) {

            $fromUserPhoto = $post['previewImgUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <div class="card " data-id="<?php echo $post['id']; ?>">

            <li class="custom-list-item post-item" data-id="<?php echo $post['id']; ?>">

                <div class="mb-2 item-header">

                    <a href="/<?php echo $post['fromUserUsername']; ?>" class="item-logo" style="background-image:url(<?php echo $fromUserPhoto; ?>)"></a>

                    <?php

                    if ((auth::isSession() && $post['fromUserId'] == auth::getCurrentUserId())) {

                        ?>

                        <div class="dropdown">
                            <a class="mb-sm-0 item-menu" data-toggle="dropdown">
                                <i class="iconfont icofont-curved-down"></i>
                            </a>

                                <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="Market.deleteItem('<?php echo $post['id']; ?>'); return false;"><?php echo $LANG['action-remove']; ?></a>
                                </div>

                        </div>

                        <?php
                    }

                    ?>

                    <a href="/<?php echo $post['fromUserUsername']; ?>" class="custom-item-link post-item-fullname"><?php echo $post['fromUserFullname']; ?></a>
                    <?php

                    if ($post['fromUserVerified'] == 1) {

                        ?>
                        <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                        <?php


                    }
                    ?>

                    <span class="post-item-time"><a href="/<?php echo $post['fromUserUsername']; ?>/market/<?php echo $post['id']; ?>"><?php echo $time->timeAgo($post['createAt']); ?></a></span>

                </div>

                <div class="item-meta post-item-content">

                    <?php

                    if (strlen($post['imgUrl'])) {

                        ?>
                        <img class="post-img my-0" data-href="<?php echo $post['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $post['previewImgUrl']; ?>">
                        <?php
                    }
                    ?>

                    <h4 class="mx-2 mt-3" style=""><span class="p-2" style="border-radius: 5px; background-color: #5EBA7D; color: white">$ <?php echo $post['price']; ?></span></h4>
                    <p class="post-text mx-2 mt-3"><strong><?php echo $post['itemTitle']; ?></strong></p>
                    <p class="post-text mx-2"><?php echo $post['itemContent']; ?></p>

                    <div class="item-footer">
                        <div class="item-footer-container">
                        <span class="item-footer-button">
                            <a class="item-like-button item-footer-button" href="/<?php echo $post['fromUserUsername']; ?>">
                                <?php echo $LANG['action-contact-seller']; ?>
                            </a>
                        </span>

                        </div>
                    </div>

                </div>

            </li>

        </div>

        <?php
    }

// Buku Alumni

    static function bukualumniItemPreview($item, $LANG, $helper = null)
    {
        $dbo = NULL;
        $user_id = $item['fromUserId'];
        $profile = new profile($dbo, $user_id);
        $profile->setRequestFrom(auth::getCurrentUserId());

        $profileInfo = $profile->get();

        $itemPhotoUrl = "/img/market_default_img.png";
        if (strlen($item['previewImgUrl']) != 0) {
            $itemPhotoUrl = $item['previewImgUrl'];
        }
        ?>

        <?php
            if($item['shareto'] == 1){
        ?>
        <div class="market-item" data-id="<?php echo $item['id']; ?>">
            <div class="item-inner">
                <a href="/<?php echo $item['fromUserUsername']; ?>/bukualumni/<?php echo $item['id']; ?>">
                    <div class="market-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">
                        <?php
                        if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                            ?>
                            <span onclick="Bukualumni.deleteItem('<?php echo $item['id']; ?>'); return false;" title="<?php echo $LANG['action-remove']; ?>" class="settings"><i class="icofont icofont-ui-delete"></i></span>
                            <?php
                        }
                        ?>
                        <span class="info-badge black">
                            <span class="title"><?php echo $item['itemTitle']; ?></span>
                            <?php
                            if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                                ?>
                                <span class="title">
                                    <?php
                                        if ($item['shareto'] == 1){
                                            echo $LANG['for-public'];
                                        } elseif ($item['shareto'] == 2){
                                            echo $LANG['friends-only'];
                                        }else{
                                            echo $LANG['msg-error-unknown'];
                                        }
                                    ?>
                                </span>
                                <?php
                            }
                            ?>                            
                        </span>
                    </div>
                </a>
            </div>
        </div>
        <?php
            }elseif (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
        ?>
            <div class="market-item" data-id="<?php echo $item['id']; ?>">
                <div class="item-inner">
                    <a href="/<?php echo $item['fromUserUsername']; ?>/bukualumni/<?php echo $item['id']; ?>">
                        <div class="market-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">
                            <?php
                            if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                                ?>
                                <span onclick="Bukualumni.deleteItem('<?php echo $item['id']; ?>'); return false;" title="<?php echo $LANG['action-remove']; ?>" class="settings"><i class="icofont icofont-ui-delete"></i></span>
                                <?php
                            }
                            ?>
                            <span class="info-badge black">
                                <span class="title"><?php echo $item['itemTitle']; ?></span>
                                <?php
                                if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                                    ?>
                                    <span class="title">
                                        <?php
                                            if ($item['shareto'] == 1){
                                                echo $LANG['for-public'];
                                            } elseif ($item['shareto'] == 2){
                                                echo $LANG['friends-only'];
                                            }else{
                                                echo $LANG['msg-error-unknown'];
                                            }
                                        ?>
                                    </span>
                                    <?php
                                }
                                ?>                            
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        <?php
            }elseif ($profileInfo['friend'] == 1 && $item['shareto'] == 2){
        ?>
            <div class="market-item" data-id="<?php echo $item['id']; ?>">
                <div class="item-inner">
                    <a href="/<?php echo $item['fromUserUsername']; ?>/bukualumni/<?php echo $item['id']; ?>">
                        <div class="market-item-preview" style="background-image:url(<?php echo $itemPhotoUrl; ?>)">
                            <?php
                            if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                                ?>
                                <span onclick="Bukualumni.deleteItem('<?php echo $item['id']; ?>'); return false;" title="<?php echo $LANG['action-remove']; ?>" class="settings"><i class="icofont icofont-ui-delete"></i></span>
                                <?php
                            }
                            ?>
                            <span class="info-badge black">
                                <span class="title"><?php echo $item['itemTitle']; ?></span>
                                <?php
                                if (auth::getCurrentUserId() != 0 && auth::getCurrentUserId() == $item['fromUserId']) {
                                    ?>
                                    <span class="title">
                                        <?php
                                            if ($item['shareto'] == 1){
                                                echo $LANG['for-public'];
                                            } elseif ($item['shareto'] == 2){
                                                echo $LANG['friends-only'];
                                            }else{
                                                echo $LANG['msg-error-unknown'];
                                            }
                                        ?>
                                    </span>
                                    <?php
                                }
                                ?>                            
                            </span>
                        </div>
                    </a>
                </div>
            </div>
        <?php
            }else{
            }
        ?>
        <?php
    }


    static function bukualumniItem($post, $LANG, $helper = null)
    {
        $post['itemDesc'] = helper::processPostText($post['itemDesc']);

        $fromUserPhoto = "/img/market_default_img.png";

        if (strlen($post['previewImgUrl']) != 0) {

            $fromUserPhoto = $post['previewImgUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <div class="card " data-id="<?php echo $post['id']; ?>">
            <li class="custom-list-item post-item" data-id="<?php echo $post['id']; ?>">
                <div class="mb-2 item-header">
                    <a href="/<?php echo $post['fromUserUsername']; ?>" class="item-logo" style="background-image:url(<?php echo $fromUserPhoto; ?>)"></a>
                    <?php
                    if ((auth::isSession() && $post['fromUserId'] == auth::getCurrentUserId())) {
                        ?>
                        <div class="dropdown">
                            <a class="mb-sm-0 item-menu" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-h"></i>
                            </a>
                                <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="Bukualumni.deleteItem('<?php echo $post['id']; ?>'); return false;"><?php echo $LANG['action-remove']; ?></a>
                                </div>
                        </div>
                        <?php
                    }
                    ?>
                    <a href="/<?php echo $post['fromUserUsername']; ?>" class="custom-item-link post-item-fullname"><?php echo $post['fromUserFullname']; ?></a>
                    <?php

                    if ($post['fromUserVerified'] == 1) {

                        ?>
                        <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                        <?php
                    }
                    ?>

                    <span class="post-item-time"><a href="/<?php echo $post['fromUserUsername']; ?>/bukualumni/<?php echo $post['id']; ?>"><?php echo $time->timeAgo($post['createAt']); ?></a></span>

                </div>

                <div class="item-meta post-item-content">

                    <?php

                    if (strlen($post['imgUrl'])) {

                        ?>
                        <img class="post-img my-0" data-href="<?php echo $post['imgUrl']; ?>" onclick="blueimp.Gallery($(this)); return false" style="" alt="post-img" src="<?php echo $post['previewImgUrl']; ?>">
                        <?php
                    }
                    ?>
                    <p class="post-text mx-2 mt-3"><strong><?php echo $post['itemTitle']; ?></strong></p>
                    <p class="post-text mx-2" style="text-align: justify; text-indent: 0.5in;"><?php echo $post['itemContent']; ?></p>

                    <div class="item-footer">
                        <div class="item-footer-container">
                        <span class="item-footer-button">
                            <a class="item-like-button item-footer-button" href="/<?php echo $post['fromUserUsername']; ?>">
                                <?php echo $LANG['action-contact-profile']; ?>
                            </a>
                        </span>

                        </div>
                    </div>

                </div>

            </li>

        </div>

        <?php
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }
}

