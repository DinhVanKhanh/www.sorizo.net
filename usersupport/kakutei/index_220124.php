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
        $DownloadProgramName = 11;	// 11 : 「みんなの確定申告(個人版)」の固有値
        $DownloadProgramYear = 2020;
        $DownloadProgramVersion = 'V20-00-00';
    // ↑↑　<2021/06/16> <YenNhi> <write log to table kakutei_download>

    if (isset($_REQUEST['option'])) {
        if ($_REQUEST['option'] == 'manual') {
            execDownload("\downloadmanual_log_2020.txt", ",みんなの確定申告2020,マニュアル,Ver210125,", "download_files/sksmanualKojin2021.pdf", true);
        }
    // ↓↓　<2021/06/16> <YenNhi> <write log to table kakutei_download>
        // execDownload("\download_log_kakutei_2020.txt", ",みんなの確定申告2020(V20-00-00),プログラム,Ver210125,", $GLOBALS['PRG_DOWNLOAD_SERVER_AWS'] . "kakutei/WeKakutei2021.exe", true)
       execDownloadAndSaveDB($DownloadProgramName, $DownloadProgramYear, $DownloadProgramVersion, $GLOBALS['PRG_DOWNLOAD_SERVER_AWS'] . "kakutei/WeKakutei2021.exe");
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
        <meta name="description" content="所得税確定申告システム ダウンロード" />
        <title>所得税確定申告システム&nbsp;ダウンロード｜そり蔵ネット</title>
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
    </head>
    <body id="logout">
        <?php require_once '../../lib/header_general.php'; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/" onfocus="this.blur()">所得税確定申告システム&nbsp;ダウンロード<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../../lib/nav_general.php'; ?>
            </nav>
            <section id="contents" class="service">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2><span>所得税確定申告システム</span>みんなの確定申告&nbsp;ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox" onfocus="this.blur()"><img src="images/logo_kakutei2020_w500.png" alt="みんなの確定申告"></a></span></dt>
                            <dd><span>「みんなの確定申告」は確定申告書と同じフォーマットの画面に必要なデータを入力するだけで、納付税額をリアルタイムで自動計算してくれる便利なソフトです。<br>計算結果は、ボタン１つで税務署提出様式の印刷（コピー用紙などの白紙を使用）ができ、かんたんに確定申告が行えます。</span>
                                <span>また「農業簿記11」のデータを「みんなの確定申告」へ取り込むことができます。</span>
                                <span class=" text-bold">「みんなの確定申告」は個人の所得税確定申告専用ソフトです。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3 class="text-red">昨年のバージョンは、まだアンインストールしないでください</h3>
                    <p>昨年の〈令和元年分申告用〉で入力された申告者データ（氏名や住所など）を、本年の〈令和２年分申告用〉へ引き継ぐことができます。データを引き継ぐ場合は、昨年の〈令和元年分申告用〉がインストールされた状態にしてください。</p>
                    <p>昨年のバージョンは令和２年分の申告が終わってからアンインストールしてください。</p><br>

                    <h3>令和２年分申告用では、最新の税制改正や帳票様式の改訂に対応しています</h3>
                    <p>※くわしくは [ <b><a href="https://www.nta.go.jp/taxes/shiraberu/shinkoku/tokushu/kaisei.htm" target="_blank">国税庁ホームページ</a></b> ] をご覧ください。</p>

                    <h4>■確定申告書の様式変更</h4>
                    <p>税制改正にともなう確定申告書様式に対応いたしました。</p>

                    <h4>■住宅借入金等特別控除の計算明細書の様式変更</h4>
                    <p>税制改正にともなう計算明細書様式に対応いたしました。</p>

                    <h4>■各種控除の見直し</h4>
                    <p>税制改正にともなう各種控除の見直しに対応いたしました。<br>
・給与所得控除の見直し（給与所得控除額が原則１０万円引き下げ）<br>
・公的年金等控除の見直し（公的年金等控除額が原則１０万円引き下げ）<br>
・基礎控除の見直し（基礎控除額が３８万円から４８万円に引き上げ）<br>
・扶養控除、配偶者控除、勤労学生控除の見直し（合計所得要件が引き上げ）<br>
・ひとり親控除の創設と寡婦（寡夫）控除の見直し</p>

                    <h4>■所得金額調整控除の創設</h4>
                    <p>所得金額調整控除に対応いたしました。</p>

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
                            <td>50MB以上（データ領域は別途必要）</td>
                        </tr>
                        <tr>
                            <th>マウス</th>
                            <td>動作OSに対応したマウス</td>
                        </tr>
                        <tr>
                            <th>プリンター</th>
                            <td>動作OSに対応したプリンター</td>
                        </tr>
                    </table>
                    <ul class="square">
                        <li>本製品をインストールおよびアンインストールする際は、コンピューターの管理者権限を有するユーザーでログオンする必要があります。</li>
                        <li>本製品を運用する際は、コンピューターの管理者権限を有するユーザーでログオンする必要があります。</li>
                        <li>本製品では、申告者5人までのデータが作成できます。</li>
                        <li>2020年1月14日(日本時間)をもってMicrosoft社のWindows 7へのサポートとセキュリティ更新プログラム等の提供が終了しています。Windows 7コンピューターを利用し続けることは、コンピューターの脆弱性を解決しないままで使用し続けることになり、セキュリティ上、危険な状態になります。最新環境への移行をご検討ください。</li>
                        <li>本ページに記載されている会社名・製品名・サービス名は、各社の登録商標または商標です。</li>
                    </ul><br>
                    <h3>使用方法と注意事項</h3>
                    <h4>◆ダウンロードとインストール</h4>
                    <ol>
                        <li>下の「ダウンロード」ボタンをクリックし、「WeKakutei2021.exe」を任意のフォルダに保存してください。</li>
                        <li>ダウンロードした「WeKakutei2021.exe」をダブルクリックすると、ファイルの解凍先（初期値はC:\WeKakutei2021Setup）を指定する画面が表示されますので指示に従って解凍してください。解凍が完了するとインストールのメニュー画面が表示されます。</li>
                        <li>「みんなの確定申告」のボタンをクリックすると、インストールがはじまります。以降は、画面の指示に従ってインストールを行なってください。</li>
                        <li>インストール終了後、正常に動作させるためWindowsを再起動してください。</li>
                    </ol>
                    <p class="text-bold text-s">PDFマニュアルを閲覧するには、Adobe Reader が必要です。すでに Adobe Reader がインストールされている場合には、インストールする必要はありません。Adobe Reader をインストールする場合には、「WeKakutei2021.exe」の解凍先フォルダ（通常は C:\WeKakutei2021Setup）の中にある「Setup.exe」をダブルクリックすると、メニュー画面が表示されますので、「Adobe Reader」のボタンをクリックしてください。</p>
                    <p class="text-bold text-s">インストール後、ダウンロードした「WeKakutei2021.exe」と、（２）で解凍したフォルダのすべては削除して構いません。</p>
                    <h4>◆開始方法</h4>
                    <p>インストール後の起動方法、操作方法につきましてはPDFマニュアルをご覧ください。</p>
                    <p>なお、このPDFマニュアルは、「みんなの確定申告」を起動後、メニューの「ヘルプ」-「マニュアル」を選択することによっても、ご覧いただけます。</p>
                    <p class="text-bold text-s"> PDFマニュアルをご覧になる場合は、Adobe Readerが必要です。</p><br>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;ダウンロード</a></li>
                        <li><a href="<?= $GLOBALS['AdobeReaderDL_URL'] ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;&nbsp;ダウンロード</a></li>
                    </ul><br>
                    <h4>◆削除方法</h4>
                    <p>「コントロールパネル」－「プログラムと機能」を選択し、「みんなの確定申告」を選択してください。Adobe Reader も同様の方法で削除できます。</p><br>
                    <h3>データ取り込みに対応する製品</h3>
                    <p>「農業簿記11」</p><br>
                    <h3>出力帳票</h3>
                    <p id="output-list">
                        <dl class="output">
                            <dt class="output"><span class="text-bold">・確定申告書Ａ</span></dt>
                            <dt class="output"><span class="text-bold">・確定申告書Ｂ</span></dt>
                            <dt class="output">・確定申告書（第三表 分離課税用）</dt>
                            <dt class="output">・確定申告書（第四表 損失申告用）</dt>
                            <dt class="output">・修正申告書（第五表）</dt>
                            <dt class="output">・所得の内訳書</dt>
                            <dt class="output">・損益の通算の計算書</dt>
                            <dt class="output">・医療費控除の明細書</dt>
                            <dt class="output">・セルフメディケーション税制の明細書</dt>
                            <dt class="output">・肉用牛の売却による所得の税額計算書(兼確定申告書付表)</dt>
                            <dt class="output">・(特定増改築等)住宅借入金等特別控除額の計算明細書</dt>
                            <dt class="output">・連帯債務がある場合の住宅借入金等の年末残高の計算明細書</dt>
                            <dt class="output">・住宅耐震改修特別控除額・住宅特定改修特別税額控除額の計算明細書（平成29年4月1日以後用）</dt>
                            <dt class="output">・認定長期優良住宅新築等特別税額控除額の計算明細書</dt>
                            <dt class="output">・政党等寄附金等特別控除額の計算明細書</dt>
                            <dt class="output">・認定ＮＰＯ法人等寄附金特別控除額の計算明細書</dt>
                            <dt class="output">・公益社団法人等寄附金特別控除額の計算明細書</dt>
                            <dt class="output">・添付書類台紙</dt>
                            <dt class="output">・事業専従者一覧表</dt>
                            <dt class="output">・過去データ比較表</dt>
                        </dl>
                    </p>
                    <!--  <p class="text-bold text-s">税務署提出用紙への印刷にはレーザープリンターが必要です。</p><br>-->
                    <blockquote>
                        <h3>「みんなの確定申告」のサポートについて</h3>
                        <p>本ソフトウェアの操作方法に関するお問い合わせにつきましては、お電話およびFAXにて受け付けております。</p>
                        <h4>農業サポートセンター</h4>
                        <p>TEL：0258-31-5850　FAX：0258-31-5651<br>受付時間　10:00～12:00／13:00～17:00<br>[ ※土・日・祝祭日・年末年始を除く ]</p>
                        <p class="text-bold text-s">お問い合わせの際は、農業簿記のシリアルナンバーを確認させて頂いておりますのでご協力をお願いいたします。</p>
                        <p class="text-bold text-s">また、申告前はサポートセンターが大変混み合いますので、ご不明な点がございましたらお早めにお問い合わせくださいますようお願い申し上げます。</p>
                    </blockquote><br>
                    <h3 id="downloadbox">みんなの確定申告〈令和２年分申告用〉<br> Ver20.00.00 インストーラー</h3>
                    <p>ファイルサイズ：28.9MB</p>
                    <p class="btn-dl01"><a href="<?= $link ?>prg" onclick="window.open('<?= $link ?>prg','dlwindow','width=700,height=200');return false;">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="<?= $link ?>manual" onfocus="this.blur()" target="_blank">PDFマニュアル&nbsp;ダウンロード</a></li>
                        <li><a href="<?= $GLOBALS['AdobeReaderDL_URL'] ?>" onfocus="this.blur()" target="_blank">Adobe Reader&reg;&nbsp;ダウンロード</a></li>
                    </ul>
                    <p class="text-bold">ご使用前には必ず使用許諾契約書をお読みください。</p>
<!--
                    <p style="margin-top:5px; color:#444;"><span style="color:#d00; font-weight:bold">【重要】 最新版サービスパックのご案内 [ 2021/X/XX現在 ]</span><br>みんなの確定申告〈令和２年分申告用〉をご利用の際には、必ず<a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2020sp/?from=sorizo" target="_blank"><b>最新版のサービスパック</b></a>をダウンロードし、インストールしてからご入力ください。<br>最新版のサービスパックは<b>「みんなの確定申告」のオンラインアップデート機能</b>からもインストールすることができます。</p>
-->

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
                                <div style="margin-top:15px;"><img src="images/dlfail_img01_kakutei.png" border="0" width="500"></div>
                                <div style="margin-top:15px;"><img src="images/dlfail_img02_kakutei.png" border="0" width="500"></div>
                            </div>
                        </div>
                    </div>

                    <!--注記（本バージョン特有）-->
                    <div style="margin:50px 30px 30px 30px; text-align:left;">
                        <div style="border:2px #ddd solid; padding:8px; border-radius:5px;">
                            <div style="color:#d00; font-size:15px; line-height:24px;" id="installmsg">
                                <b>■プログラムのダウンロード／インストールができない場合</b><br>
                            </div>
                            <div style="color:#444; font-size:14px; line-height:18px;">
                            <div style="margin-bottom:10px;">
                            お使いのPCの環境によって、ダウンロード（保存）／インストール（実行）時にメッセージが表示される場合があります。その場合はメッセージの内容に応じて、下記の操作方法をお試しください。<br>
                            </div>
                            <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                            <b>・「ダウンロード数が少ないため、PCに問題を起こす可能性があります」</b>&nbsp;と表示される場合<br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install1.pdf" target="_blank"><img src="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/images/message01.gif" border="0" width="360"></a><br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install1.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                            </div>
                            <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                            <b>・「WindowsによってPCが保護されました」</b>&nbsp;と表示される場合<br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install2.pdf" target="_blank"><img src="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/images/message02.gif" border="0" width="265"></a><br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install2.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                            </div>
                            <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                            <b>・「安全ではないと報告されました」</b>&nbsp;と表示される場合<br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install3.pdf" target="_blank"><img src="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/images/message03.gif" border="0" width="330"></a><br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install3.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                            </div>
                            <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                            <b>・「このファイルには問題があります」</b>&nbsp;と表示される場合<br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install4.pdf" target="_blank"><img src="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/images/message04.gif" border="0" width="330"></a><br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install4.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
                            </div>
                            <div style="padding:7px 0 7px 1.5em; text-indent:-1em; border-top:1px #aaa dotted;">
                            <b>・「署名が壊れているか、無効です」</b>&nbsp;と表示される場合<br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install5.pdf" target="_blank"><img src="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/images/message05.gif" border="0" width="330"></a><br>
                            <a href="https://www.sorimachi.co.jp/usersupport/products_support/softdownload/kakutei2016/pdf/kakutei2016_install5.pdf" target="_blank">→&nbsp;<b>操作方法はこちら</b></a><br>
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
                    <li><a href="/">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;みんなの確定申告&nbsp;ダウンロード</li>
                </ul>
        <?php require_once '../../lib/footer_general.php'; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>
