<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemValue();
    WriteLog(true);

    $lngResCount = $lngReadCount = 0;
    $conn        = ConnectSorizo();
    $Menu        = htmlspecialchars(@$_REQUEST["Menu"]);

    // グループ情報取得
    $ReadPassWord = "";
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//    $GroupID      = (htmlspecialchars(@$_REQUEST["GroupID"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["GroupID"])) : ALLGROUP;
    $GroupID      = isset($_REQUEST["GroupID"]) ? ceil(htmlspecialchars($_REQUEST["GroupID"])) : ALLGROUP;
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応

// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//    $sql          = "SELECT * FROM ".TGROUP." WHERE GroupID={$GroupID}";
//    $result       = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM ".TGROUP." WHERE GroupID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $GroupID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $GroupName   = $row["GroupName"];
        $GroupModeID = $row["GroupModeID"];

        if (!is_null($row["ReadPassWord"])) {
            $ReadPassWord = $row["ReadPassWord"];
        }
    }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
    mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
    mysqli_free_result($result);

    // システム情報
    $result = mysqli_query($conn, "SELECT * FROM ".TSYSTEM);
    $row    = mysqli_fetch_assoc($result);

    // デフォルトのメニュー
    $CookieArr   = GetCookie(CookieConf);
    $DefMenu     = (isset($CookieArr["DefMenu"]) && $CookieArr["DefMenu"] != '') ? $CookieArr["DefMenu"] : $row["DefMenu"];
    $psIchiran   = (isset($CookieArr["psIchiran"]) && $CookieArr["psIchiran"] != '') ? ceil($CookieArr["psIchiran"]) : $row["psIchiran"];

    // 1ページの件数
    $psNyuryoku  = (isset($CookieArr["psNyuryoku"]) && $CookieArr["psNyuryoku"] != '') ? ceil($CookieArr["psNyuryoku"]) : $row["psNyuryoku"];
    $UpdateV     = (isset($CookieArr["UpdateV"]) && $CookieArr["UpdateV"] != '') ? ceil($CookieArr["UpdateV"]) : $row["UpdateV"];
    $ThreadView  = (isset($CookieArr["ThreadView"]) && $CookieArr["ThreadView"] != '') ? ceil($CookieArr["ThreadView"]) : $row["ThreadView"];

    // 表示順
    $ThreadOrder = (isset($CookieArr["ThreadOrder"]) && $CookieArr["ThreadOrder"] != '') ? ceil($CookieArr["ThreadOrder"]) : $row["ThreadOrder"];
    $MessageRead = (isset($CookieArr["MessageRead"]) && $CookieArr["MessageRead"] != '') ? ceil($CookieArr["MessageRead"]) : $row["MessageRead"];
    mysqli_free_result($result);

    // 表示ページ数
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//    $apNow = (htmlspecialchars(@$_REQUEST["apNow"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["apNow"])) : 1;
    $apNow = isset($_REQUEST["apNow"]) ? ceil(htmlspecialchars($_REQUEST["apNow"])) : 1;
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
?>
    <html lang="ja">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8">
            <title><?= $GLOBALS['MainTitle'] ?></title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
        </head>
