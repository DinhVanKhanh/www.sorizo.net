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
<title>ログインできない｜そり蔵ネット</title>
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
                <td width="640" valign="top">
                    <div style="background-color:#19B502; padding:5px; color:#FFFFFF; font:bold 92%/120% sans-serif;">１．ソリマチクラブへのご加入が必要です</div>
                    <div style="margin:0.3em 0 3em 0; color:#404040; font:92%/130% sans-serif; padding-left:0em; text-indent:0em;">「そり蔵ネット」のすべてのコンテンツをご利用いただくには、<b>ソリマチクラブへのご加入</b>が必要です。また、ご契約が終了となっている場合もご利用いただくことができません。<br>ソリマチクラブのお申し込み方法については、製品（ソフト）パッケージに同梱されている小冊子『ソリマチクラブのご案内』、または [ <a href="http://www.sorimachi.co.jp/usersupport_af/srclub/" target="_blank"><b>こちらのホームページ</b></a> ] をご覧ください。</div>
                    <div style="background-color:#19B502; padding:5px; color:#FFFFFF; font:bold 92%/120% sans-serif;">２．シリアルNo.の入力内容をご確認ください</div>
                    <div style="margin:0.3em 0 1em 0; color:#404040; font:92%/130% sans-serif; padding-left:0em; text-indent:0em;">ログインしていただく場合のシリアルNo.は、数字13桁となっています。間にハイフン（-）などの区切りを入れないようにご注意ください。<br><font color="#FF3300"><b>【正しい例】</b> <font style="font:17px verdana,sans-serif;">1111222233333</font></font><br>
                    <b>【正しくない例】</b> <font style="font:15px verdana,sans-serif;">1111-2222-333</font> <font style="color:#FF3300; font-size:80%;">←ハイフン（-）などの区切り文字を除いてください</font></div>
                    <div style="margin:0.3em 0 3em 0; color:#404040; font:92%/130% sans-serif; padding-left:0em; text-indent:0em;">シリアルNo.が正しいものかどうかを、ソフトでご確認いただけます。<br>シリアルNo.を調べる方法は [ <a href="check_serial.php"><b>こちら</b></a> ]</div>
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