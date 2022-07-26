<?php

    require_once 'lib/common.php';
    require_once 'lib/login.php';
    require_once 'lib/contents_list.php';

?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta name="robots" content="noindex,nofollow">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<title>エラーが発生しました｜そり蔵ネット</title>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="/common/js/html5shiv-printshiv.js"></script>
<script type="text/javascript" src="/common/js/css3-mediaqueries.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/dd.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/bbct/aes.js"></script>
<script type="text/javascript" src="/js/bbct/aesprng.js"></script>
<script type="text/javascript" src="/js/bbct/armour.js"></script>
<script type="text/javascript" src="/js/bbct/entropy.js"></script>
<script type="text/javascript" src="/js/bbct/lecuyer.js"></script>
<script type="text/javascript" src="/js/bbct/md5.js"></script>
<script type="text/javascript" src="/js/bbct/scramble.js"></script>
<script type="text/javascript" src="/js/bbct/stegodict.js"></script>
<script type="text/javascript" src="/js/bbct/utf-8.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>
<?php

    $error_code = @$_GET["err"];
    $error_message = "";
    if ($error_code != "") {
        GetErrorMessage($error_code, $error_message);
    }

    // エラーメッセージの取得
    function GetErrorMessage($error_code, &$error_message) {
        switch ($error_code) {
            case "cr001":
                $error_message = "このコンテンツをご覧になる場合はログインが必要です。";
                break;
            case "cr002":
                $error_message = "ソリマチクラブの契約期間を確認するため、再度ログインしてください。";
                break;
            case "cr003":
                $error_message = "このコンテンツをご覧になる場合はログインが必要です。";
                break;
            default:
                $error_message = "エラーが発生しました";
        }
    }
?>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="480" style="margin:100px auto 0;">
    <tr>
        <td width="130" align="left" valign="top">
            <img src="/images/alert_machiko.gif">
        </td>
        <td width="350" align="left" valign="bottom">
            <div style="color:#A31D1D; margin-bottom:0.5ex; font:bold 100%/130% Meiryo,メイリオ,sans-serif;">
            <?= $error_message ?>
            </div>
            <div style="color:#808080; margin-bottom:1em; font:normal 80%/130% Meiryo,メイリオ,sans-serif;">
            （エラーコード：<?= $error_code ?> ）
            </div>
            <div style="margin-bottom:1em; font:normal 90%/130% Meiryo,メイリオ,sans-serif;">
                <form name="serial_login" method="post" action="sorikuranet_callup.php">
                シリアルNo.を入力して、ログインしてください。
                    <br>
                    <input type="text" name="serial_key" id="serial_key" style="width:200px;"><br>
                    <input type="submit" value="ログイン">
                </form>
            </div>
        </td>
    </tr>
</table>
</body>
</html>