<?php
    require_once '../lib/common.php';
    require_once '../lib/participate_senryu_common.php';

    $P_Date = date("Y-m-d H:i:s");
    $P_Serial = @$_POST["P_Serial"];
    $P_UserID = "";
    $P_Category = @$_POST["P_Category"];
    $P_Theme = @$_POST["P_Theme"];
    $P_Message = @$_POST["P_Message"];
    $P_OnlineName = @$_POST["P_OnlineName"];
    $P_Pref = @$_POST["P_Pref"];
    $IPAddress = @$_SERVER["REMOTE_ADDR"];
    $M_Judge = 0; // 初めは固定（保留中扱い）

    $Conn = ConnectSorizo();
// 2020/01/10 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//    $sql = "INSERT INTO SoriSenryu_Senryu(P_Date,P_DeleteDate,P_Serial,
//                P_UserID,P_Category,P_Theme,
//                P_Message,P_OnlineName,P_Pref,
//                IPAddress,M_Date,M_Judge)";
//    $sql .= " VALUES( '".$P_Date."',NULL,'".$P_Serial."','".$P_UserID."','";
//    $sql .= $P_Category."','".$P_Theme."','".$P_Message."','".$P_OnlineName."','";
//    $sql .= $P_Pref."','".$IPAddress."',NULL,'".$M_Judge."')";
//    mysqli_query($Conn, $sql);

    $P_DeleteDate = NULL;
    $M_Date = NULL;

    $sql = "INSERT INTO SoriSenryu_Senryu(P_Date, P_DeleteDate, P_Serial,
                    P_UserID, P_Category, P_Theme,
                    P_Message, P_OnlineName, P_Pref,
                    IPAddress, M_Date, M_Judge) ".
                " VALUES(?, ?, ?, ".
                " ?, ?, ?, ".
                " ?, ?, ?, ".
                " ?, ?, ?)";
    $stmt = mysqli_prepare($Conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssssssssi',
                $P_Date, $P_DeleteDate, $P_Serial,
                $P_UserID, $P_Category, $P_Theme,
                $P_Message, $P_OnlineName, $P_Pref,
                $IPAddress, $M_Date, $M_Judge);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
// 2020/01/10 t.maruyama 修正 ↑↑
    mysqli_close($Conn);
    header("Location: senryu_complete.php");
?>