<?php
    require_once 'lib/common.php';
    require_once 'lib/login.php';
    require_once 'lib/contents_list.php';
    require_once 'lib/newsrelease.php';
    require_once 'lib/participate_senryu_common.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="農業,簿記,日誌,ソリマチ,ソフト,サポート" />
<meta name="description" content="そり蔵ネットはソリマチクラブ会員情報サイトです。" />
<title>そり蔵公民館｜そり蔵ネット</title>
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
    <?php require_once 'lib/header_general.php'; ?>
    <article id="list" class="clearfix">
        <nav class="clearfix">
            <h1><a href="/index.php" onfocus="this.blur()">そり蔵公民館<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once 'lib/nav_general.php'; ?>
        </nav>
        <section id="listMain" class="community">
            <div class="box-lm">
                <h2><span>みんな集まれ<br>そり蔵ネット公民館</span></h2>
            </div>
        </section>
<!--
        <section id="topNews" class="community">
            <div class="box-news">
                <h3><a href="/newsrelease/list.php" onfocus="this.blur()">最新情報・お知らせ</a></h3>
                <ul><?php //NewsReleaseTitle("top"); ?></ul>
            </div>
        </section>
-->
        <section class="senryu-mvp community">
            <div class="box-mm">
                <!--ダブル受賞-->
                <p><a href="/participate/senryu.php" onfocus="this.blur()"><img src="/participate/images_senryu/PrizeShikishi_<?= $SenryuLastPrizeYYYYMM ?>.gif" alt="<?= $SenryuLastPrizeText ?>" width="150" height="168" border="0"></a></p>
            </div>
        </section>
        <section class="info community">
            <div class="box-mm">
                <h3>最新投稿</h3>
                <?php FreshSenryuPosting(3); ?>
                <ul class="boxlink">
                    <li><a href="/participate/senryu.php" onfocus="this.blur()"><span>そり蔵川柳</span></a></li>
                </ul>
            </div>
        </section>
        <?php GetContentsList("category", 1); ?>
    </article>
    <footer>
        <div id="linkBox" class="clearfix">
            <ul><li><a href="/index.php">HOME</a></li></ul>
            <?php require_once 'lib/footer_general.php'; ?>
    </footer>
<p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>