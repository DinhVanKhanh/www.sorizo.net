<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
    require_once '../../../lib/contents_list.php';
    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_2018.txt", ",農業簿記10_平成30年 年末レベルアップ版（Ver10.02.00）,マニュアル,Ver181130", "download_files/bk10_02_00_lu_setup_m1.pdf", true);
        }
// 2020/02/27 t.maruyama 修正 ↓↓ PRG_DOWNLOAD_SERVER_AWSを使うよう修正
//        execDownload("\download_log_2018.txt", ",農業簿記10_平成30年 年末レベルアップ版（Ver10.02.00）,プログラム,Ver181130,", "http://sorimachi-download.s3-ap-northeast-1.amazonaws.com/prg/lvup/nbk10_02_00/BK10LU_H30SETUP.exe", true);
        execDownload("\download_log_2018.txt", ",農業簿記10_平成30年 年末レベルアップ版（Ver10.02.00）,プログラム,Ver181130,", $PRG_DOWNLOAD_SERVER_AWS."lvup/nbk10_02_00/BK10LU_H30SETUP.exe", true);
// 2020/02/27 t.maruyama 修正 ↑↑ PRG_DOWNLOAD_SERVER_AWSを使うよう修正
    }
    $folderRoot  = mb_substr(dirname(__DIR__), mb_strrpos(dirname(__DIR__), '/') + 1);
    $folderChild = mb_substr(dirname(__FILE__), mb_strrpos(dirname(__FILE__), '/') + 1);
    $link = "/usersupport/{$folderRoot}/{$folderChild}/index.php?option=";
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="shift_jis">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="農業簿記10 平成30年 年末レベルアップ版 ダウンロード" />
        <title>農業簿記10&nbsp;平成30年 年末レベルアップ版&nbsp;ダウンロード｜そり蔵ネット</title>
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
    <?php require_once '../../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/index.php" onfocus="this.blur()">農業簿記10&nbsp;平成30年 年末レベルアップ版<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2>農業簿記10<br>平成30年 年末レベルアップ版<br>(Ver.10.02.00)&nbsp;ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="/common/images/pkg_nbk10_w316.gif" alt="農業簿記"></a></span></dt>
                            <dd><span>「農業簿記10 平成30年 年末レベルアップ版(Ver.10.02.00)」のインストール方法、レベルアップ内容については [ マニュアル（PDF） ] をご覧ください。</span>
                            <span class="text-bold">マニュアルをご覧になるにはAdobe&nbsp;Reader&reg;が必要です。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3>レベルアップ項目（Ver.10.02.00 での対応）</h3>
                    <ul class="square">
                        <li><b>収入保険制度の加入申請時に必要な計算書などを作成・印刷できるように改善</b></li>
                        <li><b>減価償却費仕訳作成機能の改善</b></li>
                        <li><b>青色申告決算書入力・収支内訳書入力機能の改善</b></li>
                        <li><b>メニューとダイレクトメニューの表示改善</b></li>
                        <li><b>製品を起動した際に自動的にアップデートチェックを行うように改善</b></li>
                        <li><b>他、お客様の声を反映した機能強化</b><br><br>詳しいレベルアップ内容や操作方法については、ページ最下部にあるマニュアルをご参照ください。</li>
                    </ul>
                    <blockquote>
                        <h3>本製品のサポートについて</h3>
                        <p>本ソフトウェアの操作方法に関するお問い合わせにつきましては、お電話およびFAXにて受け付けております。</p>
                        <h4>農業サポートセンター</h4>
                        <p>TEL：0258-31-5850　FAX：0258-31-5651<br>受付時間　10:00～12:00／13:00～17:00<br>[&nbsp;土・日・祝祭日・年末年始を除く&nbsp;]</p>
                        <p class="text-bold text-s">お問い合わせの際は、シリアルナンバーの確認をさせていただいておりますのでご協力をお願いいたします。</p>
                        <p class="text-bold text-s">申告前はサポートセンターが大変混み合いますので、ご不明な点がございましたらお早めにお問い合わせくださいますようお願い申し上げます。</p>
                    </blockquote><br>
                    <h3 id="downloadbox">農業簿記10&nbsp;平成30年 年末レベルアップ版<br>(Ver.10.02.00)&nbsp;ダウンロード</h3>
                    <p>形式：EXEファイル&nbsp;(442MB)&nbsp;<span class="text-bold">ADSL以上の回線を推奨</span></p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;(572KB)</a></li>
                        <li><a href="<?= $AdobeReaderDL_URL ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <p class="text-bold text-s">マニュアルをご覧になるにはAdobe Reader&reg;が必要です。</p>
                    <p style="color:#f30;"><b>【大切なお知らせ】<br />「農業簿記10 平成30年 年末レベルアップ版(Ver.10.02.00)」のインストール後、必ずこちらのサービスパックをダウンロードし、適用してください。</b></p>
                    <ul class="links">
                        <li><a href="../../softdown/nbk10_1002/index.php" onfocus="this.blur()" target="_blank">農業簿記10 平成30年 年末レベルアップ版(Ver.10.02.00)対応サービスパック</a></li>
                    </ul>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;農業簿記10&nbsp;平成30年 年末レベルアップ版</li>
                </ul>
            <?php require_once '../../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
