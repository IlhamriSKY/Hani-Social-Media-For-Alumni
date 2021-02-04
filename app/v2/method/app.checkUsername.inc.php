<?php
/*! Hani Halo Alumni v1  */
if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!empty($_POST)) {

    $username = isset($_POST['username']) ? $_POST['username'] : '';

    $username = helper::clearText($username);

    $result = array("error" => true);

    if (!$helper->isLoginExists($username)) {

        $result = array("error" => false);
    }

    echo json_encode($result);
    exit;
}
