<?php
    $ts = gmdate("D, d M Y H:i:s")." GMT";
    header("Expires: $ts");
    header("Last-Modified: $ts");
    header("Pragma: no-cache");
    header("Cache-Control: no-cache, must-revalidate");

    require_once "../lib/common.php";
    $serial_no = str_replace("-", "", $_GET["id"]);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <script type="text/javascript" src="/js/common.js"></script>
    </head>
    <body onload="gopagewithSN('/drm/drm.php');">
        <form name="frmSendSN" method="post" action="">
            <input name="serial_key" type="hidden" value="<?= $serial_no ?>">
        </form>
    </body>
</html>