<?php
    echo $GLOBALS['BodyVal'];

    // グループの読み取りパスワードが設定されている場合
    if ($ReadPassWord != '') {
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//        if ($_SESSION["GroupID"] != "G{$GroupID}") {
        if (isset($_SESSION["GroupID"]) && $_SESSION["GroupID"] != "G{$GroupID}") {
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
            // グループが変わった
            $_SESSION["ReadPassWord"] = $_SESSION["GroupID"] = "";
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//            session_destroy();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
        }

// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//        if ($_SESSION["ReadPassWord"] == '') {
//            $RPW = $_REQUEST["RPW"];
        if (!isset($_SESSION["ReadPassWord"])) {
            $RPW = isset($_REQUEST["RPW"]) ? $_REQUEST["RPW"] : "";
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
            $sn  = ($RPW == $ReadPassWord) ? 1 : (($RPW == "") ? 2 : 3);
        }
        else {
            $sn  = ($_SESSION["ReadPassWord"] == $ReadPassWord) ? 1 : 3;
        }

        switch ($sn) {
            case 1:
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
                if (session_id() != "") {
                    // いったんセッションをクリアする
                    $_SESSION = array();
                    session_destroy();
                }
                session_start();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
                $_SESSION["ReadPassWord"] = $ReadPassWord;
                $_SESSION["GroupID"]      = "G{$GroupID}";
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//                session_destroy();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
                break;
            case 2: ?>
                        <br><hr>グループ【<?= $GroupName ?>】のパスワードを入力してください<br><br>
                        <form ACTION="<?= ListFileQ ?>" METHOD="POST">
                            <input TYPE="hidden" NAME="Menu" value="">
                            <input TYPE="hidden" NAME="GroupID" value="<?= $GroupID ?>">　パスワード
                            <input TYPE="password" NAME="RPW" size="20">
                            <input TYPE="SUBMIT" VALUE="入力" ALIGN="MIDDLE">
                        </form>
                    </body>
                </html>
<?php
                mysqli_close($conn);
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//                break;
                exit();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
            case 3: ?>
                        <br><hr>グループ【<?= $GroupName ?>】のパスワードが違うので利用できません<br><br>
                    </body>
                </html>
<?php
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//                break;
                exit();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
        }
    }
    else {
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//        $_SESSION["ReadPassWord"] = $_SESSION["GroupID"] = '';
        $_SESSION = array();
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
        session_destroy();
    }

    // 処理により分岐
    if ($Menu == '') {
        $Menu = $DefMenu;
        SetAccessLog("Group", $GroupID);
    }

    echo "&nbsp<img src='images/pencil.gif' width='16' height='16'>&nbsp";
    echo "<a href='".ViewFileQ."?Menu=AddNew&GroupID={$GroupID}&thread=0' target='VIEW' style='font-weight:bold'>新規の質問を入力する</a>　";

    // Mori_DrbbsSum
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//    $sql = "SELECT * FROM ".TSUM." WHERE GroupID={$GroupID}";
//    $result = mysqli_query($conn, $sql);

    $sql = "SELECT * FROM ".TSUM." WHERE GroupID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $GroupID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑
    if (mysqli_num_rows($result) > 0) {
        echo "<a href='".ListFileQ."?Menu=MSum&GroupID={$GroupID}' TARGET='LIST'>まとめ</a>　";
    }
    elseif ($Menu == "MSum") {
        $Menu ="Root";
    }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
    mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
    mysqli_free_result($result);
    echo "<br><hr>";

    // メニュー選択
    switch ($Menu) {
        case "Thread":
            echo "【ツリー表示】".(($ThreadOrder == 1) ? "　★最新発言順★<br>" : "　★質問の発言順★<br>");
            ShowTitleTree(ROOT, ALLMESSAGE, ROOT, ROOT, ROOT, $GroupID, $apNow);
            break;

        case "Root":
            echo "【ルート表示】".(($ThreadOrder == 1) ? "　★最新発言順★<br>" : "　★ルートの発言順★<br>");
            ShowTitleTree(ROOT, ROOT, ROOT, ROOT, ROOT, $GroupID, $apNow);
            break;

        case "All":
            echo "【入力順表示】<br>";
            ShowAllMessage($GroupID, $apNow);
            break;

        case "AllGroup":
            echo "【全グループ入力順表示】<br>";
            ShowAllMessage(ALLGROUP, $apNow);
            break;

        case "MSum":
            echo "【まとめ表示】<br>";
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "SELECT * FROM ".TSUM." WHERE GroupID={$GroupID} ORDER BY OrderID";
//            $result = mysqli_query($conn, $sql);

            $sql = "SELECT * FROM ".TSUM." WHERE GroupID = ? ORDER BY OrderID";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $GroupID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑
            $numRow = mysqli_num_rows($result);
            $numPage = (($numRow % $psNyuryoku) == 0) ? $numRow / $psNyuryoku : ceil($numRow / $psNyuryoku);

            if ($numRow > 0) {
                $MesCnt = 0;

                mysqli_data_seek($result, ($apNow - 1) * $psNyuryoku);
                echo "<table border='0' width='100%' cellpadding='2' cellspacing='1'>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "  <tr bgcolor=".ListColorSet2($MesCnt)."><td>
                                <img src='images/qmark.gif' width='16' height='16'>
                                <a href='".ViewFileQ."?Menu=MSum&GroupID={$GroupID}&SumID=".$row["SumID"]."' target='VIEW'>".$row["SumTitle"]."</a>
                            </td></tr>";

                    $MesCnt++;
                    if ($MesCnt == $psNyuryoku) {
                        break;
                    }
                }
                echo "</table>";

                if ($numPage > 1) {
                    echo "【ページ移動】 ";
                    if ($apNow > 1) {
                        echo "  <a href='".ListFileQ."?Menu={$Menu}&apNow=1'>≪</a>　
                                <a href='".ListFileQ."?Menu={$Menu}&apNow=".($apNow - 1)."'>＜</a>　";
                    }
                    else {
                        echo "≪　＜　";
                    }

                    echo "（{$apNow}/{$numPage})　";

                    if ($apNow < $numPage) {
                    echo "  <a href='".ListFileQ."?Menu={$Menu}&apNow=".($apNow + 1)."'>＞</a>　
                            <a href='".ListFileQ."?Menu={$Menu}&apNow={$numPage}'>≫</a>　";
                    }
                    else {
                        echo "＞　≫";
                    }
                }
            }
            else {
                echo "まとめは登録されていません";
            }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            break;

        case "Find":
