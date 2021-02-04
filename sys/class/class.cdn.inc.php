<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class cdn extends db_connect
{
    private $ftp_url = "";
    private $ftp_server = "";
    private $ftp_user_name = "";
    private $ftp_user_pass = "";
    private $cdn_server = "";
    private $conn_id = false;

    public function __construct($dbo = NULL)
    {
        if (strlen($this->ftp_server) > 0) {

            $this->conn_id = @ftp_connect($this->ftp_server);
        }

        parent::__construct($dbo);
    }

    public function upload($file, $remote_file)
    {
        $remote_file = $this->cdn_server.$remote_file;

        if ($this->conn_id) {

            if (@ftp_login($this->conn_id, $this->ftp_user_name, $this->ftp_user_pass)) {

                // upload a file
                if (@ftp_put($this->conn_id, $remote_file, $file, FTP_BINARY)) {

                    return true;

                } else {

                    return false;
                }
            }
        }
    }

    public function uploadGalleryImage($imgFilename)
    {
        rename($imgFilename, GALLERY_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".GALLERY_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadPhoto($imgFilename)
    {
        rename($imgFilename, PHOTO_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".PHOTO_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadCover($imgFilename)
    {
        rename($imgFilename, COVER_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".COVER_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadPostImg($imgFilename)
    {
        rename($imgFilename, POST_PHOTO_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".POST_PHOTO_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadChatImg($imgFilename)
    {
        rename($imgFilename, CHAT_IMAGE_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".CHAT_IMAGE_PATH.basename($imgFilename));

        return $result;
    }

    public function uploadVideoImg($imgFilename)
    {
        rename($imgFilename, VIDEO_IMAGE_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".VIDEO_IMAGE_PATH.basename($imgFilename));

        @unlink($imgFilename);

        return $result;
    }

    public function uploadVideo($imgFilename)
    {
        rename($imgFilename, VIDEO_PATH.basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/".VIDEO_PATH.basename($imgFilename));

        @unlink($imgFilename);

        return $result;
    }

    public function uploadMarketImg($imgFilename)
    {
        rename($imgFilename, "market/".basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/market/".basename($imgFilename));

        return $result;
    }

    public function uploadBukuAlumniImg($imgFilename)
    {
        rename($imgFilename, "bukualumni/".basename($imgFilename));

        $result = array("error" => false,
                        "error_code" => ERROR_SUCCESS,
                        "fileUrl" => APP_URL."/bukualumni/".basename($imgFilename));

        return $result;
    }
}
