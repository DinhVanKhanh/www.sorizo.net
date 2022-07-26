<?php
    $temp    = explode("/", $_SERVER["SCRIPT_NAME"]);
    $curDir  = $temp[count($temp) - 2];
    $dirFile = (trim($curDir) == 'softdown') ? '../..' : '../../..';

    require_once "{$dirFile}/lib/common.php";
    require_once "{$dirFile}/lib/login.php";
    require_once "{$dirFile}/lib/get_filesize.php";

    global $SORIZO_HOME;
    global $SORIMACHI_HOME;
    global $AdobeReaderDL_URL;

    if ($WEBSERVER_FLG == 0) { // 本サーバーの場合
        // 社内確認用に認証しないように変更
        if (!empty($_GET["serial_key"])) {
            // 戻ってくるためのアドレスとしてこのページを保存します。
            WriteRequestedURL();
            header("Location: /sorikuranet_callup.php?mode=hkn&serial_key=" . $_GET["serial_key"]);
        }


        // TFP対応の為、処理を追加
        // ★新製品が出たら追加する必要有
        $product = GetCookie(LoginProduct);
        if ( !empty($product) ) {
            if ($product[f_serialno] != "1015118000009287"){
                // 住所の都道府県と合致している場合は保有商品のチェック
                if ($product[f_userpref] == "北海道"){
                    // 閲覧対象となる製品を限定する場合（シリアルの上4桁、もしくは3桁で指定）
                    CheckIntendedProduct("1430");	// JA接続キット
                } else {
                    echo "お客様の都道府県と異なるため、ページを表示できません";
                }

            } else {
                // デモシリアルの場合はそのままスルー
            }
        }
    } else {
        // テストサーバーの場合はチェックしない
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
    <meta name="robots" content="noindex,nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Imagetoolbar" content="no">
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/general_1006.css">
    <link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
    <script type="text/javascript" src="/js/dd.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/overlib417/overlib.js"></script>
    <link rel="stylesheet" href="/css/sg_general.css" type="text/css">
    <link rel="stylesheet" href="/css/sg_list.css" type="text/css">
    <link rel="stylesheet" href="/css/sg_blue.css" type="text/css">
    <title>ソフトウェアダウンロード｜そり蔵ネット</title>
</head>

<body id="general">
    <div id="oldHeader" background-color:#FFaaaa;>
        <h1>ソフトウェアダウンロード</h1>
        <p>農業ソフトのプログラムアップデートをダウンロードすることができます。</p>
    </div>
    <div id="oldWrapper">
        <!-- メインコンテンツ（ここから）-->
        <table border="0" cellspacing="0" cellpadding="0" class="maintable">
            <tr>
                <td width="650">
                    <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
                        <tr>
                            <td nowrap width="170" align="left" style="padding-left:2px;"><img
                                    src="images/pkg_nbk11_jahkd.gif"></td>
                            <td width="480">
                                <table width="480" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <div
                                                style="color:#404040; font:24px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">
                                                「農業簿記11　JA北海道版」
                                            </div>
                                            <div
                                                style="color:#404040; font:20px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">
                                                　変換プログラム（農業簿記11.01以降用）</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="padding:5px 0 0 2px; font:normal 90%/140% Meiryo,メイリオ,'ＭＳ Ｐゴシック',sans-serif; border-top:1px #808080 dotted;">
                                            本プログラムをダウンロードすることで、「農業簿記11　JA北海道版」のサービスをご利用いただくことができるようになります。
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top:15px;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:40px 0 10px 0px;">
                                <div>
                                    <table width="650" class="list_g1">
                                        <tr>
                                            <th colspan="4" class="list_g0" id="kit">変換プログラムのダウンロードはこちら</th>
                                        </tr>
                                        <tr>
                                            <th class="list_g1" style="border:1px #bbb solid;">ファイル内容</th>
                                            <th class="list_g1" style="border:1px #bbb solid;">形式・データ容量</th>
                                            <th class="list_g1" style="border:1px #bbb solid;">Download</th>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px; background-color:#fff; border:1px #bbb solid;">
                                                <div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;">「農業簿記11
                                                    JA北海道版」変換プログラム
                                            </td>
                                            <td style="padding:5px; background-color:#fff; border:1px #bbb solid;">
                                                <div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;">プログラムファイル
                                                    <!--（2.1MB）-->
                                            </td>
                                            <td
                                                style="padding:5px; background-color:#fff; border:1px #bbb solid; text-align:center;">
                                                <div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;"><a
                                                        href="../core/download.php?dir=<?= $curDir ?>"
                                                        target="_blank"><img src="/images/btn_down_g.gif"
                                                            onMouseover="this.src='/images/btn_down_r.gif'"
                                                            onMouseout="this.src='/images/btn_down_g.gif'" border="0"
                                                            alt="プログラムファイルダウンロード"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px; background-color:#fff; border:1px #bbb solid;">
                                                <div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;">「農業簿記11
                                                    JA北海道版」マニュアル
                                            </td>
                                            <td style="padding:5px; background-color:#fff; border:1px #bbb solid;">
                                                <div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;">PDFファイル
                                            </td>
                                            <td
                                                style="padding:5px; background-color:#fff; border:1px #bbb solid; text-align:center;">
                                                <div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;"><a
                                                        href="../core/download.php?dir=<?= $curDir ?>&option=manual"
                                                        target="_blank"><img src="/images/btn_down_g.gif"
                                                            onMouseover="this.src='/images/btn_down_r.gif'"
                                                            onMouseout="this.src='/images/btn_down_g.gif'" border="0"
                                                            alt="プログラムファイルダウンロード"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"
                                                style="padding:5px; color:#444; font:normal 80%/130% Meiryo,MS UI Gothic,osaka,sans-serif;">
                                                ※PDFマニュアルをご覧いただく場合は、<a href="<?= $AdobeReaderDL_URL; ?>"
                                                    target="_blank">Adobe Reader</a> が必要です。
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td nowrap colspan="2" height="80"></td>
                        </tr>
                    </table>
                    <!--PRODUCTS(END)-->
                </td>
            </tr>
        </table>
<?php require_once '../core/softdown_footer.php'; ?>