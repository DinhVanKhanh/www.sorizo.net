<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
    require_once '../../../lib/contents_list.php';

    // ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
        // common_files/webserver_flg.phpから値をとる。
        global $WEBSERVER_FLG;
    // ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>

    // AWSの環境
    if ($WEBSERVER_FLG == 0) {
        CheckIntendedProduct("1015"); // 農業簿記11のみ
    }

    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_2020.txt", ",農業簿記11_令和2年_年末レベルアップ版（Ver11.02.00）,マニュアル,Ver201221", "download_files/bk11_02_00_lu_setup_m1.pdf", true);
        }
        execDownload("\download_log_2020.txt", ",農業簿記11_令和2年_年末レベルアップ版（Ver11.02.00）,プログラム,Ver201221,", $PRG_DOWNLOAD_SERVER_AWS."lvup/nbk11_02_00/BK11LU_R02SETUP.exe", true);
    }
    $folderRoot  = mb_substr(dirname(__DIR__), mb_strrpos(dirname(__DIR__), '/') + 1);
    $folderChild = mb_substr(dirname(__FILE__), mb_strrpos(dirname(__FILE__), '/') + 1);
    $link = "/usersupport/{$folderRoot}/{$folderChild}/index.php?option=";
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="農業簿記11 令和2年 年末レベルアップ版 ダウンロード" />
        <title>農業簿記11&nbsp;令和2年 年末レベルアップ版&nbsp;ダウンロード｜そり蔵ネット</title>
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
                <h1><a href="/index.php" onfocus="this.blur()">農業簿記11&nbsp;令和2年 年末レベルアップ版<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2>農業簿記11<br>令和2年 年末レベルアップ版<br>(Ver.11.02.00)&nbsp;ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt>
                                <span>
                                    <a href="#downloadbox" onfocus="this.blur()"><img src="/common/images/pkg_nbk11_w316.png" alt="農業簿記"></a>
                                </span>
                            </dt>
                            <dd>
                                <span>
                                    「農業簿記11 令和2年 年末レベルアップ版(Ver.11.02.00)」のインストール方法、レベルアップ内容については [ <a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">マニュアル（PDF）</a> ] をご覧ください。
                                    </br>
                                    マニュアルをご覧になるにはAdobe&nbsp;Reader&reg;が必要です。
                                </span>
                                </br>
                                <!-- <div style="<?= showContents("online") ?>"> -->
                                    <span class="text-bold" style="color: #ee0000;">【オンラインソリマチクラブ会員の皆様へ】</span>
                                    <span>
                                        農業簿記11 V11.01.00のオンラインソリマチクラブ会員様は、SP1302102（2020年12月17日提供開始）以降のサービスパックを適用し、農業簿記を起動させると自動インストールで農業簿記11 令和2年 年末レベルアップ版に切り替わります。
                                        </br>
                                        すでに切り替えがお済みのオンラインソリマチクラブ会員様は、本プログラムをダウンロードする必要はございません。
                                        </br>
                                        &nbsp;→&nbsp;オンラインソリマチクラブのご案内は<a href="#toku_info">こちら</a>
                                    </span>
                                <!-- </div> -->
                            </dd>
                       </dl>
                    </hgroup><br>
                    <h3>レベルアップ項目（Ver.11.02.00 での対応）</h3>
                    <ul class="square">
                        <li><b>青色申告特別控除額55万円に対応</b><br>
                            ・青色申告特別控除額55万円に対応した申告書などの作成・印刷に対応
                        </li>
                        <li><b>青色申告決算書印刷・収支内訳書印刷機能の改善</b><br>
                            ・[青色申告決算書]などで不動産用決算書を印刷する際に別紙も出力できるように改善<br>
                            ・令和2年以降の会計期間を印刷する際、帳票タイプをA4よこ(モノクロ)のみに変更<br>
                            ・会計年度ごとの青色申告決算書、収支内訳書の様式に対応できるよう改善
                        </li>
                        <li><b>印刷範囲と部数の改善</b><br>
                            ・印刷する際に印刷する範囲と印刷部数の設定ができるように改善<br>
                        </li>
                        <li><b>消費税申告書作成が最新の税制改正に対応</b><br>
                            ・令和2年での消費税改正に対応した申告書などの作成・印刷ができるように改善
                        </li>
                        <li><b>オプション製品の自動ダウンロード対応</b><br>
                            ・オプション製品の最新版を自動的にダウンロードできるよう改善
                        </li>
                        <li><b>データ保存(バックアップ)機能の改善</b><br>
                            ・｢全データ一括保存｣をする際に安心データバンクにも保存できるよう改善
                        </li>
                        <li><b>仕訳データ受入の改善</b><br>
                            ・Excelなどで作成したCSV形式の仕訳データを受け入れられるように改善
                        </li>
                        <li><b>仕訳作成機能の改善</b><br>
                            ・[減価償却費仕訳作成]、[育成費用仕訳作成]などで仕訳を再作成する際の動作を改善
                        <li><b>プルダウンリストの表示を改善</b><br>
                            ・｢指定なし｣の項目が存在しないプルダウンリストでのフォーカス表示を改善
                        </li>
                    </ul>
                    <blockquote>
                        <h3>本製品のサポートについて</h3>
                        <p>本ソフトウェアの操作方法に関するお問い合わせにつきましては、お電話およびFAXにて受け付けております。</p>
                        <h4>農業サポートセンター</h4>
                        <p>TEL：0258-31-5850　FAX：0258-31-5651<br>受付時間　10:00～12:00／13:00～17:00<br>[&nbsp;土・日・祝祭日・年末年始を除く&nbsp;]</p>
                        <p class="text-bold text-s">お問い合わせの際は、シリアルナンバーの確認をさせていただいておりますのでご協力をお願いいたします。</p>
                        <p class="text-bold text-s">申告前はサポートセンターが大変混み合いますので、ご不明な点がございましたらお早めにお問い合わせくださいますようお願い申し上げます。</p>
                    </blockquote>
                    <br>
                    
                    <div id="toku_box">
                        <h3>【お得なお知らせ】</h3>
                        <p>年会費が「最大半額」になる「オンラインソリマチクラブ」をご存知ですか？</br>製品プログラムをダウンロード版でご提供する、ソリマチクラブの新制度です。</br>最新プログラムがリリースされたときには、通知でお知らせいたしますので更新忘れもなく便利です！この機会に、ぜひお申し込みください。</p>
                        <ul id="toku_info">
                            <li>お得なオンラインソリマチクラブへの切り替えはこちらから！</li>
                            <p><a href="https://mypage.sorimachi.co.jp/SCloud_A/Olsc/index" onfocus="this.blur()" target="_blank">https://mypage.sorimachi.co.jp/SCloud_A/Olsc/index</a></p>
                            <li>オンラインソリマチクラブのお申込方法はこちら</li>
                            <p><a href="https://qa.sorimachi.co.jp/hc/ja/articles/360048879912" onfocus="this.blur()" target="_blank">https://qa.sorimachi.co.jp/hc/ja/articles/360048879912</a></p>
                            <li>最新プログラムのリリース通知画面</li>
                            <div class="toku-img">
                                <img src="/usersupport/levelup/images/nbk110200/release-notification.png" width="400" border="0">
                            </div>
                        </ul>                        
                    </div>

                    <h3 id="downloadbox">農業簿記11&nbsp;令和2年 年末レベルアップ版<br>(Ver.11.02.00)&nbsp;ダウンロード</h3>
                    <p>形式：EXEファイル&nbsp;(478MB)&nbsp;<span class="text-bold">ADSL以上の回線を推奨</span></p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;(567KB)</a></li>
                        <li><a href="<?= $AdobeReaderDL_URL ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <!--
                    <p class="text-bold text-s">マニュアルをご覧になるにはAdobe Reader&reg;が必要です。</p>
                    <p style="color:#f30;"><b>【大切なお知らせ】<br />「農業簿記10 平成30年 年末レベルアップ版(Ver.10.02.00)」のインストール後、必ずこちらのサービスパックをダウンロードし、適用してください。</b></p>
                    <ul class="links">
                        <li><a href="../../softdown/nbk10_1002/index.php" onfocus="this.blur()" target="_blank">農業簿記10 平成30年 年末レベルアップ版(Ver.10.02.00)対応サービスパック</a></li>
                    </ul>
                    -->
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;農業簿記11&nbsp;令和2年 年末レベルアップ版</li>
                </ul>
            <?php require_once '../../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
