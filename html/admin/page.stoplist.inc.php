<?php

/*! Hani - Halo Alumni V1 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!admin::isSession()) {

        header("Location: /admin/login");
    }

    $accountInfo = array();

    if (isset($_GET['itemId'])) {

        $act = isset($_GET['act']) ? $_GET['act'] : "";
        $itemId = isset($_GET['itemId']) ? $_GET['itemId'] : 0;
        $fromUserId = isset($_GET['fromUserId']) ? $_GET['fromUserId'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;

        $itemId = helper::clearInt($itemId);
        $fromUserId = helper::clearInt($fromUserId);

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch ($act) {

                case "add": {

                    $post = new post($dbo);
                    $post->setRequestFrom($fromUserId);

                    $postInfo = $post->info($itemId);

                    $stoplist = new stoplist($dbo);

                    if (!$stoplist->isExists($postInfo['ip_addr'])) {

                        $stoplist->add($postInfo['ip_addr']);
                    }

                    unset($stoplist);

                    unset($postInfo);
                    unset($post);

                    break;
                }

                case "remove": {

                    $post = new post($dbo);
                    $post->setRequestFrom($fromUserId);

                    $postInfo = $post->info($itemId);

                    $stoplist = new stoplist($dbo);

                    if ($stoplist->isExists($postInfo['ip_addr'])) {

                        $stoplist->remove($postInfo['ip_addr']);
                    }

                    unset($stoplist);

                    unset($postInfo);
                    unset($post);

                    break;
                }

                default: {

                    break;
                }
            }
        }

        exit;
    }

    $page_id = "stoplist";