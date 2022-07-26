<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemValue();
    WriteLog(true);
?>
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8">
            <title><?= $GLOBALS['MainTitle'] ?></title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
        </head>
        <?= $GLOBALS['BodyVal'] ?>
<?php
    // カテゴリ名取得
    $conn    = ConnectSorizo();
    $GroupID = (htmlspecialchars(@$_REQUEST["GroupID"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["GroupID"])) : ALLGROUP;
    $result  = mysqli_query($conn, "SELECT * FROM ".TCATMST." WHERE CatID={$GroupID}");
    if (mysqli_num_rows($result) > 0) {
        $row     = mysqli_fetch_assoc($result);
        $CatName = $row["CatName"];
    }
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

    // 表示ページ数
    $apNow = (htmlspecialchars(@$_REQUEST["apNow"]) != '') ? htmlspecialchars(@$_REQUEST["apNow"]) : 1;
    $Menu  = (htmlspecialchars(@$_REQUEST["Menu"]) == '') ? $DefMenu : htmlspecialchars(@$_REQUEST["Menu"]);
    switch ($Menu) {
        case 'Thread':
            if ($GroupID != ALLGROUP) {
                echo  "【{$CatName}】<br>";
                ShowTitleList($Menu, $GroupID, $apNow);
            }
            else {
                echo "左のリストから知りたいことに関するカテゴリを選択するか、上の「検索」を使って質問を検索してください。";
            }
            break;

        case 'Find2':
            $character1 = $character2 = "";
            $FindFAQ = str_replace("　", " ", $_REQUEST["FindFAQ"]);
            preg_match_all('/\S+/', trim($FindFAQ), $key);

            $count = count($key[0]);
            if ($count > 0) {
                for ($i = 0; $i < $count; $i++) {
                    $character1 .= $key[0][$i].chr(32);
                    $character2 .= ConvertCharacter2to1byte($key[0][$i]).chr(32);
                }
            }
            else {
                $character1 = $_REQUEST["FindFAQ"];
                $character2 = ConvertCharacter2to1byte($_REQUEST["FindFAQ"]);
            }

            $character1 = trim($character1, chr(32));
            $character2 = trim($character2, chr(32));
            $Message = array("cha1" => $character1, "cha2" => $character2);
            echo "キーワード【 ".str_replace(chr(32), "／", $character1)." 】で検索した結果 <br>";
            ShowTitleList($Menu, ALLGROUP, $apNow, $Message);
            unset($character1);
            unset($character2);
            break;

        case 'Warn':
            echo " <font color='RED' SIZE='5'>【警告】</font><H2>".$_REQUEST["Warn"]."</H2>";
            break;
    }

    echo "</body></html>";
    mysqli_close($conn);

    function ShowTitleList($Menu, $GrpID, $apNow, $key=array()) {
        $conn = ConnectSorizo();
        $sql = "SELECT * FROM ".TFAQDATA;
        $Ord = " ORDER BY MsgId";
        $countKey = 0;
        $ParamKey = $KeyWord1 = $KeyWord2 = '';

        // 処理により分岐
        switch ($Menu) {
            case "Thread":
                if ($GrpID != ALLGROUP) {
                    $sql .= " WHERE CatID1={$GrpID} Or CatID2={$GrpID}";
                }
                $sql .= $Ord;
                break;

            case "Find2":
                $KeyWord1  = str_replace("'", "''", $key["cha1"]);
                $KeyWord2  = str_replace("'", "''", $key["cha2"]);
                $ParamKey  = "&FindFAQ={$KeyWord1}";
                $FindKeys1 = explode(chr(32), $KeyWord1);
                $FindKeys2 = explode(chr(32), $KeyWord2);
                $countKey  = (count($FindKeys1) > 0) ? count($FindKeys1) : 1;
                $sql      .= " WHERE";

                // 「タイトル」中を検索するクエリの組み立て
                $title = $question = $answer = '';
                $i = 0;
                // Character 1 byte
                foreach ($FindKeys1 as $key => $value) {
                    $title    .= " AND (Title Like '%{$value}%' Or Title Like '%".$FindKeys2[$i]."%')";
                    $question .= " AND (Question Like '%{$value}%' Or Question Like '%".$FindKeys2[$i]."%')";
                    $answer   .= " AND (Answer Like '%{$value}%' Or Answer Like '%".$FindKeys2[$i]."%')";
                    $i++;
                }
                $title    = trim($title, " AND");
                $question = trim($question, " AND");
                $answer   = trim($answer, " AND");

                $sql .= " ($title) Or ($question) Or ($answer) $Ord";
                $sql  = str_replace("WHERE ORDER", "ORDER", $sql);
                unset($title);
                unset($question);
                unset($answer);
                break;
        }

        // SQL実施
        $result = mysqli_query($conn, $sql);
        $numRow = mysqli_num_rows($result);
        if ($numRow > 0) {
            mysqli_data_seek($result, ($apNow - 1) * $GLOBALS['psNyuryoku']);
            $MesCnt = 0;

            echo "<table style='margin:5px; border:1px solid navy;' width='100%' cellpadding='2'>";
            while ($row = mysqli_fetch_assoc($result)) {
                $MesID = $row["MsgID"];
                echo "<tr BGCOLOR=".ListColorSet($MesCnt).">
                        <td>
                            <img src='images/qmark.gif' width='16' height='16'>
                            <a href='".ViewFile."?Menu=Mes&GroupID={$GrpID}&MesID={$MesID}";
                if ($countKey > 0) {
                    echo "&Find2=".urlencode($KeyWord1);
                }
                echo "' target='VIEW'>".$row["Title"]."</a></td></tr>";

                $MesCnt++;
                if ($MesCnt == $GLOBALS['psNyuryoku']) {
                    break;
                }
            }
            echo "</table>【ページ移動】 ";

            $numPage = (($numRow % $GLOBALS['psNyuryoku']) == 0) ? ($numRow / $GLOBALS['psNyuryoku']) : ceil($numRow / $GLOBALS['psNyuryoku']);
            $link    = "<a href='".ListFile."?Menu={$Menu}&GroupID=".$GLOBALS['GroupID']."&apNow=%s".(($ParamKey != '') ? $ParamKey : '')."' Title='%s'>%s</a>　";

            if ($apNow > 1) {
                echo sprintf($link, 1, '先頭ページへ', '|<');
                if ($numPage > 19) {
                    if ($apNow > 5) {
                        echo sprintf($link, $apNow - 5, '５ページ前へ', '≪');
                    }
                    else {
                        echo "≪　";
                    }
                }
                echo sprintf($link, $apNow - 1, '前ページへ', '＜');
            }
            else {
                if ($numPage > 19) {
                    echo "|<　≪　＜　";
                }
                else {
                    echo "|<　＜　";
                }
            }

            echo "（".$apNow."/".$numPage.")　";

            if ($apNow < $numPage) {
                echo sprintf($link, $apNow + 1, '次ページへ', '＞');
                if ($numPage > 19) {
                    if ($apNow <= ($numPage - 5)) {
                        echo sprintf($link, ($apNow + 5), '５ページ後へ', '≫');
                    }
                    else {
                        echo "≫　";
                    }
                }
                echo sprintf($link, $numPage, '最終ページへ', '>|');
            }
            else {
                if ($numPage > 19) {
                    echo "＞　≫　>|";
                }
                else {
                    echo "＞　>|";
                }
            }
        }
        else {
            echo "該当するメッセージはありません";
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
<script language="JavaScript">
    function ClearView() {
        top.VIEW.location.href = "view.php";
    }
    ClearView();
</script>
