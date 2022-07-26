<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
    require_once '../../../lib/contents_list.php';
    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] != 'manual') {
            if ($_POST["formAction"] != "DLPrg") {
                header("/");
            }
            execDownload("\download_nns_log_2014.txt", ",農業日誌V6PレベルUP版（Ver.6.70.00）,プログラム,Ver140219,", $GLOBALS['PRG_DOWNLOAD_SERVER']."levelup/nns6_70_00/1402191439/NIS6SETUP.exe", true);
        }
        execDownload("\download_nns_manual_log_2014.txt", ",農業日誌V6PレベルUP版（Ver.6.70.00）,マニュアル,Ver140219", "download_files/nns6_70_00_lu_setup_m1.pdf", true);
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
        <meta name="description" content="農業日誌V6プラス 消費税率8％対応版 ダウンロード" />
        <title>農業日誌V6プラス&nbsp;消費税率8％対応版&nbsp;ダウンロード｜そり蔵ネット</title>
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
        <script>
            <!--
            function submitFunc() {
                document.downloadProgram.submitBtn.click();
            }
            -->
        </script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
    </head>
    <body id="logout">
    <?php require_once '../../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/index.php" onfocus="this.blur()">農業日誌V6プラス&nbsp;消費税率8％対応版<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2>農業日誌V6プラス<br>消費税率8％対応版(Version 6.70.00)<br>ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="/common/images/pkg_nns06p_w316.gif" alt="農業簿記9"></a></span></dt>
                            <dd><span>こちらから農業日誌V6プラスの最新版、および追加マニュアルをダウンロードすることができます。ぜひご利用ください。</span>
                            <span class="text-bold">マニュアルをご覧になるにはAdobe&nbsp;Reader&reg;が必要です。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3 id="downloadbox">農業日誌V6プラス(Version 6.70.00)<br>ダウンロード</h3>
                    <p>消費税率8%に対応した最新版です。<br>形式：EXEファイル&nbsp;(33.1MB)　<span class="text-bold">ADSL以上の回線を推奨</span></p>
                    <p class="btn-dl01"><a href="javascript:void(0);" onclick="submitFunc(); return false;">プログラム&nbsp;ダウンロード</a></p>
                    <p>制限事項、注意事項につきましてはマニュアルをご覧ください。</p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;ダウンロード(667KB)</a></li>
                        <li><a href="<?= $AdobeReaderDL_URL ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <p class="text-bold text-s">マニュアルをご覧になるにはAdobe Reader&reg;が必要です。</p><br>
                    <p><b>「農業日誌V6プラス 消費税率8％対応版(Version 6.70.00)」のインストール後、必ずこちらのサービスパックをダウンロードし、適用してください。</b></p>
                    <ul class="links">
                        <li><a href="../../softdown/nns06p_670/index.php" onfocus="this.blur()" target="_blank">農業日誌V6プラス Version 6.70対応サービスパック</a></li>
                    </ul>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;農業日誌V6プラス&nbsp;消費税率8％対応版</li>
                </ul>
            <?php require_once '../../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
        <form action="<?= $link ?>prg" method="post" name="downloadProgram">
            <input type="hidden" name="formAction" id="formAction" value="DLPrg">
            <input type="submit" name="submitBtn" />
        </form> 
    </body>
</html>
