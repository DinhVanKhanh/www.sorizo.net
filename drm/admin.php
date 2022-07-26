<?php
    session_cache_limiter ('private');
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemvalue();

    $Conn = ConnectSorizo();
    $OwnerPassWord = $OwnerGroupID = $AdMode = $ErrSystem = $ErrOwner = $GroupID = "";

    // 認証
    $Menu           = @$_POST["Menu"];
    $SystemPassWord = htmlentities(@$_POST["SystemPassWord"]);
    $ThisFile       = $_SERVER["SCRIPT_NAME"];
    $result         = mysqli_query($Conn, CheckSQL("select SystemPassWord FROM ".TSYSTEM));
    $res            = mysqli_fetch_assoc($result);
    $iInit          = 0;

    if ($res["SystemPassWord"] == '') {
        $iInit = 1;
    }
    else {
        if ($SystemPassWord != "") {
            if ($SystemPassWord == $res["SystemPassWord"]) {
                $AdMode = "System";
            } 
            else {
                $iInit = 2;
                $ErrSystem = "パスワードが違います";
            }
        } 
        else {
            $OwnerPassWord = htmlentities(@$_POST["OwnerPassWord"]);
            if ($OwnerPassWord != "") {
                $OwnerGroupID = round(htmlentities($_POST["OwnerGroupID"]));
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $result2 = mysqli_query($Conn, CheckSQL("select GroupID FROM ".TGROUP." WHERE OwnerPassWord='".CheckSQ($OwnerPassWord)."' AND GroupID=".$OwnerGroupID));

                $sql = "SELECT GroupID FROM ".TGROUP." WHERE OwnerPassWord = ? AND GroupID = ? ";
                $stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($stmt, 'si', $OwnerPassWord,$OwnerGroupID);
                mysqli_stmt_execute($stmt);
                $result2 = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                if (($result2 != false) && (mysqli_num_rows($result2) > 0)) {
                    $AdMode = "Owner";
                }
                else {
                    $iInit = 2;
                    $ErrOwner = "パスワードが違います";
                }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                mysqli_free_result($result2);
            } 
            else {
                $iInit = 2;
            }
        }
    }
    mysqli_free_result($result);
    SetAccessLog("Admin", $Menu);

    $Warn = "";
    if ($Menu == "ADSystem2") {
        $MT            = htmlentities($_POST["MainTitle"]);
        $TI            = htmlentities($_POST["TitleImg"]);
        $BC            = htmlentities($_POST["BodyColor"]);
        $IM            = str_replace("&lt;BR&gt;", "<br>", htmlentities($_POST["InitMessage"]));
        $AMT           = htmlentities($_POST["AdminMailto"]);
        $RURL          = htmlentities($_POST["ReturnURL"]);
        $RURLT         = htmlentities($_POST["ReturnURLTitle"]);
        $ScT           = htmlentities($_POST["ScriptTimeout"]);
        $ST            = htmlentities($_POST["SessionTimeout"]);
        $CE            = htmlentities($_POST["CookiesExpires"]);
        $AL            = htmlentities($_POST["AccessLog"]);
        $SystemResMail = htmlentities($_POST["SystemResMail"]);
        $MailServer    = htmlentities($_POST["MailServer"]);
        $MailFrom      = htmlentities($_POST["MailFrom"]);
        
        if (($SystemResMail == 1) && ($MailServer == "" || $MailFrom == "")) { 
            $Warn = "メール通知をする場合には、メールサーバとメール送信者を入力してください";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "UPDATE ".TSYSTEM." SET ";
//            $sql .= ($MT != "") ? "MainTitle='".CheckSQ($MT)."'," : "MainTitle=Null,";
//            if ($TI != "") {
//                $sql .= "TitleImg='".CheckSQ($TI)."',";
//            }
//            $sql .= ($BC != "") ? "BodyColor='".CheckSQ($BC)."'," : "BodyColor=Null,";
//            $sql .= ($IM != "") ? "InitMessage='".CheckSQ($IM)."'," : "InitMessage=Null,";
//            $sql .= (is_numeric($ScT)) ? "ScriptTimeout=".$ScT."," : "";
//            $sql .= (is_numeric($ST)) ? "SessionTimeout=".$ST."," : "";
//            $sql .= (is_numeric($CE)) ? "CookiesExpires=".$CE."," : "";
//            $sql .= ($AMT != "") ? "AdminMailto='".CheckSQ($AMT)."'," : "AdminMailto=Null,";
//            $sql .= ($RURL != "") ? "ReturnURL='".CheckSQ($RURL)."'," : "ReturnURL=Null,";
//            $sql .= ($RURLT != "") ? "ReturnURLTitle='".CheckSQ($RURLT)."'," : "ReturnURLTitle=Null,";
//            $sql .= ($AL == "1") ? "AccessLog=0," : "AccessLog=1,";
//            $sql .= ($SystemResMail == 1) ? "SystemResMail=0," : "SystemResMail=1,";
//            $sql .= ($MailServer != "") ? "MailServer='".CheckSQ($MailServer)."'," : "MailServer=Null,";
//            $sql .= ($MailFrom != "") ? "MailFrom='".CheckSQ($MailFrom)."'" : "MailFrom=Null";
//            mysqli_query($Conn, CheckSQL($sql));

            $sql = "UPDATE ".TSYSTEM." SET ".
            " MainTitle = ?, ".
            " BodyColor = ?, ".
            " InitMessage = ?, ".
            " ScriptTimeout = ?, ".
            " SessionTimeout = ?, ".
            " CookiesExpires = ?, ".
            " AdminMailto = ?, ".
            " ReturnURL = ?, ".
            " ReturnURLTitle = ?, ".
            " AccessLog = ?, ".
            " SystemResMail = ?, ".
            " MailServer = ?, ".
            " MailFrom = ? ";

            if ($TI != "") {
                $sql .= ", TitleImg = ? ";
            }

            $stmt = mysqli_prepare($Conn, $sql);

            $ALValue =  ($AL == "1") ? 0 : 1;
            $SystemResMailValue =  ($SystemResMail == 1) ? 0 : 1;
            if ($TI != "") {
                mysqli_stmt_bind_param($stmt, 'sssiiisssiisss',
                        $MT, $BC, $IM, $ScT,
                        $ST, $CE, $AMT, $RURL,
                        $RURLT, $ALValue, $SystemResMailValue,
                        $MailServer, $MailFrom, $TI);
            }
            else {
                mysqli_stmt_bind_param($stmt, 'sssiiisssiiss',
                        $MT, $BC, $IM, $ScT,
                        $ST, $CE, $AMT, $RURL,
                        $RURLT, $ALValue, $SystemResMailValue,
                        $MailServer, $MailFrom);
            }
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑

            if ($SystemResMail == 2) { 
                mysqli_query($Conn, CheckSQL("UPDATE ".TGROUP." SET ResMail=1"));
            }
            $Menu = "ADSystem";
        }
    }
    elseif ($Menu == "ADGroupEdit" && $AdMode == "System") {
        $GroupID = htmlentities($_POST["GroupID"]);
    }
    elseif ($Menu == "ADGroupEdit2" && $_POST["emode"] == "　設定変更　") {
        $GroupID           = htmlentities($_POST["GroupID"]);
        $GroupName         = htmlentities($_POST["GroupName"]);
        $GroupTitle        = htmlentities($_POST["GroupTitle"]);
        $GroupModeID       = htmlentities($_POST["GroupModeID"]);
        $MaxUploadFileSize = htmlentities($_POST["MaxUploadFileSize"]);
        $WritePassWord     = htmlentities($_POST["WritePassWord"]);
        $ReadPassWord      = htmlentities($_POST["ReadPassWord"]);
        $OwnerPassWord     = htmlentities($_POST["OwnerPassWord"]);
        $ResMail           = htmlentities($_POST["ResMail"]);
        
        if ($GroupName == "") {
            $Warn = "グループ名は空にできません";
        }
        elseif (!is_numeric($MaxUploadFileSize)) {
            $Warn = "添付ファイル最大サイズには数値を入力してください";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "UPDATE ".TGROUP." SET ";
//            $sql .= ($GroupTitle != "") ? "GroupTitle='".CheckSQ($GroupTitle)."'," : "GroupTitle=Null,";
//            $sql .= "GroupModeID=".$GroupModeID.",";
//            $sql .= "MaxUploadFileSize=".$MaxUploadFileSize.",";
//            $sql .= ($WritePassWord != "") ? "WritePassWord='".CheckSQ($WritePassWord)."'," : "WritePassWord=Null,";
//            $sql .= ($ReadPassWord != "") ? "ReadPassWord='".CheckSQ($ReadPassWord)."'," : "ReadPassWord=Null,";
//            if ($AdMode == "System") {
//                $sql .= ($OwnerPassWord != "") ? "OwnerPassWord='".CheckSQ($OwnerPassWord)."'," : "OwnerPassWord=Null,";
//            }
//            $sql .= ($GLOBALS['SystemResMail'] == 0) ? "ResMail=".$ResMail."," : "ResMail=1,";
//            $sql .= "Groupname='".CheckSQ($GroupName)."'";
//            $sql .= " WHERE GroupID=".$GroupID;
//            mysqli_query($Conn, CheckSQL($sql));

            $ResMailValue = ($GLOBALS['SystemResMail'] == 0) ? $ResMail : 1;

            $sql = "UPDATE ".TGROUP." SET ".
            " GroupTitle = ?, ".
            " GroupModeID = ?, ".
            " MaxUploadFileSize = ?, ".
            " WritePassWord = ?, ".
            " ReadPassWord = ?, ".
            " ResMail = ?, ".
            " GroupName = ? ";
            if ($AdMode == "System") {
                $sql .= ", OwnerPassWord = ? ";
            }
            $sql .= " WHERE GroupID = ? ";

            $stmt = mysqli_prepare($Conn, $sql);

            $ResMailValue = ($GLOBALS['SystemResMail'] == 0) ? $ResMail : 1;
            if ($AdMode == "System") {
                mysqli_stmt_bind_param($stmt, 'siississi',
                        $GroupTitle, $GroupModeID, $MaxUploadFileSize,
                        $WritePassWord, $ReadPassWord, $ResMailValue,
                        $GroupName, $OwnerPassWord, $GroupID );
            }
            else {
                mysqli_stmt_bind_param($stmt, 'siissisi',
                        $GroupTitle, $GroupModeID, $MaxUploadFileSize,
                        $WritePassWord, $ReadPassWord, $ResMailValue,
                        $GroupName, $GroupID);
            }
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
            $Menu = "ADGroupEdit";
        }
    }
    elseif ($Menu == "ADGroupAdd2") {
        $GroupName         = $_POST["GroupName"];
        $GroupTitle        = $_POST["GroupTitle"];
        $GroupModeID       = $_POST["GroupModeID"];
        $MaxUploadFileSize = $_POST["MaxUploadFileSize"];
        $WritePassWord     = $_POST["WritePassWord"];
        $ReadPassWord      = $_POST["ReadPassWord"];
        $OwnerPassWord     = $_POST["OwnerPassWord"];
        $ResMail           = $_POST["ResMail"];

        if ($GroupName == "") {
            $Warn = "グループ名は空にできません";
        }
        elseif (!is_numeric($MaxUploadFileSize)) {
            $Warn = "添付ファイル最大サイズには数値を入力してください";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "INSERT INTO ".TGROUP." (";
//            $sql .= "GroupName,GroupTitle,GroupModeID,MaxUploadFileSize,WritePassWord,ReadPassWord,OwnerPassWord,ResMail,OrderID";
//            $sql .= ") valueS (";
//            $sql .= "'".CheckSQ($GroupName)."',";
//            $sql .= ($GroupTitle != "") ? "'".CheckSQ($GroupTitle)."'," : "Null,";
//            $sql .= $GroupModeID.",";
//            $sql .= $MaxUploadFileSize.",";
//            $sql .= ($WritePassWord != "") ? "'".CheckSQ($WritePassWord)."'," : "Null,";
//            $sql .= ($ReadPassWord != "") ? "'".CheckSQ($ReadPassWord)."'," : "Null,";
//            $sql .= (($OwnerPassWord != "") && ($AdMode == "System")) ? "'".CheckSQ($OwnerPassWord)."'," : "Null,";
//            $sql .= $GLOBALS['SystemResMail'].',';
//
//            $result = mysqli_query($Conn, "select Max(OrderID)+1 AS MaxID FROM ".TGROUP);
//            $res    = mysqli_fetch_assoc($result);
//            $sql .= (!is_null($res["MaxID"])) ? $res["MaxID"] : "1";
//            mysqli_free_result($result);
//
//            $sql .= ")";
//            echo $sql;
//            mysqli_query($Conn, CheckSQL($sql));

            $result = mysqli_query($Conn, "select Max(OrderID)+1 AS MaxID FROM ".TGROUP);
            $res    = mysqli_fetch_assoc($result);
            $MaxIDValue = (!is_null($res["MaxID"])) ? $res["MaxID"] : 1;
            mysqli_free_result($result);

            $OwnerPassWordValue = (($OwnerPassWord != "") && ($AdMode == "System")) ? $OwnerPassWord : Null;
            $SystemResMailValue = $GLOBALS['SystemResMail'];

            $sql = "INSERT INTO ".TGROUP." (".
                " GroupName, GroupTitle, GroupModeID, ".
                " MaxUploadFileSize, WritePassWord, ReadPassWord, ".
                " OwnerPassWord, ResMail, OrderID".
                ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssiisssii',
                            $GroupName, $GroupTitle, $GroupModeID,
                            $MaxUploadFileSize, $WritePassWord, $ReadPassWord,
                            $OwnerPassWordValue, $SystemResMailValue, $MaxIDValue);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
            $result = mysqli_query($Conn, "SELECT GroupID FROM ".TGROUP." ORDER BY GroupID DESC");
            $res    = mysqli_fetch_assoc($result);
            $GroupID = $res["GroupID"];
            mysqli_free_result($result);
            $Menu = "ADGroupEdit";
        }
    }
    elseif ($Menu == "ADGroupOrder" || $Menu == "ADSumOrder" || $Menu == "ADSumMessageOrder") {
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        $stmt = NULL;
// 2020/01/06 t.maruyama 追加 ↑↑
        if ($Menu == "ADGroupOrder") {
            $ot = "GroupID";
            $tn = TGROUP;
            $SQL = "SELECT * FROM ".TGROUP." ORDER BY OrderID";
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            $stmt = mysqli_prepare($Conn, $SQL);
// 2020/01/06 t.maruyama 追加 ↑↑
            $Menu = "ADGroup";
        }
        elseif ($Menu == "ADSumOrder") {
            $ot = "SumID";
            $tn = TSUM;
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $SQL = "select * FROM ".$tn." WHERE GroupID=".htmlentities($_POST["GroupID"])." ORDER BY OrderID";

            $GroupIDValue =  htmlentities($_POST["GroupID"]);
            $SQL =  "SELECT * FROM ".TSUM." WHERE GroupID = ? ORDER BY OrderID";
            $stmt = mysqli_prepare($Conn, $SQL);
            mysqli_stmt_bind_param($stmt, 'i',$GroupIDValue);
// 2020/01/06 t.maruyama 修正 ↑↑
            $Menu = "ADSum2";
        }
        else {
            $ot = "SumMesID";
            $tn = TSUMMESSAGE;
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $SQL = "select * FROM ".$tn." WHERE SumID=".htmlentities($_POST["SumID"])." ORDER BY OrderID";

            $SumIDValue = htmlentities($_POST["SumID"]);
            $SQL = "SELECT * FROM ".TSUMMESSAGE." WHERE SumID = ? ORDER BY OrderID";
            $stmt = mysqli_prepare($Conn, $SQL);
            mysqli_stmt_bind_param($stmt, 'i',$SumIDValue);
// 2020/01/06 t.maruyama 修正 ↑↑
            $Menu = "ADSumMessage";
        }
        $otv = htmlspecialchars($_POST[$ot]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $result = mysqli_query($Conn, $SQL);

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        if (($result != false) && (mysqli_num_rows($result) > 0)) {
            $data = array();
            while ($res = mysqli_fetch_assoc($result)) {
                $data[] = $res;
            }

            $count = count($data);
            for ($i = 0; $i < $count; $i++) {
                if ($data[$i][$ot] == round($otv)) {
                    $B1 = $data[$i][$ot];
                    $O2 = $data[$i]["OrderID"];

                    if (($_POST["Dir"] == "上へ") && ($i > 0)) {
                        $B2 = $data[$i - 1][$ot];
                        $O1 = $data[$i - 1]["OrderID"];
                    }
                    else {
                        if ($i == ($count - 1)) {
                            break;
                        }
                        $B2 = $data[$i + 1][$ot];
                        $O1 = $data[$i + 1]["OrderID"];
                    }

                    mysqli_query($Conn, "UPDATE ".$tn." SET OrderID=".$O1." WHERE ".$ot."=".$B1);
                    mysqli_query($Conn, "UPDATE ".$tn." SET OrderID=".$O2." WHERE ".$ot."=".$B2);
                    break;
                }
            }
        }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
        mysqli_free_result($result);
    }
    elseif ($Menu == "ADGroupDelete2") {
        if (htmlentities($_POST["emode"]) == "　削　除　") {
            $GroupID = htmlentities($_POST["GroupID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $result = mysqli_query($Conn, CheckSQL("select * FROM ".TMESSAGE." WHERE GroupID=".$GroupID));

            $sql = "SELECT * FROM ".TMESSAGE." WHERE GroupID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$GroupID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                while ($res = mysqli_fetch_assoc($result)) {
                    // $res["MessageID"]は、テーブルから取得した値なのでmysqli_prepareを使わなくても大丈夫だと思う。
                    mysqli_query($Conn, "DELETE FROM ".TUPLOAD." WHERE MessageID=".$res["MessageID"]);
                    mysqli_query($Conn, "DELETE FROM ".TSUMMESSAGE." WHERE MessageID=".$res["MessageID"]);
                }
            }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
            mysqli_free_result($result);

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            mysqli_query($Conn, CheckSQL("DELETE FROM ".TMESSAGE." WHERE GroupID=".$GroupID));

            $sql = "DELETE FROM ".TMESSAGE." WHERE GroupID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$GroupID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            mysqli_query($Conn, CheckSQL("DELETE FROM ".TGROUP." WHERE GroupID=".$GroupID));

            $sql = "DELETE FROM ".TGROUP." WHERE GroupID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$GroupID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        }
        $Menu = "ADGroup";
    }
    elseif ($Menu == "ADMessageAdminAdd") {
        $MailAddr = htmlentities($_POST["MailAddr"]);
        $GroupID = htmlentities($_POST["GroupID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        mysqli_query($Conn, CheckSQL("INSERT INTO ".TMAILOWNER." (GroupID,MailAddr,MailLevel) valueS (".$GroupID.",'".CheckSQ($MailAddr)."',"."1)"));

        $MailLevel = 1;

        $sql = "INSERT INTO ".TMAILOWNER." (GroupID,MailAddr,MailLevel) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'isi',$GroupID, $MailAddr, $MailLevel);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        $Menu = "ADGroupEdit2";
    }
    elseif ($Menu == "ADMessageAdminDel") {
        $MailID = htmlentities($_POST["MailID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        mysqli_query($Conn, CheckSQL("DELETE FROM ".TMAILOWNER." WHERE MailID=".$MailID));

        $sql = "DELETE FROM ".TMAILOWNER." WHERE MailID = ?";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i',$MailID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        $Menu = "ADGroupEdit2";
    }
    elseif ($Menu == "ADMessageDel3") {
        if ($_POST["emode"] == "　削　除　") {
            $MessageID = htmlentities($_POST["MessageID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            mysqli_query($Conn, CheckSQL("DELETE FROM ".TUPLOAD." WHERE MessageID=".$MessageID));
//            mysqli_query($Conn, CheckSQL("UPDATE ".TMESSAGE." SET Uname='-',EMail=NULL,Title='削除',Message='削除',URL=NULL,RetMail=0 WHERE MessageID=".$MessageID));

            $sql = "DELETE FROM ".TUPLOAD." WHERE MessageID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$MessageID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $sql = "UPDATE ".TMESSAGE." SET UName = '-', EMail = NULL, Title = '削除', ".
                                            "Message = '削除', URL = NULL, RetMail = 0 WHERE MessageID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$MessageID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        }
        $Menu = "ADMessageDel";
    }
    elseif (($Menu == "ADSum") && ($AdMode == "Owner")) { 
        $Menu = "ADSum2";
    }
    elseif ($Menu == "ADSumAdd2") {
        $SumTitle = htmlentities($_POST["SumTitle"]);
        $GroupID = htmlentities($_POST["GroupID"]);
        if ($SumTitle == "") {
            $Warn = "まとめタイトルは空にできません";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "INSERT INTO ".TSUM." (SumTitle,GroupID,OrderID) valueS ('".CheckSQ($SumTitle)."',".$GroupID.",";
//            $result = mysqli_query($Conn, "select Max(OrderID)+1 AS MaxID FROM ".TSUM);
//            $res    = mysqli_fetch_assoc($result);
//            $sql .= (!is_null($res["MaxID"])) ? $res["MaxID"] : "1";
//            mysqli_free_result($result);
//            $sql .= ")";
//            mysqli_query($Conn, CheckSQL($sql));

            $result = mysqli_query($Conn, "select Max(OrderID)+1 AS MaxID FROM ".TSUM);
            $res    = mysqli_fetch_assoc($result);
            $OrderIDValue = (!is_null($res["MaxID"])) ? $res["MaxID"] : 1;
            mysqli_free_result($result);

            $sql = "INSERT INTO ".TSUM." (SumTitle,GroupID,OrderID) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sii',$SumTitle, $GroupID, $OrderIDValue);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
            $Menu = "ADSum2";
        }
    }
    elseif ($Menu == "ADSumDel") {
        if (htmlentities($_POST["emode"]) == "　削　除　") {
            $SumID = htmlentities($_POST["SumID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            mysqli_query($Conn, CheckSQL("DELETE FROM ".TSUMMESSAGE." WHERE SumID=".$SumID));
//            mysqli_query($Conn, CheckSQL("DELETE * FROM ".TSUM." WHERE SumID=".$SumID));

            $sql = "DELETE FROM ".TSUMMESSAGE." WHERE SumID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$SumID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $sql = "DELETE FROM ".TSUM." WHERE SumID = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i',$SumID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        }
        $Menu = "ADSum2";
    }
    elseif ($Menu == "ADSumMessageAdd2") {
        $MessageID = htmlentities($_POST["MessageID"]);
        $SumID = htmlentities($_POST["SumID"]);

        $result = mysqli_query($Conn, "SELECT Max(OrderID)+1 AS MaxID FROM ".TSUMMESSAGE);
        $res    = mysqli_fetch_assoc($result);
        $OrderIDVaue = (!is_null($res["MaxID"])) ? $res["MaxID"] : 1;
        mysqli_free_result($result);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql = "INSERT INTO ".TSUMMESSAGE." (MessageID,SumID,OrderID) valueS (".$MessageID.",".$SumID.",";
//        $sql .= (!is_null($res["MaxID"])) ? $res["MaxID"] : "1";
//        $sql .= ")";
//        mysqli_query($Conn, CheckSQL($sql));

        $sql = "INSERT INTO ".TSUMMESSAGE." (MessageID,SumID,OrderID) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sii',$MessageID, $SumID, $OrderIDVaue);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        $Menu = "ADSum2";
    }
    elseif ($Menu == "ADSumMessageDel") {
        $SumMesID = htmlentities($_POST["SumMesID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        mysqli_query($Conn, CheckSQL("DELETE FROM ".TSUMMESSAGE." WHERE SumMesID=".htmlentities($_POST["SumMesID"])));

        $sql = "DELETE FROM ".TSUMMESSAGE." WHERE SumMesID = ?";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i',$SumMesID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        $Menu = "ADSum2";
    }
    elseif ($Menu == "ADDefvalue2") {
        $DefMenu = htmlentities($_POST["DefMenu"]);
        $psIchiran = htmlentities($_POST["psIchiran"]);
        $psNyuryoku = htmlentities($_POST["psNyuryoku"]);
        $UpdateV = htmlentities($_POST["UpdateV"]);
        $ThreadView = htmlentities($_POST["ThreadView"]);
        $ThreadOrder = htmlentities($_POST["ThreadOrder"]);
        $MessageRead = htmlentities($_POST["MessageRead"]);
        
        if (!is_numeric($psIchiran)) {
            $Warn = "『スレッド表示、ルート表示の数』には数値を入力してください";
        }
        elseif (!is_numeric($psNyuryoku)) {
            $Warn = "『入力順表示、検索結果の数』には数値を入力してください";
        }
        elseif (!is_numeric($UpdateV)) {
            $Warn = "『最新表示の日数』には数値を入力してください";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql  = "UPDATE ".TSYSTEM." SET ";
//            $sql .= "DefMenu='".CheckSQ($DefMenu)."',";
//            $sql .= "psIchiran=".$psIchiran.",";
//            $sql .= "psNyuryoku=".$psNyuryoku.",";
//            $sql .= "UpdateV=".$UpdateV.",";
//            $sql .= "ThreadView=".$ThreadView.",";
//            $sql .= "ThreadOrder=".$ThreadOrder.",";
//            $sql .= "MessageRead=".$MessageRead;
//            mysqli_query($Conn, CheckSQL($sql));

            $sql  = "UPDATE ".TSYSTEM." SET ".
                            "DefMenu = ?, ".
                            "psIchiran = ?, ".
                            "psNyuryoku = ?, ".
                            "UpdateV = ?, ".
                            "ThreadView = ?, ".
                            "ThreadOrder = ?, ".
                            "MessageRead = ?";

            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'siiiiii',
                            $DefMenu,$psIchiran, $psNyuryoku,
                            $UpdateV, $ThreadView, $ThreadOrder, $MessageRead );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
            $Menu = "ADDefvalue";
        }
    }
    elseif ($Menu == "ADPassWord2") {
        $SPW = $_POST["SPW"];
        if ($SPW == "") {
            $Warn = "パスワードは空にできません";
        }
        elseif ($SPW != $_POST["SPW2"]) {
            $Warn = "パスワードが違います";
        }
        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            mysqli_query($Conn, CheckSQL("UPDATE ".TSYSTEM." SET SystemPassWord='".CheckSQ($SPW)."'"));

            $sql = "UPDATE ".TSYSTEM." SET SystemPassWord = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $SPW);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
        }
    } ?>

    <!-- Show Front End -->
    <!DOCTYPE html>
            <html lang="ja">
                <head>
                    <meta HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=x-sjis'>
                    <title><?= $GLOBALS['MainTitle'] ?></title>
                </head>
                <?= $GLOBALS['BodyVal'] ?>
<?php
    if ($iInit == 1 || $iInit == 2) {
?>
        <img src="<?= $GLOBALS['TitleImg'] ?>" width='250'><br><br>
        管理用パスワード入力<br><br>
        <table BORDER=0 CELLPADDING='4' CELLSPACING='0'>
        <tr>
            <td bgcolor='#FFFFCC'>システム管理パスワード</td>
            <form action='<?= $ThisFile ?>' method='POST'>
                <input type='hidden' name='Menu' value=''>
                <td bgcolor='#FFFFCC'>　<input type='PASSWORD' name='SystemPassWord'></td>
                <td bgcolor='#FFFFCC'><input type='SUBMIT' value='<?= ($iInit == 1) ? "設　定" : "ログオン" ?>'></td>
            </form>
            <td>　<?= $ErrSystem ?></td>
        </tr>
<?php
        if ($iInit == 1) {
?>
            </table></body></html>
<?php
            mysqli_close($Conn);
            return;
        }

        $result = mysqli_query($Conn, CheckSQL("select GroupID,GroupName FROM ".TGROUP." WHERE OwnerPassWord IS NOT NULL ORDER BY OrderID"));
        if (($result != false) && (mysqli_num_rows($result) > 0))
        {
?>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <form action='<?= $ThisFile ?>' method='POST'>
                    <input type='hidden' name='Menu' value=''>
                    <td bgcolor='#FFFF99'>グループ別管理パスワード<br>
                    　グループ：<select name='OwnerGroupID'>
                    <?php while ($res = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?= $res["GroupID"] ?>"><?= $res["GroupName"] ?></option>
                    <?php } ?>
                    </select></td>
                    <td bgcolor='#FFFF99'>　<input type='PASSWORD' name='OwnerPassWord'></td>
                    <td bgcolor='#FFFF99'><input type='SUBMIT' value='ログオン'></td>
                </form>
                <td>　<?= $ErrOwner ?></td>
            </tr>
<?php
        }
        mysqli_free_result($result);
        mysqli_close($Conn);
        echo "</table><p><a href=".MainFile." target='_top'>掲示板</a>へ戻る</p></body></html>";
        return;
    }
?>
    <table width='100%' BORDER='0' CELLPADDING='2' CELLSPACING='0'>
        <tr>
            <td width='100' valign='TOP'>
                <img src='<?= $GLOBALS['TitleImg'] ?>' width='250'><br><br>【管理】
                <?php if ($AdMode == "System") { ?>
                    <form action='<?= $ThisFile ?>' method='POST'>
                        <input type='hidden' name='Menu' value='ADSystem'>
                        <?php RPassWord(); ?>
                        <input type='SUBMIT' value='システム設定　'>
                    </form><br>
                <?php } ?>

                <form action='<?= $ThisFile ?>' method='POST'>
                    <input type='hidden' name='Menu' value='ADGroup'>
                    <?php RPassWord(); ?>
                    <input type='SUBMIT' value='グループ設定　'>
                </form><br>

                <form action='<?= $ThisFile ?>' method='POST'>
                    <input type='hidden' name='Menu' value='ADMessageDel'>
                    <?php RPassWord(); ?>
                    <input type='SUBMIT' value='メッセージ削除'>
                </form><br>

                <form action='<?= $ThisFile ?>' method='POST'> 
                    <input type='hidden' name='Menu' value='ADSum'>
                    <?php RPassWord(); ?>
                    <input type='SUBMIT' value='まとめ設定　　'>
                </form><br>

                <?php if ($AdMode == "System") { ?>
                    <form action='<?= $ThisFile ?>' method='POST'>
                        <input type='hidden' name='Menu' value='ADDefvalue'>
                        <?php RPassWord(); ?>
                        <input type='SUBMIT' value='規定値設定　'>
                    </form><br>
                    
                    <form action='<?= $ThisFile ?>' method='POST'>
                        <input type='hidden' name='Menu' value='ADLog'>
                        <?php RPassWord(); ?>
                        <input type='SUBMIT' value='　　ロ　グ　　　'>
                    </form><br>
                    
                    <form action='<?= $ThisFile ?>' method='POST'>
                        <input type='hidden' name='Menu' value='ADPassWord'>
                        <?php RPassWord(); ?>
                        <input type='SUBMIT' value='　パスワード 　'>
                    </form><br>
                <?php } ?>

                <HR>
                <p><a href="<?= MainFile ?>" target='_top'>掲示板</a>へ戻る</p>
            </td>
            <td width='10'></td>
            <td valign='TOP'>
                <font color='RED'>** 注意 ** このページのソース表示でパスワードが確認できます。設定が終了したら一旦ブラウザを閉じてください。</font><br><br>
                <?php
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//                    $apNow = (htmlentities($_POST["apNow"]) != "") ? round(htmlentities($_POST["apNow"])) : 1;
                    $apNow = (isset($_POST["apNow"])) ? round(htmlentities($_POST["apNow"])) : 1;
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
                    if ($Warn != "") {
                        echo  "<font SIZE='+1'>【設定エラー】　</font><br><br>".$Warn."<br>";
                    }
                    elseif (($Menu == "ADSystem") && ($AdMode == "System")) {
                        $result = mysqli_query($Conn, CheckSQL("select * FROM ".TSYSTEM));
                        $res    = mysqli_fetch_assoc($result); ?>
                        <font SIZE='+1'>【システム設定】　</font>システム全般の設定をします<br><br>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <table BORDER='1' width='100%' CELLPADDING='3' CELLSPACING='1'>
                                <input type='hidden' name='Menu' value='ADSystem2'>
                                <?php RPassWord(); ?>
                                <tr align='CENTER' bgcolor='#DDFFDD'><td>項　目</td><td>設定値</td><td>備　考</td></tr>
                                <tr bgcolor='#FFFFCC'>
                                    <td>メインタイトル</td>
                                    <td><input type='TEXT' name='MainTitle' value='<?= $res["MainTitle"] ?>'></td>
                                    <td>ウィンドウのタイトルとして表示する文字です。</td>
                                </tr>
                                
                                <tr bgcolor='#FFFF99'>
                                    <td>メインタイトルイメージファイル</td>
                                    <td><input type='TEXT' name='TitleImg' value='<?= $res["TitleImg"] ?>'></td>
                                    <td>掲示板のタイトル画像です。同じフォルダに置いたファイル名を入力します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td>背景色</td>
                                    <td><input type='TEXT' name='BodyColor' value='<?= $res["BodyColor"] ?>'></td>
                                    <td>背景色を000000からFFFFFFで指定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>初期メッセージ</td>
                                    <td><input type='TEXT' name='InitMessage' value='<?= $res["InitMessage"] ?>' SIZE='60'></td>
                                    <td>起動時に右下のフレームに表示するメッセージです。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td>問い合わせ先管理者メールアドレス</td>
                                    <td><input type='TEXT' name='AdminMailto' value='<?= $res["AdminMailto"] ?>' SIZE='60'></td>
                                    <td>管理者のメールアドレスを入力します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>戻り先URL</td>
                                    <td><input type='TEXT' name='ReturnURL' value='<?= $res["ReturnURL"] ?>' SIZE='60'></td>
                                    <td>戻り先のURLを指定します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td>戻り先URLタイトル</td>
                                    <td><input type='TEXT' name='ReturnURLTitle' value='<?= $res["ReturnURLTitle"] ?>'></td>
                                    <td>戻り先URLのタイトルを指定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>スクリプトのタイムアウト時間（秒）</td>
                                    <td><input type='TEXT' name='ScriptTimeout' value='<?= $res["ScriptTimeout"] ?>'></td>
                                    <td>サーバのタイムアウト時間を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td>セッションのタイムアウト時間（分）</td>
                                    <td><input type='TEXT' name='SessionTimeout' value='<?= $res["SessionTimeout"] ?>'></td>
                                    <td>クライアントのタイムアウト時間を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>クッキーの有効期間（日）</td>
                                    <td><input type='TEXT' name='CookiesExpires' value='<?= $res["CookiesExpires"] ?>'></td>
                                    <td>クッキーの有効期間を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>アクセスログ</td>
                                    <td>
                                        <select name='AccessLog'>
                                            <?= str_replace("value='".(intval($res["AccessLog"]) + 1)."'", "value='".(intval($res["AccessLog"]) + 1)."' selected", "<option value='1'>記録しない</option><option value='2'>記録する</option>") ?>
                                        </select>
                                    </td>
                                    <td>アクセスログを記録します。</font></td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td>メール通知</td>
                                    <td>
                                        <select name='SystemResMail'>
                                            <?= str_replace("value='".(intval($res["SystemResMail"]) + 1)."'", "value='".(intval($res["SystemResMail"]) + 1)."' selected", "<option value='1'>する</option><option value='2'>しない</option>") ?>
                                        </select>
                                    </td>
                                    <td>メッセージの追加時にメールで通知します。<br><font color='RED'>【注意】メール通知を利用するには<a href='http://www.hi-ho.ne.jp/babaq/'>BASP21</a>がインストールしてある必要があります。</font></td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td>メールサーバ</td>
                                    <td><input type='TEXT' name='MailServer' value='<?= $res["MailServer"] ?>' SIZE='40'></td>
                                    <td>メール通知を選択した場合には、メールサーバを指定します。</td>
                                </tr>
                                
                                <tr bgcolor='#FFFF99'>
                                    <td>メール送信者</td>
                                    <td><input type='TEXT' name='MailFrom' value='<?= $res["MailFrom"] ?>' SIZE='40'></td>
                                    <td>メール通知を選択した場合には、メール送信者を指定します。</td>
                                </tr>
                            </table><br>
                            <input type='SUBMIT' name='emode' value='　設定変更　' align='MIDDLE'><br>
                        </form>
                    <?php
                        mysqli_free_result($result);
                    }
                    elseif ($Menu == "ADGroup") {
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        $stmt = NULL;
// 2020/01/06 t.maruyama 追加 ↑↑
                        if ($AdMode == "System") {
                            echo "<font SIZE='+1'>【グループ設定】　</font>グループの設定変更、追加、削除（設定変更の中で）を行います<br><br>";
                            $SQL = "SELCET * FROM ".TGROUP." ORDER BY OrderID";
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            $stmt = mysqli_prepare($Conn, $SQL);
// 2020/01/06 t.maruyama 追加 ↑↑
                        }
                        else {
                            echo "<font SIZE='+1'>【グループ設定】　</font>グループの設定変更を行います<br><br>";
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $result = mysqli_query($Conn, CheckSQL($SQL));

                            $SQL = "SELECT * FROM ".TGROUP." WHERE GroupID = ?";
                            $stmt = mysqli_prepare($Conn, $SQL);
                            mysqli_stmt_bind_param($stmt, 'i',$OwnerGroupID);
// 2020/01/06 t.maruyama 修正 ↑↑
                        }

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $result = mysqli_query($Conn, CheckSQL($SQL));

                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        if (($result != false) && (mysqli_num_rows($result) > 0)) {
                            echo "<table BORDER='1' width='100%' CELLPADDING='3' CELLSPACING='1'>";
                            echo  "<tr align='CENTER' bgcolor='#DDFFDD'>";
                            if ($AdMode == "System") {
                                echo "<td>順序</td>";
                            }
                            echo "<td>グループ名</td><td>タイトル</td>";
                            echo "<td>スタイル</td><td>添付ファイル最大サイズ<br>(KB)</td>";
                            echo "<td>書き込みパスワード</td><td>読み取りパスワード</td>";
                            if ($AdMode == "System") {
                                echo "<td>管理用パスワード</td>";
                            }

                            if ($GLOBALS['SystemResMail'] == 0) {
                                echo "<td>メール通知</td>";
                            }

                            echo "<td>編集</td>";
                            if ($AdMode == "System") {
                                echo "<td>順序変更</td>";
                            }
                            echo "</tr>";
                            
                            $Cnt = 1;
                            while ($res = mysqli_fetch_assoc($result)) { ?>
                                <tr bgcolor='<?= colorSet($Cnt) ?>'>
                                    <?php
                                        if ($AdMode == "System") {
                                            echo "<td align='RIGHT'>".$Cnt."</td>";
                                        }
                                        echo "<td>".$res["GroupName"]."</td>";
                                        echo ($res["GroupTitle"] == '') ? "<td>&nbsp;</td>" : "<td>".$res["GroupTitle"]."</td>";
                                        if ($res["GroupModeID"] == 1) {
                                            echo "<td align='CENTER'>通常</td>";
                                            echo "<td>&nbsp;</td>";
                                        }
                                        else {
                                            echo "<td align='CENTER'>添付可能</td>";
                                            echo "<td align='CENTER'>".$res["MaxUploadFileSize"]."</td>";
                                        }

                                        echo ($res["WritePassWord"] == '') ? "<td>&nbsp;</td>" : "<td align='CENTER'>".$res["WritePassWord"]."</td>";
                                        echo ($res["ReadPassWord"] == '') ? "<td>&nbsp;</td>" : "<td align='CENTER'>".$res["ReadPassWord"]."</td>";

                                        if ($AdMode == "System") {
                                            echo ($res["OwnerPassWord"] == '') ? "<td>&nbsp;</td>" : "<td align='CENTER'>".$res["OwnerPassWord"]."</td>";
                                        }
                                        if ($GLOBALS['SystemResMail'] == 0) {
                                            $ResMail = $res["ResMail"];
                                            switch ($ResMail) {
                                                case 1:
                                                    echo "<td align='CENTER'>しない</td>";
                                                    break;
                                                case 2:
                                                    echo "<td align='CENTER'>管理者</td>";
                                                    break;
                                                case 3:
                                                    echo "<td align='CENTER'>管理者＋利用者</td>";
                                                    break;
                                                default:
                                                    echo "<td align='CENTER'>利用者</td>";
                                            }
                                        }
                                    ?>

                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADGroupEdit'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $res["GroupID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' value='　設定変更　'></td>
                                    </form>

                                    <?php if ($AdMode == "System") { ?>
                                        <form action='<?= $ThisFile ?>' method='POST'>
                                            <input type='hidden' name='Menu' value='ADGroupOrder'>
                                            <?php RPassWord(); ?>
                                            <input type='hidden' name='GroupID' value="<?= $res["GroupID"] ?>">
                                            <td align='CENTER'><input type='SUBMIT' name='Dir' value='上へ'><input type='SUBMIT' name='Dir' value='下へ'></td>
                                        </form>
                                    <?php } ?>
                                </tr>
                        <?php   $Cnt++;
                            } ?>
                            </table>
                    <?php
                        }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                        mysqli_free_result($result);

                        if ($AdMode == "System") { ?>
                            <form action='<?= $ThisFile ?>' method='POST'>
                                <input type='hidden' name='Menu' value='ADGroupAdd'>
                                <?php RPassWord(); ?>
                                <input type='SUBMIT' value='　グループ追加　'>
                            </form>
                        <?php } ?>
                        <br>
            <?php   }
                    elseif (($Menu == "ADGroupEdit") || ($Menu == "ADGroupAdd")) {
                        if ($Menu == "ADGroupEdit") {
                            echo "<font SIZE='+1'>【グループ設定変更】　</font>グループの設定を変更します<br><br>";
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $sql               = "select * FROM ".TGROUP." WHERE GroupID=".(($AdMode == "System") ? $GroupID : $OwnerGroupID);
//                            $result            = mysqli_query($Conn, CheckSQL($sql));

                            $GroupIDValue = ($AdMode == "System") ? $GroupID : $OwnerGroupID;
                            $sql = "SELECT * FROM ".TGROUP." WHERE GroupID = ?";
                            $stmt = mysqli_prepare($Conn, $sql);
                            mysqli_stmt_bind_param($stmt, 'i',$GroupIDValue);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                            $res               = mysqli_fetch_assoc($result);
                            $GroupID           = $res["GroupID"];
                            $GroupName         = $res["GroupName"];
                            $GroupTitle        = $res["GroupTitle"];
                            $GroupModeID       = $res["GroupModeID"];
                            $MaxUploadFileSize = $res["MaxUploadFileSize"];
                            $WritePassWord     = $res["WritePassWord"];
                            $ReadPassWord      = $res["ReadPassWord"];
                            $OwnerPassWord     = $res["OwnerPassWord"];
                            $ResMail           = $res["ResMail"];
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                            mysqli_free_result($result);
                        }
                        else {
                            $GroupName = "";
                            $GroupTitle = "";
                            $GroupModeID = 1;
                            $MaxUploadFileSize = 100;
                            $WritePassWord = "";
                            $ReadPassWord = "";
                            $OwnerPassWord = "";
                            $ResMail = 1;
                        } ?>

                        <form action='<?= $ThisFile ?>' method='POST'>
                            <?php if ($Menu == "ADGroupEdit") { ?>
                                    <input type='hidden' name='Menu' value='ADGroupEdit2'>
                                    <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                            <?php }
                                  else { ?>
                                    <input type='hidden' name='Menu' value='ADGroupAdd2'>
                            <?php }
                                    RPassWord(); ?>
                            <table BORDER='1' width='100%' CELLPADDING='3' CELLSPACING='1'>
                                <tr align='CENTER' bgcolor='#DDFFDD'><td>項　目</td><td>設定値</td><td>備　考</td></tr>
                                <?php $Cnt = 1 ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>グループ名</td>
                                    <td><input type='TEXT' name='GroupName' value='<?= $GroupName ?>'></td>
                                    <td>グループ名</td>
                                </tr>

                                <?php $Cnt++; ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>グループタイトル</td>
                                    <td><input type='TEXT' name='GroupTitle' value='<?= $GroupTitle ?>' SIZE='50'></td>
                                    <td>グループの簡単な説明</td>
                                </tr>

                                <?php $Cnt++; ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>スタイル</td>
                                    <td>
                                        <select name='GroupModeID'>
                                            <?= str_replace("value='{$GroupModeID}'", "value='{$GroupModeID}' selected", "<option value='1'>通常</option><option value='2'>添付可能</option>"); ?>
                                        </select>
                                    </td>
                                    <td>掲示板のスタイルを指定します。<br><font color='RED'>【注意】添付可能な掲示板にするには<a href='http://www.hi-ho.ne.jp/babaq/'>BASP21</a>がインストールしてある必要があります。</font></td>
                                </tr>

                                <?php $Cnt++; ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>添付ファイル最大サイズ(KB)</td>
                                    <td><input type='TEXT' name='MaxUploadFileSize' value='<?= $MaxUploadFileSize ?>'></td>
                                    <td>添付可能掲示板の添付ファイルの最大値を設定します。</td>
                                </tr>

                                <?php $Cnt++; ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>書き込みパスワード</td>
                                    <td><input type='PassWord' name='WritePassWord' value='<?= $WritePassWord ?>'></td>
                                    <td>書き込み用のパスワードです。これを設定すると読み取り専用となり、書き込みにはパスワードが必要になります。読み取り専用にしない場合には空にします。</td>
                                </tr>

                                <?php $Cnt++; ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td>読み取りパスワード</td>
                                    <td><input type='PassWord' name='ReadPassWord' value='<?= $ReadPassWord ?>'></td>
                                    <td>読み取り用のパスワードです。これを設定すると読み取り時にパスワードの入力が必要になります。パスワードの入力を要求させないためには空にします。</td>
                                </tr>

                                <?php if ($AdMode == "System") {
                                        $Cnt++; ?>
                                        <tr bgcolor="<?= colorSet($Cnt) ?>">
                                            <td>管理用パスワード</td>
                                            <td><input type='PassWord' name='OwnerPassWord' value='<?= $OwnerPassWord ?>'></td>
                                            <td>管理用パスワードを設定します。このパスワードを設定するとシステム管理者以外に、個々の掲示板を管理できるパスワードを設定することができます。</td>
                                        </tr>
                                <?php }
                                    if ($GLOBALS['SystemResMail'] == 0) {
                                        $Cnt++; ?>
                                        <tr bgcolor="<?= colorSet($Cnt) ?>">
                                            <td>メール通知</td>
                                            <td>
                                                <select name='ResMail'>
                                                    <?php
                                                        $tmp = "<option value='1'>しない</option>
                                                                <option value='2'>管理者</option>
                                                                <option value='3'>管理者＋利用者</option>
                                                                <option value='4'>利用者</option>";
                                                        echo str_replace("value='{$ResMail}'", "value='{$ResMail}' selected", $tmp);
                                                    ?>
                                                </select>
                                                <?php
                                                    if (($ResMail == 2) || ($ResMail == 3)) {
                                                        echo "　　<input type='SUBMIT' name='emode' value='管理者設定' align='MIDDLE'>";
                                                    }
                                                ?>
                                            </td>
                                            <td>掲示板に書き込みがあったときにメールで通知するようにします。通知するレベルはメッセージの投稿者が選択できます。</td>
                                        </tr>
                            <?php   } ?>
                            </table><br>
                            <?php if ($Menu == "ADGroupEdit") { ?>
                                <table BORDER=0>
                                    <tr>
                                        <td><input type='SUBMIT' name='emode' value='　設定変更　' align='MIDDLE'></td>
                                        <?php 
                                            if ($AdMode == "System") {
                                                echo "<td><input type='SUBMIT' name='emode' value='グループ削除' align='MIDDLE'></td>";
                                            }
                                        ?>
                                    </tr>
                                </table>
                    <?php   }
                            else {
                                echo "<input type='SUBMIT' name='emode' value='　追加　' align='MIDDLE'><br>";
                            } ?>
                        </form>
                <?php
                    }
                    elseif (($Menu == "ADGroupEdit2") && (htmlentities($_POST["emode"]) == "管理者設定")) { ?>
                        <font SIZE='+1'>【管理者メール設定】　</font>投稿を通知する管理者のメールアドレスを設定します<br><br>
                        <table Border='1' CELLPADDING='3' CELLSPACING='1'>
                            <tr align='CENTER' bgcolor='#DDFFDD'>
                                <td>No</td>
                                <td>Mail Address</td>
                                <td>編集</td>
                            </tr>

                        <?php 
                            $GroupID = ($AdMode == "System") ? htmlentities($_POST["GroupID"]) : $OwnerGroupID;
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $result  = mysqli_query($Conn, CheckSQL("select * FROM ".TMAILOWNER." WHERE GroupID=".$GroupID));

                            $sql = "SELECT * FROM ".TMAILOWNER." WHERE GroupID = ?";
                            $stmt = mysqli_prepare($Conn, $sql);
                            mysqli_stmt_bind_param($stmt, 'i',$GroupID);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                                $Cnt = 1;
                                while ($res = mysqli_fetch_assoc($result)) { ?>
                                    <?= colorSet($Cnt) ?>
                                    <tr>
                                        <td align='RIGHT'><?= $Cnt ?></td>
                                        <td><?= $res["MailAddr"] ?></td>
                                        <form action='<?= $ThisFile ?>' method='POST'>
                                            <input type='hidden' name='Menu' value='ADMessageAdminDel'>
                                            <?php RPassWord(); ?>
                                            <input type='hidden' name='GroupID' value=".$GroupID.">
                                            <input type='hidden' name='MailID' value="<?= $res["MailID"] ?>">
                                            <input type='hidden' name='emode' value='管理者設定'>
                                            <td align='CENTER'><input type='SUBMIT' value='削除'></td>
                                        </form>
                                    </tr>
                        <?php       $Cnt++;
                                }
                            }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                            mysqli_free_result($result); ?>

                            <form action='<?= $ThisFile ?>' method='POST'>
                                <input type='hidden' name='Menu' value='ADMessageAdminAdd'>
                                <?php RPassWord(); ?>
                                <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                                <input type='hidden' name='emode' value='管理者設定'>
                                <tr><td>&nbsp</td>
                                <td><input type='text' name='MailAddr' size='40'></td>
                                <td><input type='SUBMIT' value='追加'></td>
                                </tr>
                            </form>
                        </table>
            <?php   }
                    elseif (($Menu == "ADGroupEdit2") && (htmlentities($_POST["emode"]) == "グループ削除")) { ?>
                        <font SIZE='+1'>【グループ削除】　</font><br><br>
                        グループを削除すると、そのグループに投稿されたメッセージもすべて削除されます<br>
                        本当に削除しますか<br>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADGroupDelete2'>
                            <?php RPassWord(); ?>
                            <input type='hidden' name='GroupID' value="<?= htmlentities($_POST["GroupID"]) ?>">
                            <input type='SUBMIT' name='emode' value='　削　除　'>　
                            <input type='SUBMIT' name='emode' value='キャンセル'>
                        </form>
            <?php   }
                    elseif ($Menu == "ADMessageDel") {
                        echo "<font SIZE='+1'>【メッセージ削除】　</font>メッセージの削除を行います<br><br>";
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        $stmt = NULL;
// 2020/01/06 t.maruyama 追加 ↑↑
                        if ($AdMode == "System") {
                            $SQL = "SELECT ".TMESSAGE.".*,".TGROUP.".GroupName FROM ".TGROUP." INNER JOIN ".TMESSAGE." ON ".TGROUP.".GroupID = ".TMESSAGE.".GroupID ORDER BY MessageID DESC";
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            $stmt = mysqli_prepare($Conn, $SQL);
// 2020/01/06 t.maruyama 追加 ↑↑
                        }
                        else {
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $SQL = "select * FROM ".TMESSAGE." WHERE GroupID=".$OwnerGroupID." ORDER BY MessageID DESC";

                            $SQL = "SELECT * FROM ".TMESSAGE." WHERE GroupID = ? ORDER BY MessageID DESC";
                            $stmt = mysqli_prepare($Conn, $SQL);
                            mysqli_stmt_bind_param($stmt, 'i',$OwnerGroupID);
// 2020/01/06 t.maruyama 修正 ↑↑
                        }

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $result     = mysqli_query($Conn, CheckSQL($SQL));

                        mysqli_stmt_execute($stmt);
                        $result     = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        $PageSize   = 20;
                        $TotalRows  = mysqli_num_rows($result);
                        $TotalPages = ceil($TotalRows / $PageSize); 

                        if (($result != false) && (mysqli_num_rows($result) > 0)) {
                            echo "<table BORDER='1' width='100%' CELLPADDING='3' CELLSPACING='1'>";
                            echo  "<tr align='CENTER' bgcolor='#DDFFDD'>";
                            echo "<td>No</td>";
                            if ($AdMode == "System") {
                                echo "<td>グループ</td>";
                            }
                            echo "<td>タイトル</td>";
                            echo "<td>氏名</td><td>日時</td><td>編集</td>";
                            echo  "</tr>";
                            $Cnt = 0;
                            while ($res = mysqli_fetch_assoc($result)) {
                                echo "<tr bgcolor='".colorSet($Cnt)."'>";
                                echo "<td align=RIGHT>".$res["MessageID"]."</td>";
                                if ($AdMode == "System") {
                                    echo "<td>".$res["GroupName"]."</td>";
                                }
                                echo "<td>".$res["Title"]."</td>";
                                echo "<td>".$res["UName"]."</td>";
                                echo "<td>".date('Y/m/d h:i:s', strtotime($res["UDate"]))."</td>";

                                if (($res["Title"] != "削除") || ($res["Message"] != "削除")) { ?>
                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADMessageDel2'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='MessageID' value="<?= $res["MessageID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' value='削除'></td>
                                    </form>
                        <?php   }
                                else {
                                    echo "<td>&nbsp</td>";
                                }
                                echo  "</tr>";
                                $Cnt++;
                                if ($Cnt == 20) {
                                    break;
                                }
                            }
                            echo "</table>";
                        }
                        echo "<table BORDER=0><tr>";
                        if ($apNow > 1) { ?>
                            <form action='<?= $ThisFile ?>' method='POST'>
                                <input type='hidden' name='Menu' value='ADMessageDel'>
                                <?php RPassWord(); ?>
                                <input type='hidden' name='apNow' value="<?= ($apNow - 1) ?>">
                                <td><input type='SUBMIT' value='前の２０メッセージ'></td>
                            </form>
                <?php   }
                        if ($apNow < $TotalPages) { ?>
                            <form action='<?= $ThisFile ?>' method='POST'>
                                <input type='hidden' name='Menu' value='ADMessageDel'>
                                <?php RPassWord(); ?>
                                <input type='hidden' name='apNow' value="<?= ($apNow + 1) ?>">
                                <td><input type='SUBMIT' value='次の２０メッセージ'></td>
                            </form>
                <?php   }
                        echo "</tr></table>";
                        echo "<br>";
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                        mysqli_free_result($result);
                    }
                    elseif ($Menu == "ADMessageDel2") { ?>
                        <font SIZE='+1'>【メッセージ削除】　</font><br><br>
                        本当に削除しますか<br>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADMessageDel3'>
                            <?php RPassWord(); ?>
                            <input type='hidden' name='MessageID' value="<?= $_POST["MessageID"] ?>">
                            <input type='SUBMIT' name='emode' value='　削　除　'>
                            <input type='SUBMIT' name='emode' value='キャンセル'>
                        </form>
            <?php   }
                    elseif ($Menu == "ADMessageThread") {
                        echo "<font SIZE='+1'>【スレッド変更】　</font>メッセージのスレッドを変更します<br><br>";
                    }
                    elseif ($Menu == "ADMessageGroup") {
                        echo "<font SIZE='+1'>【グループ変更】　</font>メッセージのグループを変更します<br><br>";
                    }
                    elseif ($Menu == "ADSum") {
                        echo "<font SIZE='+1'>【まとめ設定グループ選択】　</font>メッセージのまとめをするグループを選択します<br><br>";
                        $result = mysqli_query($Conn, CheckSQL("select * FROM ".TGROUP." ORDER BY OrderID"));
                        if (($result != false) && (mysqli_num_rows($result) > 0)) {
                            echo "<table BORDER=1 CELLPADDING='3' CELLSPACING='1'>";
                            echo "<tr align=CENTER bgcolor=#DDFFDD>";
                            if ($AdMode == "System") {
                                echo "<td>順序</td>";
                            }
                            echo "<td>グループ名</td>";
                            echo "<td>操作</td>";
                            echo "</tr>";

                            $Cnt = 1;
                            while ($res = mysqli_fetch_assoc($result)) { ?>
                                <tr bgcolor='<?= colorSet($Cnt) ?>'>
                                    <td align='RIGHT'><?= $Cnt ?></td>
                                    <td><?= $res["GroupName"] ?></td>
                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADSum2'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $res["GroupID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' value='選択'></td>
                                    </form>
                                </tr>
                        <?php   $Cnt++;
                            }
                            echo "</table>";
                        }
                        mysqli_free_result($result);
                    }
                    elseif ($Menu == "ADSum2") {
                        echo  "<font SIZE='+1'>【まとめ設定】　</font>メッセージのまとめを設定します<br><br>";
                        $GroupID = ($AdMode == "System") ? htmlentities($_POST["GroupID"]) : htmlentities($_POST["OwnerGroupID"]);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $result  = mysqli_query($Conn, CheckSQL("select * FROM ".TSUM." WHERE GroupID=".$GroupID." ORDER BY OrderID"));

                        $sql = "SELECT * FROM ".TSUM." WHERE GroupID = ? ORDER BY OrderID";
                        $stmt = mysqli_prepare($Conn, $sql);
                        mysqli_stmt_bind_param($stmt, 'i',$GroupID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        if (($result != false) && (mysqli_num_rows($result) > 0)) { ?>
                            <table BORDER='1' CELLPADDING='3' CELLSPACING='1'>
                                <tr align='CENTER' bgcolor='#DDFFDD'>
                                    <td>順序</td>
                                    <td>まとめタイトル</td>
                                    <td>編集</td>
                                    <td>順序変更</td>
                                </tr>

                        <?php
                            $Cnt = 1;
                            while ($res = mysqli_fetch_assoc($result)) { ?>
                                <tr bgcolor='<?= colorSet($Cnt) ?>'>
                                    <td align='RIGHT'><?= $Cnt ?></td>
                                    <td><?= $res["SumTitle"] ?></td>
                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADSumMessage'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                                        <input type='hidden' name='SumID' value="<?= $res["SumID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' name='emode' value='　設定変更　'>　<input type='SUBMIT' name='emode' value='削除'></td>
                                    </form>
                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADSumOrder'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                                        <input type='hidden' name='SumID' value="<?= $res["SumID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' name='Dir' value='上へ'><input type='SUBMIT' name='Dir' value='下へ'></td>
                                    </form>
                                </tr>
                        <?php   $Cnt++;
                            }
                            echo "</table>";
                        }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                        mysqli_free_result($result); ?>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADSumAdd'>
                            <?php RPassWord(); ?>
                            <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                            <input type='SUBMIT' value='　まとめ追加　'>
                        </form><br>
            <?php   }
                    elseif (($Menu == "ADSumMessage") && ($_POST["emode"] == "　設定変更　")) {
                        echo "<font SIZE='+1'>【まとめ編集】　</font>まとめに含めるメッセージを編集します<br><br>";

                        $GroupID = htmlentities($_POST["GroupID"]);
                        $SumID = htmlentities($_POST["SumID"]);

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $SQL = "select TbbsSumMessage.SumMesID, TbbsMessage.UName, TbbsMessage.Title, TbbsMessage.UDate";
//                        $SQL .= " FROM ".TMESSAGE." INNER JOIN ".TSUMMESSAGE." ON ".TMESSAGE.".MessageID = ".TSUMMESSAGE.".MessageID";
//                        $SQL .= " WHERE ".TSUMMESSAGE.".SumID=".$SumID." ORDER BY ".TSUMMESSAGE.".OrderID";
//                        $result = mysqli_query($Conn, CheckSQL($SQL));

                        $SQL = "SELECT ".TSUMMESSAGE.".SumMesID, ".TMESSAGE.".UName, ".TMESSAGE.".Title, ".TMESSAGE.".UDate".
                                " FROM ".TMESSAGE." INNER JOIN ".TSUMMESSAGE." ON ".TMESSAGE.".MessageID = ".TSUMMESSAGE.".MessageID".
                                " WHERE ".TSUMMESSAGE.".SumID = ? ORDER BY ".TSUMMESSAGE.".OrderID";

                        $stmt = mysqli_prepare($Conn, $SQL);
                        mysqli_stmt_bind_param($stmt, 'i',$SumID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        if (($result != false) && (mysqli_num_rows($result) > 0)) {
                            echo "<table BORDER=1 width='100%' CELLPADDING='3' CELLSPACING='1'>";
                            echo "<tr align=CENTER bgcolor=#DDFFDD>";
                            echo "<td>順序</td>";
                            echo "<td>タイトル</td><td>氏　名</td><td>日　時</td>";
                            echo "<td>編集</td>";
                            echo "<td>順序変更</td>";
                            echo "</tr>";

                            $Cnt = 1;
                            while ($res = mysqli_fetch_assoc($result)) { ?>
                                <tr bgcolor="<?= colorSet($Cnt) ?>">
                                    <td align='RIGHT'><?= $Cnt ?></td>
                                    <td><?= $res["Title"] ?></td>";
                                    <td><?= $res["UName"] ?></td>";
                                    <td><?= $res["UDate"] ?></td>";
                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADSumMessageDel'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                                        <input type='hidden' name='SumID' value="<?= $SumID ?>">
                                        <input type='hidden' name='SumMesID' value="<?= $res["SumMesID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' value='削除'></td>
                                    </form>

                                    <form action='<?= $ThisFile ?>' method='POST'>
                                        <input type='hidden' name='Menu' value='ADSumMessageOrder'>
                                        <?php RPassWord(); ?>
                                        <input type='hidden' name='GroupID' value="<?= $GroupID ?>">
                                        <input type='hidden' name='SumID' value="<?= $SumID ?>">
                                        <input type='hidden' name='SumMesID' value="<?= $res["SumMesID"] ?>">
                                        <td align='CENTER'><input type='SUBMIT' name='Dir' value='上へ'><input type='SUBMIT' name='Dir' value='下へ'></td>
                                    </form>
                                </tr>
                            <?php
                                $Cnt++;
                            }
                            echo "</table>";
                        }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                        mysqli_free_result($result);

                        echo "<form action='".$ThisFile."' method='POST'>";
                        echo "<input type='hidden' name='Menu' value='ADSumMessageAdd'>";
                        RPassWord();
                        echo "<input type='hidden' name='GroupID' value=".$GroupID.">";
                        echo "<input type='hidden' name='SumID' value=".$SumID.">";
                        echo "<input type='SUBMIT' value='　メッセージ追加　'>";
                        echo "</form>";
                        echo "<br>";
                    }
                    elseif (($Menu == "ADSumMessage") && (htmlentities($_POST["emode"]) == "削除")) { ?>
                        <font SIZE='+1'>【まとめグループ削除】　</font><br><br>
                        本当に削除しますか<br>
                            <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADSumDel'>
                            <?php RPassWord(); ?>
                            <input type='hidden' name='GroupID' value="<?= htmlentities($_POST["GroupID"]) ?>">
                            <input type='hidden' name='SumID' value="<?= htmlentities($_POST["SumID"]) ?>">
                            <input type='SUBMIT' name='emode' value='　削　除　'>
                            <input type='SUBMIT' name='emode' value='キャンセル'>
                        </form>
            <?php   }
                    elseif ($Menu == "ADSumMessageAdd") {
                        echo  "<font SIZE='+1'>【まとめ編集】　</font>まとめに含めるメッセージを追加します<br><br>";

                        $GroupID = htmlentities($_POST["GroupID"]);
                        $SumID = htmlentities($_POST["SumID"]);

// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $SQL  = "select TbbsMessage.MessageID, TbbsMessage.UName, TbbsMessage.Title, TbbsMessage.UDate";
//                        $SQL .= " FROM ".TMESSAGE." LEFT JOIN ".TSUMMESSAGE." ON ".TMESSAGE.".MessageID = ".TSUMMESSAGE.".MessageID";
//                        $SQL .= " WHERE ".TMESSAGE.".GroupID=".$GroupID." And ".TSUMMESSAGE.".SumMesID Is Null";
//                        $SQL .= " ORDER BY ".TMESSAGE.".MessageID";
//
//                        $result = mysqli_query($Conn, CheckSQL($SQL));

                        $SQL  = "SELECT ".TMESSAGE.".MessageID, ".TMESSAGE.".UName, ".TMESSAGE.".Title, ".TMESSAGE.".UDate".
                                " FROM ".TMESSAGE." LEFT JOIN ".TSUMMESSAGE." ON ".TMESSAGE.".MessageID = ".TSUMMESSAGE.".MessageID".
                                " WHERE ".TMESSAGE.".GroupID = ? AND ".TSUMMESSAGE.".SumMesID IS NULL".
                                " ORDER BY ".TMESSAGE.".MessageID";

                        $stmt = mysqli_prepare($Conn, $SQL);
                        mysqli_stmt_bind_param($stmt, 'i',$GroupID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        $PageSize = 20;
                        $TotalRows = mysqli_num_rows($result);
                        $TotalPages = ceil($TotalRows / $PageSize);
                        if (($result != false) && (mysqli_num_rows($result) > 0)) {
                            echo "<table BORDER=1 width='100%' CELLPADDING='3' CELLSPACING='1'>";
                            echo "<tr align=CENTER bgcolor=#DDFFDD>";
                            echo "<td>No</td>";
                            echo "<td>タイトル</td><td>氏　名</td><td>日　時</td>";
                            echo "<td>編集</td>";
                            echo "</tr>";
                            $Cnt = 0;
                            while ($res = mysqli_fetch_assoc($result)) {
                                echo (($Cnt % 2) == 1) ? "<tr bgcolor=#FFFFCC>" : "<tr bgcolor=#FFFF99>";
                                echo "<td align=RIGHT>".$res["MessageID"]."</td>";
                                echo "<td>".$res["Title"]."</td>";
                                echo "<td>".$res["UName"]."</td>";
                                echo "<td>".$res["UDate"]."</td>";
                                echo "<form action='".$ThisFile."' method='POST'>";
                                echo "<input type='hidden' name='Menu' value='ADSumMessageAdd2'>";
                                RPassWord();
                                echo "<input type='hidden' name='GroupID' value=".$GroupID.">";
                                echo "<input type='hidden' name='SumID' value=".$SumID.">";
                                echo "<input type='hidden' name='MessageID' value=".$res["MessageID"].">";
                                echo "<td align=CENTER><input type='SUBMIT' value='追加'></td>";
                                echo "</form>";
                                echo  "</tr>";
                                $Cnt++;
                                if ($Cnt == 20) {
                                    break;
                                }
                            }
                            echo "</table>";
                        }
                        echo "<table BORDER=0><tr>";
                        if ($apNow > 1) {
                            echo "<form action='".$ThisFile."' method='POST'>";
                            echo "<input type='hidden' name='Menu' value='ADSumMessageAdd'>";
                            RPassWord();
                            echo "<input type='hidden' name='GroupID' value=".$GroupID.">";
                            echo "<input type='hidden' name='SumID' value=".$SumID.">";
                            echo "<input type='hidden' name='apNow' value=".($apNow - 1).">";
                            echo "<td><input type='SUBMIT' value='前の２０メッセージ'></td>";
                            echo "</form>";
                        }

                        if ($apNow < $TotalPages) {  
                            echo "<form action='".$ThisFile."' method='POST'>";
                            echo "<input type='hidden' name='Menu' value='ADSumMessageAdd'>";
                            RPassWord();
                            echo "<input type='hidden' name='GroupID' value=".$GroupID.">";
                            echo "<input type='hidden' name='SumID' value=".$SumID.">";
                            echo "<input type='hidden' name='apNow' value=".($apNow + 1).">";
                            echo "<td><input type='SUBMIT' value='次の２０メッセージ'></td>";
                            echo "</form>";
                        }
                        echo "</tr></table>";
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑
                        mysqli_free_result($result);
                    }
                    elseif ($Menu == "ADSumAdd") { ?>
                        <font SIZE='+1'>【まとめ追加】　</font>まとめの追加をします<br><br>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADSumAdd2'>
                            <input type='hidden' name='GroupID' value="<?= htmlentities($_POST["GroupID"]) ?>">
                            <?php RPassWord(); ?>
                            <table BORDER='1' CELLPADDING='3' CELLSPACING='1'>
                                <tr align='CENTER' bgcolor='#DDFFDD'><td>項　目</td><td>設定値</td><td>備　考</td></tr>
                                <tr bgcolor="<?= colorSet(1) ?>">
                                    <td>まとめタイトル</td>
                                    <td><input type='TEXT' name='SumTitle'></td>
                                    <td>まとめに付けるタイトルを指定します</td>
                                </tr>
                            </table><br>
                            <input type='SUBMIT' name='emode' value='　追加　' align='MIDDLE'><br>
                        </form>
            <?php   }
                    elseif ($Menu == "ADFilter") { 
                        echo "<font SIZE='+1'>【フィルタ設定】　</font>IPアドレスのファイルタを設定します<br><br>";
                    }
                    elseif ($Menu == "ADTag") { 
                        echo  "<font SIZE='+1'>【タグ設定】　</font>利用可能タグを選択します<br><br>";
                    }
                    elseif (($Menu == "ADLog") && ($AdMode == "System")) { ?>
                        <font SIZE='+1'>【ロ　グ】　</font>アクセスログです。<br><br>
                <?php   if ($GLOBALS['AccessLog'] == true) {
                            $LD = (htmlentities($_POST["LogDate"]) != "") ? round(htmlentities($_POST["LogDate"])) : 0;
                            $da = strtotime(DateAdd(date("Y/m/d"), $LD, "d")); ?>
                            <table>
                                <tr>
                                    <td>
                                        <form action='<?= $ThisFile ?>' method='POST'>
                                            <input type='hidden' name='Menu' value='ADLog'>
                                            <input type='hidden' name='LogDate' value="<?= ($LD - 1) ?>">
                                            <?php RPassWord(); ?>
                                            <input type='SUBMIT' value='前の日'>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='<?= $ThisFile ?>' method='POST'>
                                            <input type='hidden' name='Menu' value='ADLog'>
                                            <input type='hidden' name='LogDate' value="<?= ($LD + 1) ?>">
                                            <?php RPassWord(); ?>
                                            <input type='SUBMIT' value='次の日'>
                                        </form>
                                    </td>
                                </tr>
                            </table>

                        <?php
                            var_dump($da);
// 2020/01/06 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $SQL  = "select * FROM ".TLOG;
//                            $SQL .= " WHERE ADate Between '".date("Y/m/d", $da)." 00:00:00' And '".date("Y/m/d", $da)." 23:59:59'";
//                            $SQL .= " ORDER BY ADate DESC";
//                            $result = mysqli_query($Conn, CheckSQL($SQL));

                            $from = date("Y/m/d", $da)." 00:00:00";
                            $to = date("Y/m/d", $da)." 23:59:59";

                            $SQL  = "SELECT * FROM ".TLOG.
                                " WHERE ADate BETWEEN ? AND ?".
                                " ORDER BY ADate DESC";
                            $stmt = mysqli_prepare($Conn, $SQL);
                            mysqli_stmt_bind_param($stmt, 'ss',$from, $to);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
// 2020/01/06 t.maruyama 修正 ↑↑

                            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                                echo "<table BORDER='1'>
                                        <tr align='CENTER'><td>No</td><td>日付</td><td>アドレス</td><td>メニュー</td><td>値</td></tr>";

                                $Cnt = 1;
                                while ($res = mysqli_fetch_assoc($result)) {
                                    echo "  <tr>
                                                <td>".$Cnt."</td>
                                                <td>".date('Y/m/d h:i:s', strtotime($res["ADate"]))."</td>
                                                <td>".$res["RemoteAddr"]."</td>
                                                <td>".$res["AccessMenu"]."</td>
                                                <td>".$res["AccessValue"]."</td>
                                            </tr>";
                                    $Cnt++;
                                }
                                echo "</table>";
                            }
// 2020/01/06 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            mysqli_stmt_close($stmt);
// 2020/01/06 t.maruyama 追加 ↑↑
                            mysqli_free_result($result);
                        }
                        else {
                            echo "『アクセスログを記録する』が選択されていません<br>";
                        }
                    }
                    elseif (($Menu == "ADDefvalue") && ($AdMode == "System")) {
                        $result = mysqli_query($Conn, CheckSQL("select * FROM ".TSYSTEM));
                        $res = mysqli_fetch_assoc($result); ?>
                        <font SIZE='+1'>【規定値設定】　</font>ユーザー毎にクッキーを利用して設定できる値の規定値を設定します<br><br>
                        <form action='<?= $ThisFile ?>' method='POST'>
                            <input type='hidden' name='Menu' value='ADDefvalue2'>
                            <table BORDER='1' width='100%' CELLPADDING='3' CELLSPACING='1'>
                                <?php RPassWord(); ?>
                                <tr align='CENTER' bgcolor='#DDFFDD'><td>項　目</td><td>設定値</td><td>備　考</td></tr>
                                <tr bgcolor='#FFFFCC'>
                                    <td>デフォルト表示　</td>
                                    <td>
                                        <select name='DefMenu'>
                                            <?php
                                                $tmp = "<option value='Root' selected>ルート表示</option>
                                                        <option value='Thread'>スレッド表示</option>
                                                        <option value='All'>入力順表示</option>
                                                        <option value='AllGroup'>全グループ入力順表示</option>
                                                        <option value='MSum'>まとめ</option>
                                                        <option value='Find'>検索</option>";
                                                echo str_replace("value='".$res["DefMenu"]."'", "value='".$res["DefMenu"]."' selected", $tmp);
                                            ?>
                                        </select>
                                    </td>
                                    <td>メッセージ一覧のデフォルトの表示を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td align='LEFT'>スレッド表示、ルート表示の数</td>
                                    <td><input type='text' name='psIchiran' size='5' value="<?= $res["psIchiran"] ?>"></td>
                                    <td>タイトル一覧でスレッド表示、ルート表示の表示数を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td align='LEFT'>入力順表示、検索結果の数</td>
                                    <td><input type='text' name='psNyuryoku' size='5' value="<?= $res["psNyuryoku"] ?>"></td>
                                    <td>タイトル一覧で入力順表示、検索結果の表示数を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td align='LEFT'>最新表示の日数</td>
                                    <td><input type='text' name='UpdateV' size='5' value="<?= $res["UpdateV"] ?>"></td>
                                    <td>最新の発言として表示する期間を設定します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td align='LEFT'>メッセージの表示</td>
                                    <td>
                                        <select name='ThreadView'>
                                            <?= str_replace("value='".$res["ThreadView"]."'", "value='".$res["ThreadView"]."' selected", "<option value='1'>スレッドの全メッセージを表示</option><option value='2'>１メッセージのみ表示</option>"); ?>
                                        </select>
                                    </td>
                                    <td>メッセージを表示するときに、スレッドの全メッセージを表示するか、選択した１つのメッセージだけを表示するかを選択します。</td>
                                </tr>

                                <tr bgcolor='#FFFF99'>
                                    <td align='LEFT'>スレッド表示、ルート表示の表示順</td>
                                    <td>
                                        <select name='ThreadOrder'>
                                            <?= str_replace("value='".$res["ThreadOrder"]."'", "value='".$res["ThreadOrder"]."' selected", "<option value='1'>最新発言順</option><option value='2'>ルートの発言順</option>"); ?>
                                        </select>
                                    </td>
                                    <td>タイトル一覧の表示順で、スレッド元メッセージの入力順か、最新の発言順かを選択します。</td>
                                </tr>

                                <tr bgcolor='#FFFFCC'>
                                    <td align='LEFT'>未読の表示</td>
                                    <td>
                                        <select name='MessageRead'>
                                            <?= str_replace("value='".$res["MessageRead"]."'", "value='".$res["MessageRead"]."' selected", "<option value='1' selected>しない</option><option value='2'>する（クッキーを利用）</option>"); ?>
                                        </select>
                                    </td>
                                    <td>メッセージに未読の表示をするかどうかを選択します。未読を表示させるにはクッキーを利用します。</td>
                                </tr>
                            </table><br>
                            <input type='SUBMIT' name='emode' value='　設定変更　' align='MIDDLE'><br>
                        </form>
            <?php       mysqli_free_result($result);
                    }
                    elseif ($Menu == "ADPassWord" && $AdMode == "System") { ?>
                        <font SIZE='+1'>【パスワード】　</font>システム管理用のパスワードを変更します<br><br>
                        <table BORDER='0' CELLPADDING='4' CELLSPACING='0'>
                            <form action='<?= $ThisFile ?>' method='POST'>
                                <tr>
                                    <td bgcolor='#FFFFCC'>システム管理パスワード</td>
                                    <?php RPassWord(); ?>
                                    <input type='hidden' name='Menu' value='ADPassWord2'>
                                    <td bgcolor='#FFFFCC'>　<input type='PASSWORD' name='SPW'></td>
                                    <td bgcolor='#FFFFCC' ROWSPAN='2'><input type='SUBMIT' value='変　更'></td>
                                </tr>
                                <tr>
                                    <td bgcolor='#FFFFCC'>システム管理パスワード(確認)</td>
                                    <td bgcolor='#FFFFCC'>　<input type='PASSWORD' name='SPW2'></td>
                                    <td>　<?= $ErrSystem ?></td>
                                </tr>
                            </form>
                        </table>
            <?php   }
                    mysqli_close($Conn); ?>
                    </td>
                </tr>
            </table><br>
        </body>
    </html>
    <?php
        function RPassWord() {
            if ($GLOBALS['AdMode'] == "System") {
                echo "<input type='hidden' name='SystemPassWord' value='".$GLOBALS['SystemPassWord']."'>";
            }
            else {
                echo "<input type='hidden' name='OwnerPassWord' value='".$GLOBALS['OwnerPassWord']."'>";
                echo "<input type='hidden' name='OwnerGroupID' value=".$GLOBALS['OwnerGroupID'].">";
            }
        }

        function colorSet($lngCnt) {
            return (($lngCnt%2) == 1) ? "#FFFFCC" : "#FFFF99";
        }
    ?>
