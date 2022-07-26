<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';

    $serial_no = GetLoginSerial();
    if ($serial_no == "") {
        $serial_no = str_replace("-", "", trim($_POST["serial_key"]));
        if ($serial_no != "") {
            WriteRequestedURL();
            header("location: /sorikuranet_callup.php?mode=drm&serial_key=".$serial_no);
            exit;
        }
        CheckIntendedProduct("all");
    }
?>