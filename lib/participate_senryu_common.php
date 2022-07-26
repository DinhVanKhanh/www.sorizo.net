<?php
// テーマ年月の切り替えを期間で指定します
// ※毎月変えるとは限らないので、期間を決められます。
// 更新前に各ページの準備をすることを忘れないでください。
$Today = date("Y/m/d");
if (FormatDateTime($Today) >= "2014/12/01" && FormatDateTime($Today) < "2015/01/01") {
    $SenryuThemeYYYYMM = "2014-12";
}
elseif (FormatDateTime($Today) >= "2015/01/01" && FormatDateTime($Today) < "2015/02/01") {
    $SenryuThemeYYYYMM = "2015-01";
}
else {
    $SenryuThemeYYYYMM = "2015-02";
}

// 通常、自動的にテーマを変更する場合はこちらを使用します。
// 必要ない場合にはコメントにして上の値を活かしてください。
$SenryuThemeYYYYMM = substr(FormatDateTime($Today), 0, 4)."-".substr(FormatDateTime($Today), 5, 2);

// 優秀賞（最新）の年月を指定します（毎月更新）
global $SenryuLastPrizeYYYYMM;
global $SenryuLastPrizeText;
global $SenryuLastPrizeMessage;
$SenryuLastPrizeYYYYMM  = "2022-07";
$SenryuLastPrizeText    = "缶ビール　飲んだ空缶　リサイクル（田吾作の息子）";
$SenryuLastPrizeMessage = "おめでとうございます！<br>福井県の田吾作の息子 様には<br>記念品をお送りします！";
// テストサーバーで強制的にテーマ年月を指定する場合はこちらで（確認用等）
// 優秀賞の画像のファイル名
$PrizeShikishiFile = "/participate/images_senryu/PrizeShikishi_".$SenryuThemeYYYYMM.".gif";
$PrizeMessageText  = $SenryuLastPrizeMessage;
if ($SenryuLastPrizeYYYYMM != $SenryuThemeYYYYMM) {
    $PrizeShikishiFile = "/participate/images_senryu/PrizeShikishi_preparation.gif";
    $PrizeMessageText  = "少々お待ちくださいね♪";
}

// 最新の投稿を件数を指定して抽出します（2013/06/22 Kentaro.Watanabe）
function FreshSenryuPosting($number) {
    // 掲載ナンバー（連番）のカウントを始めます。
    $keisai_no = 0;
    $Conn = ConnectSorizo();
    $sql = "SELECT * FROM SoriSenryu_Senryu ORDER BY ID desc";
    $result = mysqli_query($Conn, $sql);
    while ($res = mysqli_fetch_assoc($result)) {
        // 管理者による許可が行われており、投稿者による削除が行われていない場合は掲載します。
        // M_Judge　0:掲載確認待ち、1:掲載OK、2:掲載不可
        // シリアルが空の場合は飛ばすことに(2016/12/26 K.Watanabe)
        if (($res["M_Judge"] == 0 || $res["M_Judge"] == 1) && is_null($res["P_DeleteDate"]) && ($res["P_Serial"] != "")) {
            // 投稿者名が入力されていればその名前を、無ければ匿名扱いとします。
            $OnlineName = ($res["P_OnlineName"] == "") ? "匿名" : $res["P_OnlineName"];
            $Pref = ($res["P_Pref"] != "") ? "（".$res["P_Pref"]."）" : "";

            $keisai_no++;
            if ($keisai_no == $number) {
                echo "<p"." class='senryu-3rd'>".$res["P_Message"]."［&nbsp;<!--投稿者：-->".$OnlineName.$Pref."&nbsp;］</p>\n";
                break;
            }
            else {
                echo "<p>".$res["P_Message"]."［&nbsp;<!--投稿者：-->".$OnlineName.$Pref."&nbsp;］</p>\n";
            }
        }
    }
    mysqli_free_result($result);
    mysqli_close($Conn);

    echo ($keisai_no == 0) ? "<TR><TD align='center' style='padding:50px 0;'>現在、掲載されている投稿はありません。</TD></TR>" : "";
}

// 投稿毎の拍手の数をカウントします。投稿された際のIDを持ってくる必要があります。
function ApplauseCount($ID) {
    $Conn = ConnectSorizo();
    $sql = "SELECT COUNT(*) AS AC FROM SoriSenryu_Applause WHERE A_ApplauseID = ".$ID.";";
    $result = mysqli_query($Conn, $sql);
    $res = mysqli_fetch_assoc($result);
    $ret = $res["AC"];
    mysqli_free_result($result);
    mysqli_close($Conn);
    return $ret;
}

// すでに拍手を送ったかどうかをチェックします。シリアルNoでチェックします。
function SentApplauseChk($ID, $serial) {
    $Conn = ConnectSorizo();
    $sql = "SELECT COUNT(*) AS AC FROM SoriSenryu_Applause WHERE A_ApplauseID = ".$ID." AND A_Serial = '".$serial."';";
    $result = mysqli_query($Conn, $sql);
    $res = mysqli_fetch_assoc($result);
    $ret = $res["AC"];
    mysqli_free_result($result);
    mysqli_close($Conn);
    return $ret;
}

// Xuan add test
function prcSqlError($sql = "") {
    echo $sql;
}
?>