?>
            【検索】
            <table border="0">
                <form ACTION="<?= ListFileQ ?>" METHOD="POST">
                    <input TYPE="hidden" NAME="Menu" value="Find2">
                    <input TYPE="hidden" NAME="GroupID" value="<?= $GroupID ?>">
                    <tr>
                        <td ALIGN="CENTER">グループ</td>
                        <td>
<?php
// 2020/01/08 t.maruyama 修正 ↓↓ 文法エラー修正
//            $result = mysqli_query($conn, "SELECT * FROM {$TGROUP}");
            $result = mysqli_query($conn, "SELECT * FROM ".TGROUP);
// 2020/01/08 t.maruyama 修正 ↑↑
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<input TYPE='radio' NAME='FindGroup' value='".$row["GroupID"]."'>".$row["GroupName"];
            }
            mysqli_free_result($result);
?>
                        <input TYPE="radio" NAME="FindGroup" value="0" CHECKED>全グループ</td>
                    </tr>
                    <tr>
                        <td ALIGN="CENTER">投稿者</td>
                        <td><input TYPE="text" NAME="UName" size="30"></td>
                    </tr>
                    <tr>
                        <td ALIGN="CENTER">タイトル</td>
                        <td><input TYPE="text" NAME="Title" size="30">　　<input TYPE="SUBMIT" VALUE="検索開始" ALIGN="MIDDLE"></td>
                    </tr>
                    <tr>
                        <td ALIGN="CENTER">内　容</td>
                        <td><input TYPE="text" NAME="Message" size="30"></td>
                    </tr>
                    <tr>
                        <td ALIGN="CENTER">　</td>
                        <td>複数キーワードはスペースで区切る</td>
                    </tr>
                </form>
            </table>
