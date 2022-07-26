<?php
    require_once "../../../lib/common.php";
    require_once "../../../lib/login.php";
    //require_once "../../../lib/get_csv.php";
    require_once "../../../lib/contents_list.php";

    $temp = explode("/", $_SERVER["SCRIPT_NAME"]);
    $curDir = $temp[count($temp) - 2];
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="format-detection" content="telephone=no" />
        <meta name="keywords" content="農業,簿記,新着情報,サポート" />
        <meta name="description" content="「れん太郎」Version 1.09.00専用レベルアッププログラム ダウンロード" />
        <title>「れん太郎」Version 1.09.00専用レベルアッププログラム&nbsp;ダウンロード｜そり蔵ネット</title>
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
        <?php require_once "../../../lib/header_general.php"; ?>
        <article id="home" class="clearfix">
            <nav class="clearfix">
                <h1><a href="/index.php" onfocus="this.blur()">「れん太郎」レベルアップ&nbsp;ダウンロード<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
                <?php require_once "../../../lib/nav_general.php"; ?>
            </nav>
            <section id="contents" class="program">
                <div class="box-contents">
                    <hgroup>
                        <p><a href="/program.php" onfocus="this.blur()">↑プログラム更新</a></p>
                        <h2><span>れん太郎&nbsp;Ver&nbsp;1.09.00専用</span>レベルアッププログラム<br>ダウンロード</h2><br>
                        <dl class="clearfix">
                            <dt><span><a href="#downloadbox"><img src="images/pkg_ren.gif" alt="農業日誌V6プラス専用郵便番号辞書"></a></span></dt>
                            <dd><span>Microsoft Windows&reg;8 に対応するレベルアッププログラムです。お使いのパソコンにあらかじめ「れん太郎 Version 1.09.00」がインストールされている必要があります。</span>
                            <span class="text-bold">お使いのれん太郎のバージョン情報が1.05～1.08の場合は、先に1.09(連携オプション)をインストールしてください。</span></dd>
                        </dl>
                    </hgroup><br>
                    <h3>バージョン情報の確認方法</h3>
                    <p>メニューバーの「ヘルプ」-「バージョン情報」をクリックすることでご確認いただけます。</p>
                    <p>もしバージョン情報が1.05～1.08の場合は下記のページをご利用ください。</p>
                    <ul class="links">
                        <li><a href="../ren_nbk08_renkeiop/index.php" onfocus="this.blur()">1.09(連携オプション)</a></li>
                    </ul><br>
                    <h3>Version 1.10 のレベルアップ項目</h3>
                    <p>Microsoft WindowsR8 に対応しました。</p><br>
                    <h3>インストール、及びバージョン確認方法（画面は Windows 8 の場合）</h3>
                    <p>【1】 上の表のダウンロードボタンをクリックして、プログラムファイル（RENSP011031.exe）をダウンロードします。その際、デスクトップなどの分かりやすい場所にファイルを保存してください。</p>
                    <p><img src="images/scr001.gif" alt="【1】"></p><br>
                    <p>【2】 ダウンロードが完了したら「実行」ボタンをクリックしてください。</p>
                    <p><img src="images/scr002.gif" alt="【2】"></p><br>
                    <p>【3】 アップデートプログラムの解凍先フォルダーを指定する画面が表示されますので、「解凍」をクリックし、サービスパックの画面に従ってインストールを行ってください。</p>
                    <p><img src="images/scr003.gif" alt="【3】"></p><br>
                    <p>【4】 メッセージやウィンドウが消えたらインストールは完了です。インストールが完了すると「れん太郎」はバージョンが「1.10」になります。確認する際は「れん太郎」を起動してください。</p><br>
                    <p>【5】 [ヘルプ]メニューから[バージョン情報]を選択してください。開いた「バージョン情報」画面で現在のバージョンを確認することができます。</p>
                    <p><img src="images/scr005.gif" alt="【5】"></p><br>
                    <h3 id="downloadbox">れん太郎&nbsp;Ver&nbsp;1.09.00専用<br>レベルアッププログラム<br>ダウンロード</h3>
                    <p>形式：EXEファイル（644KB）</p>
                    <p class="btn-dl01"><a href="/usersupport/softdown/core/download.php?dir=<?= $curDir ?>" onfocus="this.blur()" target="_blank">ダウンロード</a></p>
                    <ul class="links">
                        <li><a href="../ren_nbk08_renkeiop/index.php" onfocus="this.blur()">1.09(連携オプション)</a></li>
                    </ul>
                    <p>Microsoft WindowsR8 に対応するレベルアッププログラムです。お使いのパソコンにあらかじめ「れん太郎 Version 1.09.00」がインストールされている必要があります。 </p>
                    <p class="text-bold">お使いのれん太郎のバージョン情報が1.05～1.08の場合は、先に1.09(連携オプション)をインストールしてください。</p>
                </div>
            </section>
        </article>
        <footer>
            <div id="linkBox" class="clearfix">
                <ul>
                    <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/program.php">プログラム更新</a>&nbsp;&gt;&nbsp;「れん太郎」Version&nbsp;1.09.00専用レベルアッププログラム&nbsp;ダウンロード</li>
                </ul>
                <?php require_once "../../../lib/footer_general.php"; ?>
        </footer>
        <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
    </body>
</html>