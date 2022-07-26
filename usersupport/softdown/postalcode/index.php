<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
//    require_once '/lib/get_csv.php';
    require_once '../../../lib/contents_list.php';
    CheckIntendedProduct("1021"); // 農業日誌V6プラス
    $PCSPVersion = "平成23年11月30日";
    $PCSPFilename = "NS6PPCUP03211102.exe";

// 2020/03/03 t.maruyama 修正 ↓↓ 一時的に削除
    $temp    = explode("/", $_SERVER["SCRIPT_NAME"]);
    $curDir  = $temp[count($temp) - 2];
// 2020/03/03 t.maruyama 修正 ↑↑ 一時的に削除
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="モバイル作業日誌のご案内" />
        <title>農業日誌V6プラス専用郵便番号辞書&nbsp;ダウンロード｜そり蔵ネット</title>
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
    </head>
    <body id="logout">
        <?php require_once '../../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/" onfocus="this.blur()">農業日誌V6プラス専用&nbsp;郵便番号辞書<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2><span>農業日誌V6プラス専用</span>郵便番号辞書<span>（<?= $PCSPVersion ?>現在）</span></h2><br>
                        <dl class="clearfix">
                            <dt><span><img src="/common/images/pkg_nns06p_w316.gif" alt="農業日誌V6プラス専用郵便番号辞書"></span></dt>
                            <dd><span>こちらから、「農業日誌V6プラス」の販売管理で利用する郵便番号辞書のサービスパックをダウンロードすることができます。ぜひご利用ください。</span><span class="text-bold">郵便番号辞書をお客さまが編集されている場合、こちらのサービスパックをインストールすると編集内容がすべて破棄されてしまいます。ご注意ください。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3>郵便番号辞書&nbsp;サービスパック&nbsp;ダウンロード</h3>
                    <p><?= $PCSPVersion ?>版<br>プログラムファイル[EXE]（2.1MB）</p><br>
                    <p class="btn-dl01"><a href="/usersupport/softdown/core/download.php?dir=<?= $curDir ?>" target="_blank">ダウンロード</a></p>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;農業日誌V6プラス専用郵便番号辞書&nbsp;ダウンロード</li>
                </ul>
            <?php require_once '../../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>