<?php
            break;

        case "Find2":
            $UName     = htmlspecialchars(@$_REQUEST["UName"]);
            $UName     = str_replace("'", "''", $UName);

            $Title     = htmlspecialchars(@$_REQUEST["Title"]);
            $Title     = str_replace("'", "''", $Title);

            $Message   = htmlspecialchars(@$_REQUEST["Message"]);
            $Message   = str_replace("'", "''", $Message);
            $FindGroup = ceil(htmlspecialchars(@$_REQUEST["FindGroup"]));

            echo "【検索結果】<br>";
            $sql = "SELECT * FROM ".TMESSAGE." WHERE";
            if ($FindGroup != 0) {
                $sql .= " GroupID={$FindGroup}";
            }

            if ($UName != '') {
                $i = 0;
                $sql2 = "";
                $FindKey = explode(chr(32), $UName);
                $UName2  = ConvertCharacter2to1byte($UName);
                $UName2  = str_replace("'", "''", $UName2);
                $Key2    = explode(chr(32), $UName2);
                foreach ($FindKey as $key => $value) {
                    $sql2 .= " AND (UName Like '%{$value}%' Or UName Like '%".$Key2[$i]."%')";
                    $i++;
                }
                $sql .= " AND ".trim($sql2, " AND");
            }

            if ($Title != '') {
                $i = 0;
                $sql2 = "";
                $FindKey = explode(chr(32), $Title);
                $Title2  = ConvertCharacter2to1byte($Title);
                $Title2  = str_replace("'", "''", $Title2);
                $Key2    = explode(chr(32), $Title2);
                foreach ($FindKey as $key => $value) {
                    $sql2 .= " AND (Title Like '%{$value}%' Or Title Like '%".$Key2[$i]."%')";
                    $i++;
                }
                $sql .= " AND ".trim($sql2, " AND");
            }

            if ($Message != '') {
                $i = 0;
                $sql2 = "";
                $FindKey  = explode(chr(32), $Message);
                $Message2 = ConvertCharacter2to1byte($Message);
                $Message2 = str_replace("'", "''", $Message2);
                $Key2     = explode(chr(32), $Message2);
                foreach ($FindKey as $key => $value) {
                    $sql2 .= " AND (Message Like '%{$value}%' Or Message Like '%".$Key2[$i]."%')";
                    $i++;
                }
                $sql .= " AND ".trim($sql2, " AND");
            }

            $sql .= " AND GroupID In (SELECT GroupID FROM ".TGROUP." WHERE (ReadPassWord Is Null) Or (ReadPassWord='".$_SESSION["ReadPassWord"]."'))";
            $sql  = str_replace("WHERE AND", "WHERE", $sql);
            $sql .= " ORDER BY MessageID DESC";
            $sql  = str_replace("WHERE ORDER", "ORDER", $sql);

            $result  = mysqli_query($conn, $sql);
            $numRow  = mysqli_num_rows($result);
            $numPage = ($numRow % $psNyuryoku == 0) ? $numRow / $psNyuryoku : ceil($numRow / $psNyuryoku);

            if ($numRow > 0) {
                $md = false;
                $MesCnt = 0;
                echo "<table border='0' width='100%' cellpadding='2' cellspacing='1'>";

                mysqli_data_seek($result, ($apNow - 1) * $psNyuryoku);
                while ($row = mysqli_fetch_assoc($result)) {
                    $MesID = $row["MessageID"];
                    echo "  <tr bgcolor='".ListColorSet2($MesCnt)."'><td>
                                <img src='images/qmark.gif' width='16' height='16'>
                                <a href='".ViewFileQ."?Menu=Mes&GroupID={$GroupID}&MesID={$MesID}&Find1=".str_replace(chr(32), "　", $Title)."&Find2=".str_replace(chr(32), "　", $Message);
                    if ($row["thread"] != 0) {
                        echo "#{$MesID}";
                    }
                    echo "' target='VIEW'>".$row["Title"]."</a>";

                    if ($GroupModeID == 2) {
                        $result2 = mysqli_query($conn, "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID={$MesID}");
                        if (mysqli_num_rows($result2) > 0) {
                            echo IMGGBBS;
                        }
                        mysqli_free_result($result2);
                    }

                    if ($MessageRead == 2) {
                        $CookieReadNo = (!is_null($CookieArr["CookieReadNo"])) ? ceil($CookieArr["CookieReadNo"]) : 1;
                        $b = false;

                        for ($i = 1; $i <= $CookieReadNo; $i++) {
                            $MesRead = $_COOKIE[CookieRead.$i]["MesRead"];
                            if (strpos($MesRead, ",{$MesID},") != false) {
                                $b = true;
                                break;
                            }
                        }

                        if ($b == false) {
                            echo "　<font COLOR=GREEN>[未読]</font>";
                        }
                    }

                    echo "　[".$row["UName"]."]　";
                    echo "<font COLOR='".(strtotime(DateAdd($UpdateV, $row["UDate"])) >= strtotime(date('d')) ? '#FF0000' : '#FF9999')."'>".date('Y/m/d H:i:s', strtotime($row["UDate"]))."</font>";
                    echo "　(No.{$MesID})</td></tr>";

                    $md = true;
                    $MesCnt++;
                    if ($MesCnt == $psNyuryoku) {
                        break;
                    }
                }
                echo "</table>";

                if ($md) {
                    Pagination($apNow, $numPage, array('UName'=>$UName, 'Title'=>$Title, 'Message'=>$Message));
                }
                else {
                    echo "該当するメッセージはありません";
                }
            }
            else {
                echo "該当するメッセージはありません";
            }
            mysqli_free_result($result);
            break;

        case "conf":
