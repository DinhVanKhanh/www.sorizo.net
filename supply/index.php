<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';

    global $SORIZO_HOME;
    global $SORIMACHI_HOME;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Imagetoolbar" content="no">
<script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
<link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
<link rel="stylesheet" type="text/css" media="screen" href="/css/base.css">
<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">
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
<script type="text/javascript" src="/js/participate.js"></script>
<title>専用帳票（サプライ用品）のご案内｜そり蔵ネット</title>
<style type="text/css">
A { text-decoration: none; }
A:hover { text-decoration: underline; }
A.link1 { color : #ff0000; }
A.link1:visited{ color : #ff0000; }
</style>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>
<body id="general">
    <div id="oldHeader">
        <h1>専用帳票（サプライ用品）のご案内</h1>
    </div>
    <div id="oldWrapper">
        <!-- 農業サプライ製品 （ここから）-->
        <table width="800" style="margin:0 auto;">
            <tr>
                <td width="690" style="font-size:24px; text-align:left;"><b>農業ソフト 専用帳票（サプライ用品）</b></td>
                <td rowspan="2" valign="top" width="110" align="right">
                    <img src="/common/images/pkg_nbk11_w316.gif" height="120" alt="農業簿記11">
                    <img src="/common/images/pkg_nns06p_w316.gif" height="120" alt="農業日誌V6プラス">
                </td>
            </tr>
            <tr>
                <td width="690" valign="top" style="line-height:150%; text-align:left;">
                    【 <b>取り扱い製品</b> 】<br>
                    農業簿記・農業日誌でご利用いただける専用帳票（サプライ用品）<br><br>
                    【 <b>購入方法</b> 】<br>
            <?php if (CheckUserStatusByProduct("all") == 1) { ?>
                    ソリマチ公式オンラインショップ、またはFAXでお申し込みいただけます。<br>
                    [ <a href="javascript:WDSupplyLogin();"><b>ソリマチ公式オンラインショップを利用する</b></a> ]<br>
                    
            <?php } else { ?>
                    FAXでお申し込みいただけます。<br>
                    <b>ソリマチクラブ会員の皆さま</b><br>
　                   ソリマチ公式オンラインショップをご利用いただくことができます。トップページからログインしてご利用ください。<br><br>
            <?php } ?>
                    [ <a href="#howtoorder"><b>ＦＡＸでのご注文方法はこちら</b></a> ]<br>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center" colspan="2" style="border-top:2px #B0B0B0 dotted; padding-top:30px;">
                    <a name="list"></a>
                    <table cellspacing="0" cellpadding="2" border="0" width="800" style="background-color:#fff;">
                        <?php
                        BlockTitle("「農業簿記11/10/9」 専用帳票");
                        BlockContent("images_supply/SR1281t.gif", "SR1281", "振替伝票", "https://member.sorimachi.co.jp/supply/sr1281.php", "A4縦・500枚", "￥8,470");
                        BlockContent("images_supply/SR1291t.gif", "SR1291", "仕訳帳", "https://member.sorimachi.co.jp/supply/sr1291.php", "A4縦・500枚", "￥6,710");
                        BlockContent("images_supply/SR1301t.gif", "SR1301", "出納帳", "https://member.sorimachi.co.jp/supply/sr1301.php", "A4縦・500枚", "￥6,710");
                        BlockContent("images_supply/SR9291t.gif", "SR9291", "元帳（2穴仕様）", "https://member.sorimachi.co.jp/supply/sr9291.php", "A4縦・500枚", "￥6,710");
                        BlockContent("images_supply/SR9301t.gif", "SR9301", "元帳（30穴仕様）", "https://member.sorimachi.co.jp/supply/sr9301.php", "A4縦・500枚", "￥7,260");
                        BlockContent("images_supply/SR9481t.gif", "SR9481", "合計残高試算表", "https://member.sorimachi.co.jp/supply/sr9481.php", "A4縦・500枚", "￥6,050");
                        BlockContent("images_supply/sr250t.gif", "SR250", "源泉徴収票・給与支払報告書セット（令和3年度版） ※対応製品:農業簿記11", "https://member.sorimachi.co.jp/supply/sr250.php", "A4横・100セット", "￥6,600");
                        ?>
                    </table>
                    <table cellspacing="0" cellpadding="2" border="0" width="800" style="margin-top:30px; background-color:#fff;">
                        <?php
                        BlockTitle("「農業日誌V6プラス」 専用帳票");
                        BlockContent("images_supply/SR290t.gif", "SR290", "宛名タックシール12面", "https://member.sorimachi.co.jp/supply/sr290.php", "A4縦（ラベルサイズH42×W84mm）・200枚", "￥9,680");
                        BlockContent("images_supply/SR310t.gif", "SR310", "見積書", "https://member.sorimachi.co.jp/supply/sr310.php", "A4縦・500枚", "￥6,270");
                        BlockContent("images_supply/SR320t.gif", "SR320", "売上伝票（売上伝票・請求書・納品書）", "https://member.sorimachi.co.jp/supply/sr320.php", "A4縦・500枚", "￥11,550");
                        BlockContent("images_supply/SR330t.gif", "SR330", "納品書A（納品書・請求書・納品書控）", "https://member.sorimachi.co.jp/supply/sr330.php", "A4縦・500枚", "￥11,550");
                        BlockContent("images_supply/SR331t.gif", "SR331", "納品書B（請求書・納品書・物品受領書）", "https://member.sorimachi.co.jp/supply/sr331.php", "A4縦・500枚", "￥11,550");
                        BlockContent("images_supply/SR332t.gif", "SR332", "納品書C（納品書・物品受領書）", "https://member.sorimachi.co.jp/supply/sr332.php", "A4縦・500枚", "￥10,340");
                        BlockContent("images_supply/SR333t.gif", "SR333", "納品書D（請求書・納品書）", "https://member.sorimachi.co.jp/supply/sr333.php", "A4縦・500枚", "￥10,340");
                        BlockContent("images_supply/SR340t.gif", "SR340", "明細請求書", "https://member.sorimachi.co.jp/supply/sr340.php", "A4縦・500枚", "￥6,270");
                        BlockContent("images_supply/SR341t.gif", "SR341", "合計請求書", "https://member.sorimachi.co.jp/supply/sr341.php", "A4縦・500枚", "￥8,800");
                        BlockContent("images_supply/SR350t.gif", "SR350", "払込取扱票A（加入者負担）", "https://member.sorimachi.co.jp/supply/sr350.php", "A4縦・500枚", "￥9,900");
                        BlockContent("images_supply/SR351t.gif", "SR351", "払込取扱票B（払込人負担）", "https://member.sorimachi.co.jp/supply/sr351.php", "A4縦・500枚", "￥9,900");
                        BlockContent("images_supply/SR360t.gif", "SR360", "納品書・払込取扱票A（加入者負担）", "https://member.sorimachi.co.jp/supply/sr360.php", "A4縦・500枚", "￥13,530");
                        BlockContent("images_supply/SR361t.gif", "SR361", "納品書・払込取扱票B（払込人負担）", "https://member.sorimachi.co.jp/supply/sr361.php", "A4縦・500枚", "￥13,530");
                        BlockContent("images_supply/SR391t.gif", "SR391", "窓あき封筒", "https://member.sorimachi.co.jp/supply/sr391.php", "H105×W220mm・500枚", "￥13,530");
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="center" colspan="2" style="border-top:2px #B0B0B0 dotted;">
                    <a name="howtoorder"></a>
                    <!-- 【申し込みフロー】 -->
                    <table width="650" border="0" cellspacing="0" cellpadding="0">
                        <tr><td nowrap height="20"></td></tr>
                        <tr><td align="left" style="font-size:24px; color:#A04040; font-weight:bold; line-height:140%;">◆ご注文方法</td></tr>
                        <tr><td nowrap height="20"></td></tr>
                        <tr>
                            <td align="center">
                                <table width="650" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td nowrap width="28" height="40" align="left"><img src="images/flow_no_01.gif" width="28" height="40"></td>
                                        <td nowrap width="622" height="40" align="left" valign="middle" class="p085_130" style="padding-left:1ex;" bgcolor="#FFFBBE">
                                            ご利用になる専用帳票（サプライ用品）を<a href="#list">上の一覧</a>からご確認ください。
                                        </td>
                                    </tr>
                                    <tr><td nowrap colspan="2" align="center"><img src="images/allow_down.gif" width="21" height="23"></td></tr>
                                    <tr>
                                        <td nowrap width="28" height="40" align="left"><img src="images/flow_no_02.gif" width="28" height="40"></td>
                                        <td nowrap width="622" height="40" align="left" valign="middle" class="p085_130" style="padding-left:1ex;" bgcolor="#FFFBBE">
                                            [ <a href="<?= $SORIMACHI_HOME ?>files_pdf/apply/supply_order_ag.pdf" target="blank"><b>ご注文書</b>（PDF形式）</a> ] をダウンロードし、印刷してください。<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td nowrap width="28" height="40" align="left"></td>
                                        <td width="622" height="40" align="left" valign="middle" class="p085_130" style="padding-top:3px; padding-left:2ex;">
                                            <div style="padding-left:1em; text-indent:-1em;">※ご注文書をご利用になるには<a href="<?= $AdobeReaderDL_URL ?>" target="_blank">Adobe Reader</a>が必要です。</div>
                                            <div style="padding-left:1em; text-indent:-1em;">※リンクを右クリックし、「対象をファイルに保存」を選択すると、ご注文書のファイルを保存していただくことができます。</div>
                                            <div style="padding-left:1em; text-indent:-1em;">※ご注文書はソフトのパッケージにも同梱されています。</div>
                                        </td>
                                    </tr>
                                    <tr><td nowrap colspan="2" align="center"><img src="images/allow_down.gif" width="21" height="23"></td></tr>
                                    <tr>
                                        <td nowrap width="28" height="40" align="left"><img src="images/flow_no_03.gif" width="28" height="40"></td>
                                        <td nowrap width="622" height="40" align="left" valign="middle" class="p085_130" style="padding-left:1ex;" bgcolor="#FFFBBE">
                                            必要事項、ご注文になる専用帳票（サプライ用品）をご記入ください。
                                        </td>
                                    </tr>
                                    <tr><td nowrap colspan="2" align="center"><img src="images/allow_down.gif" width="21" height="23"></td></tr>
                                    <tr>
                                        <td nowrap width="28" height="40" align="left"><img src="images/flow_no_04.gif" width="28" height="40"></td>
                                        <td nowrap width="622" height="40" align="left" valign="middle" class="p085_130" style="padding-left:1ex;" bgcolor="#FFFBBE">
                                            弊社サプライセンターまでFAX（<b>0258-31-0185</b>）にてお送りください。
                                        </td>
                                    </tr>
                                </table>
                                <table width="650" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="padding-top:20px;">
                                            <table width="650" border="0" bgcolor="#ee0000" cellspacing="1">
                                                <tr>
                                                    <td align="center" style="background-color:#fff; padding:10px;"> 
                                                        <table width="600" border="0" cellspacing="1" cellpadding="2">
                                                            <tr>
                                                                <td width="150" valign="top"><STRONG>配　　送：</STRONG></td>
                                                                <td width="450">
                                                                    <font size="2" style="line-height:120%;">
                                                                    15時までにご注文をいただきますと、翌営業日の発送となります。<br>
                                                                    15時以降のご注文は翌々営業日の発送となります。<br>
                                                                    ※土日祝日は休業日となります。</font>
                                                                </td>
                                                            </tr>
                                                            <tr><td nowrap colspan="2" height="2"></td></tr>
                                                            <tr>
                                                                <td><STRONG>お支払方法：</STRONG></td>
                                                                <td><font size="2">弊社よりお送りする振込用紙にてお支払いください。</font></td>
                                                            </tr>
                                                            <tr><td nowrap height="2" colspan="2"></td></tr>
                                                            <tr>
                                                                <td valign="top"><STRONG>送　　料：</STRONG></td>
                                                                <td><font size="2" style="line-height:120%;">送料は弊社負担とさせていただきます。</font></td>
                                                            </tr>
                                                            <tr><td nowrap colspan="2" height="2"></td></tr>
                                                            <tr>
                                                                <td><STRONG>返　　品：</STRONG></td>
                                                                <td><font size="2">ご購入後の返品はできません。あらかじめご了承ください。</font></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top:30px;">
                                <div style="font-size:18px; line-height:30px;">
                                    <font color="#136D46" style="border-left:2px #009A25 solid; padding-left:8px;"><b>お問い合わせ先</b></font><br>
                                    <div style="padding:1ex 0 0 2em;"><b>ソリマチ サプライセンター</b><br>　TEL：0258-36-5045　　FAX：0258-31-0185<br>　受付時間：10：00～17：00　※土・日・祝日および弊社指定日を除く</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- 農業サプライ製品 （ここまで）-->
        <form name="WDSend">
            <input type="hidden" name="SerialNo" value="<?= GetLoginSerial() ?>">
        </form>
    </div>
    <div id="oldFooter">
        <ul>
            <li><a href="<?= $SORIZO_HOME ?>index.php" onfocus="this.blur()" target="_blank">そり蔵ネット トップ</a></li>
            <li><a href="<?= $SORIMACHI_HOME ?>" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
        </ul>
        <p>Copyright&copy;&nbsp;Sorimachi&nbsp;Co.,Ltd.&nbsp;All&nbsp;rights&nbsp;reserved.</p>
    </div>
    <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p>
<?php require_once '../lib/gajs.php'; ?>
</body>
</html>

<?php
function BlockTitle($title) {
    echo '<tr bgColor="#808080" height="40">
            <td colspan="3" style="font-size:20px; line-height:140%; text-align:center; color:#FFFFFF;"><b>'.$title.'</b></td>
        </tr>
        <tr bgcolor="#E0E0E0" height="30">
            <td style="font-size:14px" align="center" width="80">イメージ</td>
            <td style="font-size:14px" align="center" width="90">コード</td>
            <td style="font-size:14px" align="center">品　　名</td>
        </tr>';
}

function BlockContent($pic, $code, $name, $link, $size, $price) {
    echo '<tr>
            <td width="80" height="80" style="text-align:center; vertical-align:middle; background-color:#f8f8f8;"><a href="'.$link.'" target="_blank"><img src="'.$pic.'" border=0></a></td>
            <td width="90" style="font-weight:bold; text-align:center; vertical-align:middle;">'.$code.'</td>
            <td style="padding:5px 0px;"><a href="'.$link.'" target="_blank"><font style="font-size:16px; line-height:20px;">'.$name.'</font></a><br><font size="2" color="#404040">'.$size.'<br>税込価格 '.$price.'</font></td>
        </tr>
        <tr><td nowrap align="left" colspan="3" height="1" style="background-color:#e0e0e0;"></td></tr>';
}
?>