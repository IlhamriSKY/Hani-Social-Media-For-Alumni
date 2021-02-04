<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

    $imgFileUrl = "";
    $videoFileUrl = "";

    $result = array(
        "error" => true
    );

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if (isset($_FILES['uploaded_file']['name'])) {

        $currentTime = time();
        $uploaded_file_ext = @pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
        $uploaded_file_ext = strtolower($uploaded_file_ext);

        if ($uploaded_file_ext !== "php") {

            if (@move_uploaded_file($_FILES['uploaded_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                $cdn = new cdn($dbo);

                $response = $cdn->uploadVideoImg(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                if ($response['error'] === false) {

                    $imgFileUrl = $response['fileUrl'];

                    $result = array(
                        "error" => false,
                        "imgFileUrl" => $imgFileUrl,
                        "videoFileUrl" => $videoFileUrl
                    );
                }

                unset($cdn);
            }
        }
    }

    if (isset($_FILES['uploaded_video_file']['name'])) {

        $currentTime = time();
        $uploaded_file_ext = @pathinfo($_FILES['uploaded_video_file']['name'], PATHINFO_EXTENSION);
        $uploaded_file_ext = strtolower($uploaded_file_ext);

        if ($uploaded_file_ext !== "php") {

            if (@move_uploaded_file($_FILES['uploaded_video_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                $cdn = new cdn($dbo);

                $response = $cdn->uploadVideo(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                if ($response['error'] === false) {

                    $videoFileUrl = $response['fileUrl'];

                    $result = array("error" => false,
                        "imgFileUrl" => $imgFileUrl,
                        "videoFileUrl" => $videoFileUrl);
                }

                unset($cdn);
            }
        }
    }

    echo json_encode($result);
    exit;
}