?>
            【設定】<br>
            <table BORDER='0'>
                <form ACTION="<?= MainFileQ ?>" METHOD="POST" TARGET="_top">
                    <input TYPE="hidden" NAME="Menu" value="conf2">
                    <input TYPE="hidden" NAME="GroupID" value="<?= $GroupID ?>">
                    <tr>
                        <td ALIGN="LEFT">デフォルト表示</td>
                        <td>
                            <SELECT NAME="DefMenu">
<?php
            $temp = '   <OPTION VALUE="Root">ルート表示
                        <OPTION VALUE="Thread">スレッド表示
                        <OPTION VALUE="All">入力順表示
                        <OPTION VALUE="AllGroup">全グループ入力順表示
                        <OPTION VALUE="MSum">まとめ
                        <OPTION VALUE="Find">検索';
            echo str_replace('VALUE="'.$DefMenu.'"', 'VALUE="'.$DefMenu.'" SELECTED', $temp);
?>
                            </SELECT>
                        </td>
                    </tr>
                    <tr>
                        <td ALIGN='LEFT'>スレッド表示、ルート表示の数</td>
                        <td><input TYPE="text" NAME="psIchiran" size="5" VALUE="<?= $psIchiran ?>"></td>
                    </tr>
                    <tr>
                        <td ALIGN="LEFT">入力順表示、検索結果の数</td>"
                        <td><input TYPE="text" NAME="psNyuryoku" size="5" VALUE="<?= $psNyuryoku ?>"></td>
                    </tr>
                    <tr>
                        <td ALIGN="LEFT">最新表示の日数</td>
                        <td><input TYPE="text" NAME="UpdateV" size="5" VALUE="<?= $UpdateV ?>"></td>
                    </tr>
                    <tr>
                        <td ALIGN="LEFT">メッセージの表示</td>
                        <td>
                            <SELECT NAME="ThreadView">
                                <OPTION VALUE="1"<?= ($ThreadView == 1) ? ' SELECTED' : '' ?>>スレッドの全メッセージを表示
                                <OPTION VALUE="2"<?= ($ThreadView != 1) ? ' SELECTED' : '' ?>>１メッセージのみ表示
                            </SELECT>
                        </td>
                    </tr>
                    <tr>
                        <td ALIGN="LEFT">スレッド表示、ルート表示の表示順</td>
                        <td>
                            <SELECT NAME="ThreadOrder">
                                <OPTION VALUE="1"<?= ($ThreadOrder == 1) ? ' SELECTED' : '' ?>>最新発言順
                                <OPTION VALUE="2"<?= ($ThreadOrder != 1) ? ' SELECTED' : '' ?>>ルートの発言順
                            </SELECT>
                        </td>
                    </tr>
                    <tr>
                        <td ALIGN="LEFT">未読の表示</td>
                        <td>
                            <SELECT NAME="MessageRead">
                                <OPTION VALUE="1"<?= ($MessageRead == 1) ? ' SELECTED' : '' ?>>しない
                                <OPTION VALUE="2"<?= ($MessageRead == 2) ? ' SELECTED' : '' ?>>する（クッキーを利用）
                            </SELECT>
                        </td>
                        <td>
                            <input TYPE="SUBMIT" NAME="ConfMenu" VALUE="設定" ALIGN="MIDDLE">
                            <input TYPE="SUBMIT" NAME="ConfMenu" VALUE="規定値に戻す" ALIGN="MIDDLE">
                        </td>
                    </tr>
                </form>
            </table>
<?php
            break;

        case "Warn":
            echo  ' <font color="RED" SIZE="5">【警告】</font>
                    <H2>'.$_REQUEST["Warn"].'</H2>';
            break;
    }

    mysqli_close($conn);
?>
        </body>
    </html>
