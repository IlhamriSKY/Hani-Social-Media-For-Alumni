<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

class imglib extends db_connect
{
    private $profile_id = 0;
    private $request_from = 0;

    public function __construct($dbo = NULL, $profile_id = 0)
    {
        parent::__construct($dbo);
    }

    public function isImageFile($filename, $png = true, $gif = true)
    {
        $imagefile = true;

        $whitelist_type = array('image/jpeg');

        if ($png) {

            $whitelist_type[] = 'image/png';
        }

        if ($gif) {

            $whitelist_type[] = 'image/gif';
        }

        if (function_exists('finfo_open')) {

            $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

            if (!$fileinfo) {

                $imagefile  = false; // Uploaded file is not a valid image

            } else {

                if (!in_array(finfo_file($fileinfo, $filename), $whitelist_type)) {

                    $imagefile  = false; // Uploaded file is not a valid image
                }
            }

        } else {

            //@ - for hide warning when image not valid

            if (!@getimagesize($filename)) {

                $imagefile  = false; // Uploaded file is not a valid image
            }
        }

        return $imagefile;
    }

    public function checkImg($img_filename, $min_width = 99, $min_height = 99)
    {
        $result = false;

        if (file_exists($img_filename)) {

            list($w, $h, $type) = getimagesize($img_filename);

            if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {

                if ($w > $min_width && $h > $min_height) {

                    $result = true;
                }
            }
        }

        return $result;
    }

