<?php
    require_once '../../lib/common.php';
    require_once '../../lib/login.php';
    require_once '../../lib/contents_list.php';

    // ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
        // common_files/webserver_flg.phpから値をとる。
        global $WEBSERVER_FLG;
    // ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>

    // AWSの環境
    if ($WEBSERVER_FLG == 0) {
        CheckIntendedProduct("1015"); // 農業簿記11のみ
    }

// ↓↓　<2021/06/16> <YenNhi> <write log to table kakutei_download>
    $DownloadProgramName = 31;	// 31 : 「みんなの電子申告」の固有値
    $DownloadProgramYear = 2020;
    $DownloadProgramVersion = 'V1-14-00';
// ↑↑　<2021/06/16> <YenNhi> <write log to table kakutei_download>

    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_2020.txt",",eTax連携Op2020,マニュアル,Ver210125,","download_files/eTaxHelp2021.pdf");
        }
    // ↓↓　<2021/06/16> <YenNhi> <write log to table kakutei_download>    
        // execDownload("\download_log_etaxop_2020.txt",",eTax連携Op2020(V1-14-00),プログラム,Ver210125,",$PRG_DOWNLOAD_SERVER_AWS."etax/etaxOP2021Setup.exe");
           execDownloadAndSaveDB($DownloadProgramName, $DownloadProgramYear, $DownloadProgramVersion, $GLOBALS['PRG_DOWNLOAD_SERVER_AWS'] . "etax/etaxOP2021Setup.exe");
    // ↑↑　<2021/06/16> <YenNhi> <write log to table kakutei_download>
    }
    $folderRoot  = mb_substr(dirname(__FILE__), mb_strrpos(dirname(__FILE__), '/') + 1);
    $link = "/usersupport/{$folderRoot}/index.php?option=";
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
        <title>電子申告連携ソフト「みんなの電子申告」&nbsp;ダウンロード｜そり蔵ネット</title>
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
        <?php require_once '../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/" onfocus="this.blur()">電子申告連携ソフト&nbsp;ダウンロード<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once '../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="service">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2><span>令和2年分 e-Tax連携オプション</span>みんなの電子申告 ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="./images/logo_etax2020_w500.png" alt="みんなの電子申告"></a></span></dt>
                            <dd><span>「みんなの電子申告〈e-Tax連携オプション〉」は、「農業簿記11」および「みんなの確定申告」のデータを国税庁が提供するe-Taxソフトへ組み込むことで、よりかんたんに電子申告が行えるオプションソフトです。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3>ご利用にあたっての注意</h3>
                    <ul class="square">
                        <li>「農業簿記11」が必要です。</li>
                        <li>本システムについては、オプションソフトを提供するものであり、国税庁の「e-Tax」自体のサポートは行なっておりません。あらかじめご了承ください。</li>
                        <li>電子申告を行うためには、事前にe-Taxの開始届出書の提出などが必要です。国税電子申告・納税システム「e-Tax」及び国税庁が提供するe-Taxソフトについては、下記より[ 国税庁 e-Taxホームページ ] をご参照ください。</li>
                    </ul>
                    <p>※e-Taxソフト使用上の注意<br>
                    国税庁が提供するe-Taxソフトを使用中に「受付システムとの接続に失敗しました。」というメッセージが表示される場合は、暗号化通信の設定が影響している可能性があります。以下の国税庁のページをご参照ください。<br>　<a href="http://www.e-tax.nta.go.jp/topics/topics_ssl3.0.htm" target="_blank">→&nbsp;e-Tax及び確定申告書等作成コーナーに接続できない事象について</a></p>
                    <br>

                    <ul class="links">
                        <li><a href="https://www.e-tax.nta.go.jp/download/index.htm" onfocus="this.blur()" target="_blank"><b>e-Taxソフトの入手</b></a></li>
                        <li><a href="http://www.e-tax.nta.go.jp/" onfocus="this.blur()" target="_blank"><b>国税庁 e-Taxホームページ</b></a></li>
                    </ul><br>

                    <h3>動作環境</h3>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="textlayout">
                        <tr>
                            <th>動作OS</th>
                            <td>
                                Windows 10 (64bit/32bit)<br>
                                Windows 8.1 (64bit/32bit)<br>
                                Windows 7 (64bit/32bit)<br>
                                <span class="text-bold text-s">
                                    Windows 10 の表記は、Windows 10 Enterprise / Pro / Homeの略称。<br>
                                    Windows 8.1 の表記は、Windows 8.1 / Windows 8.1 Pro / Windows 8.1 Enterpriseの略称。（Windows RTは含みません）<br>
                                    Windows 7 の表記は、Windows 7 Ultimate / Enterprise / Professional / Home Premium SP1以降の略称。<br>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>対応機種</th>
                            <td>動作OSが稼動するパソコン<br><span class="text-bold text-s">自作機での動作は保証しません。</span></td>
                        </tr>
                        <tr>
                            <th>メモリ</th>
                            <td>
                                [ Windows 10 / 8.1 ] 2GB以上(64bit) / 1GB以上(32bit)<br />
                                [ Windows 7 ] 512MB以上(推奨1GB以上)
                            </td>
                        </tr>
                        <tr>
                            <th>ディスプレイ</th>
                            <td>
                                本体に接続可能で動作OSに対応のディスプレイ<br>解像度 1024×768ピクセル以上推奨
                            </td>
                        </tr>
                        <tr>
                            <th>ハードディスク</th>
                            <td>80MB以上（データ領域は別途必要）</td>
                        </tr>
                        <tr>
                            <th>マウス</th>
                            <td>動作OSに対応したマウス</td>
                        </tr>
                    </table>
                    <ul class="square">
                        <li>本製品をインストールおよびアンインストールする際は、コンピューターの管理者権限を有するユーザーでログオンする必要があります。</li>
                        <li>本製品を運用する際は、コンピューターの管理者権限を有するユーザーでログオンする必要があります。</li>
                        <li>2020年1月14日(日本時間)をもってMicrosoft社のWindows 7へのサポートとセキュリティ更新プログラム等の提供が終了しています。Windows 7コンピューターを利用し続けることは、コンピューターの脆弱性を解決しないままで使用し続けることになり、セキュリティ上、危険な状態になります。最新環境への移行をご検討ください。</li>
                        <li>本ページに記載されている会社名・製品名・サービス名は、各社の登録商標または商標です。</li>
                    </ul><br>

                    <h3>ご利用のイメージ</h3>
                    <p>「農業簿記11」のダイレクトメニューの [ <b>申告</b> ] から [ <b>みんなの電子申告</b> ] ボタンをクリックすると、「みんなの電子申告」メイン画面が表示されます。</p>
                    <p>「みんなの電子申告〈令和2年分 e-Tax連携オプション〉」の操作方法については [ PDFマニュアル ] をダウンロードの上、必ずご参照ください。</p>
                    <p><img src="images/flow_210126.jpg" alt="ご利用のイメージ"></p><br>

                    <h3>データ取り込みに対応する製品</h3>
                    <p>・「農業簿記11」<br>・「みんなの確定申告〈令和2年分申告用〉」</p><br>
                    <blockquote>
                    <h3>「みんなの電子申告」のサポートについて</h3>
                    <p>本ソフトウェアの操作方法に関するお問い合わせにつきましては、お電話およびFAXにて受け付けております。</p>
                    <h4>農業サポートセンター</h4>
                    <p>TEL：0258-31-5850　FAX：0258-31-5651<br>受付時間　10:00～12:00／13:00～17:00<br>[ ※土・日・祝祭日・年末年始を除く ]</p>
                    <p class="text-bold text-s">お問い合わせの際は、農業簿記のシリアルナンバーを確認させて頂いておりますのでご協力をお願いいたします。</p>
                    <p class="text-bold text-s">また、申告前はサポートセンターが大変混み合いますので、ご不明な点がございましたらお早めにお問い合わせくださいますようお願い申し上げます。</p>
                    </blockquote><br>

                    <h3 id="downloadbox">みんなの電子申告<span style="font-size:70%;">〈令和2年分 e-Tax連携オプション〉</span><br>ダウンロード</h3>
                    <p>形式：EXEファイル（38.6MB）　更新日：2021/01/26</p>
                    <p>「みんなの電子申告〈令和2年分 e-Tax連携オプション〉」をインストールするプログラムファイルです。</p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg" onclick="window.open(<?= $_SERVER['script_name'] ?>,'dlwindow','width=700,height=200'); return false;">ダウンロード</a></p>
                    <p>併せて以下の操作マニュアルもご覧ください。</p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">操作マニュアル（PDF：1.8MB）</a></li>
                        <li><a href="<?= $AdobeReaderDL_URL ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;</a></li>
                    </ul>
                    <p class="text-bold text-s">操作マニュアルをご覧になるにはAdobe Reader&reg;が必要です。 </p>

                    <!--注記（ブラウザでダウンロードできない）-->
                    <div style="margin:50px 30px 30px 30px; text-align:left;">
                        <div style="border:2px #ddd solid; padding:12px; border-radius:5px;">
                            <div style="color:#444; font-size:14px; line-height:18px;">
                                <div style="margin-bottom:10px;">
                                    <div class="error_message" style="font-size:95%;">
                                        <span>ダウンロードボタンを押してもインストール画面が出ない場合があります</span><br>
                                        ご利用のＯＳによってはインストールが「開始されない」、「進まない」場合があります。<br>
                                        以下の状況が考えられますのでご確認をお願いいたします。
                                    </div>
                                    インストールを開始した後に表示される画面が、他のアプリケーションやウェブブラウザ（ソリマチホームページなど）の裏面に隠れる場合があります。その場合は、タスクバーに表示されている「みんなの確定申告／みんなの電子申告」や「InstallShield..」をクリックすることでインストール画面が最前面に表示されます。
                                    <br>
                                </div>
                                <div style="margin-top:15px;"><img src="images/dlfail_img01_etax.png" border="0" width="500"></div>
                                <div style="margin-top:15px;"><img src="images/dlfail_img02_etax.png" border="0" width="500"></div>
                            </div>
                        </div>
                    </div>

                    <!--注記（前バージョンが残っている）-->
                    <div style="margin:50px 30px 30px 30px; text-align:left;">
                        <div style="border:2px #ddd solid; padding:12px; border-radius:5px;">
                            <div style="color:#444; font-size:14px; line-height:18px;">
                            <div style="margin-bottom:10px;">
                                <div class="error_message" style="font-size:95%;">
                                    <span>旧バージョンのアンインストールを促す画面が表示される場合があります</span><br>
                                    ご利用のＯＳによってはインストール時に旧バージョンのアンインストールを促すメッセージ画面が表示される場合があります。
                                </div>
                                ご利用のＯＳによってはインストール時に下のメッセージが表示される場合があります。<br>
                                下のメッセージが表示される場合は［ＯＫ］をクリックし、旧バージョンのみんなの電子申告をアンインストール後に、再度、みんなの電子申告〈e-Tax連携オプション〉のインストールをお試しください。
                                <br>
                                <div style="margin-top:15px;"><img src="images/prever_img01.png" border="0"></div><br>
                                旧バージョンのみんなの電子申告をアンインストールする方法については [ <a href="https://qa.sorimachi.co.jp/hc/ja/articles/360000581491" target="_blank"><b>こちらの製品Ｑ＆Ａ</b></a> ] をご参照ください。<br>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!--注記（本バージョン特有）-->
                    <div style="margin:50px 30px 0px 30px; text-align:left;">
                        <div style="border:2px #ddd solid; padding:15px; border-radius:5px;">
                            <div style="color:#d00; font-size:15px; line-height:24px;" id="installmsg"><b>■プログラムのダウンロード／インストールができない場合</b><br></div>
                            <div style="color:#444; font-size:14px; line-height:18px;">
                                <div style="margin-bottom:10px;">
                                    お使いのPCの環境によって、ダウンロード（保存）／インストール（実行）時にメッセージが表示される場合があります。その場合はメッセージの内容に応じて、下記の操作方法をお試しください。<br>
                                </div>
                                <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                                    ・<b>「ダウンロードしたユーザー数が少ないため、PCに問題を起こす可能性があります」</b>&nbsp;と表示される場合<br>
                                    <a href="./pdf/etax2016_install1.pdf" target="_blank"><img src="./images/message01.gif" border="0" width="360"></a><br>
                                    <a href="./pdf/etax2016_install1.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                                </div>
                                <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                                    ・<b>「WindowsによってPCが保護されました」</b>&nbsp;と表示される場合<br>
                                    <a href="./pdf/etax2016_install2.pdf" target="_blank"><img src="./images/message02.gif" border="0" width="265"></a><br>
                                    <a href="./pdf/etax2016_install2.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;みんなの電子申告&nbsp;ダウンロード</li>
                </ul>
            <?php require_once '../../lib/footer_general.php'; ?>
        </footer>
    <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
