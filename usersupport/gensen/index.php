<?php
    require_once '../../lib/common.php';
    require_once '../../lib/login.php';
    require_once '../../lib/contents_list.php';
    require_once '../softdown/core/download_common_agri.php';

    $folderRoot       = mb_substr(dirname(__FILE__), mb_strrpos(dirname(__FILE__), '/') + 1);
    $PrgVersion       = "20211111";
    $TargetYear       = "2021";
    $TargetYearHeisei = "3";
    $PrgModifiedDate  = "2021年11月12日";
    $PrgFileName      = "gensen".$TargetYear."setup.exe";
    $downloadExe      = $GLOBALS['PRG_DOWNLOAD_SERVER_AWS']. $folderRoot. "/". $PrgFileName;
    $downloadPdf      = "download_files/gensen_manual".$TargetYear.".pdf";
    $ManualFileSize   = "3.6MB";
    $link             = "/usersupport/{$folderRoot}/index.php?option=";

    // ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
        // common_files/webserver_flg.phpから値をとる。
        global $WEBSERVER_FLG;
    // ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>

    // AWSの環境
    if ($WEBSERVER_FLG == 0) {
        CheckIntendedProduct("1015"); // 農業簿記11のみ
    }

    // ↓↓　<2021/06/16> <YenNhi> <write log to table kakutei_download>
    	$DownloadProgramName = 21;	// 21 : 「源泉徴収票作成システム」の固有値
    	// $DownloadProgramYear = 2020;
    	$DownloadProgramYear = $TargetYear;
    	// $DownloadProgramVersion = 'Ver20201209';
   	$DownloadProgramVersion = 'Ver'.$PrgVersion;
    // ↑↑　<2021/06/16> <YenNhi> <write log to table kakutei_download>

    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_".$TargetYear.".txt", ",源泉徴収票作成システム（マニュアル）,Ver".$PrgVersion.",", $downloadPdf);
        }
    // ↓↓　<2021/06/16> <YenNhi> <write log to table kakutei_download>
        // execDownload("\download_log_".$TargetYear.".txt", ",源泉徴収票作成システム（".$TargetYear."）,プログラム,Ver".$PrgVersion.",", $PRG_DOWNLOAD_SERVER_AWS."gensen/gensen".$TargetYear."setup.exe");
       execDownloadAndSaveDB($DownloadProgramName, $DownloadProgramYear, $DownloadProgramVersion, $downloadExe);
    // ↑↑　<2021/06/16> <YenNhi> <write log to table kakutei_download>
    }
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="農業簿記 源泉徴収票作成システム(令和<?= $TargetYearHeisei ?>年版) ダウンロード" />
        <title>農業簿記11&nbsp;源泉徴収票作成システム〈令和<?= $TargetYearHeisei ?>年版〉&nbsp;ダウンロード｜そり蔵ネット</title>
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
                <h1><a href="/" onfocus="this.blur()">源泉徴収票作成システム&nbsp;ダウンロード<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="service">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2>源泉徴収票作成システム<br>令和<?= $TargetYearHeisei ?>年版&nbsp;ダウンロード<span>対象製品：農業簿記11 (Version.11.02.00以降)</span></h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="/common/images/pkg_nbk11_w316.png" alt="源泉徴収票作成システム"></a></span></dt>
                            <dd><span>令和<?= $TargetYearHeisei ?>年版の「農業簿記 源泉徴収票作成システム」です。</span></dd>
                        </dl>
                    </hgroup><br>
<!--                    <p>源泉徴収票作成システム 令和<?= $TargetYearHeisei ?>年版では、新元号「令和」に対応しています。</p>-->
                    <h3>ダウンロードとインストールについて</h3>
                    <ol>
                        <li>下表のプログラムファイルの欄にある「ダウンロード」ボタンをクリックし、「<?= $PrgFileName ?>」を任意のフォルダに保存してください。</li>
                        <li>ダウンロードした「<?= $PrgFileName ?>」をダブルクリックすると、ファイルの解凍先（初期値はC:\GensenSetup）を指定する画面が表示されますので指示に従って解凍してください。</li>
                        <li>解凍が完了すると源泉徴収票作成システムのインストールがはじまります。以降は、画面の指示に従ってインストールを行ってください。</li>
                        <li>インストール終了後、正常に動作させるためWindowsを再起動してください。</li>
                    </ol>
                    <p class="text-bold text-s">インストール後、ダウンロードした「<?= $PrgFileName ?>」と、(２)で解凍したフォルダのすべては削除して構いません。</p><br>
                    <h3 id="downloadbox">源泉徴収票作成システム<br>令和<?= $TargetYearHeisei ?>年版&nbsp;ダウンロード</h3>
                    <p>形式：EXEファイル（<?php echo getFileSizeFromURL($downloadExe) ?>）　更新日：<?= $PrgModifiedDate ?></p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg" onfocus="this.blur()" target="_blank">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル（<?= $ManualFileSize ?>）</a></li>
                        <li><a href="<?= $GLOBALS['AdobeReaderDL_URL'] ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <p class="text-bold text-red">ご使用の際には必ずPDFマニュアルをダウンロードをしてください。</p>
                    <p class="text-bold text-s">PDFマニュアルを閲覧するには、Adobe Reader&reg;が必要です。</p>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;源泉徴収票作成システム&nbsp;ダウンロード</li>
                </ul>
            <?php require_once '../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
