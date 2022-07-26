<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';
    require_once '../lib/contents_list.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="robots" content="noindex">
<title>役立つリンク集｜そり蔵ネット</title>
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
<body id="logout">
    <?php require_once '../lib/header_general.php'; ?>
    <article id="home" class="clearfix">
        <nav class="clearfix">
            <h1><a href="/index.php" onfocus="this.blur()">役立つリンク集<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../lib/nav_general.php'; ?>
        </nav>
        <section id="contents" class="community">
            <div class="box-contents">
                <hgroup>
                    <p><a href="/community.php">↑そり蔵公民館</a></p>
                    <h2>役立つリンク集</h2>
                    <p>農業、実務などに関するリンク集です。</p>
                </hgroup><br>
                <h3>農業リンク </h3>
                <ul class="links">
                    <li><a href="http://www.maff.go.jp" onfocus="this.blur()" target="_blank">農林水産省</a></li>
                    <li><a href="http://www.maff.go.jp/j/shokusan/index.html" onfocus="this.blur()" target="_blank">農林水産省 総合食料局（旧食糧庁）</a></li>
                    <li><a href="http://www.nca.or.jp/" onfocus="this.blur()" target="_blank">全国農業会議所</a></li>
                    <li><a href="http://www.zenchu-ja.or.jp" onfocus="this.blur()" target="_blank">全国農業協同組合中央会（ＪＡ全中）</a></li>
                    <li><a href="https://www.ek-system.ne.jp/" onfocus="this.blur()" target="_blank">全国農業改良普及支援協会</a></li>
                    <li><a href="http://www.jsai.or.jp/" onfocus="this.blur()" target="_blank">農業情報学会</a></li>
                    <li><a href="http://www.jacom.or.jp/" onfocus="this.blur()" target="_blank">農業協同組合新聞</a></li>
                    <li><a href="http://www.nougyou-shimbun.ne.jp/" onfocus="this.blur()" target="_blank">日本農業新聞</a></li>
                </ul><br>
                <h3>お役立ちリンク</h3>
                <ul class="links">
                    <li><a href="http://www.nta.go.jp/" onfocus="this.blur()" target="_blank">国税庁</a></li>
                    <li><a href="https://www.nta.go.jp/taxes/shiraberu/taxanswer/index2.htm" onfocus="this.blur()" target="_blank">タックスアンサー（税務相談）</a></li>
                    <li><a href="http://www.e-tax.nta.go.jp/" onfocus="this.blur()" target="_blank">国税電子申告・納税システム（e-Tax）</a></li>
                    <li><a href="http://www.nta.go.jp/tetsuzuki/shinkoku/shotoku/tokushu/index.htm" onfocus="this.blur()" target="_blank">e-Taxで確定申告</a></li>
                    <li><a href="http://tenki.jp/" onfocus="this.blur()" target="_blank">全国の気象情報</a></li>
                    <li><a href="http://aoki2.si.gunma-u.ac.jp/zipcode/" onfocus="this.blur()" target="_blank">７桁郵便番号検索</a></li>
                    <li><a href="http://www.teglet.co.jp/naoko/" onfocus="this.blur()" target="_blank">直子の代筆</a></li>
                    <li><a href="http://www.sorimachi.co.jp/" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
                </ul><br>
            </div>
        </section>
    </article>
    <footer>
        <div id="linkBox" class="clearfix">
            <ul>
                <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/community.php">そり蔵公民館</a>&nbsp;&gt;&nbsp;役立つリンク集</li>
            </ul>
            <?php require_once "../lib/footer_general.php"; ?>
    </footer>
<p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>