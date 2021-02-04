<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class update extends db_connect
{
    public function __construct($dbo = NULL)
    {
        parent::__construct($dbo);

    }

    // For reply to comments v1.2

    function addColumnToCommentsTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE comments ADD replyToUserId INT(11) UNSIGNED DEFAULT 0 after fromUserId");
        $stmt->execute();
    }

    // For settings reply to comments v1.2

    function addColumnToUsersTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowCommentReplyGCM SMALLINT(6) UNSIGNED DEFAULT 1 after allowMessagesGCM");
        $stmt->execute();
    }

    function addColumnToPostsTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD rePostsCount INT(11) UNSIGNED DEFAULT 0 after likesCount");
        $stmt->execute();
    }

    function addColumnToPostsTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD rePostId INT(11) UNSIGNED DEFAULT 0 after fromUserId");
        $stmt->execute();
    }

    // For version 1.5 | Emoji support

    function setChatEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages charset = utf8mb4, MODIFY COLUMN message VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    function setCommentsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE comments charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    function setPostsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts charset = utf8mb4, MODIFY COLUMN post VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    // For version 1.6

    function setPhotosEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE photos charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    function addColumnToUsersTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD photos_count INT(11) UNSIGNED DEFAULT 0 after likes_count");
        $stmt->execute();
    }

    // For version 1.7

    function addColumnToPostsTable3()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD groupId INT(11) UNSIGNED DEFAULT 0 after id");
        $stmt->execute();
    }

    function addColumnToUsersTable3()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowPosts INT(11) UNSIGNED DEFAULT 0 after allowMessages");
        $stmt->execute();
    }

    function addColumnToUsersTable4()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD account_category INT(11) UNSIGNED DEFAULT 0 after account_type");
        $stmt->execute();
    }

    function addColumnToUsersTable5()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD gifts_count INT(11) UNSIGNED DEFAULT 0 after photos_count");
        $stmt->execute();
    }

    function addColumnToUsersTable6()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowGiftsGCM INT(11) UNSIGNED DEFAULT 0 after allowCommentReplyGCM");
        $stmt->execute();
    }

    function addColumnToUsersTable7()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD followers_count INT(11) UNSIGNED DEFAULT 0 after gifts_count");
        $stmt->execute();
    }

    function addColumnToUsersTable8()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD following_count INT(11) UNSIGNED DEFAULT 0 after followers_count");
        $stmt->execute();
    }

    function addColumnToUsersTable9()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD friends_count INT(11) UNSIGNED DEFAULT 0 after following_count");
        $stmt->execute();
    }

    function addColumnToUsersTable10()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD last_guests_view INT(10) UNSIGNED DEFAULT 0 after last_feed_view");
        $stmt->execute();
    }

    // For version 1.8

    function addColumnToUsersTable11()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD ghost INT(10) UNSIGNED DEFAULT 0 after admob");
        $stmt->execute();
    }

    // For version 1.9

    function addColumnToUsersTable12()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD lat float(10,6) DEFAULT 0 after city_id");
        $stmt->execute();
    }

    function addColumnToUsersTable14()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD lng float(10,6) DEFAULT 0 after lat");
        $stmt->execute();
    }

    // For version 2.1 | Emoji support

    function setGiftsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE gifts charset = utf8mb4, MODIFY COLUMN message VARCHAR(400) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    // For version 2.2

    function addColumnToPostsTable4()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD urlPreviewTitle VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after post");
        $stmt->execute();
    }

    function addColumnToPostsTable5()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD urlPreviewImage VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after urlPreviewTitle");
        $stmt->execute();
    }

    function addColumnToPostsTable6()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD urlPreviewDescription VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after urlPreviewImage");
        $stmt->execute();
    }

    function addColumnToPostsTable7()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD urlPreviewLink VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after urlPreviewDescription");
        $stmt->execute();
    }

    // For version 2.3

    function addColumnToChatsTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE chats ADD message varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after toUserId_lastView");
        $stmt->execute();
    }

    function addColumnToChatsTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE chats ADD messageCreateAt INT(11) UNSIGNED DEFAULT 0 after message");
        $stmt->execute();
    }

    function setDialogsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE chats charset = utf8mb4, MODIFY COLUMN message VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    // For version 2.6

    function addColumnToUsersTable15()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowPhotosComments SMALLINT(6) UNSIGNED DEFAULT 1 after allowComments");
        $stmt->execute();
    }

    function setImagesCommentsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE images_comments charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    // For version 2.8

    function addColumnToUsersTable16()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD videos_count INT(11) UNSIGNED DEFAULT 0 after photos_count");
        $stmt->execute();
    }

    function addColumnToUsersTable17()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowVideoComments SMALLINT(6) UNSIGNED DEFAULT 1 after allowPhotosComments");
        $stmt->execute();
    }

    function setVideoCommentsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE video_comments charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    // For version 2.9

    function addColumnToUsersTable18()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD ios_fcm_regid TEXT after gcm_regid");
        $stmt->execute();
    }

    // For version 3.4

    function addColumnToUsersTable19()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD last_friends_view INT(10) UNSIGNED DEFAULT 0 after last_notify_view");
        $stmt->execute();
    }

    function addColumnToUsersTable20()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyInfo SMALLINT(6) UNSIGNED DEFAULT 0 after allowGiftsGCM");
        $stmt->execute();
    }

    function addColumnToUsersTable21()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyPhotos SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyInfo");
        $stmt->execute();
    }

    function addColumnToUsersTable22()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyVideos SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyPhotos");
        $stmt->execute();
    }

    function addColumnToUsersTable23()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyFriends SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyVideos");
        $stmt->execute();
    }

    function addColumnToUsersTable24()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyGifts SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyFriends");
        $stmt->execute();
    }

    function addColumnToUsersTable25()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD referrer INT(10) UNSIGNED DEFAULT 0 after ios_fcm_regid");
        $stmt->execute();
    }

    function addColumnToUsersTable26()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD credits_to_referrer INT(10) UNSIGNED DEFAULT 0 after referrer");
        $stmt->execute();
    }

    function addColumnToUsersTable27()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD purchases_count INT(10) UNSIGNED DEFAULT 0 after credits_to_referrer");
        $stmt->execute();
    }

    function addColumnToUsersTable28()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD referrals_count INT(10) UNSIGNED DEFAULT 0 after purchases_count");
        $stmt->execute();
    }

    function addColumnToMessagesTable1()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages ADD stickerId INT(11) UNSIGNED DEFAULT 0 after imgUrl");
        $stmt->execute();
    }

    function addColumnToMessagesTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages ADD stickerImgUrl varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after stickerId");
        $stmt->execute();
    }

    //

    function addColumnToUsersTable29()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD android_msg_fcm_regid TEXT after ios_fcm_regid");
        $stmt->execute();
    }

    function addColumnToUsersTable30()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD ios_msg_fcm_regid TEXT after android_msg_fcm_regid");
        $stmt->execute();
    }

    // for version 4.1

    function addColumnToMessagesTable3()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages ADD seenAt INT(11) UNSIGNED DEFAULT 0 after removeAt");
        $stmt->execute();
    }

    function addColumnToMessagesTable4()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages ADD seenFromUserId INT(11) UNSIGNED DEFAULT 0 after seenAt");
        $stmt->execute();
    }

    function addColumnToMessagesTable5()
    {
        $stmt = $this->db->prepare("ALTER TABLE messages ADD seenToUserId INT(11) UNSIGNED DEFAULT 0 after seenFromUserId");
        $stmt->execute();
    }

    // for version 4.3

    function addColumnToUsersTable31()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD pro INT(10) UNSIGNED DEFAULT 0 after ghost");
        $stmt->execute();
    }

    function addColumnToUsersTable32()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD pro_create_at INT(10) UNSIGNED DEFAULT 0 after pro");
        $stmt->execute();
    }

    // for version 4.5

    function addColumnToPostsTable8()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD viewsCount INT(11) UNSIGNED DEFAULT 0 after commentsCount");
        $stmt->execute();
    }

    function addColumnToPostsTable9()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD deviceType INT(11) UNSIGNED DEFAULT 0 after viewsCount");
        $stmt->execute();
    }

    function addColumnToPostsTable10()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD moderateAt INT(11) UNSIGNED DEFAULT 0 after removeAt");
        $stmt->execute();
    }

    function addColumnToPostsTable11()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD needToModerate INT(11) UNSIGNED DEFAULT 0 after moderateAt");
        $stmt->execute();
    }

    // for version 4.6

    function addColumnToUsersTable33()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD mood INT(10) UNSIGNED DEFAULT 0 after referrals_count");
        $stmt->execute();
    }

    function addColumnToPostsTable12()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD feeling INT(10) UNSIGNED DEFAULT 0 after id");
        $stmt->execute();
    }

    function addColumnToPostsTable14()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD postType INT(10) UNSIGNED DEFAULT 0 after feeling");
        $stmt->execute();
    }

    // for version 4.9

    function addColumnToUsersTable34()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD age SMALLINT(6) UNSIGNED DEFAULT 18 after sex");
        $stmt->execute();
    }

    function addColumnToUsersTable35()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyAgeAndGender SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyGifts");
        $stmt->execute();
    }

    function addColumnToPostsTable15()
    {
        $stmt = $this->db->prepare("ALTER TABLE posts ADD imagesCount INT(11) UNSIGNED DEFAULT 0 after viewsCount");
        $stmt->execute();
    }

    function addColumnToUsersTable36()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD photoModerateAt int(11) UNSIGNED DEFAULT 0 after coverPosition");
        $stmt->execute();
    }

    function addColumnToUsersTable37()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD photoModerateUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after photoModerateAt");
        $stmt->execute();
    }

    function addColumnToUsersTable38()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD photoPostModerateAt int(11) UNSIGNED DEFAULT 0 after photoModerateUrl");
        $stmt->execute();
    }

    function addColumnToUsersTable39()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD coverModerateAt int(11) UNSIGNED DEFAULT 0 after photoPostModerateAt");
        $stmt->execute();
    }

    function addColumnToUsersTable40()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD coverModerateUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after coverModerateAt");
        $stmt->execute();
    }

    function addColumnToUsersTable41()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD coverPostModerateAt int(11) UNSIGNED DEFAULT 0 after coverModerateUrl");
        $stmt->execute();
    }

    // for version 5.0

    function addColumnToAccessDataTable1()
    {
        $stmt = $this->db->prepare("ALTER TABLE access_data ADD fcm_regId VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '' after accessToken");
        $stmt->execute();
    }

    function addColumnToAccessDataTable2()
    {
        $stmt = $this->db->prepare("ALTER TABLE access_data ADD appType int(10) UNSIGNED DEFAULT 0 after fcm_regId");
        $stmt->execute();
    }

    function addColumnToAccessDataTable3()
    {
        $stmt = $this->db->prepare("ALTER TABLE access_data ADD lang CHAR(10) DEFAULT 'en' after clientId");
        $stmt->execute();
    }

    function addColumnToUsersTable42()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD gallery_items_count INT(11) UNSIGNED DEFAULT 0 after photos_count");
        $stmt->execute();
    }

    function addColumnToUsersTable43()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowShowMyGallery SMALLINT(6) UNSIGNED DEFAULT 0 after allowShowMyVideos");
        $stmt->execute();
    }

    function addColumnToUsersTable44()
    {
        $stmt = $this->db->prepare("ALTER TABLE users ADD allowGalleryComments SMALLINT(6) UNSIGNED DEFAULT 1 after allowVideoComments");
        $stmt->execute();
    }

    function setGalleryEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE gallery charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    function setGalleryCommentsEmojiSupport()
    {
        $stmt = $this->db->prepare("ALTER TABLE gallery_comments charset = utf8mb4, MODIFY COLUMN comment VARCHAR(800) CHARACTER SET utf8mb4");
        $stmt->execute();
    }

    function renameRowInLikesTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE likes CHANGE postId itemId int(11) UNSIGNED DEFAULT 0");
        $stmt->execute();
    }

    function renameRowInCommentsTable()
    {
        $stmt = $this->db->prepare("ALTER TABLE comments CHANGE postId itemId int(11) UNSIGNED DEFAULT 0");
        $stmt->execute();
    }

    public function updateUsersTable1()
    {
        $stmt = $this->db->prepare("UPDATE users SET allowMessages = 0");
        $stmt->execute();
    }

    // addon

    function recalculate()
    {
        $stmt = $this->db->prepare("SELECT id FROM users");

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $account = new account($this->db, $row['id']);
                $account->updateCounters();
                unset($account);
            }
        }
    }

    function delete_all_followers_for_users()
    {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE notifyType = 1");
        $stmt->execute();

        $stmt = $this->db->prepare("UPDATE users SET followers_count = 0, following_count = 0, friends_count = 0 WHERE account_type = 0");
        $stmt->execute();
    }

    function recalculate_friends_for_users()
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE friends_count = 0");

        if ($stmt->execute()) {

            while ($row = $stmt->fetch()) {

                $account = new account($this->db, $row['id']);

                $friendsCount = $account->getFriendsCount();

                $account->setFriendsCount($friendsCount);

                unset($account);
            }
        }
    }
}
