<?php

/*! Hani Halo Alumni v1  */

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    if (isset($_GET['action'])) {

        $act = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($act) {

            case "get-box": {

                ?>

                    <div class="box-body">
                        <div class="msg" style="margin-top: 0">
                            <?php echo $LANG['label-image-upload-description']; ?>
                        </div>

                        <div class="file_loader_block" style=""></div>

                        <div class="file_select_block" style="">
                            <div style="" class="file_select_btn cover_input button green"><?php echo $LANG['action-select-file-and-upload']; ?></div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="controls">
                            <button onclick="$.colorbox.close(); return false;" class="primary_btn blue"><?php echo $LANG['action-cancel']; ?></button>
                        </div>
                    </div>

                <?php

                exit;
            }

            default: {

                break;
            }
        }
    }

