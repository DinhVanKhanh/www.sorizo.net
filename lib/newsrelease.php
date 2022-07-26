<?php
//　【NEWS RELEASE 共通指定】
//　元はソリマチホームページ用（主に業務）で使用していたものです。
define("COL_NR_TURN_DOWN", 0);          // 掲載拒否フラグ 値が何かしら入っていたら掲載しない
define("COL_NR_START", 1);              // 掲載日時
define("COL_NR_END", 2);                // 掲載終了日時
define("COL_NR_TITLE1", 4);             // タイトル1
define("COL_NR_BODY1", 5);              // 本文1
define("COL_NR_LINKURL1", 6);           // リンク先1
define("COL_NR_ANCHOR_NAME", 7);        // アンカー名
define("COL_NR_TITLE2", 8);             // タイトル2
define("COL_NR_BODY2", 9);              // 本文2
define("COL_NR_LINKURL2", 10);          // リンク先2
define("COL_NR_SRZ_PICKUP_FLG", 11);    // PickUpランダム掲載
define("COL_NR_SRZ_TREE", 12);          // FREE
define("COL_NR_SRZ_NBK08", 13);         // 簿記8
define("COL_NR_SRZ_NBK09", 14);         // 簿記9
define("COL_NR_SRZ_NNS06", 15);         // 日誌6
define("COL_NR_SRZ_NNS06P", 16);        // 日誌6プラス
define("COL_NR_SRZ_JA08", 17);          // JA8
define("COL_NR_SRZ_JA09", 18);          // JA9
define("COL_NR_SRZ_NBK10", 19);         // 簿記10
define("COL_NR_SRZ_JA10", 20);          // JA10
define("COL_NR_SRZ_HKN10", 21);         // ホクノウ版10
define("NewsreleaseCodeLogout", 12);    // 表示制限区分-ログイン前
define("NR_ForReading", 1);

global $NR_h_cnt;
$NR_h_cnt = 0;
global $NR_page_cnt;
$NR_page_cnt = 5000;    // 業務サポートトップページの先頭用
global $NR_page;
$NR_page = 0;
global $NR_page_e;
global $NR_page_s;
if ( !empty($_POST["NR_page"]) ) {
    $NR_page   = intval($_POST["NR_page"]) + 1;
    $NR_page_e = $NR_page_cnt * $NR_page;
    $NR_page_s = $NR_page_e - $NR_page_cnt;
}
else {
    $NR_page_e = $NR_page_cnt;
    $NR_page_s = 0;
    $NR_page = 1;
}

function NewsreleaseCode($ipcode) {
    switch ($ipcode) {
        case "":             // 非会員(ログイン前)
            return "z001";
        case "1012":         // 農業簿記8
            return "znbk08";
        case "1013":         // 農業簿記9
            return "znbk09";
        case "1014":         // 農業簿記10
            return "znbk10";
        case "1015":         // 農業簿記11
            return "znbk11";
        case "1020":         // 農作業日誌Ver6
            return "znns06";
        case "1021":         // 農業日誌V6プラス
            return "znns06p";
        case "1052":         // 簿記8JAバージョン
            return "znbk08ja";
        case "1053":         // 簿記9JAバージョン
            return "znbk09ja";
        case "1054":         // 簿記10JAバージョン
            return "zngk10ja";
        case "1055":         // 簿記11JAバージョン
            return "zngk11ja";
        case "1430":         // ホクノウ版
            return "zhkn10";
    }
}

// ニュースリリース一覧ページ専用
// ニュースリリースのタイトルのみを記述
// 呼出 NewsReleaseCSVReadTitle（CSVから必要な情報を取り出す）
// 呼出 WriteNewsReleaseTitle（取り出すした情報をそれぞれHTMLの体裁にする）
function NewsReleaseTitle($PageCategory) {
    global $NR_h_cnt;
    global $NR_page_e;
    global $NR_page_s;
    $listData = NewsReleaseCSVReadTitle($PageCategory);
    if (!empty($listData)) {
        foreach ($listData as $aLine) {
            if (is_array($aLine)) {
                if ($NR_h_cnt >= $NR_page_e) {
                    break;
                }
                if (count($aLine) > 0) {
                    $NR_h_cnt++;
                    if ($NR_h_cnt >= $NR_page_s) {
                        if ($PageCategory == "top") {
                            WriteNRTopTitle($aLine, $NR_h_cnt);
                        } elseif ($PageCategory == "nr") {
                            WriteNRInfoTitle($aLine);
                        }
                    }
                }
            }
        }
    }
}

