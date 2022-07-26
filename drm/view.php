<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemValue();
    WriteLog(true);

    // パラメータ取得
    $Menu  = htmlspecialchars(@$_REQUEST["Menu"]);
    $MesID = (htmlspecialchars(@$_REQUEST["MesID"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["MesID"])) : '';
    $conn  = ConnectSorizo();
    echo "<html lang='ja'>
            <head>
                <meta HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=x-sjis'>
                <title>".$GLOBALS['MainTitle']."</title>
";

include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php');

    echo "            </head>
            <body BGCOLOR='lightyellow'>";

    // デフォルト画面
    switch ($Menu) {
        case '':
        case 'Mes':
            if ($MesID != '') {
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $SQL = "SELECT * FROM ".TFAQDATA." WHERE MsgId=".$MesID." ORDER BY MsgID";
//                $result = mysqli_query($conn, $SQL);

                $SQL = "SELECT * FROM ".TFAQDATA." WHERE MsgId = ? ORDER BY MsgID";
                $stmt = mysqli_prepare($conn, $SQL);
                mysqli_stmt_bind_param($stmt, 'i', $MesID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑

                if (mysqli_num_rows($result) > 0) {
                    echo "<table cellpadding='5' width='100%'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "  <tr><td style='font-weight:bold'>&nbsp".CnvMsg2View($row["Title"])."</td></tr>
                                <tr><td BGCOLOR='lightcyan' style='border:1px solid steelblue;'><img src='images/mkqes.png'>&nbsp".CnvMsg2View($row["Question"])."</td></tr>
                                <tr><td HEIGHT=10></td></tr>
                                <tr><td BGCOLOR='seashell' style='border:1px solid steelblue;'><img src='images/mkans.png'>&nbsp".CnvMsg2View($row["Answer"])."</td></tr>
                                <tr><td><br><font Color='RED'>※この回答は、".date('Y/m/d', strtotime($row["AnsDate"]))."時点のものです。</font></td></tr>";
                    }
                    echo "</table>";
                }
                else {
                    echo "該当する情報がありません";
                }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
                mysqli_free_result($result);
            }
            else {
                echo "上のリストから選択してください。";
            }
            break;
        case 'Warn':
            echo "<font color='RED' SIZE='5'>【警告】</font><H2>".$_REQUEST["Warn"]."</H2>";
            break;
    }
    mysqli_close($conn);
    echo "</body></html>";
?>
