<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Imagetoolbar" content="no">
<meta name="Robots" content="noindex, nofollow">
<script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
<link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
<link rel="stylesheet" type="text/css" media="screen" href="/css/base.css">
<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">
<script type="text/javascript" src="/js/dd.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<title>シリアルナンバーの調べ方｜そり蔵ネット</title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>

<body id="general">
    <div id="oldHeader">
        <h1>ユーザー登録内容変更</h1>
        <p>ユーザー登録情報の変更はこちらから</p>
    </div>
    <div id="oldWrapper"><center>
        <table width="640" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
            <tr>
                <td width="300" valign="top">
                    <div style="background-color:#19B502; padding:5px; color:#FFFFFF; font:bold 92%/120% sans-serif;">「農業簿記」「漁業簿記」の場合</div>
                    <div style="margin:3px 0;"><a href="images/check_serial_nbk08.jpg"><img src="images/check_serial_nbk08.jpg" width="298" border="1"></a></div>
                    <div style="text-align:right; font:75%/120% sans-serif;">[ <a href="images/check_serial_nbk08.jpg">画像を拡大する</a> ]</div>
                    <div style="color:#606060; font:bold 92%/150% sans-serif;"><font style="color:#19B502; font-size:80%;">◆</font>操作方法</div>
                    <div style="margin-top:0.3em; color:#404040; font:92%/130% sans-serif; padding-left:2.0em; text-indent:-1.5em;">１．ダイレクトメニューを表示し、製品ロゴ（農業簿記8など）をクリックしてください。</div>
                    <div style="margin-top:0.3em; color:#404040; font:92%/130% sans-serif; padding-left:2.0em; text-indent:-1.5em;">２．表示された13桁の数字（赤枠）がシリアルNo.です。数字の部分だけを枠内に入力してログインしてください。</div>
                </td>
                <td nowrap width="40"></td>
                <td width="300" valign="top">
                    <div style="background-color:#19B502; padding:5px; color:#FFFFFF; font:bold 92%/120% sans-serif;">「農業日誌」の場合</div>
                    <div style="margin:3px 0;"><a href="images/check_serial_nns06p.jpg"><img src="images/check_serial_nns06p.jpg" width="298" border="1"></a></div>
                    <div style="text-align:right; font:75%/120% sans-serif;">[ <a href="images/check_serial_nns06p.jpg">画像を拡大する</a> ]</div>
                    <div style="color:#606060; font:bold 92%/150% sans-serif;"><font style="color:#19B502; font-size:80%;">◆</font>操作方法</div>
                    <div style="margin-top:0.3em; color:#404040; font:92%/130% sans-serif; padding-left:2.0em; text-indent:-1.5em;">１．[ヘルプ]メニューから[バージョン情報]を選択してください。</div>
                    <div style="margin-top:0.3em; color:#404040; font:92%/130% sans-serif; padding-left:2.0em; text-indent:-1.5em;">２．表示された13桁の数字（赤枠）がシリアルNo.です。この数字を枠内に入力してログインしてください。</div>
                </td>
            </tr>
        </table>
    </center></div>
    <div id="oldFooter">
        <ul>
            <li><a href="<?= $SORIZO_HOME ?>" onfocus="this.blur()" target="_blank">そり蔵ネット トップ</a></li>
            <li><a href="<?= $SORIMACHI_HOME ?>" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
        </ul>
        <p>Copyright&copy;&nbsp;Sorimachi&nbsp;Co.,Ltd.&nbsp;All&nbsp;rights&nbsp;reserved.</p>
    </div>
    <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p>
<?php require_once '../lib/gajs.php'; ?>
</body>
</html>