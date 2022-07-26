<?php
    require_once 'lib/common.php';
    require_once 'lib/login.php';
    require_once 'lib/contents_list.php';
    require_once 'lib/newsrelease.php';
    require_once 'lib/participate_senryu_common.php';

    // ログイン状態なので、会員用トップページへ飛びます。
    if ( GetLoginSerial() == "" && !empty($_GET["serial_key"]) ) {
        header("Location: /sorikuranet_callup.php?serial_key=".$_GET["serial_key"]);
        exit;
    }

    if (GetLoginSerial() != "") {
        // 閲覧対象となる製品を限定する場合（シリアルの上4桁、もしくは3桁で指定）
        CheckIntendedProduct("all");    // ソリクラ会員かどうか
    }
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="農業,簿記,日誌,ソリマチ,ソフト,サポート" />
<meta name="description" content="「そり蔵ネット」は、ソリマチの農業ソフトをお使いの皆様向けのポータルサイトです。ソフトの使い方はもちろん、税に関する疑問にお答えするコーナーなど、役に立つ情報をご提供してまいります。" />
<title>home｜そり蔵ネット</title>
<!-- comment for test environment -->
<!-- <script type="text/javascript">
if(location.protocol == 'http:') {
  location.replace(location.href.replace(/http:/, 'https:'));
}
</script> -->
<script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
<link rel="stylesheet" type="text/css" href="/common/css/gloval.css" media="all">
<link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all">
<link rel="stylesheet" type="text/css" href="/common/css/size.css" media="all">
<!--[if lt IE 9]>
<script type="text/javascript" src="/common/js/html5shiv-printshiv.js"></script>
<script type="text/javascript" src="/common/js/css3-mediaqueries.js"></script>
<![endif]-->
<script type="text/javascript" charset="utf-8" src="/js/dd.js"></script>
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

<body id="logout">
    <?php require_once 'lib/header_general.php'; ?>
    <article id="home" class="clearfix">
        <nav class="clearfix">
            <h1><a href="/index.php">home</a></h1>
            <?php require_once 'lib/nav_general.php'; ?>
        </nav>
        <section id="topMain">
            <div class="box-ll">
                <h2><span>元気な農業は<br>元気な人々の源です</span></h2>
            </div>
        </section>
        <section id="topNews">
            <div class="box-news">
                <h3><a href="/newsrelease/list.php">最新情報・お知らせ</a></h3>
                <ul><?php NewsReleaseTitle("top"); ?></ul>
            </div>
        </section>
        <section class="senryu-mvp community">
            <div class="box-mm">
                <!--ダブル受賞-->
                <p><a href="/participate/senryu.php" onfocus="this.blur()"><img src="/participate/images_senryu/PrizeShikishi_<?= $SenryuLastPrizeYYYYMM ?>.gif" alt="<?= $SenryuLastPrizeText ?>" width="150" height="168" border="0"></a></p>
            </div>
        </section>
