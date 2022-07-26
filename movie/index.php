<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';
    require_once '../lib/contents_list.php';

    // ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
        // common_files/webserver_flg.phpから値をとる。
        global $WEBSERVER_FLG;
    // ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>

    // ログインチェック
    CheckIntendedProduct("all");	// ソリクラ会員かどうかを見ます

    // AWSの環境
    // if ($WEBSERVER_FLG == 0) {
    //     CheckIntendedProduct("1015"); // 農業簿記11のみ
    // }
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="所得税確定申告システム ダウンロード" />
        <title>動画で解説！農業簿記｜そり蔵ネット</title>
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
        <style>
            #output-list dl {  padding:0 0 0.2em 1em;  text-indent:-1em; margin:0; }*/
            dl.output { margin:0; }
            dt.output { padding:0 0 0.3em 1em;  text-indent:-1em; margin:0; }
        </style>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
    </head>
    <body id="logout">
        <?php require_once '../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/" onfocus="this.blur()">動画で解説！農業簿記<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="support">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/support.php" onfocus="this.blur()">↑製品サポート</a></p>
                        <h2><span class="text-red">ソリマチクラブ会員様限定！</span>動画で解説！農業簿記</h2><br>
                    </hgroup><br>
                    <h3>農業簿記の設定方法や使い方など、操作方法を動画でわかりやすく解説いたします！</h3>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="textlayout">
                        <tr>
                            <th width="40%"><a href="https://youtu.be/fkr6b5TGWJc" target="_blank"><img src="images/movie_201225_01.jpg" alt="【農業簿記11研修動画】 決算処理の概要" width="100%"></a></th>
                            <td width="60%"><b><a href="https://youtu.be/fkr6b5TGWJc" target="_blank">【農業簿記11研修動画】 決算処理の概要</a></b><br>[2020/12/24公開]<br>農業簿記11を使った決算処理について解説いたします。
                            </td>
                        </tr>
                        <tr>
                            <th width="40%"><a href="https://youtu.be/K1ArvOsnjgo" target="_blank"><img src="images/movie_201229_01.jpg" alt="【農業簿記11研修動画】 減価償却費の仕訳作成" width="100%"></a></th>
                            <td width="60%"><b><a href="https://youtu.be/K1ArvOsnjgo" target="_blank">【農業簿記11研修動画】 減価償却費の仕訳作成</a></b><br>[2020/12/28公開]<br>農業簿記11を使った減価償却費の仕訳作成を解説いたします。
                            </td>
                        </tr>
                        <tr>
                            <th width="40%"><a href="https://youtu.be/qJdolEFe-Tg" target="_blank"><img src="images/movie_210210_01.jpg" alt="【農業簿記11研修動画】 家事関連費の仕訳作成" width="100%"></a></th>
                            <td width="60%"><b><a href="https://youtu.be/qJdolEFe-Tg" target="_blank">【農業簿記11研修動画】 家事関連費の仕訳作成</a></b><br>[2021/02/10公開]<br>農業簿記11を使った家事関連費の仕訳作成を解説いたします。
                            </td>
                        </tr>
                        <tr>
                            <th width="40%"><a href="https://www.youtube.com/watch?v=2vzBDWd4szA&feature=youtu.be" target="_blank"><img src="images/movie_210226_01.jpg" alt="【農業簿記11研修動画】 青色申告決算書入力" width="100%"></a></th>
                            <td width="60%"><b><a href="https://www.youtube.com/watch?v=2vzBDWd4szA&feature=youtu.be" target="_blank">【農業簿記11研修動画】 青色申告決算書入力</a></b><br>[2021/02/26公開]<br>農業簿記11を使った青色申告決算書の入力方法を解説いたします。
                            </td>
                        </tr>
                    </table>
                    <br>
                    <blockquote>
                        <h3>ご不明点は以下までご連絡ください！<br>ソリマチサポートセンター</h3>
                        <h4>■電話でのお問い合わせ</h4>
                        <p>0258-31-5850<br>受付時間：平日10:00〜17:00</p>
                        <h4>■FAXでのお問い合わせ</h4>
                        <p>0258-31-5651<br>24時間受付</p>
                    </blockquote>
                    </div>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/">HOME</a>&nbsp;&gt;&nbsp;<a href="/support.php">製品サポート</a>&nbsp;&gt;&nbsp;動画で解説！農業簿記</li>
                </ul>
        <?php require_once '../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
