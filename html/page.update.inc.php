<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }


    $page_id = "update";

    include_once("../sys/core/initialize.inc.php");

    $update = new update($dbo);
    $update->addColumnToCommentsTable();
    $update->addColumnToUsersTable();
    $update->addColumnToPostsTable();
    $update->addColumnToPostsTable2();

    $update->addColumnToUsersTable2();

    $update->addColumnToPostsTable3();
    $update->addColumnToUsersTable3();
    $update->addColumnToUsersTable4();
    $update->addColumnToUsersTable5();
    $update->addColumnToUsersTable6();
    $update->addColumnToUsersTable7();
    $update->addColumnToUsersTable8();
    $update->addColumnToUsersTable9();
    $update->addColumnToUsersTable10();

    $update->addColumnToUsersTable11();

    $update->addColumnToUsersTable12();
    $update->addColumnToUsersTable14();

    $update->addColumnToPostsTable4();
    $update->addColumnToPostsTable5();
    $update->addColumnToPostsTable6();
    $update->addColumnToPostsTable7();

    $update->addColumnToChatsTable();
    $update->addColumnToChatsTable2();

    $update->addColumnToUsersTable15();

    $update->addColumnToUsersTable16();
    $update->addColumnToUsersTable17();

    $update->addColumnToUsersTable18();

    // for v3.4

    $update->addColumnToUsersTable19();
    $update->addColumnToUsersTable20();
    $update->addColumnToUsersTable21();
    $update->addColumnToUsersTable22();
    $update->addColumnToUsersTable23();
    $update->addColumnToUsersTable24();

    //$update->delete_all_followers_for_users();

    // for v3.5

//    $update->recalculate_friends_for_users();

//    $update->recalculate();

    // for v3.7

    $update->addColumnToUsersTable25();
    $update->addColumnToUsersTable26();
    $update->addColumnToUsersTable27();
    $update->addColumnToUsersTable28();

    // for v3.9

    $update->addColumnToMessagesTable1();
    $update->addColumnToMessagesTable2();

    //

    $update->addColumnToUsersTable29();
    $update->addColumnToUsersTable30();

    // for v4.1

    $update->addColumnToMessagesTable3();
    $update->addColumnToMessagesTable4();
    $update->addColumnToMessagesTable5();

    // for v4.3

    $update->addColumnToUsersTable31();
    $update->addColumnToUsersTable32();

    // for v4.5

    $update->addColumnToPostsTable8();
    $update->addColumnToPostsTable9();
    $update->addColumnToPostsTable10();
    $update->addColumnToPostsTable11();

    // for v4.6

    $update->addColumnToUsersTable33();
    $update->addColumnToPostsTable12();
    $update->addColumnToPostsTable14();

    // for v4.9

    $update->addColumnToUsersTable34();
    $update->addColumnToUsersTable35();

    $update->addColumnToUsersTable36();
    $update->addColumnToUsersTable37();
    $update->addColumnToUsersTable38();

    $update->addColumnToUsersTable39();
    $update->addColumnToUsersTable40();
    $update->addColumnToUsersTable41();

    $update->addColumnToPostsTable15();

    // for v5.0

    $update->addColumnToAccessDataTable1();
    $update->addColumnToAccessDataTable2();
    $update->addColumnToAccessDataTable3();
    $update->addColumnToUsersTable42();
    $update->addColumnToUsersTable43();
    $update->addColumnToUsersTable44();

    $update->renameRowInLikesTable();
    $update->renameRowInCommentsTable();

    $settings = new settings($dbo);
    $settings->createValue("allowMultiAccountsFunction", 1); //Default allow create multi-accounts
    $settings->createValue("allowFacebookAuthorization", 1); //Default allow facebook authorization
    $settings->createValue("photoModeration", 1); //Default on
    $settings->createValue("coverModeration", 1); //Default on
    unset($settings);

    // for 5.3

    $update->updateUsersTable1();

    $settings = new settings($dbo);
    $settings->createValue("defaultAllowMessages", 0); //Default off
    unset($settings);

    // Add standard feelings

    $feelings = new feelings($dbo);

    if ($feelings->db_getMaxId() < 1) {

        for ($i = 1; $i <= 12; $i++) {

            $feelings->db_add(APP_URL."/feelings/".$i.".png");

        }
    }

    // Add standard stickers

    $stickers = new sticker($dbo);

    if ($stickers->db_getMaxId() < 1) {

        for ($i = 1; $i < 28; $i++) {

            $stickers->db_add(APP_URL."/stickers/".$i.".png");
        }
    }

    unset($stickers);

    $css_files = array("main.css", "my.css");
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");
?>

<body class="remind-page has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <div class="success-container" style="margin-top: 15px;">
                        <ul>
                            <b>Success!</b>
                            <br>
                            Your MySQL version:
                                <?php

                                    if (function_exists('mysql_get_client_info')) {

                                        print mysql_get_client_info();

                                    } else {

                                        echo $dbo->query('select version()')->fetchColumn();
                                    }
                                ?>
                            <br>
                            Database refactoring success!
                        </ul>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body>
</html>