// NEWS RELEASE CSVを読み込み
// NewsReleaseTitle(PageCategory) から呼び出される
function NewsReleaseCSVReadTitle($PageCategory) {
    $strSQL  = "SELECT DISTINCT Newsrelease.NRCode, Newsrelease.NRStopFlag,";
    $strSQL .= " Newsrelease.NRStartDate, Newsrelease.NREndDate, Newsrelease.NRComment,";
    $strSQL .= " Newsrelease.NRTitle, Newsrelease.NRBody, Newsrelease.NRLinkAddress,";
    $strSQL .= " Newsrelease.NRLinkAnchor, Newsrelease.NRSortValue";
    $strSQL .= "  FROM News_Summary Newsrelease, News_Target Target, News_Category Category, News_TargetMaster ";
    $strSQL .= "  WHERE Newsrelease.NRCode = Target.NRCode";
    $strSQL .= "   AND Target.NRCode = Category.NRCode";
    $strSQL .= "   AND Target.NRTarget = News_TargetMaster.NRTarget ";
    $strSQL .= "   AND News_TargetMaster.NRTargetClass = '011' ";

global $WEBSERVER_FLG;

    // ログインしていない状態の場合
    if (GetLoginSerial() == "") {
        $strSQL .= " AND Target.NRTarget = '".NewsreleaseCode("")."'";
    }
    else {
        // 保有確認製品かどうかを判別します
        $strSQL2 = "";
        global $IntendedProductsCode;
        foreach ($IntendedProductsCode as $IPCode) {
            // IPCode毎の契約終了期間をCookieから取得
            $CodeExpires = GetCookie($IPCode, f_expires);
            // Cookieから何らかの日付を取得できた場合
            if ($CodeExpires != "") {
                $strSQL2 .= ($strSQL2 == "") ? "Target.NRTarget ='".NewsreleaseCode($IPCode)."'" : " OR Target.NRTarget ='".NewsreleaseCode($IPCode)."'";
            }
        }
        $strSQL .= ($strSQL2 != "") ? " AND ( ".$strSQL2.") " : " AND Target.NRTarget = '".NewsreleaseCode("")."'";
    }

    // 本サーバーは掲載日以降のものだけ抽出。テストサーバーの場合は掲載日以前のものも掲載する。
    $Today   = date("Y/m/d");
// ↓↓　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
    // $strSQL .= ($WEBSERVER_FLG == "0") ? " AND Newsrelease.NRStartDate <= '".$Today."'" : "";
    // AWSの環境
    if ( $WEBSERVER_FLG == 0 ) {
        $strSQL .= " AND Newsrelease.NRStartDate <= '" . $Today . "'";
    }
// ↑↑　<2020/06/26> <VinhDao> <AWSの環境で「$WEBSERVER_FLG = 0」を変更する。>
    $strSQL .= " ORDER BY Newsrelease.NRStartDate DESC, Newsrelease.NRSortValue DESC, Newsrelease.NRCode DESC";
    $Conn    = ConnectNewsRelease();
    $result  = mysqli_query($Conn, $strSQL);
    if ($result != false && mysqli_num_rows($result) < 1) {
        mysqli_close($Conn);
        return;
    }

    $arrRet = array();
    $numLine = 0;
    while ($res = mysqli_fetch_assoc($result)) {
        $aLine = array();

        // （本サーバー用）掲載日を過ぎていて、ストップフラグが経っていないもの
        if ($res["NRStopFlag"] == "0") {
            // 掲載期間を記載する条件を追加すること（1460日前（4年前）～今日まで？）
            if (strtotime($Today."-1460 days") <= strtotime($res["NRStartDate"])) {
                // 掲載終了日が指定されていない場合 // 1日後を掲載終了日とする // 掲載終了日が指定されている場合
                $vNREndDateTemp = (is_null($res["NREndDate"])) ? date("Y/m/d", strtotime($Today."+1 days")) : $res["NREndDate"];
                if (strtotime($Today) <= strtotime($vNREndDateTemp)) {
                    $aLine[] = "ok";
                    $aLine[] = $res["NRStopFlag"];
                    $aLine[] = $res["NRStartDate"];
                    $aLine[] = $res["NREndDate"];
                    $aLine[] = $res["NRComment"];
                    $aLine[] = $res["NRTitle"];
                    $aLine[] = $res["NRBody"];
                    $aLine[] = $res["NRLinkAddress"];
                    $aLine[] = $res["NRLinkAnchor"];
                    $aLine[] = "";      // タイトル2(ダミー)
                    $aLine[] = "";      // 本文2(ダミー)
                    $aLine[] = "";      // リンク先2
                    $aLine[] = "";      // PickUpランダム掲載(農業独自)
                    $arrRet[] = $aLine;
                    $numLine++;
                }
            }
        }

        // トップページやそり蔵公民館のページでは4件が限度
        if ($PageCategory == "top") {
            if ($numLine == 4) {
                break;
            }
        }
    }
    mysqli_free_result($result);
    mysqli_close($Conn);
    return $arrRet;
}

