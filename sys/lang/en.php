<?php
    /*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $TEXT = array();
    $SEX = array("Male" => 0, "Female" => 1);

    $TEXT['lang-code'] = "en";
    $TEXT['lang-name'] = "English";

    $TEXT['main-page-welcome'] = "Publish messages and pictures With My Social Network!";

    $TEXT['main-page-about'] = "My Social Network - a social network through which you can share interesting news, images and videos from YouTube with your friends and all the people..";

    $TEXT['main-page-prompt-app'] = "%s - a social network specially developed for alumni of Soegijapranata Catholic University.";

    $TEXT['mode-demo'] = "Enabled demo version. The changes you've made will not be saved.";

    $TEXT['topbar-users'] = "Users";

    $TEXT['topbar-stats'] = "Statistics";

    $TEXT['topbar-signin'] = "Sign in";

    $TEXT['topbar-login'] = "Log in";

    $TEXT['topbar-logout'] = "Log out";

    $TEXT['topbar-signup'] = "Sign up";

    $TEXT['topbar-friends'] = "Friends";

    $TEXT['topbar-settings'] = "Settings";

    $TEXT['topbar-support'] = "Support";

    $TEXT['topbar-profile'] = "Profile";

    $TEXT['topbar-likes'] = "Notifications";

    $TEXT['topbar-search'] = "Search";

    $TEXT['topbar-main-page'] = "Home";

    $TEXT['topbar-wall'] = "Home";

    $TEXT['topbar-messages'] = "Messages";

    $TEXT['topbar-reports-to-users'] = "Complaints to members";

    $TEXT['topbar-live'] = "Posts stream";

    $TEXT['topbar-ad'] = "Ads";

    $TEXT['footer-about'] = "about";

    $TEXT['footer-terms'] = "terms";

    $TEXT['footer-contact'] = "contact us";

    $TEXT['footer-support'] = "support";

    $TEXT['footer-android-application'] = "Android app";

    $TEXT['page-main'] = "Main";

    $TEXT['page-ad'] = "Ads";

    $TEXT['page-users'] = "Users";

    $TEXT['page-hashtags'] = "Hashtags";

    $TEXT['page-reports-to-users'] = "Complaints to members";

    $TEXT['page-terms'] = "Terms and Policies";

    $TEXT['page-about'] = "About";

    $TEXT['page-language'] = "Choose your language";

    $TEXT['page-support'] = "Support";

    $TEXT['page-restore'] = "Password retrieval";

    $TEXT['page-restore-sub-title'] = "Please enter the email, on which registered page.";

    $TEXT['page-signup'] = "create account";

    $TEXT['page-login'] = "Login";

    $TEXT['page-login-sub-title'] = "Stay in touch on the go with QA Script mobile.";

    $TEXT['page-wall'] = "News";

    $TEXT['page-blacklist'] = "Blocked list";

    $TEXT['page-messages'] = "Messages";

    $TEXT['page-stream'] = "Stream";

    $TEXT['page-following'] = "Following";

    $TEXT['page-friends'] = "Friends";

    $TEXT['page-followers'] = "Followers";

    $TEXT['page-posts'] = "Posts";

    $TEXT['page-search'] = "Search";

    $TEXT['page-profile-report'] = "Report";

    $TEXT['page-profile-block'] = "Block";

    $TEXT['page-profile-upload-avatar'] = "Upload photo";

    $TEXT['page-profile-upload-cover'] = "Upload cover";

    $TEXT['page-profile-report-sub-title'] = "Reported profiles are sent to our moderators for a review. They will ban the reported profiles if they violate terms of use";

    $TEXT['page-profile-block-sub-title'] = "will not be able write comments to your Posts and send your messages, and you will not see notifications from";

    $TEXT['page-post-report-sub-title'] = "Reported posts are sent to our moderators for a review. They will removed the reported posts if they violate terms of use";

    $TEXT['page-likes'] = "People who like this";

    $TEXT['page-services'] = "Services";

    $TEXT['page-services-sub-title'] = "Connect My Social Network with your social network accounts";

    $TEXT['page-prompt'] = "create account or login";

    $TEXT['page-settings'] = "Settings";

    $TEXT['page-profile-settings'] = "Profile";

    $TEXT['page-profile-password'] = "Change password";

    $TEXT['page-notifications-likes'] = "Notifications";

    $TEXT['page-profile-deactivation'] = "Deactivate account";

    $TEXT['page-profile-deactivation-sub-title'] = "Leaving us?<br>If you proceed with deactivating your account, you can always come back. Just enter your login and password on the log-in page. We hope to see you again!";

    $TEXT['page-error-404'] = "Page not found";

    $TEXT['label-location'] = "Location";
    $TEXT['label-facebook-link'] = "Facebook page";
    $TEXT['label-instagram-link'] = "Instagram page";
    $TEXT['label-status'] = "Bio";

    $TEXT['label-error-404'] = "Requested page was not found.";

    $TEXT['label-account-disabled'] = "This user has disabled your account.";

    $TEXT['label-account-blocked'] = "This account has been blocked by the administrator.";

    $TEXT['label-account-deactivated'] = "This account is not activated.";

    $TEXT['label-reposition-cover'] = "Drag to Reposition Cover";

    $TEXT['label-or'] = "or";

    $TEXT['label-and'] = "and";

    $TEXT['label-signup-confirm'] = "By clicking Sign up, you agree to our";

    $TEXT['label-likes-your-post'] = "likes your post.";

    $TEXT['label-login-to-like'] = "You have to be a registered user to like posts.";

    $TEXT['label-login-to-follow'] = "You must have an account to follow this user.";

    $TEXT['label-empty-my-wall'] = "You haven't any posts yet.";

    $TEXT['label-empty-wall'] = "This user has no posts.";

    $TEXT['label-empty-page'] = "Here is empty.";

    $TEXT['label-empty-questions'] = "This is the link to your profile. Share it with your friends to get more followers.";

    $TEXT['label-empty-friends-header'] = "You have no friends.";

    $TEXT['label-empty-likes-header'] = "You have no notifications.";

    $TEXT['label-empty-list'] = "List is empty.";

    $TEXT['label-empty-feeds'] = "Here you'll see updates your friends.";

    $TEXT['label-search-result'] = "Search results";

    $TEXT['label-search-empty'] = "Nothing found.";

    $TEXT['label-search-prompt'] = "Find people by username.";

    $TEXT['label-thanks'] = "Hooray!";

    $TEXT['label-post-missing'] = "This post is missing.";

    $TEXT['label-post-deleted'] = "This post has been deleted.";

    $TEXT['label-posts-privacy'] = "Privacy settings for posts";

    $TEXT['label-comments-allow'] = "I authorize to comment on my posts";

    $TEXT['label-messages-privacy'] = "Privacy settings for messages";

    $TEXT['label-messages-allow'] = "Receive messages from anyone";

    $TEXT['label-settings-saved'] = "Settings saved.";

    $TEXT['label-password-saved'] = "Password successfully changed.";

    $TEXT['label-profile-settings-links'] = "And also you can";

    $TEXT['label-photo'] = "Photo";

    $TEXT['label-background'] = "Background";

    $TEXT['label-username'] = "Username";

    $TEXT['label-fullname'] = "Full name";

    $TEXT['label-services'] = "Services";

    $TEXT['label-blacklist'] = "Blocked list";

    $TEXT['label-blacklist-desc'] = "View blocked list";

    $TEXT['label-profile'] = "Profile";

    $TEXT['label-email'] = "Email";

    $TEXT['label-password'] = "Password";

    $TEXT['label-old-password'] = "Current password";

    $TEXT['label-new-password'] = "New password";

    $TEXT['label-change-password'] = "Change password";

    $TEXT['label-facebook'] = "Facebook";

    $TEXT['label-prompt-follow'] = "You must have an account to follow this user.";

    $TEXT['label-prompt-like'] = "You have to be a registered user to like posts.";

    $TEXT['label-placeholder-post'] = "Write your post ...";

    $TEXT['label-placeholder-message'] = "Write a message...";

    $TEXT['label-img-format'] = "Maximum size 3 Mb. JPG, PNG";

    $TEXT['label-message'] = "Message";

    $TEXT['label-subject'] = "Subject";

    $TEXT['label-support-message'] = "What are you contacting us about?";

    $TEXT['label-support-sub-title'] = "We are glad to hear from you! ";

    $TEXT['label-profile-info'] = "At the moment, there appears not all the information! All information is available from the application Sprosi.in for Android!";

    $TEXT['label-profile-report-reason-1'] = "This is spam.";

    $TEXT['label-profile-report-reason-2'] = "Hate Speech or violence.";

    $TEXT['label-profile-report-reason-3'] = "Nudity or Pornography.";

    $TEXT['label-profile-report-reason-4'] = "Fake profile.";

    $TEXT['label-profile-report-reason-5'] = "Piracy.";

    $TEXT['label-like-user'] = "person";

    $TEXT['label-mylike-user'] = "other";

    $TEXT['label-like-peoples'] = "people";

    $TEXT['label-mylike-peoples'] = "others";

    $TEXT['label-mylike'] = "You";

    $TEXT['label-like'] = "like this";

    $TEXT['label-success'] = "Success";

    $TEXT['label-password-reset-success'] = "A new password has been successfully installed!";

    $TEXT['label-verify'] = "verify";

    $TEXT['label-account-verified'] = "Verified account";

    $TEXT['label-account-bot'] = "Bot account";

    $TEXT['label-account-staff'] = "Staff account";

    $TEXT['label-true'] = "true";

    $TEXT['label-false'] = "false";

    $TEXT['label-state'] = "account status";

    $TEXT['label-stats'] = "Statistics";

    $TEXT['label-id'] = "Id";

    $TEXT['label-count'] = "Count";

    $TEXT['label-repeat-password'] = "repeat password";

    $TEXT['label-category'] = "Category";

    $TEXT['label-from-user'] = "from";

    $TEXT['label-to-user'] = "to";

    $TEXT['label-reason'] = "Reason";

    $TEXT['label-action'] = "Action";

    $TEXT['label-warning'] = "Warning!";

    $TEXT['label-connected-with-facebook'] = "Connected with Facebook";

    $TEXT['label-authorization-with-facebook'] = "Authorization via Facebook.";

    $TEXT['label-services-facebook-connected'] = "You have successfully linked My Social Network with your account on Facebook!";

    $TEXT['label-services-facebook-disconnected'] = "Connect with your Facebook account removed.";

    $TEXT['label-services-facebook-error'] = "Your account on Facebook is already associated with another account.";

    $TEXT['action-login-with'] = "Login with";

    $TEXT['action-signup-with'] = "Sign up with";
    $TEXT['action-delete-profile-photo'] = "Delete photo";
    $TEXT['action-delete-profile-cover'] = "Remove the cover image";
    $TEXT['action-change-photo'] = "Change photo";
    $TEXT['action-change-password'] = "Change password";

    $TEXT['action-more'] = "View more";

    $TEXT['action-next'] = "Next";

    $TEXT['action-another-post'] = "Write another post";

    $TEXT['action-add-img'] = "Add an image";

    $TEXT['action-remove-img'] = "Delete image";

    $TEXT['action-close'] = "Close";

    $TEXT['action-go-to-conversation'] = "Go to conversation";

    $TEXT['action-post'] = "Post";

    $TEXT['action-remove'] = "Delete";

    $TEXT['action-report'] = "Report";

    $TEXT['action-block'] = "Block";

    $TEXT['action-unblock'] = "UnBlock";

    $TEXT['action-follow'] = "Follow";

    $TEXT['action-unfollow'] = "Unfollow";

    $TEXT['action-change-cover'] = "Change cover";

    $TEXT['action-change'] = "Change";

    $TEXT['action-change-image'] = "Change image";

    $TEXT['action-edit-profile'] = "Edit profile";

    $TEXT['action-edit-question'] = "Edit question";

    $TEXT['action-edit'] = "Edit";

    $TEXT['action-restore'] = "Restore";

    $TEXT['label-for-followers'] = "Only for followers";

    $TEXT['label-question-removed'] = "Question has been removed.";

    $TEXT['action-deactivation-profile'] = "Deactivate account";

    $TEXT['action-connect-profile'] = "Connect with social network accounts";

    $TEXT['action-connect-facebook'] = "Connect with Facebook";

    $TEXT['action-disconnect'] = "Remove connection";

    $TEXT['action-back-to-default-signup'] = "Back to the regular registration form";

    $TEXT['action-back-to-main-page'] = "Return to main page";

    $TEXT['action-back-to-previous-page'] = "Return to previous page";

    $TEXT['action-forgot-password'] = "Forgot your password or username?";

    $TEXT['action-full-profile'] = "View full user profile";

    $TEXT['action-delete-image'] = "Delete image";

    $TEXT['action-send'] = "Send";

    $TEXT['action-cancel'] = "Cancel";

    $TEXT['action-upload'] = "Upload";

    $TEXT['action-search'] = "Search";

    $TEXT['action-change'] = "Change";

    $TEXT['action-save'] = "Save";

    $TEXT['action-login'] = "Log in";

    $TEXT['action-signup'] = "Sign up";

    $TEXT['action-join'] = "JOIN NOW!";
//    $TEXT['action-join'] = "Ð ÐµÐ³Ð¸ÑÑ‚Ñ€Ð°Ñ†Ð¸Ñ";

    $TEXT['action-forgot-password'] = "Forgot password?";

    $TEXT['msg-loading'] = "Loading...";

    $TEXT['msg-post-sent'] = "Your post has been published.";

    $TEXT['msg-login-taken'] = "A user with that username already exists.";

    $TEXT['msg-login-incorrect'] = "Username wrong format.";

    $TEXT['msg-login-incorrect'] = "Username wrong format.";

    $TEXT['msg-fullname-incorrect'] = "Fullname wrong format.";

    $TEXT['msg-password-incorrect'] = "Password wrong format.";

    $TEXT['msg-password-save-error'] = "Password not changed, wrong current password.";

    $TEXT['msg-email-incorrect'] = "Email wrong format.";

    $TEXT['msg-email-taken'] = "User with this email address is already registered.";

    $TEXT['msg-email-not-found'] = "User with this email was not found in the database.";

    $TEXT['msg-reset-password-sent'] = "A message with link to reset your password has been sent to your email.";

    $TEXT['msg-error-unknown'] = "Error. Try again later.";

    $TEXT['msg-error-authorize'] = "Incorrect username or password.";

    $TEXT['msg-error-deactivation'] = "Wrong password.";

    $TEXT['placeholder-users-search'] = "Find users by login. Minimum of 5 characters.";

    $TEXT['ticket-send-success'] = 'In a short time we will review your request and send a response to your email.';

    $TEXT['ticket-send-error'] = 'Please fill all fields.';






    $TEXT['action-go-to-post'] = "Go to post";
    $TEXT['label-follow-your'] = "followed you";
    $TEXT['label-full-profile'] = "View full profile";

    $TEXT['label-placeholder-comment'] = "Add a comment...";
    $TEXT['action-show-all'] = "Show all";
    $TEXT['action-comment'] = "Comment";
    $TEXT['label-comments-prompt'] = "Leave a comment can only registered users.";
    $TEXT['label-comments-disallow'] = "User comments forbade their posts.";
    $TEXT['label-new-comment'] = "added a new comment.";

    $TEXT['label-image-upload-description'] = "We support JPG, PNG or GIF files.";
    $TEXT['label-cover-upload-description'] = "Choose a unique image for the cover of your profile.<br>We support JPG or PNG files.";
    $TEXT['label-photo-upload-description'] = "Please upload a real photo of yourself, so that friends can recognize you.<br>We support JPG or PNG files.";
    $TEXT['action-select-file-and-upload'] = "Select a file and upload";

    $TEXT['fb-linking'] = "Connect with Facebook";
    // $TEXT['fb-linking'] = "ÐŸÐ¾Ð´ÐºÐ»ÑŽÑ‡Ð¸Ñ‚ÑŒÑÑ Ðº Facebook";
    // $TEXT['fb-linking'] = "ÐŸÑ–Ð´ÐºÐ»ÑŽÑ‡Ð¸Ñ‚Ð¸ÑÑ Ð´Ð¾ Facebook";

    $TEXT['label-social-search'] = "You do not connect your account to the Facebook.";
    $TEXT['label-social-search-not-found'] = "We did not find your friends from Facebook";

    $TEXT['sidebar-profile'] = "My Profile";
    $TEXT['sidebar-friends'] = "My Friends";
    $TEXT['sidebar-messages'] = "My Messages";
    $TEXT['sidebar-news'] = "My News";
    $TEXT['sidebar-settings'] = "My Settings";
    $TEXT['sidebar-favorites'] = "My Favorites";

    $TEXT['page-favorites'] = "Favorites";

    $TEXT['label-search-hashtag-prompt'] = "Search posts by hashtag.";

    $TEXT['label-gender'] = "Gender";
    $TEXT['label-birth-date'] = "Birth Date";

    $TEXT['gender-unknown'] = "Gender Unknown";
    $TEXT['gender-male'] = "Gender Male";
    $TEXT['gender-female'] = "Gender Female";

    $TEXT['month-jan'] = "January";
    $TEXT['month-feb'] = "February";
    $TEXT['month-mar'] = "March";
    $TEXT['month-apr'] = "April";
    $TEXT['month-may'] = "May";
    $TEXT['month-june'] = "June";
    $TEXT['month-july'] = "July";
    $TEXT['month-aug'] = "August";
    $TEXT['month-sept'] = "September";
    $TEXT['month-oct'] = "October";
    $TEXT['month-nov'] = "November";
    $TEXT['month-dec'] = "December";

    $TEXT['label-likes'] = "Likes";

    $TEXT['sidebar-stream'] = "Stream";
    $TEXT['sidebar-popular'] = "Popular";

    $TEXT['page-popular'] = "Popular";

    $TEXT['label-new-reply-to-comment'] = "replied to your comment.";
    $TEXT['action-reply'] = "Reply";

    $TEXT['action-share'] = "Share";
    $TEXT['action-share-post'] = "Share post";
    $TEXT['label-share-options'] = "Sharing options";
    $TEXT['label-share-where'] = "Where to share";
    $TEXT['label-share-on-wall'] = "On my wall";
    $TEXT['label-share-on-wall-desc'] = "Share this post with your friends and followers";
    $TEXT['label-share-add-comment'] = "Add your comment";

    $TEXT['label-prompt-repost'] = "You have to be a registered user to do reposts.";
    $TEXT['label-repost-error'] = "Content is not available.";


    $TEXT['page-gallery'] = "Gallery";
    $TEXT['action-add-photo'] = "Add photo";
    $TEXT['label-add-photo'] = "Select a photo to add it to the gallery";
    $TEXT['sidebar-gallery'] = "My Gallery";
    $TEXT['label-photos'] = "Photos";

    $TEXT['sidebar-groups'] = "My Communities";
    $TEXT['page-groups'] = "Communities";
    $TEXT['label-groups'] = "Communities";
    $TEXT['label-my-groups'] = "My Communities";
    $TEXT['label-managed-groups'] = "Managed Communities";
    $TEXT['action-create-group'] = "Create Community";
    $TEXT['label-group-search-prompt'] = "Find community by name.";

    $TEXT['label-group-fullname'] = "Name";
    $TEXT['label-group-username'] = "Community link (Short name)";
    $TEXT['label-group-status'] = "Community description";
    $TEXT['label-group-location'] = "Location";
    $TEXT['label-group-web-page'] = "Website";
    $TEXT['label-group-category'] = "Community subject";
    $TEXT['label-group-date'] = "Founded";
    $TEXT['label-group-privacy'] = "Privacy settings";
    $TEXT['label-group-allow-comments'] = "All community members are allowed to leave comments to posts";
    $TEXT['label-group-allow-posts'] = "All community members are allowed to write posts";

    $TEXT['label-group-name-error'] = "Community name (Short name) is already taken, or incorrectly";
    $TEXT['label-group-fullname-error'] = "Full community name must contain at least 2 characters";

    $TEXT['group-category_0'] = "Activity holidays";
    $TEXT['group-category_1'] = "Art and culture";
    $TEXT['group-category_2'] = "Auto/moto";
    $TEXT['group-category_3'] = "Beauty and fashion";
    $TEXT['group-category_4'] = "Business";
    $TEXT['group-category_5'] = "Cinema";
    $TEXT['group-category_6'] = "Cooking";
    $TEXT['group-category_7'] = "Dating and communication";
    $TEXT['group-category_8'] = "Design and graphics";
    $TEXT['group-category_9'] = "Education";
    $TEXT['group-category_10'] = "Electronics and appliances";
    $TEXT['group-category_11'] = "Entertainment";
    $TEXT['group-category_12'] = "Erotic";
    $TEXT['group-category_13'] = "Esoterics";
    $TEXT['group-category_14'] = "Family";
    $TEXT['group-category_15'] = "Finance";
    $TEXT['group-category_16'] = "Food";
    $TEXT['group-category_17'] = "Games";
    $TEXT['group-category_18'] = "Goods and services";
    $TEXT['group-category_19'] = "Health";
    $TEXT['group-category_20'] = "Hobbies";
    $TEXT['group-category_21'] = "Home and renovations";
    $TEXT['group-category_22'] = "Humor";
    $TEXT['group-category_23'] = "Industry";
    $TEXT['group-category_24'] = "Insurance";
    $TEXT['group-category_25'] = "IT (computers and software)";
    $TEXT['group-category_26'] = "Literature";
    $TEXT['group-category_27'] = "Mobile services and internet";
    $TEXT['group-category_28'] = "Music";
    $TEXT['group-category_29'] = "News and Media";
    $TEXT['group-category_30'] = "Pets";
    $TEXT['group-category_31'] = "Photo";
    $TEXT['group-category_32'] = "Politics";
    $TEXT['group-category_33'] = "Real estate";
    $TEXT['group-category_34'] = "Religion";
    $TEXT['group-category_35'] = "Science and technology";
    $TEXT['group-category_36'] = "Security";
    $TEXT['group-category_37'] = "Society, humanities";
    $TEXT['group-category_38'] = "Sports";
    $TEXT['group-category_39'] = "Television";
    $TEXT['group-category_40'] = "Travel";
    $TEXT['group-category_41'] = "Work";


    // For version 1.8

    $TEXT['page-guests'] = "Guests";
    $TEXT['sidebar-guests'] = "My Guests";

    // For version 1.9

    $TEXT['page-nearby'] = "People Nearby";

    // For version 2.1

    $TEXT['page-balance'] = "Balance";
    $TEXT['page-balance-desc'] = "Credits - a universal currency. In addition, the credits can be paid for gifts and other functions.";
    $TEXT['action-get-credits'] = "Get more credits";
    $TEXT['action-get-explanation'] = "You can get credit by inviting your friends to register by entering the referral code you have, clicking referral menu to see more information";
    $TEXT['label-credits'] = "Credits";
    $TEXT['label-balance'] = "You have:";
    $TEXT['page-gifts'] = "Gifts";
    $TEXT['label-new-gift'] = "made a gift.";
    $TEXT['action-view'] = "view";
    $TEXT['dlg-select-gift'] = "Choose a gift";
    $TEXT['page-send-gift'] = "Send a gift";
    $TEXT['label-placeholder-gift'] = "Comment to gift...";

    // For version 2.6

    $TEXT['label-image-missing'] = "This photo is missing.";

    $TEXT['label-image-deleted'] = "This photo has been deleted.";

    $TEXT['label-images-privacy'] = "Privacy settings for photos";

    $TEXT['label-images-comments-allow'] = "I authorize to comment on my photos";

    $TEXT['action-go-to-photo'] = "Go to photo";

    $TEXT['label-likes-your-photo'] = "likes your photo.";

    // For version 3.1

    $TEXT['label-missing-account'] = "Don't have an account?";
    $TEXT['label-existing-account'] = "You already have an account?";
    $TEXT['label-errors-title'] = "Error. Read below:";
    $TEXT['label-signup-sub-title'] = "Create an account and join our community now!";

    $TEXT['label-friends-search'] = "Find";
    $TEXT['label-friends-search-sub-title'] = "Find my friends";

    $TEXT['label-notifications-sub-title'] = "Here you can see notifications about likes, new followers, comments, gifts.";
    $TEXT['label-friends-sub-title'] = "Here you can see the list of your friends.";
    $TEXT['label-guests-sub-title'] = "This section displays people who have visited to your profile.";
    $TEXT['label-messages-sub-title'] = "This section tells you about your conversations with other users of the social network.";
    $TEXT['label-gallery-sub-title'] = "Add more photos and pictures!";
    $TEXT['label-settings-main-section-title'] = "Basics";
    $TEXT['label-settings-main-section-sub-title'] = "Enter your name, your gender, date of birth, etc.";
    $TEXT['label-settings-password-sub-title'] = "Enter your old password and new password.";
    $TEXT['label-settings-deactivation-sub-title'] = "Enter your current password.";

    $TEXT['nav-communities'] = "Communities";
    $TEXT['nav-profile'] = "Profile";
    $TEXT['nav-friends'] = "Friends";
    $TEXT['nav-messages'] = "Messages";
    $TEXT['nav-notifications'] = "Notifications";
    $TEXT['nav-search'] = "Search";
    $TEXT['nav-settings'] = "Settings";
    $TEXT['nav-logout'] = "Log out";
    $TEXT['nav-news'] = "News";

    $TEXT['nav-popular'] = "Popular";
    $TEXT['nav-favorites'] = "Favorites";
    $TEXT['nav-guests'] = "Guests";
    $TEXT['nav-gallery'] = "Gallery";
    $TEXT['nav-home'] = "Home";

    $TEXT['label-profile-report-block'] = "Something is wrong with this account? Let us know!";


    // For version 3.4

    $TEXT['action-add-to-friends'] = "Add to friends";
    $TEXT['action-remove-from-friends'] = "Remove from friends";
    $TEXT['action-accept-friend-request'] = "Accept Friend Request";
    $TEXT['action-cancel-friend-request'] = "Cancel Friend Request";

    $TEXT['label-notify-request-to-friends'] = "sent you a request to friends";

    $TEXT['action-accept'] = "Accept";
    $TEXT['action-reject'] = "Reject";

    $TEXT['label-error-permission'] = "The information is only available to friends of the user.";

    $TEXT['label-privacy'] = "Privacy";

    $TEXT['label-allow-show-friends'] = "Show my friends only for friends";
    $TEXT['label-allow-show-photos'] = "Show my gallery only for friends";
    $TEXT['label-allow-show-videos'] = "Show my videos only for friends";
    $TEXT['label-allow-show-gifts'] = "Show my gifts only for friends";
    $TEXT['label-allow-show-info'] = "Show profile info only for friends";

    $TEXT['label-allow-show-friends-desc'] = "Friends";
    $TEXT['label-allow-show-photos-desc'] = "Photos";
    $TEXT['label-allow-show-videos-desc'] = "Videos";
    $TEXT['label-allow-show-gifts-desc'] = "Gifts";
    $TEXT['label-allow-show-info-desc'] = "Profile Info";

    // For version 3.5

    $TEXT['label-for-friends'] = "Only for friends";

    // For version 3.7

    $TEXT['page-referrals'] = "Referrals";

    $TEXT['page-referrals-label-id'] = "You User Id:";
    $TEXT['page-referrals-label-hint'] = "Invite your friends and get Credits!";
    $TEXT['page-referrals-label-hint2'] = "Referrals are people who registered at your invitation.";

    $TEXT['label-user-id'] = "User ID";
    $TEXT['label-signup-invite'] = "User ID who invited you (optional)";

    // For version 4.1

    $TEXT['label-seen'] = "seen";

    // For version 4.4

    $TEXT['page-news-description'] = "Here you can read posts of your friends and communities.";
    $TEXT['page-stream-description'] = "Here you can read all posts from social network.";
    $TEXT['page-favorites-description'] = "In this section you can see posts that you liked earlier.";
    $TEXT['page-popular-description'] = "Here you can see the most popular posts.";

    $TEXT['page-communities'] = "Communities";
    $TEXT['page-communities-description'] = "Communities you follow.";
    $TEXT['page-managed-communities'] = "Management";
    $TEXT['page-managed-communities-description'] = "Communities that you created and manage.";
    $TEXT['page-search-communities'] = "Search Communities";
    $TEXT['page-search-communities-description'] = "Enter community name to search";
    $TEXT['page-create-communities'] = "Create a new community";
    $TEXT['page-create-communities-description'] = "Fill in all the required fields to create your community.";

    $TEXT['label-community-verified'] = "Community verified";
    $TEXT['label-community-followers'] = "Followers";


    $TEXT['tab-search-users'] = "People";
    $TEXT['tab-search-communities'] = "Communities";
    $TEXT['tab-search-facebook'] = "Facebook";
    $TEXT['tab-search-hashtags'] = "Hashtags";
    $TEXT['tab-search-nearby'] = "People Nearby";

    $TEXT['tab-search-users-description'] = "Looking for friends! Enter a name, login, email, country or city to start search.";
    $TEXT['tab-search-communities-description'] = "Enter community name to search";
    $TEXT['tab-search-facebook-description'] = "You are looking for friends and acquaintances with whom you are friends on Facebook.";
    $TEXT['tab-search-hashtags-description'] = "Enter a hashtag to search for posts.";
    $TEXT['tab-search-nearby-description'] = "People who are close to you.";

    $TEXT['page-notifications'] = "Notifications";
    $TEXT['page-notifications-description'] = "Here you can see notifications about likes, friends requests, comments, gifts...";

    $TEXT['action-go-to-chat'] = "Go to chat";

    // For version 4.5

    $TEXT['hint-item-android-version'] = "Posted via Android app";
    $TEXT['hint-item-ios-version'] = "Posted via iOS app";

    $TEXT['search-filters-show'] = "Show Search Filters";
    $TEXT['search-filters-hide'] = "Hide Search Filters";
    $TEXT['search-filters-gender'] = "Gender";
    $TEXT['search-filters-all'] = "All";
    $TEXT['search-filters-male'] = "Male";
    $TEXT['search-filters-female'] = "Female";
    $TEXT['search-filters-online'] = "Online";
    $TEXT['search-filters-active'] = "Last active";
    $TEXT['search-filters-photo'] = "Photo";
    $TEXT['search-filters-photo-filter'] = "Only with Photo";
    $TEXT['search-filters-action-search'] = "Search";

    $TEXT['search-editbox-placeholder'] = "Enter a keyword";

    $TEXT['tab-friends-all'] = "All Friends";
    $TEXT['tab-friends-online'] = "Online Friends";
    $TEXT['tab-friends-inbox-requests'] = "Inbox Requests";
    $TEXT['tab-friends-outbox-requests'] = "Outbox Requests";

    $TEXT['label-friends-online-sub-title'] = "Here you can see the list of friends who are now online.";
    $TEXT['label-friends-inbox-requests-sub-title'] = "Here you can see people who want to be friends with you.";
    $TEXT['label-friends-outbox-requests-sub-title'] = "List of people to whom you sent a friend request.";

    $TEXT['label-last-seen'] = "Last seen";
    $TEXT['label-last-visit'] = "Last visit";
    $TEXT['label-create-at'] = "Creation time";

    $TEXT['label-outbox-request'] = "Outbox Request";
    $TEXT['label-inbox-request'] = "Inbox Request";

    $TEXT['page-friends-requests'] = "Friend Requests";
    $TEXT['page-chat'] = "Chat";

    $TEXT['action-clear-all'] = "Clear All";
    $TEXT['action-clear'] = "Clear";

    $TEXT['page-welcome'] = "Welcome";
    $TEXT['page-welcome-sub-title'] = "Thank you for registering!";

    $TEXT['page-welcome-message-1'] = "Upload a photo!";
    $TEXT['page-welcome-message-2'] = "You can upload your photo now!";
    $TEXT['page-welcome-message-3'] = "Photo shows your uniqueness, individuality and style!";
    $TEXT['page-welcome-message-4'] = "Uploading a photo is not a requirement. You can skip this step and upload your photo later.";

    $TEXT['action-start'] = "Start";

    // For version 4.6

    $TEXT['label-updated-profile-photo'] = "updated profile photo.";
    $TEXT['label-updated-cover-photo'] = "updated cover image.";

    $TEXT['page-gdpr'] = "GDPR (General Data Protection Regulation) Privacy Rights";
    $TEXT['footer-gdpr'] = "GDPR";

    $TEXT['label-cookie-message'] = "We use cookies to analyze our website traffic. By continuing to use the site, you agree to our ";

    $TEXT['label-is-feeling'] = "is feeling";

    // For version 4.9

    $TEXT['page-privacy'] = "Privacy policy"; //ÐŸÐ¾Ð»Ð¸Ñ‚Ð¸ÐºÐ° ÐºÐ¾Ð½Ñ„Ð¸Ð´ÐµÐ½Ñ†Ð¸Ð°Ð»ÑŒÐ½Ð¾ÑÑ‚Ð¸

    $TEXT['label-notify-profile-photo-reject'] = "rejected you profile photo.";
    $TEXT['label-notify-profile-photo-reject-subtitle'] = "Upload/change profile photo to repeat moderation.";
    $TEXT['label-notify-profile-cover-reject'] = "rejected you profile cover.";
    $TEXT['label-notify-profile-cover-reject-subtitle'] = "Upload/change profile cover to repeat moderation.";

    // For version 5.0

    $TEXT['label-comments'] = "Comments";
    $TEXT['label-reposts'] = "Reposts";
    $TEXT['label-create-post'] = "Create post";
    $TEXT['label-search-filters'] = "Filters";
    $TEXT['label-profile-info'] = "Information";

    $TEXT['action-like'] = "Like";
    $TEXT['action-yes'] = "Yes";
    $TEXT['action-no'] = "No";

    $TEXT['msg-contact-promo'] = "Want to contact %s? Join now!";

    $TEXT['label-allow-show-gallery'] = "Show gallery only to friends";
    $TEXT['label-allow-show-gallery-desc'] = "Gallery";

    $TEXT['label-gallery-privacy'] = "Gallery privacy setting";
    $TEXT['label-gallery-comments-allow'] = "I allow comments on my photos and videos";

    $TEXT['dlg-confirm-block-title'] = "Block user";
    $TEXT['msg-block-user-text'] = "User %s will be added to your blacklist. You will not receive personal messages and other notifications from %s. Do you confirm your action?";

    $TEXT['label-chats'] = "Chats";

    // For version 5.1

    $TEXT['action-activate'] = "Activate";
    $TEXT['label-activated'] = "Activated";

    $TEXT['action-buy-credits'] = "Buy credits";
    $TEXT['action-pay'] = "Pay";
    $TEXT['action-stripe-pay'] = "Pay with ";
    $TEXT['label-stripe'] = "Stripe";
    $TEXT['label-payments-for'] = "for";
    $TEXT['label-payments-credits'] = "Credits";
    $TEXT['label-payments-amount'] = "Amount";
    $TEXT['label-payments-description'] = "Description";
    $TEXT['label-payments-date'] = "Date";

    $TEXT['label-balance-not-enough'] = "On your balance has not enough funds.";

    $TEXT['label-payments-credits-stripe'] = "Buying credits with Stripe";
    $TEXT['label-payments-credits-android'] = "Buying credits with Android in-app purchases";
    $TEXT['label-payments-credits-ios'] = "Buying credits with iOS in-app purchases";
    $TEXT['label-payments-credits-admob'] = "Credits for viewing ads";
    $TEXT['label-payments-send-gift'] = "Gift Sending";
    $TEXT['label-payments-verified-badge'] = "Activating the Verified Badge Feature";
    $TEXT['label-payments-ghost-mode'] = "Activate Ghost Mode";
    $TEXT['label-payments-off-admob'] = "Disabling Admob Ads in the App";
    $TEXT['label-payments-registration-bonus'] = "Sign Up Bonus";
    $TEXT['label-payments-referral-bonus'] = "Referral Bonus";

    $TEXT['label-payments-success_added'] = "Credits added to you balance.";

    $TEXT['label-payments-history'] = "Balance History";

    $TEXT['page-upgrades'] = "Upgrades";
    $TEXT['page-upgrades-desc'] = "Get more with the activation of additional features!";

    $TEXT['label-upgrades-verified-badge'] = "Verified Badge";
    $TEXT['label-upgrades-verified-badge-desc'] = "Show others that you are an legitimate user with this awesome badge";
    $TEXT['label-upgrades-ghost-mode'] = "Ghost Mode";
    $TEXT['label-upgrades-ghost-mode-desc'] = "View user profiles anonymously, users will not know about your visit to their profile.";
    $TEXT['label-upgrades-off-admob'] = "Disable Ads";
    $TEXT['label-upgrades-off-admob-desc'] = "Tired of seeing advertisements? Disable them from showing!";

    // For version 5.2

    $TEXT['page-market'] = "Market";
    $TEXT['page-market-sub-title'] = "Here you can buy and sell goods.";

    $TEXT['page-products'] = "My Products";

    $TEXT['market-new-item-dlg-title'] = "New listing";
    $TEXT['market-new-item-ad-title'] = "Ad title";
    $TEXT['market-new-item-ad-title-placeholder'] = "Enter the title of your ad";
    $TEXT['market-new-item-ad-desc'] = "Ad Description";
    $TEXT['market-new-item-ad-desc-placeholder'] = "Make a detailed description of your product or service";
    $TEXT['market-new-item-ad-price'] = "Price (price in $, must be greater than zero)";

    $TEXT['market-new-item-button-title'] = "Create ad";
    $TEXT['market-new-item-promo-title'] = "Create an ad now!";
    $TEXT['market-new-item-promo-desc'] = "Sell and buy goods here and now";

    $TEXT['action-contact-seller'] = "Contact seller";

    $TEXT['action-access-mode-all'] = "For all"; //Ð”Ð»Ñ Ð²ÑÐµÑ…
    $TEXT['action-access-mode-friends'] = "Friends"; //Ð”Ñ€ÑƒÐ·ÑŒÑ

    // For version 5.3

    $TEXT['action-message'] = "Write message";

    $TEXT['action-ok'] = "Ok";
    $TEXT['label-messages-not-allowed'] = "%s wants to receive private messages only from friends";

    $TEXT['footer-privacy'] = "privacy policy";

    $TEXT['page-nearby'] = "People Nearby";
    $TEXT['page-nearby-desc'] = "See who is near you.";

    $TEXT['action-apply'] = "Apply";
    $TEXT['action-allow'] = "Allow";

    $TEXT['label-location-request'] = "Provide access to your location to be able to use this feature.";
    $TEXT['label-location-denied'] = "Allow access to your location in your browser settings.";
    $TEXT['label-location-unsupported'] = "Sorry, but your browser does not support geolocation features.";

    $TEXT['label-distance'] = "Distance";

    $TEXT['page-privacy-settings'] = "Privacy";

    $TEXT['label-menu-post'] = "Post";
    $TEXT['label-menu-item'] = "Item";
    $TEXT['label-menu-likes'] = "Likes";
    $TEXT['label-menu-profile'] = "Profile %s";
    $TEXT['label-menu-gallery'] = "Gallery %s";

    // For version 5.4

    $TEXT['label-chat-empty'] = "Chat is empty.";
    $TEXT['label-chat-empty-promo'] = "Start chatting! Send a message, sticker, emoticon or picture!";

    $TEXT['label-select-gift'] = "Select gift";

    $TEXT['action-buy'] = "Buy credits";

    $TEXT['label-balance'] = "Balance";
    $TEXT['label-you-balance'] = "You have";

    $TEXT['label-gift-message-promo'] = "Here you can add a comment to your gift...";

    // For version 5.5

    $TEXT['page-explore'] = "Explore";

    $TEXT['action-explore'] = "Explore";

    $TEXT['main-page-promo-google-app'] = "%s is where you go, so you can create posts and chat with your friends anytime, anywhere. Stay in touch with your friends with the mobile application %s. Available for Android. BETA";
    $TEXT['main-page-promo-login'] = "%s - a social network specially developed for alumni of Soegijapranata Catholic University.";
    $TEXT['main-page-promo-explore'] = "%s gives you the ability to see the full stream of publications from users and communities, to search for people, and communities without registration! Read posts, watch videos, search for information and explore the social network!";


    // For Halo Alumni
    // Buku Alumni
    $TEXT['tab-search-bukualumni'] = "Alumni Book";
    $TEXT['label-buku-alumni'] = "Alumni Book";
    $TEXT['page-buku-alumni-sub-title'] = 'Welcome to alumni book page of Soegijapranata Catholic University, on this page you can see Alumni information, content in this page is created and approved for sharing by related Alumni.';
    $TEXT['page-buku-alumni-search-guide'] = 'You can search for alumni books by entering name, nim, or faculty code.';
    $TEXT['buku-alumni-ku-title'] = "Share your experience for Alumni book Now!";
    $TEXT['buku-alumni-ku-desc'] = "You can share information and your experience, to be displayed on the Alumni book page like other Alumni.";
    $TEXT['buku-alumni-new-item-button-title'] = "Create";
    $TEXT['buku-alumni-new-item-dlg-title'] = "New listing";
    $TEXT['buku-alumni-new-item-title'] = "Title";
    $TEXT['buku-alumni-new-item-title-placeholder'] = "Enter the title of your alumni book here";
    $TEXT['buku-alumni-new-item-desc'] = "Information";
    $TEXT['buku-alumni-new-item-desc-placeholder'] = "Share your experience here";
    $TEXT['buku-alumni-new-item-shareto'] = "Share your experience to?";
    // About
    $TEXT['page-about-version'] = "Version";
    // Setting
    $TEXT['msg-nim-incorrect'] = "Nim not Found";
    $TEXT['label-nim'] = "NIM Ex 15.N1.0099";
    $TEXT['label-tgllahir'] = "Birth Date Ex 01-12-1999";
    $TEXT['label-tgllahir-problem'] = "Input 01-01-0001 if you have any problem";
    $TEXT['apanel-admob'] = "Add Mob";
    $TEXT['private'] = "Private";
    $TEXT['friends-only'] = "For Friends";
    $TEXT['for-public'] = "For Public";
    $TEXT['action-contact-profile'] = "Open Profile";
    $TEXT['may-know'] = "You may know";    
    $TEXT['dont-have-account'] = "Dont have account?";
    $TEXT['already-have-account'] = "Already have account?";
    $TEXT['login-now'] = "Login now";   
    // Side Nav
    $TEXT['label-settings-main-section-title-bukualumni-ku'] = "My Alumni Book";
    $TEXT['label-settings-main-section-title-bukualumni'] = "Alumni Book";
    $TEXT['label-settings-main-section-sub-title-bukualumni'] = "Enter information that you want to share, examples on 'Alumni Book' and 'Metamorfosa' menu.";
    $TEXT['action-readmore'] = "Read More";
    $TEXT['page-infoloker'] = "Info Loker";
    $TEXT['page-infoloker-description'] = "This section you can view posts about job vacancies.";
    $TEXT['page-careernews'] = "Career News";
    $TEXT['page-careernews-description'] = "This section you can view posts about career news.";
    $TEXT['page-unikanews'] = "Unika News";
    $TEXT['page-unikanews-description'] = "This section you can view posts about Unika Soegijapranata news.";
    $TEXT['page-campushiring'] = "Campus Hiring";
    $TEXT['page-campushiring-description'] = "This section you can view posts about Campus Hiring.";
    $TEXT['page-metamorfosa'] = "Metamorfosa";
    $TEXT['page-metamorfosa-description'] = "This section you can view posts about Metamorfosa.";
    $TEXT['page-buku-alumni'] = "Alumni Book";
    $TEXT['page-buku-alumni-ku'] = "My Alumni Book";
    $TEXT['page-buku-alumni-description'] = "This section you can view posts about Alumni Book.";
    $TEXT['page-video'] = "Video";
    $TEXT['page-video-description'] = "This section you can view posts about Video.";