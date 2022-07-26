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
<meta name="keywords" content="農業,簿記,新着情報,サポート" />
<meta name="description" content="農業簿記11 令和2年 年末レベルアップ版ダウンロードサービス開始のお知らせ" />
<title>新着情報&nbsp;[2020/12/21]｜そり蔵ネット</title>
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
            <h1><a href="/top.asp" onfocus="this.blur()">新着情報&nbsp;[2020/12/21]<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../../lib/nav_general.php'; ?>
		</nav>
		<section id="contents" class="community">
			<div class="box-contents">
				<hgroup>
					<p><a href="/newsrelease/list.asp">↑新着情報一覧</a></p>
					<h2><span class="text-red">重要なお知らせ</span>農業簿記11<br>令和2年 年末レベルアップ版<span>ダウンロードサービス開始のお知らせ</span></h2>
				</hgroup><br>
				<p>平素は弊社ならびに弊社製品をご愛顧いただきまして、誠にありがとうございます。</p>
				<p>2020(令和2)年12月21日（月）より、「農業簿記11」をお使いのソリマチクラブ会員様向けに「令和2年 年末レベルアップ版」のダウンロードサービスを開始いたします。</p>
				<p>例年ソリマチクラブ会員様には「農業簿記」のレベルアップ版をCD-ROMにてお送りしておりますが、ダウンロードサービスをご利用いただくことで、より早い時期にレベルアップ版をお使いいただくことができます。</p>
				<p>今後とも変わらぬご愛顧を賜りますよう、よろしくお願い申し上げます。</p>
				<h4>ご注意</h4>
				<ol>
					<li>本システムを使用するためには、「農業簿記11(11.00.00)」以上の製品がインストールされているパソコンが必要です。</li>
					<li>令和2年 年末レベルアップ版はソリマチクラブ農業簿記会員様限定の提供プログラムです。</li>
					<li>所得税確定申告ソフトの最新版「みんなの確定申告〈令和2年分申告用〉」は、令和3年1月下旬の提供開始を予定しております。</li>
					<li>農業簿記11 V11.01.00のオンラインソリマチクラブ会員様は、SP1302102（2020年12月17日提供開始）以降のサービスパックを適用し、農業簿記を起動させると自動インストールで農業簿記11 令和2年 年末レベルアップ版に切り替わります。</br>既に切り替えがお済みのオンラインソリマチクラブ会員様は、本プログラムをダウンロードする必要はございません。</li>
<!--
					<li>巡回冗長エラーにより、CD-ROMから農業簿記9をインストールできないお客様はこちらのニュースリリースをご覧ください。<br><a href="/newsrelease/20140926_nbk9/index.asp">→ニュースリリース</a>
</li>
-->
				</ol><br>
				<ul class="links">
				<?php if (CheckUserStatusByProduct("1015") == 1) { ?>
					<li style="list-style-type:none;"><a href="/usersupport/levelup/nbk110200/">「農業簿記11 令和2年 年末レベルアップ版」のダウンロードはこちら</a></li>
				<?php } ?>
				</ul>
			</div>
		</section>
	</article>
	<footer>
		<div id="linkBox" class="clearfix">
			<ul>
				<li><a href="/top.asp">HOME</a>&nbsp;&gt;&nbsp;<a href="/community.asp">そり蔵公民館</a>&nbsp;&gt;&nbsp;<a href="/newsrelease/list.asp">新着情報一覧</a>&nbsp;&gt;&nbsp;新着情報&nbsp;[2020/12/21]</li>
            </ul>
            <?php require_once '../../lib/footer_general.php'; ?>
	</footer>
<p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>