<?php if (CheckUserStatusByProduct("1015") == 1 && isLoginSerialOnlineNormal("normal")) { ?>
        <section class="info service">
            <div class="box-mm">
                <h3>オンラインソリマチクラブ<br>お申し込み</h3>
                <p>製品やマニュアルをダウンロード版でご提供する保守会員サービスの新プラン、「オンラインソリマチクラブ」のお申込みはこちらから。</p>
                <ul class="boxlink">
                    <li><a href="https://mypage.sorimachi.co.jp/SCloud_A/olsc/index" onfocus="this.blur()"  target='_blank'><span>オンラインソリクラ申込</span></a></li>
                </ul>
            </div>
        </section>
<?php } ?>
        <section class="info community">
            <div class="box-mm">
                <h3>最新投稿</h3>
                <?php FreshSenryuPosting(3); ?>
                <ul class="boxlink">
                    <li><a href="/participate/senryu.php" onfocus="this.blur()"><span>そり蔵川柳</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info support">
            <div class="box-mm">
                <h3>農業簿記11Q&amp;A</h3>
                <p>「農業簿記11」に関して、よくいただくご質問と回答集です。</p>
                <ul class="boxlink">
                    <li><a href="/usersupport/faq/products_faq/nbk11/" onfocus="this.blur()" target="_blank"><span>農業簿記11&nbsp;Q&amp;A</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info support">
            <div class="box-mm">
                <h3>農業簿記10Q&amp;A</h3>
                <p>「農業簿記10」に関して、よくいただくご質問と回答集です。</p>
                <ul class="boxlink">
                    <li><a href="/usersupport/faq/products_faq/nbk10/" onfocus="this.blur()" target="_blank"><span>農業簿記10&nbsp;Q&amp;A</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info program">
            <div class="box-mm">
                <h3>各製品のアップデート</h3>
                <p>ソリマチ製品のアップデートプログラムのダウンロードができます。</p>
                <ul class="boxlink">
                    <li><a href="/usersupport/softdown/" onfocus="this.blur()"><span>製品アップデート</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info support">
            <div class="box-mm">
                <h3>最新OS対応状況</h3>
                <p>Microsoft社の最新のOSについて、ソリマチ農業製品の対応状況をお知らせいたします。</p>
                <ul class="boxlink">
                    <li><a href="/information/os_compliant.php" onfocus="this.blur()" target="_blank"><span>最新OS対応状況</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info service">
            <div class="box-mm">
                <h3>専用帳票(サプライ用品)</h3>
                <p>ソフトで使用できる専用帳票(サプライ用品)のご案内です。「ソリマチ公式オンラインショップ」またはFAXでお申し込みいただくことができます。</p>
                <ul class="boxlink">
                    <li><a href="/supply/" onfocus="this.blur()"><span>専用帳票のご案内</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info support">
            <div class="box-mm">
                <h3>教えて！森先生</h3>
                <p>税理士の森剛一先生による、経営、税務相談サービスです。</p>
                <p></p>
                <ul class="boxlink">
                    <li><a href="/drm/drm.php" onfocus="this.blur()" target="_blank"><span>教えて！森先生</span></a></li>
                </ul>
            </div>
        </section>
        <section class="info service">
            <div class="box-mm">
                <h3>MoneyLink<br>無料ダウンロード</h3>
                <p>金融機関の明細を自動で取込み、一元管理できる便利なアプリケーションです。</p>
                <ul class="boxlink">
                    <li><a href="https://www.sorimachi.co.jp/lp-moneylink/" onfocus="this.blur()" target="_blank"><span>MoneyLink</span></a></li>
                </ul>
            </div>
        </section>
    </article>
    <footer>
        <div id="linkBox" class="clearfix">
            <ul><li><a href="/index.php">HOME</a></li></ul>
            <?php require_once 'lib/footer_general.php'; ?>
    </footer>
<p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>
<?php
// index.php専用のエラーメッセージ表示
function GetTopErrorMessage($error_code, &$error_message) {
    switch ($error_code) {
        case "cu001":
            $error_message = "シリアルNo.は20桁や16桁もしくは13桁の数字を入力してください。";
            break;
        case "cu002":
            $error_message = "シリアルNo.(16桁)の入力に誤りがあります。お手元のシリアルNo.を再度ご確認ください。";
            break;
        case "cu003":
            $error_message = "入力されたシリアルNo.はログインできません。「そり蔵ネット」の閲覧対象外の製品です。";
            break;
        case "cu004":
            $error_message = "シリアルNo.がデータベースにありません。お手元のシリアルNo.を再度ご確認ください。";
            break;
        case "cu005":
            $error_message = "シリアルNo.を入力した製品は、保守契約期間がすでに終了しています。";
            break;
        case "cu006":
            $error_message = "シリアルNo.を入力した製品は、保守契約情報がありません。";
            break;
        case "cu007":
            $error_message = "シリアルNo.が確認できませんでした。原因不明のエラーです。";
            break;
        case "cu008":
            $error_message = "入力されたシリアルNo.は、保守契約が確認できないためログインできません。";
            break;
        case "dx001":
            $error_message = "サイトをご覧になる場合はログインが必要です。シリアルNo.を入力してください。";
            break;
        default:
            $error_message = "エラーが発生しました（汎用）";
            break;
    }
}
?>