// パートナー会員頁専用(SAAG/SOSP/SOUPで使用)
// NewsReleaseBody から呼び出される
function WriteNRTopTitle($arrLine, $count) {
    $vNRStartDate = date("Y/m/d", strtotime($arrLine[2]));
    $vNRTitle1 = str_replace("<BR>", "&nbsp;", $arrLine[5]);
    $vNRTarget = (substr($arrLine[7], 0, 4) == "http") ? " target='_blank'" : "";

    if ($arrLine[0] == "ok") { ?>
        <li<?php if ($count == 1) { ?> class="noline"<?php } ?>><?= $vNRStartDate ?><br><a href="<?= ((strpos($arrLine[7], "www") !== false) ? $arrLine[7] : str_replace("asp", "php", $arrLine[7])) ?>" <?= $vNRTarget ?> onfocus="this.blur()" title="<?= $vNRTitle1 ?>"><?= AbbreviateString($vNRTitle1, 46) ?></a></li>
<?php
    }
    elseif ($arrLine[0] == "show") {
?>
        <li<?php if ($count == 1) { ?> class="noline"<?php } ?>><?= $vNRStartDate ?><br><?= AbbreviateString($vNRTitle1, 46) ?></li>
<?php
    }
}

// パートナー会員頁専用(SAAG/SOSP/SOUPで使用)
// NewsReleaseBody から呼び出される
function WriteNRInfoTitle($arrLine) {
    $vNRStartDate = date("Y/m/d", strtotime($arrLine[2]));
    $vNRTitle1 = str_replace("<BR>", "&nbsp;", $arrLine[5]);
    $vNRTarget = (substr($arrLine[7], 0, 21) != "http://www.sorizo.net") ? " target='_blank'" : "";

    if ($arrLine[0] == "ok") { ?>
        <!--News(START)-->
        <dl>
            <dt><?= $vNRStartDate ?></dt>
            <dd><a href="<?= ((strpos($arrLine[7], "www") !== false) ? $arrLine[7] : str_replace("asp", "php", $arrLine[7])) ?>" <?= $vNRTarget ?>><?= $vNRTitle1 ?></a></dd>
        </dl>
<?php
    }
    elseif ($arrLine[0] == "show") {
?>
        <!--News(START)-->
        <dl>
            <dt><?= $vNRStartDate ?></dt>
            <dd><?= $vNRTitle1 ?></dd>
        </dl>
<?php
    }
}

// 業務サポート頁専用（news.asp）
// ReadCLHeaderTitle、ContentsListToppage から呼び出される
function ReplaceCSVText(&$Wk_NR_komoku) {
    // シリアルNo.の置き換え（もっといい方法はないのか…）
    $Wk_NR_komoku = str_replace("%%SERIAL_NUMBER%%", @$_SESSION["serial_no"], $Wk_NR_komoku);
}

// 文字数を丸めるスクリプト
function AbbreviateString($str, $limit) {
//　↓↓　<2020/08/13> <Vinhdao>
    //if (strlen($str) > $limit) {
        // return substr($str, 0, $limit - 1)."...";
    if (mb_strlen($str) > $limit) {
        return mb_substr($str, 0, $limit - 1) . "...";
//　↑↑　<2020/08/13> <Vinhdao>
    }
    return $str;
}
?>