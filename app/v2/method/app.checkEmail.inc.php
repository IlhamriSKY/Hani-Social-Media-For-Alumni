<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $email = helper::clearText($email);
    $email = helper::escapeText($email);

    $result = array("error" => true);

    if (!$helper->isEmailExists($email)) {

        $result = array("error" => false);
    }

    echo json_encode($result);
    exit;
}