    public function createPostImg($imgFilename, $addon)
    {
        $result = array("error" => true);

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(10);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "img_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (copy($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

                $this->img_resize($imgFilename, TEMP_PATH . $imgOrigin, 800, 0);

                $result['error'] = false;

            } else if ($type == IMAGETYPE_GIF) {

                $result['error'] = false;

            } else {

                $result['error'] = true;
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadPostImg(TEMP_PATH.$imgOrigin);

            if ($response['error'] === false) {

                $result['imgUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
        }

        return $result;
    }

    public function createChatImg($imgFilename, $addon)
    {
        $result = array("error" => true);

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(10);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "img_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (copy($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

                $this->img_resize($imgFilename, TEMP_PATH . $imgOrigin, 800, 0);

                $result['error'] = false;

            } else if ($type == IMAGETYPE_GIF) {

                $result['error'] = false;

            } else {

                $result['error'] = true;
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadChatImg(TEMP_PATH.$imgOrigin);

            if ($response['error'] === false) {

                $result['imgUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
        }

        return $result;
    }

    public function createMarketItemImg($imgFilename, $addon)
    {
        $result = array("error" => true,
                        "error_type" => "Unknown");

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(10);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "img_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (copy($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

                $this->img_resize($imgFilename, TEMP_PATH.$imgOrigin, 800, 0);

                $result['error'] = false;

            } else if ($type == IMAGETYPE_GIF) {

                $result['error'] = true;
                $result['error_type'] = "Gif";

            } else {

                $result['error'] = true;
                $result['error_type'] = "Unknown";
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadMarketImg(TEMP_PATH.$imgOrigin);

            if ($response['error'] === false) {

                $result['imgUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
        }

        return $result;
    }

    public function createBukuAlumniItemImg($imgFilename, $addon)
    {
        $result = array("error" => true,
                        "error_type" => "Unknown");

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(10);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "img_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (copy($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG || $type == IMAGETYPE_PNG) {

                $this->img_resize($imgFilename, TEMP_PATH.$imgOrigin, 800, 0);

                $result['error'] = false;

            } else if ($type == IMAGETYPE_GIF) {

                $result['error'] = true;
                $result['error_type'] = "Gif";

            } else {

                $result['error'] = true;
                $result['error_type'] = "Unknown";
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadBukuAlumniImg(TEMP_PATH.$imgOrigin);

            if ($response['error'] === false) {

                $result['imgUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
        }

        return $result;
    }


    public function newProfileCover($imgFilename)
    {
        $result = array("error" => true);

        $imgFilename_ext = pathinfo($imgFilename, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(10);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgNormal = "normal_c_".$imgNewName.".".$imgFilename_ext;

        if (rename($imgFilename, TEMP_PATH.$imgNormal)) {

            $imgFilename = TEMP_PATH.$imgNormal;
        }

        $cdn = new cdn($this->db);

        $response = $cdn->uploadCover($imgFilename);

        if (!$response['error']) {

            $result['error'] = false;
            $result['normalCoverUrl'] = $response['fileUrl'];
            $result['originCoverUrl'] = $response['fileUrl'];
        }

        @unlink($imgFilename);

        return $result;
    }

    public function newProfilePhoto($imgFilename)
    {
        $result = array(
            "error" => true
        );

        $imgFilename_ext = pathinfo($imgFilename, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(7);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgNormal = "normal_".$imgNewName.".".$imgFilename_ext;
        $imgThumbLow = "thumb_low_".$imgNewName.".".$imgFilename_ext;
        $imgThumbBig = "thumb_big_".$imgNewName.".".$imgFilename_ext;

        if (rename($imgFilename, TEMP_PATH.$imgNormal)) {

            $imgFilename = TEMP_PATH.$imgNormal;
        }

        list($w, $h, $type) = getimagesize($imgFilename);

        if ($type == IMAGETYPE_JPEG) {

            $photo = new photo($this->db, $imgFilename, 512);
            imagejpeg($photo->getImgData(), TEMP_PATH.$imgThumbBig, 100);
            unset($photo);

            $photo = new photo($this->db, $imgFilename, 256);
            imagejpeg($photo->getImgData(), TEMP_PATH.$imgThumbLow, 100);
            unset($photo);

            $result['error'] = false;

        } elseif ($type == IMAGETYPE_PNG) {

            //PNG

            $photo = new photo($this->db, $imgFilename, 512);
            imagepng($photo->getImgData(), TEMP_PATH.$imgThumbBig);
            unset($photo);

            $photo = new photo($this->db, $imgFilename, 256);
            imagepng($photo->getImgData(), TEMP_PATH.$imgThumbLow);
            unset($photo);

            $result['error'] = false;

        } else {

            $result['error'] = true;
        }

        if (!$result['error']) {

            $cdn = new cdn($this->db);

            $response = $cdn->uploadPhoto($imgFilename);

            if ($response['error'] === false) {

                $result['normalPhotoUrl'] = $response['fileUrl'];
                $result['originPhotoUrl'] = $response['fileUrl'];
            }

            $response = $cdn->uploadPhoto(TEMP_PATH.$imgThumbBig);

            if ($response['error'] === false) {

                $result['bigPhotoUrl'] = $response['fileUrl'];
            }

            $response = $cdn->uploadPhoto(TEMP_PATH.$imgThumbLow);

            if ($response['error'] === false) {

                $result['lowPhotoUrl'] = $response['fileUrl'];
            }
        }

        return $result;
    }

    public function createPhoto($imgFilename, $addon)
    {
        $result = array("error" => true);

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(7);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "origin_".$imgNewName.".".$imgFilename_ext;
        $imgNormal = "normal_".$imgNewName.".".$imgFilename_ext;
        $imgThumbLow = "thumb_low_".$imgNewName.".".$imgFilename_ext;
        $imgThumbBig = "thumb_big_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (rename($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG) {

                $this->img_resize($imgFilename, TEMP_PATH.$imgNormal, 800, 0);

                $photo = new photo($this->db, $imgFilename, 512);
                imagejpeg($photo->getImgData(), TEMP_PATH.$imgThumbBig, 100);
                unset($photo);

                $photo = new photo($this->db, $imgFilename, 256);
                imagejpeg($photo->getImgData(), TEMP_PATH.$imgThumbLow, 100);
                unset($photo);

                $result['error'] = false;

            } elseif ($type == IMAGETYPE_PNG) {

                //PNG

                $this->img_resize($imgFilename, TEMP_PATH.$imgNormal, 800, 0);

                $photo = new photo($this->db, $imgFilename, 512);
                imagepng($photo->getImgData(), TEMP_PATH.$imgThumbBig);
                unset($photo);

                $photo = new photo($this->db, $imgFilename, 256);
                imagepng($photo->getImgData(), TEMP_PATH.$imgThumbLow);
                unset($photo);

                $result['error'] = false;

            } else {

                $result['error'] = true;
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadPhoto(TEMP_PATH.$imgNormal);

            if ($response['error'] === false) {

                $result['normalPhotoUrl'] = $response['fileUrl'];
                $result['originPhotoUrl'] = $response['fileUrl'];
            }

            $response = $cdn->uploadPhoto(TEMP_PATH.$imgThumbBig);

            if ($response['error'] === false) {

                $result['bigPhotoUrl'] = $response['fileUrl'];
            }

            $response = $cdn->uploadPhoto(TEMP_PATH.$imgThumbLow);

            if ($response['error'] === false) {

                $result['lowPhotoUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
            @unlink(TEMP_PATH.$imgNormal);
            @unlink(TEMP_PATH.$imgThumbBig);
            @unlink(TEMP_PATH.$imgThumbLow);
        }

        return $result;
    }

    public function createGalleryImage($imgFilename, $addon)
    {
        $result = array("error" => true);

        $imgFilename_ext = pathinfo($addon, PATHINFO_EXTENSION);
        $imgFilename_ext = strtolower($imgFilename_ext);

        $imgNewName = helper::generateHash(7);

        if ($imgFilename_ext !== "png") {

            if ($imgFilename_ext !== "jpg") {

                return $result;
            }
        }

        $imgOrigin = "origin_".$imgNewName.".".$imgFilename_ext;
        $imgNormal = "normal_".$imgNewName.".".$imgFilename_ext;
        $imgPreview = "preview_".$imgNewName.".".$imgFilename_ext;

        if ($this->checkImg($imgFilename)) {

            list($w, $h, $type) = getimagesize($imgFilename);

            if (rename($imgFilename, TEMP_PATH.$imgOrigin)) {

                $imgFilename = TEMP_PATH.$imgOrigin;
            }

            if ($type == IMAGETYPE_JPEG) {

                $this->img_resize($imgFilename, TEMP_PATH.$imgNormal, 800, 0);

                $photo = new photo($this->db, $imgFilename, 512);
                imagejpeg($photo->getImgData(), TEMP_PATH.$imgPreview, 100);
                unset($photo);

                $result['error'] = false;

            } elseif ($type == IMAGETYPE_PNG) {

                //PNG

                $this->img_resize($imgFilename, TEMP_PATH.$imgNormal, 800, 0);

                $photo = new photo($this->db, $imgFilename, 512);
                imagepng($photo->getImgData(), TEMP_PATH.$imgPreview);
                unset($photo);

                $result['error'] = false;

            } else {

                $result['error'] = true;
            }
        }

        if ($result['error'] === false) {

            $cdn = new cdn($this->db);

            $response = array();

            $response = $cdn->uploadGalleryImage(TEMP_PATH.$imgNormal);

            if ($response['error'] === false) {

                $result['normalPhotoUrl'] = $response['fileUrl'];
                $result['originPhotoUrl'] = $response['fileUrl'];
            }

            $response = $cdn->uploadGalleryImage(TEMP_PATH.$imgPreview);

            if ($response['error'] === false) {

                $result['previewPhotoUrl'] = $response['fileUrl'];
            }

            @unlink(TEMP_PATH.$imgOrigin);
            @unlink(TEMP_PATH.$imgNormal);
            @unlink(TEMP_PATH.$imgPreview);
        }

        return $result;
    }

    /***********************************************************************************
    Функция img_resize(): генерация thumbnails
    Параметры:
    $src             - имя исходного файла
    $dest            - имя генерируемого файла
    $width, $height  - ширина и высота генерируемого изображения, в пикселях
    Необязательные параметры:
    $rgb             - цвет фона, по умолчанию - белый
    $quality         - качество генерируемого JPEG, по умолчанию - максимальное (100)
     ***********************************************************************************/
    public function img_resize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
    {

        if (!file_exists($src)) return false;

        $size = getimagesize($src);

        if ($size === false) return false;

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = 'imagecreatefrom'.$format;

        if (!function_exists($icfunc)) return false;

        $x_ratio = $width  / $size[0];
        $y_ratio = $height / $size[1];

        if ($height == 0) {

            $y_ratio = $x_ratio;
            $height  = $y_ratio * $size[1];

        } elseif ($width == 0) {

            $x_ratio = $y_ratio;
            $width   = $x_ratio * $size[0];
        }

        $ratio       = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);

        $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
        $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width)   / 2);
        $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

        // если не нужно увеличивать маленькую картинку до указанного размера
        if ($size[0] < $new_width && $size[1] < $new_height) {

            $width = $new_width = $size[0];
            $height = $new_height = $size[1];
        }

        $isrc  = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);

        $i = strrpos($dest,'.');
        if (!$i) return '';
        $l = strlen($dest) - $i;
        $ext = substr($dest,$i+1,$l);

        switch ($ext) {

            case 'jpeg':
            case 'jpg':
                imagejpeg($idest, $dest, $quality);
                break;
            case 'gif':
                imagegif($idest, $dest);
                break;
            case 'png':
                imagepng($idest, $dest);
                break;
        }

        imagedestroy($isrc);
        imagedestroy($idest);

        return true;
    }
}
