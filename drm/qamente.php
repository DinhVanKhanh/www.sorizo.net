<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';

    $MsgID = $ErrMsg = "";
    $Conn  = ConnectSorizo();

    if ($_SESSION['QAPass'] == "") {
        $result = mysqli_query($Conn, CheckSQL("SELECT SystemPassWord FROM ".TSYSTEM));
        $res    = mysqli_fetch_assoc($result);
        $_SESSION['QAPass'] = $res["SystemPassWord"];
        mysqli_free_result($result);
    }
    $ThisFile = $_SERVER["SCRIPT_NAME"];
    $Menu = htmlentities(@$_REQUEST["Menu"]);
    echo "<!DOCTYPE HTML>
            <HTML lang='ja'>
                <head>
                    <meta HTTP-EQUIV='Content-Type' CONTENT='text/html;charset=x-sjis'>
                    <title>Q&Aメンテナンス</title>
";

include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php');

    echo "                    <meta http-equiv='cache-control' content='no-cache' />
                </head>";

    // 実際の追加/修正/削除処理
    switch ($Menu) {
        case "Login_Proc":
            if ($_SESSION['QAPass'] == $_REQUEST["QAPassWord"]) {
                $_SESSION['LogInState'] = "LogInOk";
            }
            else {
                $_SESSION['LogInState'] = "";
                $ErrMsg = "ログインパスワードが正しくありません。";
            }
            $Menu = $SubMenu = "";
            break;

        case "Add_Pro_Cat":
            $ErrMsg = CheckCatData();
            if ($ErrMsg == "") {
                $result2 = mysqli_query($Conn, 'select CatID from '.TCATMST.' where CatID='.htmlentities($_REQUEST["CatID"]));
                if (mysqli_num_rows($result2) >= 1) {
                    $_SESSION['category'] = array("CatID" => $_REQUEST["CatID"], "CatName" => $_REQUEST["CatName"]);
                    header('Expires: '.gmdate('D, d M Y H:i:s', time()+10).'GMT');
                    echo "  <span style='font-weight:bold'>入力されたカテゴリは既に登録済みです。</span>
                            <a href='{$ThisFile}?Menu=CatMent&SubMenu=' style='margin-left:50px'>カテゴリの一覧表示</a>に戻る
                            <form method='GET' action='".$_SERVER['PHP_SELF']."' style='width:140px; display:inline; margin-left:50px;'>
                                <input type='hidden' name='Menu' value='CatMent' />
                                <input type='hidden' name='SubMenu' value='Add' />
                                <input type='hidden' name='CatID' value='".$_REQUEST["CatID"]."' />
                                <input type='hidden' name='CatName' value='".$_REQUEST["CatName"]."' />
                                <input type='submit' value='登録ページ' style='background:none; border:none; text-decoration:underline; color:#0000ee; cursor:pointer; font-size:16px; padding:0;'/>に戻る
                            </form>";
                    return;
                }

                if (isset($_SESSION['category'])) {
                    unset($_SESSION['category']);
                }

                // エラーなしなら追加処理
                $SQL  = "INSERT INTO ".TCATMST." (";
                $SQL .= "CatID, CatName) VALUES (";
                $SQL .= htmlentities($_REQUEST["CatID"]).", ";
                $SQL .= "'".CheckSQ(htmlentities($_REQUEST["CatName"]))."'";
                $SQL .= ")";
                mysqli_query($Conn, CheckSQL($SQL));
                echo "<div style='font-weight:bold'>カテゴリのデータが正常に追加されました</div><a href='".$ThisFile."?Menu=CatMent&SubMenu='>カテゴリの一覧表示</a>に戻る";
            }
            else {
                // エラー付きで再表示
                $Menu    = "CatMent";
                $SubMenu = "Add";
            }
            break;

        case "Edit_Pro_Cat":
            $ErrMsg = CheckCatData();
            if ($ErrMsg == "") {
                // エラーなしなら更新処理
                $SQL  = "UPDATE ".TCATMST." SET ";
                $SQL .= "CatID = ".htmlentities($_REQUEST["CatID"]).", ";
                $SQL .= "CatName = '".CheckSQ(htmlentities($_REQUEST["CatName"]))."' ";
                $SQL .= "Where CatID=".htmlentities($_REQUEST["CatID"]);
                mysqli_query($Conn, CheckSQL($SQL));
                echo "<div style='font-weight:bold'>カテゴリのデータが正常に変更されました</div><a href='".$ThisFile."?Menu=CatMent&SubMenu='>カテゴリの一覧表示</a>に戻る";
            }
            else {
                // エラー付きで再表示
                echo "エラー：".$ErrMsg."<br>";
                $Menu    = "CatMent";
                $SubMenu = "Edit";
            }
            break;

        case "Del_Pro_Cat":
            $SQL = "DELETE FROM ".TCATMST." WHERE CatID=".htmlentities($_POST["CatID"]);
            mysqli_query($Conn, CheckSQL($SQL));
            echo "<div style='font-weight:bold'>カテゴリデータ(ID=".htmlentities($_POST["CatID"]).")が正常に削除されました</div><a href='".$ThisFile."?Menu=CatMent&SubMenu='>カテゴリの一覧表示</a>に戻る";
            break;

        case "Add_Pro_QA":
            $ErrMsg = CheckQAData();
            if ($ErrMsg == "") {
                $result2 = mysqli_query($Conn, 'select MsgID from '.TFAQDATA.' where MsgID='.htmlentities($_REQUEST["MsgID"]));
                if (mysqli_num_rows($result2) >= 1) {
                    echo "  <span style='font-weight:bold'>入力されたQ&Aは既に登録済みです。</span>
                            <a href='{$ThisFile}?Menu=QADataMent&SubMenu=' style='margin-left:50px'>Q&Aデータ一覧</a>に戻る
                            <a href='http://javascript:history.go(-1);' style='margin-left:50px'>登録ページ</a>に戻る";
                    return;
                }

                // エラーなしなら追加処理
                $SQL  = "INSERT INTO ".TFAQDATA." (";
                $SQL .= "MsgID, CatID1, CatID2, Title, Question, Answer, AnsDate, RefCnt) VALUES (";
                $SQL .= htmlentities($_REQUEST["MsgID"]).", ";
                $SQL .= htmlentities($_REQUEST["CatID1"]).", ";
                $SQL .= htmlentities($_REQUEST["CatID2"]).", ";
                $SQL .= "'".CheckSQ(htmlentities($_REQUEST["Title"]))."', ";
                $SQL .= "'".CheckSQ(htmlentities($_REQUEST["Question"]))."', ";
                $SQL .= "'".CheckSQ(htmlentities($_REQUEST["Answer"]))."', ";
                $SQL .= "'".CheckSQ(htmlentities($_REQUEST["AnsDate"]))." 00:00:00', ";
                $SQL .= "0)";
                mysqli_query($Conn, CheckSQL($SQL));
                echo "<div style='font-weight:bold'>Q&Aデータが正常に追加されました</div><a href='".$ThisFile."?Menu=QADataMent&SubMenu='>Q&Aデータ一覧</a>に戻る";
            }
            else {
                // エラー付きで再表示
                $Menu    = "QADataMent";
                $SubMenu = "Add";
            }
            break;

        case "Edit_Pro_QA":
            $ErrMsg = CheckQAData();
            if ($ErrMsg == "") {
                // エラーなしなら更新処理
                $SQL  = "UPDATE ".TFAQDATA." SET ";
                $SQL .= "CatID1=".htmlentities($_REQUEST["CatID1"]).", ";
                $SQL .= "CatID2=".htmlentities($_REQUEST["CatID2"]).", ";
                $SQL .= "Title='".CheckSQ(htmlentities($_REQUEST["Title"]))."', ";
                $SQL .= "Question='".CheckSQ(htmlentities($_REQUEST["Question"]))."', ";
                $SQL .= "Answer='".CheckSQ(htmlentities($_REQUEST["Answer"]))."', ";
                $SQL .= "AnsDate='".CheckSQ(date('Y-m-d', strtotime(htmlentities($_REQUEST["AnsDate"]))))." 00:00:00' ";
                $SQL .= "Where MsgID=".htmlentities($_REQUEST["MsgID"]);
                mysqli_query($Conn, CheckSQL($SQL));
                echo "<div style='font-weight:bold'>Q&Aデータが正常に変更されました</div><a href='".$ThisFile."?Menu=QADataMent&SubMenu='>Q&Aデータ一覧</a>に戻る";
            }
            else {
                // エラー付きで再表示
                echo "エラー：".$ErrMsg."<br>";
                $Menu    = "QADataMent";
                $SubMenu = "Edit";
            }
            break;

        case "Del_Pro_QA":
            $SQL = "DELETE FROM ".TFAQDATA." WHERE MsgID = ".htmlentities($_POST["MsgID"]);
            mysqli_query($Conn, CheckSQL($SQL));
            echo "<div style='font-weight:bold'>Q&Aデータ(ID=".htmlentities($_POST["MsgID"]).")が正常に削除されました</div>";
            echo "<a href='".$ThisFile."?Menu=QADataMent&SubMenu='>Q&Aデータ一覧</a>に戻る";
            break;
    }

    // メニューに従った表示処理
    switch ($Menu) {
        case "":
            echo "<div style='font-weight:bold'>Q&Aデータのメンテナンスメイン画面</div>";
            // エラー有無
            if ($ErrMsg != "") {
                echo "<div style='color:red; font-weight:bold'>".$ErrMsg."</div>";
            }
            echo "<table BORDER=1 CELLPADDING='4' CELLSPACING='0'>";
            if ($_SESSION['LogInState'] == "LogInOk") {
                echo "  <tr BGCOLOR=lightcyan>
                            <form ACTION='".$ThisFile."' METHOD = 'POST'>
                                <input TYPE='hidden' NAME='Menu' value='CatMent'>
                                <td><input TYPE='SUBMIT' VALUE='カテゴリ' style='width:100px; text-align:center'></td>
                            </form>
                            <td>「カテゴリ」の追加/修正/削除を行います</td>
                        </tr>
                        <tr BGCOLOR=ivory>
                            <form ACTION='".$ThisFile."' METHOD = 'POST'>
                                <input TYPE='hidden' NAME='Menu' value='QADataMent'>
                                <td><input TYPE='SUBMIT' VALUE='Q&Aデータ' style='width:100px; text-align:center'></td>
                            </form>
                            <td>「Q&Aデータ」の追加/修正/削除を行います</td>
                        </tr>";
            }
            else {
                echo "  <tr BGCOLOR=seashell>
                            <form ACTION='".$ThisFile."' METHOD = 'POST'>
                                <input TYPE='hidden' NAME='Menu' value='Login_Proc'>
                                <td>パスワードを指定してください</td><td><input TYPE='PASSWORD' NAME='QAPassWord' VALUE=''></td>
                                <td><input TYPE='SUBMIT' VALUE='ログイン'></td>
                            </form>
                        </tr>";
            }
            echo "</table>";
            break;

        case "CatMent":
            // サブメニューの指定が無ければページ情報から取得
            if ($SubMenu == "") {
                $SubMenu = htmlentities(@$_REQUEST["SubMenu"]);
            }

            switch ($SubMenu) {
                case "":
                    echo "  <div style='font-weight:bold'>カテゴリのメンテナンスを行います。</div><br>
                            <a style='font-weight:bold' href='".$ThisFile."?Menu=CatMent&SubMenu=Add&CatID=0'>【新規追加】</a> </td>
                            <table BORDER=0 CELLPADDING='4' CELLSPACING='0' style='border:1px solid steelblue;'>
                                <tr BGCOLOR=lightblue style='border:1px solid steelblue;'>
                                    <td style='border:1px solid steelblue;'>ID</td>
                                    <td style='border:1px solid steelblue;'>カテゴリ名</td>
                                    <td colspan=2 style='border:1px solid steelblue;'>&nbsp</td>
                                </tr>";

                    $SQL    = "SELECT * FROM ".TCATMST." ORDER BY CatID";
                    $result = mysqli_query($Conn, CheckSQL($SQL));
                    $RowNo  = 0;
                    // 既存データの一覧表示
                    while ($res = mysqli_fetch_assoc($result)) {
                        $RowNo++;
                        $CatID = $res["CatID"];
                        echo (($CatID % 100) != 0) ? "<tr BGCOLOR=".ViewColorSet($RowNo)." style='border:1px solid steelblue;'>" : "<tr BGCOLOR=#CCFFCC style='border:1px solid steelblue;'>";
                        echo "  <td style='border:1px solid steelblue;'>".$CatID."</td>
                                <td style='border:1px solid steelblue;'>".$res["CatName"]."</td>
                                <td style='border:1px solid steelblue;'> <a href='".$ThisFile."?Menu=CatMent&SubMenu=Edit&CatID=".$CatID."'> 修正</a> </td>
                                <td style='border:1px solid steelblue;'> <a href='".$ThisFile."?Menu=CatMent&SubMenu=Del&CatID=".$CatID."'> 削除</a> </td>
                            </tr>";
                    }
                    mysqli_free_result($result);
                    echo "</table><br><a href='".$ThisFile."?Menu=&SubMenu='>メイン画面</a>に戻る";
                    break;

                // 追加画面
                case "Add":
                    echo "<div>カテゴリの追加処理<div>";
                    $CatID = htmlentities($_REQUEST["CatID"]);
                    MakeCatAddForm($CatID, 0, $ErrMsg, $ThisFile);
                    unset($_REQUEST["CatID"]);
                    break;

                // 修正画面
                case "Edit":
                    echo "<div>カテゴリの修正処理<div>";
                    $CatID = htmlentities($_REQUEST["CatID"]);
                    MakeCatAddForm($CatID, 1, $ErrMsg, $ThisFile);
                    break;

                // 削除画面
                case "Del":
                    echo "<div>カテゴリの削除処理<div>";
                    $CatID = htmlentities($_REQUEST["CatID"]);
                    MakeCatDelForm($CatID, $ThisFile);
                    break;
            }
            break;

        case "QADataMent":
            // サブメニューの指定が無ければページ情報から取得
            if ($SubMenu == "") {
                $SubMenu = htmlentities($_REQUEST["SubMenu"]);
            }
            switch ($SubMenu) {
                case "":
                    echo "<div style='font-weight:bold'>Q&Aデータのメンテナンスを行います。</div><br>";
                    echo "<a style='font-weight:bold' href='".$ThisFile."?Menu=QADataMent&SubMenu=Add&MsgID=0'>【Q&Aの新規追加】</a> </td>";

                    // 見出しの表示
                    echo "  <table BORDER=0 CELLPADDING='4' CELLSPACING='0' width='100%' style='border:1px solid steelblue;'>
                                <tr BGCOLOR=lightblue width='100%'>
                                    <td rowspan=2 align=center width='4%'  style='border:1px solid steelblue;'>ID</td>
                                    <td align=center width='10%' style='border:1px solid steelblue;'>カテゴリ１</td>
                                    <td rowspan=2 align=center width='15%' style='border:1px solid steelblue;'>タイトル</td>
                                    <td rowspan=2 align=center width='30%' style='border:1px solid steelblue;'>質問</td>
                                    <td rowspan=2 align=center width='30%' style='border:1px solid steelblue;'>回答</td>
                                    <td rowspan=2 align=center width='8%'  style='border:1px solid steelblue;'>回答日</td>
                                    <td rowspan=2 width='*' style='border:1px solid steelblue;'>&nbsp</td>
                                </tr>
                                <tr BGCOLOR=lightblue width='100%'>
                                    <td align=center width='10%'   style='border:1px solid steelblue;'>カテゴリ２</td>
                                </tr>";
                    $SQL    = "SELECT a.*, (SELECT CatName From ".TCATMST." Where CatID=a.CatID1) As CatName1,";
                    $SQL   .= " CASE WHEN a.CatID2<>0 THEN  (SELECT CatName From ".TCATMST." Where CatID=a.CatID2) ELSE '' END  As CatName2 FROM ".TFAQDATA." As a ORDER BY a.MsgID";
                    $result = mysqli_query($Conn, CheckSQL($SQL));
                    $RowNo  = 0;

                    // 既存データの一覧表示
                    while ($res = mysqli_fetch_assoc($result)) {
                        $RowNo++;
                        $MsgID = $res["MsgID"];
                        echo "  <tr BGCOLOR=".ViewColorSet($RowNo)." width='100%'>
                                    <td rowspan=2 width='4%'  style='border:1px solid steelblue;' align=right>".$MsgID."</td>
                                    <td width='10%' style='border:1px solid steelblue;'>".$res["CatName1"]."</td>
                                    <td rowspan=2 width='15%' style='border:1px solid steelblue;'>".$res["Title"]."</td>
                                    <td rowspan=2 valign=top width='30%' style='border:1px solid steelblue;'>".CnvMsg2View($res["Question"])."</td>
                                    <td rowspan=2 valign=top width='30%' style='border:1px solid steelblue;'>".CnvMsg2View($res["Answer"])."</td>
                                    <td rowspan=2 width='8%'  style='border:1px solid steelblue;'>".date('Y/m/d', strtotime($res["AnsDate"]))."</td>
                                    <td align=center width='*' style='border:1px solid steelblue;'> <a href='".$ThisFile."?Menu=QADataMent&SubMenu=Edit&MsgID=".$MsgID."'> 修正</a> </td>
                                </tr>
                                <tr BGCOLOR=".ViewColorSet($RowNo)." width='100%'>";
                        if ($res["CatID2"] != 0) {
                            echo "  <td style='border:1px solid steelblue;'>".$res["CatName2"]."</td>";
                        }
                        else {
                            echo "  <td style='border:1px solid steelblue;'>&nbsp</td>";
                        }
                        echo "<td align=center style='border:1px solid steelblue;'> <a href='".$ThisFile."?Menu=QADataMent&SubMenu=Del&MsgID=".$MsgID."'> 削除</a> </td></tr>";
                    }
                    mysqli_free_result($result);
                    echo "</table><br><a href='".$ThisFile."?Menu=&SubMenu='>メイン画面</a>に戻る";
                    break;

                // 追加画面
                case "Add":
                    echo "<div style='font-weight:bold'>新規のQ&Aデータを追加します。</div><br>";
                    if ($ErrMsg == "") {
                        $MsgID = htmlentities($_REQUEST["MsgID"]);
                    }
                    // 追加データの入力画面を表示する
                    MakeQAAddForm($MsgID, 0, $ErrMsg, $ThisFile);
                    break;

                // 修正画面
                case "Edit":
                    echo "<div style='font-weight:bold'>Q&Aデータを修正します。</div><br>";
                    $MsgID = htmlentities($_REQUEST["MsgID"]);
                    MakeQAAddForm($MsgID, 1, $ErrMsg, $ThisFile);
                    break;

                // 削除画面
                case "Del":
                    echo "<div style='font-weight:bold'>Q&Aデータを削除します。</div><br>";
                    $MsgID = htmlentities($_REQUEST["MsgID"]);
                    MakeQADelForm($MsgID, $ThisFile);
                    break;
            }
            break;
    }
    echo "</html>";
    mysqli_close($Conn);

    // カテゴリデータの追加/修正画面を生成する
    // CatID   ：カテゴリのID
    // ProcTyp ：処理種別  0-追加、1-修正
    // ErrMsg  ：エラーメッセージ   <>空文字列 - エラー有
    // メッセージフォーマット   エラー箇所:エラー内容
    // MoveAddr：SUBMITで移動するページアドレス（現在は自分自身）
    function MakeCatAddForm($CatID, $ProcTyp, $ErrMsg, $MoveAddr) {
        $SQL = $CatName = $ErrIdx = $ErrPos = $MarkPos = "";
        $Conn = ConnectSorizo();
        // ボタンを押した際の処理を定義
        echo "<form ACTION='".$MoveAddr."' METHOD = 'POST'>";
        if ($ProcTyp == 0) {
            // 追加用のパラメータをセット
            echo "<input TYPE='hidden' NAME='Menu' value='Add_Pro_Cat'><input TYPE='hidden' NAME='SubMenu' value=''>";
        }
        else {
            // 修正用のパラメータをセット
            echo "<input TYPE='hidden' NAME='Menu' value='Edit_Pro_Cat'><input TYPE='hidden' NAME='SubMenu' value=''>";
        }

        // エラー判定
        if ($ErrMsg == "") {
            // エラーなし
            if ($CatID != 0) {
                // メッセージIDが０以外ならDB上のデータを取得する
                $SQL = "SELECT * FROM ".TCATMST." Where CatID=".$CatID;
                $result = mysqli_query($Conn, CheckSQL($SQL));
                if (($result != false) && (mysqli_num_rows($result) > 0)) {
                    // カテゴリID、カテゴリ名を取得
                    $res     = mysqli_fetch_assoc($result);
                    $CatID   = $res["CatID"];
                    $CatName = $res["CatName"];
                }
                mysqli_free_result($result);
            }
            else {
                // 初期化
                $CatID   = 0;
                $CatName = "";
            }
        }
        else {
            // エラーメッセージが設定されていた場合、位置を取得
            $ErrIdx = strpos($ErrMsg, ":") + 1;
            $ErrPos = round(substr($ErrMsg, 0, $ErrIdx - 1));
            $ErrMsg = substr($ErrMsg, $ErrIdx);

            // カテゴリID、カテゴリ名を取得する
            $CatID   = round(htmlentities($_REQUEST["CatID"]));
            $CatName = htmlentities($_REQUEST["CatName"]);
        }

        // 画面の表示処理
        // エラーメッセージを表示する
        if ($ErrMsg != "") {
            echo "<div style='color:red; font-weight:bold'>".$ErrMsg."</div>";
        }
        $MarkPos = 1; // エラー位置(先頭)
        echo "<table BORDER=1 CELLPADDING='4' CELLSPACING='0'>";
        // カテゴリID
        echo "<tr>";
        echo "<td>ID";
        // 項目の位置にエラーがある場合は項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        if ($ProcTyp == 0) {
            echo "<td><input TYPE='TEXT' NAME='CatID' VALUE='".((isset($_GET["CatID"])) ? $_GET["CatID"] : $CatID)."' onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength='5'></td>";
        }
        else {
            // コードの変更は後で考える
            echo "<td>";
            echo "<input TYPE='hidden' NAME='CatID' value='".$CatID."'>";
            echo $CatID."</td>";
        }
        echo "</tr>";
        $MarkPos++;
        // カテゴリ名
        echo "<tr>";
        echo "<td>カテゴリ名";
        // 項目の位置にエラーがある場合は項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td><input TYPE='TEXT' NAME='CatName' VALUE='".((isset($_GET["CatName"])) ? $_GET["CatName"] : $CatName)."' size=100 maxlength=50></td>";
        echo "</tr>";
        echo "</table>";
        echo "</BR>";

        // ボタン
        echo ($ProcTyp == 0) ? "<input TYPE='SUBMIT' VALUE='データ追加'>" : "<input TYPE='SUBMIT' VALUE='データ更新'>";
        echo "</form>";
        echo "<br>";
        echo "<a href='".$MoveAddr."?Menu=CatMent&SubMenu='>カテゴリの一覧表示</a>に戻る";
    }

    // カテゴリデータの削除確認画面の表示
    // CatID   ：カテゴリのID
    // MoveAddr：SUBMITで移動するページアドレス（現在は自分自身）
    function MakeCatDelForm($CatID, $MoveAddr) {
        $SQL = $UseMsgID = $UseCnt = "";
        $Conn = ConnectSorizo();
        echo "<form ACTION='".$MoveAddr."' METHOD = 'POST'>";
        echo "<input TYPE='hidden' NAME='Menu' value='Del_Pro_Cat'>";
        echo "<input TYPE='hidden' NAME='SubMenu' value=''>";
        // 他で使用している件数を取得
        $UseCnt = 0;
        if (($CatID % 100) != 0) {
            $SQL = "SELECT Count(MsgID) As MsgCnt FROM ".TFAQDATA." WHERE CatID1=".$CatID." Or CatID2=".$CatID;
            $result = mysqli_query($Conn, CheckSQL($SQL));
            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                $res = mysqli_fetch_assoc($result);
                $UseCnt = $res["MsgCnt"];
            }
            mysqli_free_result($result);
        }
        else {
            $SQL = "SELECT Count(CatID) As MsgCnt FROM ".TCATMST." WHERE (CatID > ".$CatID." And CatID < ".($CatID + 100).")";
            $result = mysqli_query($Conn, CheckSQL($SQL));
            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                $res = mysqli_fetch_assoc($result);
                $UseCnt = $res["MsgCnt"];
            }
            mysqli_free_result($result);
        }
        // 使用していたら先頭のIDを取得する
        if ((($CatID % 100) != 0) && ($UseCnt != 0)) {
            $UseMsgID = 0;
            $SQL = "SELECT MsgID FROM ".TFAQDATA." WHERE CatID1=".$CatID." Or CatID2=".$CatID." Order By MsgID";
            $result = mysqli_query($Conn, CheckSQL($SQL));
            if (($result != false) && (mysqli_num_rows($result) > 0)) {
                $res = mysqli_fetch_assoc($result);
                $UseMsgID = $res["MsgID"];
            }
            mysqli_free_result($result);
        }
        // DB上のデータを取得する
        $SQL = "SELECT * FROM ".TCATMST." Where CatID = ".$CatID;
        $result = mysqli_query($Conn, CheckSQL($SQL));
        echo "<div style='font-weight:bold'>下記のデータを削除します。よろしいですか？</div>";
        echo "<input TYPE='hidden' NAME='CatID' value='".$CatID."'>";
        echo "<table BORDER=1 CELLPADDING='4' CELLSPACING='0'>";
        if (($result != false) && (mysqli_num_rows($result) > 0)) {
            $res = mysqli_fetch_assoc($result);
            // メッセージID
            echo "<tr>";
            echo "<td>ID</td>";
            echo "<td>".$CatID."</td>";
            echo "</tr>";
            // カテゴリ
            echo "<tr>";
            echo "<td>カテゴリ名&nbsp</td>";
            echo "<td>".$res["CatName"]."</td>";
            echo "</tr>";
        }
        mysqli_free_result($result);
        echo "</table>";
        echo "<br>";
        // ボタン
        if ($UseCnt != 0) {
            echo "<br>";
            if (($CatID % 100) != 0) {
                echo "<div style='color:red; font-weight:bold'>このカテゴリは、既にQ&Aで使用されているので削除できません。</div>";
                echo "<div style='color:red; font-weight:bold'>（メッセージID ".$UseMsgID."、 他".$UseCnt."件）</div>";
            }
            else {
                echo "<div style='color:red; font-weight:bold'>このグループに属しているカテゴリがあるため削除できません。</div>";
                echo "<div style='color:red; font-weight:bold'>（カテゴリID ".($CatID + 1)." ～ ".($CatID + 99)."の間）</div>";
            }
        }
        else {
            echo "<input TYPE='SUBMIT' VALUE='削除実行'>";
        }
        echo "</form>";
        echo "<br>";
        echo "<a href='".$MoveAddr."?Menu=CatMent&SubMenu='>カテゴリの一覧表示</a>に戻る";
    }

    // Q&Aデータの追加/修正画面を生成する
    // MsgID   ：メッセージのID （0なら新規の番号を取得する)
    // ProcTyp ： 処理種別  0-追加、1-修正
    // ErrMsg  ：エラーメッセージ   <>空文字列 - エラー有
    // メッセージフォーマット   エラー箇所:エラー内容
    // MoveAddr：SUBMITで移動するページアドレス（現在は自分自身）
    function MakeQAAddForm($MsgID, $ProcTyp, $ErrMsg, $MoveAddr) {
        $SQL = $CatID1 = $CatID2 = $Title = $Question = $Answer = $AnsDate = $ErrIdx = $ErrPos = $MarkPos = "";
        $Conn = ConnectSorizo();

        // ボタンを押した際の処理を定義
        echo "<form ACTION='".$MoveAddr."' METHOD='POST'>";
        if ($ProcTyp == 0) {
            // 追加用のパラメータをセット
            echo "<input TYPE='hidden' NAME='Menu' value='Add_Pro_QA'>";
            echo "<input TYPE='hidden' NAME='SubMenu' value=''>";
        }
        else {
            // 修正用のパラメータをセット
            echo "<input TYPE='hidden' NAME='Menu' value='Edit_Pro_QA'>";
            echo "<input TYPE='hidden' NAME='SubMenu' value=''>";
        }

        // エラー判定
        if ($ErrMsg == "") {
            // エラーなし
            if ($MsgID != 0) { 
                // メッセージIDが０以外ならDB上のデータを取得する
                $SQL = "SELECT * FROM ".TFAQDATA." Where MsgID=".$MsgID;
                $result = mysqli_query($Conn, CheckSQL($SQL));
                if (($result != false) && (mysqli_num_rows($result) > 0)) {
                    $res = mysqli_fetch_assoc($result);
                    // カテゴリID1,2、タイトル、質問、回答、回答日付を取得する
                    $CatID1   = $res["CatID1"];
                    $CatID2   = $res["CatID2"];
                    $Title    = $res["Title"];
                    $Question = $res["Question"];
                    $Answer   = $res["Answer"];
                    $AnsDate  = ($res["AnsDate"] == '') ? '' : date('Y/m/d', strtotime($res["AnsDate"]));
                    $RefCnt   = $res["RefCnt"];
                }
                mysqli_free_result($result);
            }
            else {
                // 新しいメッセージIDを取得する
                $SQL = "SELECT (Max(MsgID)+1) As MaxId FROM ".TFAQDATA;
                $result = mysqli_query($Conn, CheckSQL($SQL));
                if (($result != false) && (mysqli_num_rows($result) > 0)) {
                    $res = mysqli_fetch_assoc($result);
                    $MsgID = $res["MaxId"];
                }
                mysqli_free_result($result);

                // 初期化
                $CatID1   = 0;
                $CatID2   = 0;
                $Title    = "";
                $Question = "";
                $Answer   = "";
                $AnsDate  = "";
                $RefCnt   = 0;
            }
        }
        else {
            // エラーメッセージが設定されていた場合、位置を取得
            $ErrIdx = strpos($ErrMsg, ":") + 1;
            $ErrPos = round(substr($ErrMsg, 0, $ErrIdx - 1)); 
            $ErrMsg = substr($ErrMsg, $ErrIdx);
            // メッセージID、カテゴリID1,2、タイトル、質問、回答、回答日付を取得する
            $MsgID    = round(htmlentities($_REQUEST["MsgID"]));
            $CatID1   = round(htmlentities($_REQUEST["CatID1"]));
            $CatID2   = round(htmlentities($_REQUEST["CatID2"]));
            $Title    = htmlentities($_REQUEST["Title"]);
            $Question = htmlentities($_REQUEST["Question"]);
            $Answer   = htmlentities($_REQUEST["Answer"]);
            $AnsDate  = htmlentities($_REQUEST["AnsDate"]);
        }
        // 画面の表示処理
        echo ($ProcTyp == 0) ? "<div>追加するQ&Aの情報を入力してください</div>" : "<div>修正するQ&Aの情報を入力してください</div>";
        // エラー有無
        if ($ErrMsg != "") {
            echo "<div style='color:red; font-weight:bold'>".$ErrMsg."</div>";
        }
        $MarkPos = 1; // エラー位置(先頭)
        echo "<input TYPE='hidden' NAME='MsgID' value='".$MsgID."'>";
        echo "<table BORDER=1 CELLPADDING='4' CELLSPACING='0'>";
        // メッセージID
        echo "<tr>";
        echo "<td>ID";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td>".$MsgID."</td>";
        echo "</tr>";
        $MarkPos++;
        // カテゴリ１（コンボボックスを生成する）
        echo "<tr>";
        echo "<td>カテゴリ１";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td>";
        MakeCatCombo("CatID1", $CatID1);
        echo "</td>";
        echo "</tr>";
        $MarkPos++;
        // カテゴリ２（コンボボックスを生成する）
        echo "<tr>";
        echo "<td>カテゴリ２";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td>";
        MakeCatCombo("CatID2", $CatID2);
        echo "</td>";
        echo "</tr>";
        $MarkPos++;
        // タイトル
        echo "<tr>";
        echo "<td>タイトル";
        $colsIdx = 100;
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td><textarea cols=".$colsIdx." rows='10' NAME='Title'>".$Title."</textarea></td>";
        echo "</tr>";
        $MarkPos++;
        // 質問
        echo "<tr>";
        echo "<td>質問";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td><textarea cols=".$colsIdx." rows='10' NAME='Question'>".$Question."</textarea></td>";
        echo "</tr>";
        echo "<tr>";
        $MarkPos++;
        // 回答
        echo "<td>回答";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td><textarea cols=".$colsIdx." rows='10' NAME='Answer'>".$Answer."</textarea></td>";
        echo "</tr>";
        $MarkPos++;
        // 回答日付
        echo "<tr>";
        echo "<td>回答日付";
        // 該当位置にエラーがあった場合、項目名の後ろに「*」を表示する
        if ($ErrPos == $MarkPos) {
            echo "<span style='color:red'>*</span>";
        }
        echo "</td>";
        echo "<td><input TYPE='TEXT' NAME='AnsDate' VALUE='".$AnsDate."' size=30 maxlength=30></td>";
        echo "</tr>";
        echo "</table>";
        echo "<br>";
        // ボタン
        echo ($ProcTyp == 0) ? "<input TYPE='SUBMIT' VALUE='データ追加'>" : "<input TYPE='SUBMIT' VALUE='データ更新'>";
        echo "</form>";
        echo "<br>";
        echo "<a href='".$MoveAddr."?Menu=QADataMent&SubMenu='>Q&Aデータ一覧</a>に戻る";
    }

    // カテゴリのコンボボックス表示
    // CtrlName：コンボボックスの名前
    // CtrlVal ：選択する項目の値（０は指定なし)
    function MakeCatCombo($CtrlName, $CtrlVal) {
        // カテゴリ
        $Conn   = ConnectSorizo();
        $SQL    = "SELECT * FROM ".TCATMST." ORDER BY CatID";
        $result = mysqli_query($Conn, CheckSQL($SQL));
        // コンボボックスの要素を出力
        echo "<SELECT NAME='{$CtrlName}'>
                <OPTION VALUE='0'>指定なし</OPTION>";
        while ($res = mysqli_fetch_assoc($result)) {
            $CatID = $res["CatID"];
            // 下二桁が0以外
            if (($CatID % 100) != 0) {
                // カテゴリのIDと名称を出力
                echo "<OPTION VALUE=".$CatID;
                if ($CatID == $CtrlVal) {
                    echo " SELECTED";
                }
                echo ">".$res["CatName"]."</OPTION>";
            }
        }
        echo "</SELECT>";
        mysqli_free_result($result);
    }

    // Q&Aのデータ削除確認画面の表示
    function MakeQADelForm($MsgID, $MoveAddr) {
?>
        <form ACTION="<?= $MoveAddr ?>" METHOD="POST">
            <input TYPE="hidden" NAME="Menu" value="Del_Pro_QA">
            <input TYPE="hidden" NAME="SubMenu" value="">
            <div style="font-weight:bold">下記のデータを削除します。よろしいですか？</div>
            <input TYPE="hidden" NAME="MsgID" value="<?= $MsgID ?>">
            <table BORDER="1" CELLPADDING="4" CELLSPACING="0">
<?php
        // DB上のデータを取得する
        $Conn = ConnectSorizo();
        $SQL  = "SELECT a.*, (SELECT CatName From ".TCATMST." Where CatID=a.CatID1) As CatName1,";
        $SQL .= " CASE WHEN a.CatID2<>0 THEN (SELECT CatName From ".TCATMST." Where CatID=a.CatID2) ELSE '' END As CatName2 FROM ".TFAQDATA." As a";
        $SQL .= " Where a.MsgID=".$MsgID;
        $result = mysqli_query($Conn, CheckSQL($SQL));
        if (($result != false) && (mysqli_num_rows($result) > 0)) {
            $res = mysqli_fetch_assoc($result);
?>
                <!-- メッセージID -->
                <tr>
                    <td>ID</td>
                    <td><?= $MsgID ?></td>
                </tr>

                <!-- カテゴリ１ -->
                <tr>
                    <td>カテゴリ１</td>
                    <td><?= $res["CatName1"] ?></td>
                </tr>

                <!-- カテゴリ２ -->
                <tr>
                    <td>カテゴリ２</td>
                    <?= ($res["CatID2"] != 0) ? "<td>".$res["CatName2"]."</td>" : "<td>&nbsp</td>" ?>
                </tr>

                <!-- タイトル -->
                <tr>
                    <td>タイトル</td>
                    <td><?= CnvMsg2View($res["Title"]) ?></td>
                </tr>

                <!-- 質問 -->
                <tr>
                    <td>質問</td>
                    <td><?= CnvMsg2View($res["Question"]) ?></td>
                </tr>

                <!-- 回答 -->
                <tr>
                    <td>回答</td>
                    <td><?= CnvMsg2View($res["Answer"]) ?></td>
                </tr>

                <!-- 回答日付 -->
                <tr>
                    <td>回答日付</td>
                    <td><?= date('Y/m/d', strtotime($res["AnsDate"])) ?></td>
                </tr>
<?php   } ?>
            </table><br>
            <!-- ボタン -->
            <input TYPE="SUBMIT" VALUE="削除実行">
        </form><br>
        <a href="<?= $MoveAddr ?>?Menu=QADataMent&SubMenu=">Q&Aデータ一覧</a>に戻る
<?php
        mysqli_free_result($result);
    }

    // 入力されたカテゴリのデータのチェックをする
    // IDが0かどうか
    // カテゴリ名に入力があるか？
    function CheckCatData() {
        $CatID   = htmlentities($_REQUEST["CatID"]);
        $CatName = htmlentities($_REQUEST["CatName"]);
        if (round($CatID) == 0) {
            unset($_REQUEST["CatID"]);
            return "1:「ID」が不正です。";
        }

        if ($CatName == "") {
            return "2:「カテゴリ名」は未入力にできません。";
        }
        return '';
    }

    // 入力されたQ&Aのデータのチェックをする
    // IDが0かどうか
    // カテゴリ１が「指定なし」かどうか？
    // タイトル、質問、回答、回答日付に入力があるか？
    // 回答日付に不正な日付がないか？
    function CheckQAData() {
        $MsgID    = htmlentities($_REQUEST["MsgID"]);
        $CatID1   = htmlentities($_REQUEST["CatID1"]);
        $Title    = htmlentities($_REQUEST["Title"]);
        $Question = htmlentities($_REQUEST["Question"]);
        $Answer   = htmlentities($_REQUEST["Answer"]);
        $AnsDate  = htmlentities($_REQUEST["AnsDate"]);

        if (intval($MsgID) == 0) {
            return "1:IDが不正です。";
        }

        if ($CatID1 == 0) {
            return "2:カテゴリ１は「指定なし」にはできません。";
        }

        if ($Title == "") {
            return "4:「タイトル」は未入力にできません。";
        } 

        if ($Question == "") {
            return "5:「質問」は未入力にできません。";
        }

        if ($Answer == "") {
            return "6:「回答」は未入力にできません。";
        }

        if ($AnsDate == "") {
            return "7:「回答日付」は未入力にできません。";
        }

        if (preg_match('/^[0-9]{4}\/[0-9]{1}[0-9]?\/[0-9]{1}[0-9]?$/', $AnsDate) == false) {
            return "7:「回答日付」の形式が正しくありません。";
        }

        list($year, $month, $day) = explode('/', $AnsDate);
        if (($month < 1 || $month > 12) || ($day < 1 || $day > 31) || ($month == 2 && $day > 29)) {
            return "7:「回答日付」の値が正しくありません。";
        }
        return '';
    }
?>
