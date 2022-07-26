<?php 
    require_once '../../lib/common.php';
    require_once '../../lib/login.php';
    require_once '../../lib/contents_list.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="農業,簿記,年末調整,ソフト,サポート,ダウンロード" />
<meta name="description" content="そり蔵ネットはソリマチクラブ会員情報サイトです。" />
<title>新着情報&nbsp;[2020/11/13]｜そり蔵ネット</title>
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
    <?php require_once '../../lib/header_general.php'; ?>
	<article id="home" class="clearfix">
		<nav class="clearfix">
            <h1><a href="/top.asp" onfocus="this.blur()">新着情報&nbsp;[2020/11/13]<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../../lib/nav_general.php'; ?>
		</nav>
		<section id="contents" class="community">
			<div class="box-contents">
				<hgroup>
					<p><a href="/newsrelease/list.asp">↑新着情報一覧</a></p>
					<h2><span class="text-red">農業簿記をお使いの皆様へ</span>源泉徴収票作成システム<br>令和2年版<span>ダウンロードサービス開始のお知らせ<br>対象製品：農業簿記11 (Version.11.01.00以降)</span></h2>
				</hgroup><br>

				<p>平素は弊社ならびに弊社製品をご愛顧いただきまして、誠にありがとうございます。</p>
				<p>令和2年11月13日（金）より、『源泉徴収票作成システム 令和2年版』のダウンロードサービスを開始いたしました。<!--令和元度制度改正版の源泉徴収票に対応した最新版です。--></p>
				<p>専従者の支払給与など年末調整に関わるデータを入力することで、源泉徴収簿や源泉徴収票が作成できる、ソリマチクラブ会員様限定のオプションシステムです。ご提供方法につきましては「そり蔵ネット」からのダウンロードとなります。</p>
				<p>今後とも変わらぬご愛顧を賜りますよう、よろしくお願い申し上げます。</p>
                <p>※源泉徴収票作成システムのダウンロードサービスの対象者は、ソリマチクラブ会員様限定とさせていただきます。</p>

                <?php if (CheckUserStatusByProduct("1015") == 1) { ?>
                    <p><b><a href="/usersupport/gensen/">→源泉徴収票作成システム　ダウンロード</a></b></p>
                <?php } else { ?>
                    <h4>本件に関するお問い合わせ先</h4>
                    <p>ソリマチ株式会社 東京本社<br>ＴＥＬ：03-5475-5301</p>
				    <h5>受付時間</h5> 
                    <p>9:00～17:30<br>※土・日・祝日および弊社指定休日を除く</p>
                <?php } ?>


            </div>
		</section>
	</article>
	<footer>
		<div id="linkBox" class="clearfix">
			<ul>
				<li><a href="/top.asp">HOME</a>&nbsp;&gt;&nbsp;<a href="/community.asp">そり蔵公民館</a>&nbsp;&gt;&nbsp;<a href="/newsrelease/list.asp">新着情報一覧</a>&nbsp;&gt;&nbsp;新着情報&nbsp;[2020/11/13]</li>
			</ul>
            <?php require_once '../../lib/footer_general.php'; ?>
	</footer>
<p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>
