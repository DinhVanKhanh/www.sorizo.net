<?php
    include("../../../../common_files/external_websites.php");
    include("../../../../common_files/webserver_flg.php");
    include("../../../lib/get_filesize.php");
    if ($WEBSERVER_FLG == 0 ){ // 本サーバーの場合
        // TFP対応の為、処理を追加
        // ★新製品が出たら追加する必要有
        if ($_COOKIE["LoginProduct"]["serialno"] != "1015118000009287"){
            echo "このページは表示できません";
        } else {
            // デモシリアルの場合はそのままスルー
        }
    } else {
        // テストサーバーの場合はチェックしない
    }
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="robots" content="noindex,nofollow">
</head>
<body>
<!--
<b>農業簿記10 専用</b><br>
<a href="../nbk10_jahkd/">農業簿記10 JA北海道</a><br>
<a href="../nbk10_ja_ot/">接続キット 大分県版</a><br>
<a href="../nbk10_ja_hs/">接続キット 広島県版</a><br>
<a href="../nbk10_ja_ac/">接続キット 愛知県版</a><br>
<br>
-->
<b>農業簿記11 専用</b><br>
<a href="../nbk11_jahkd/">農業簿記11 JA北海道</a><br>
<a href="../nbk11_ja_ot/">接続キット 大分県版</a><br>
<a href="../nbk11_ja_hs/">接続キット 広島県版</a><br>
<a href="../nbk11_ja_ac/">接続キット 愛知県版</a><br>
<a href="../nbk11_ja_ym/">接続キット 山形県版</a><br>
</body>