<?php
    function ShowTitleTree($Parent, $MessageThread, $Thread, $ROOTID, $MessageID, $GrpID, $apNow) {
        if ($MessageThread == ALLMESSAGE || ($MessageThread == ROOT && $Parent == ROOT)) {
            $conn = ConnectSorizo();
            $Ord = ($GLOBALS['ThreadOrder'] == 1 && $Parent == ROOT) ? 'ORDER BY OrderID DESC, MessageID' : 'ORDER BY MessageID';
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "SELECT * FROM ".TMESSAGE.' WHERE '.(($GrpID != ALLGROUP) ? "GroupID={$GrpID} AND " : '')."thread={$Parent} {$Ord}";

            if ($GrpID != ALLGROUP) {
                $sql = "SELECT * FROM ".TMESSAGE." WHERE GroupID = ? AND thread = ? ";
            } else {
                $sql = "SELECT * FROM ".TMESSAGE." WHERE thread = ? ";
            }
            $sql .= $Ord;
// 2020/01/08 t.maruyama 修正 ↑↑

            if ($Parent == ROOT) {
                $sql .= " DESC";
            }
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $result  = mysqli_query($conn, $sql);

            $stmt = mysqli_prepare($conn, $sql);
            if ($GrpID != ALLGROUP) {
                mysqli_stmt_bind_param($stmt, 'ii', $GrpID,$Parent);
            } else {
                mysqli_stmt_bind_param($stmt, 'i', $Parent);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑
            $numRow  = mysqli_num_rows($result);
            $numPage = ($numRow % $GLOBALS['psIchiran'] == 0) ? $numRow / $GLOBALS['psIchiran'] : ceil($numRow / $GLOBALS['psIchiran']);

            if ($numRow > 0) {
                echo "<table border='0' width='100%' ".(($Parent == ROOT) ? "cellpadding='3' cellspacing='1'" : "cellpadding='0' cellspacing='0'" ).">";
                $MesCnt = 0;
                $CookieArr = GetCookie(CookieConf);

                mysqli_data_seek($result, ($apNow - 1) * $GLOBALS['psIchiran']);
                while ($row = mysqli_fetch_assoc($result)) {
                    $MesID = $row["MessageID"];

                    if ($Parent != ROOT || ($Parent == ROOT && ($ROOTID == ROOT || $MesID == $ROOTID))) {
                        echo "<tr".(($Parent == ROOT) ? ' bgcolor='.ListColorSet2($MesCnt).'><td>' : '><td>');
                        echo str_repeat("&emsp;", $Thread);
                        echo ($Thread == ROOT) ? "<img src='images/qmark.gif' width='16' height='16'>" : MarkerThread;
                        echo ($MesID == $MessageID) ? $row["Title"] : "<a href='".ViewFileQ."?Menu=Mes&GroupID=".$row["GroupID"]."&MesID={$MesID}".(($Thread != ROOT) ? "#{$MesID}" : '')."' target='VIEW'>".$row["Title"]."</a>";

                        if ($GLOBALS['GroupModeID'] == 2) {
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $result2 = mysqli_query($conn, "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID={$MesID}");

                            $sql = "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID = ?";
                            $stmt2 = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt2, 'i', $MesID);
                            mysqli_stmt_execute($stmt2);
                            $result2 = mysqli_stmt_get_result($stmt2);
// 2020/01/08 t.maruyama 修正 ↑↑
                            if (mysqli_num_rows($result2) > 0) {
                                echo IMGGBBS;
                            }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            mysqli_stmt_close($stmt2);
// 2020/01/08 t.maruyama 追加 ↑↑
                            mysqli_free_result($result2);
                        }

                        if ($GLOBALS['MessageRead'] == 2) {
                            $CookieReadNo = !is_null($CookieArr["CookieReadNo"]) ? ceil($CookieArr["CookieReadNo"]) : 1;
                            $b = false;
                            for ($i = 1; $i <= $CookieReadNo; $i++) {
                                $MesRead = GetCookie(CookieConf.$i, "MesRead");
                                if (strpos($MesRead, ",{$MesID},") != false) {
                                    $b = true;
                                    break;
                                }
                            }

                            // if ($b == false) {
                            //     echo "　<font COLOR=GREEN>[未読]</font>";
                            // }
                        }

                        echo "　[".$row["UName"]."]　";
                        echo "<font COLOR=".(strtotime(DateAdd($GLOBALS['UpdateV'], $row["UDate"]) >= strtotime(date('d'))) ? "#FF0000" : "#FF9999").">".date('Y/m/d H:i:s', strtotime($row["UDate"]))."</font>";
                        echo "　(No.{$MesID}";

                        if ($Parent == ROOT) {
                            $GLOBALS['lngResCount'] = 0;
                            ResCount($MesID);
                            echo "　Res:";
                            if ($GLOBALS['MessageRead'] == 2) {
                                echo "<font COLOR='GREEN'>".$GLOBALS['lngReadCount']."</font>/";
                            }
                            echo $GLOBALS['lngResCount'];
                        }
                        echo ")<br>";

                        ShowTitleTree($MesID, $MessageThread, $Thread + 1, $ROOTID, $MessageID, $GrpID, $apNow);
                        echo "</td></tr>";

                        $MesCnt++;
                        if ($Parent == ROOT && $MesCnt == $GLOBALS['psIchiran']) {
                            break;
                        }
                    }
                }
                echo "</table>";

                if ($Parent== ROOT && $Parent == $ROOTID) {
                    Pagination($apNow, $numPage);
                }
            }
            else {
                if ($Parent== ROOT && $Parent == $ROOTID) {
                    echo "<br>このグループに投稿されているメッセージはありません<br><br>";
                }
            }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            mysqli_close($conn);
        }
    }

    function ShowAllMessage($GrpID, $apNow) {
        $conn = ConnectSorizo();
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql  = "SELECT * FROM ".TMESSAGE." WHERE GroupID ";
//        $sql .= ($GrpID == ALLGROUP) ? "In (SELECT GroupID FROM ".TGROUP." WHERE (ReadPassWord Is Null) Or (ReadPassWord='".$_SESSION["ReadPassWord"]."'))" : "={$GrpID}";
//        $sql .= " ORDER BY MessageID DESC";
//        $result = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM ".TMESSAGE;
        if ($GrpID == ALLGROUP)
        {
            $rp = $_SESSION["ReadPassWord"];
            $sql .= " WHERE GroupID In " .
                                " (SELECT GroupID FROM ".TGROUP.
                                    " WHERE (ReadPassWord Is Null)".
                                    " Or (ReadPassWord = ?))".
                                    " ORDER BY MessageID DESC";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 's', $rp);
        } else {
            $sql .= " WHERE GroupID = ?".
                    " ORDER BY MessageID DESC";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $GrpID);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑
        $numRow = mysqli_num_rows($result);
        $numPage = ($numRow % $GLOBALS['psNyuryoku'] == 0) ? $numRow / $GLOBALS['psNyuryoku'] : ceil($numRow / $GLOBALS['psNyuryoku']);

        if ($numRow > 0) {
            $MesCnt = 0;
            $CookieArr = GetCookie(CookieConf);
            echo "<table border='0' width='100%' cellpadding='2' cellspacing='1'>";

            mysqli_data_seek($result, ($apNow - 1) * $GLOBALS['psNyuryoku']);
            while ($row = mysqli_fetch_assoc($result)) {
                $MesID = $row["MessageID"];
                echo "<tr bgcolor=".ListColorSet2($MesCnt)."><td>";
                echo GetIcon($row["GroupID"]);
                echo "<a href='".ViewFileQ."?Menu=Mes&GroupID=".$row["GroupID"]."&MesID={$MesID}".(($row["thread"] != 0) ? "#{$MesID}" : '')."' target='VIEW'>".$row["Title"]."</a>";

                if ($GLOBALS['GroupModeID'] == 2) {
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                    $sql = "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID={$MesID}";
//                    $result2 = mysqli_query($conn, $sql);

                    $sql = "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID = ?";
                    $stmt2 = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt2, 'i', $MesID);
                    mysqli_stmt_execute($stmt2);
                    $result2 = mysqli_stmt_get_result($stmt2);
// 2020/01/08 t.maruyama 修正 ↑↑
                    if (mysqli_num_rows($result2) > 0) {
                        echo IMGGBBS;
                    }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                    mysqli_stmt_close($stmt2);
                    mysqli_free_result($result2);
// 2020/01/08 t.maruyama 追加 ↑↑
                }

                if ($GLOBALS['MessageRead'] == 2) {
                    $CookieReadNo = !is_null($CookieArr["CookieReadNo"]) ? ceil($CookieArr["CookieReadNo"]) : 1;
                    $b = false;
                    for ($i = 1; $i <= $CookieReadNo; $i++) {
                        $MesRead = GetCookie(CookieConf.$i, "MesRead");
                        if (strpos($MesRead, ",{$MesID},") !== false) {
                            $b = true;
                            break;
                        }
                    }

                    if ($b == false) {
                        echo "　<font COLOR=GREEN>[未読]</font>";
                    }
                }

                echo "　[".$row["UName"]."]　";
                echo "<font COLOR='".((strtotime(DateAdd($GLOBALS['UpdateV'], $row["UDate"])) >= strtotime(date('d'))) ? '#FF0000' : '#FF9999')."'>".date('Y/m/d H:i:s', strtotime($row["UDate"]))."</font>";
                echo "　(No.{$MesID})</td></tr>";

                $MesCnt++;
                if ($MesCnt == $GLOBALS['psNyuryoku']) {
                    break;
                }
            }
            echo "</table>";
        }

        Pagination($apNow, $numPage);
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
        mysqli_free_result($result);
        mysqli_close($conn);
    }

    function ResCount($MesID) {
        $conn   = ConnectSorizo();
// 2020/01/08 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql    = "SELECT MessageID FROM ".TMESSAGE." WHERE thread={$MesID}";
//        $result = mysqli_query($conn, $sql);

        $sql    = "SELECT MessageID FROM ".TMESSAGE." WHERE thread = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $MesID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
// 2020/01/08 t.maruyama 修正 ↑↑

        $numRow = mysqli_num_rows($result);
        if ($numRow > 0) {
            $CookieArr = GetCookie(CookieConf);
// 2020/01/08 t.maruyama 修正 ↓↓ 文法エラー修正
            $CookieReadNo = 1;
// 2020/01/08 t.maruyama 修正 ↑↑
            if ($GLOBALS['MessageRead'] == 2) {
                $CookieReadNo = (!is_null($CookieArr["CookieReadNo"])) ? ceil($CookieArr["CookieReadNo"]) : 1;
            }

            for ($i = 0; $i < $numRow; $i++) {
                $GLOBALS['lngResCount']++;
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $tMesID = $row["MessageID"];
                if ($GLOBALS['MessageRead'] == 2) {
                    $b = false;
                    for ($i = 1; $i <= $CookieReadNo; $i++) {
                        $MesRead = GetCookie(CookieConf.$i, "MesRead");
                        if (strpos($MesRead, ",{$tMesID},") !== false) {
                            $b = true;
                            break;
                        }
                    }

                    if ($b === false) {
                        $GLOBALS['lngReadCount']++;
                    }
                }
                ResCount($tMesID);
            }
        }
// 2020/01/08 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        mysqli_stmt_close($stmt);
// 2020/01/08 t.maruyama 追加 ↑↑
        mysqli_free_result($result);
        mysqli_close($conn);
    }

    function Pagination($apNow, $numPage, $value = array()) {
        if (count($value) == 0) {
            $link = "<a href='".ListFileQ."?Menu=".$GLOBALS['Menu']."&GroupID=".$GLOBALS['GroupID']."&apNow=%d' Title='%s'>%s</a>　";
        }
        else {
            $link  = "<a href='".ListFileQ."?Menu=".$GLOBALS['Menu']."&GroupID=".$GLOBALS['GroupID']."&FindGroup=".$GLOBALS['FindGroup'];
            $link .= "&apNow=%d&UName=".$value['UName']."&Title=".$value['Title']."&Message=".$value['Message']."' Title='%s'>%s</a>　";
        }

        echo "【ページ移動】 ";
        if ($apNow > 1) {
            echo sprintf($link, 1, '先頭ページへ', '|<');
            if ($numPage > 19) {
                echo ($apNow > 5) ? sprintf($link, $apNow - 5, '５ページ前へ', "≪") : "≪　";
            }
            echo sprintf($link, $apNow - 1, '前ページへ', '＜');
        }
        else {
            echo ($numPage > 19) ? "|<　≪　＜" : "|<　＜";
        }

        echo "（{$apNow}/{$numPage})　";

        if ($apNow < $numPage) {
            echo sprintf($link, $apNow + 1, '次ページへ', '＞');
            if ($numPage > 19) {
                echo ($apNow <= $numPage - 5) ? sprintf($link, $apNow + 5, '５ページ後へ', "≫") : '≫　';
            }
            echo sprintf($link, $numPage, '最終ページへ', '>|');
        }
        else {
            echo ($numPage > 19) ? "＞　≫　>|" : "＞　>|";
        }
    }
?>
