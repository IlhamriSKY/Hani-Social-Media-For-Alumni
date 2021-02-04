<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    $toUserId = $helper->getUserId($request[0]);
    $accountId = auth::getCurrentUserId();
    $accessToken = auth::getAccessToken();

    if (!$auth->authorize($accountId, $accessToken)) {

        exit;
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $postText = isset($_POST['postText']) ? $_POST['postText'] : '';
        $rePostId = isset($_POST['rePostId']) ? $_POST['rePostId'] : 0;
        $accessMode = isset($_POST['access_mode']) ? $_POST['access_mode'] : 0;

        $imagesArray = isset($_POST['images']) ? $_POST['images'] : array();

        $postText = helper::clearText($postText);

        $postText = preg_replace( "/[\r\n]+/", "<br>", $postText); //replace all new lines to one new line
        $postText  = preg_replace('/\s+/', ' ', $postText);        //replace all white spaces to one space

        $postText = helper::escapeText($postText);

        $rePostId = helper::clearInt($rePostId);
        $accessMode = helper::clearInt($accessMode);

        if ($accessMode > 1) {

            $accessMode = 0;
        }

        $result = array(
            "error" => true,
            "error_description" => "token"
        );

        if (auth::getAuthenticityToken() !== $token) {

            echo json_encode($result);
            exit;
        }

        $mId = $helper->getUserId($request[0]);

        $m = new profile($dbo, $mId);
        $m->setRequestFrom(auth::getCurrentUserId());

        $mInfo = $m->get();

        if ($mInfo['accountType'] == ACCOUNT_TYPE_GROUP || $mInfo['accountType'] == ACCOUNT_TYPE_PAGE) {

            $groupId = $mInfo['id'];
            $accessMode = 0;

            if ($mInfo['accountAuthor'] == $accountId) {

                $fromUserId = $mInfo['id'];

            } else {

                $fromUserId = $accountId;
            }

        } else {

            $groupId = 0;
            $fromUserId = $accountId;
        }

        $postImg = "";

        if (count($imagesArray) != 0) {

            if (strpos($imagesArray[0], APP_URL."/".POST_PHOTO_PATH) !== false) {

                $postImg = $imagesArray[0];
            }
        }

        $post = new post($dbo);
        $post->setRequestFrom($fromUserId);
        $result = $post->add($accessMode, $postText, $postImg, $rePostId, $groupId);

        if (!$result['error']) {

            if (count($imagesArray) <= POST_MAX_IMAGES_COUNT) {

                if (count($imagesArray) > 1) {

                    $postimg2 = new postimg($dbo);
                    $postimg2->setRequestFrom($fromUserId);

                    for ($i = 1; $i <= count($imagesArray) -1; $i++) {

                        if (strpos($imagesArray[$i], APP_URL."/".POST_PHOTO_PATH) !== false) {

                            $postimg2->add($result['itemId'], $imagesArray[$i], $imagesArray[$i], $imagesArray[$i]);
                        }
                    }

                    $post->setImagesCount($result['itemId'], count($imagesArray) - 1);

                    $result['post']['imagesCount'] = count($imagesArray) - 1;
                }
            }

            ob_start();

            if ($groupId == 0) {

                draw::post($result['post'], $LANG, $helper, false);

            } else {

                draw::post($result['post'], $LANG, $helper, false);
            }

            $result['html'] = ob_get_clean();

            if ($groupId == 0) {

                $profile = new profile($dbo, $fromUserId);

                $result['postsCount'] = $profile->getPostsCount();

            } else {

                $group = new group($dbo, $groupId);
                $group->setRequestFrom($accountId);

                $result['postsCount'] = $group->getPostsCount();

                unset($group);
            }
        }

        echo json_encode($result);
        exit;
    }
