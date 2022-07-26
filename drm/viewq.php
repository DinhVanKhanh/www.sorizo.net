<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemValue();
    WriteLog(true);

    // パラメータ取得
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//    $Menu    = htmlspecialchars(@$_REQUEST["Menu"]);
//    $GroupID = (htmlspecialchars(@$_REQUEST["GroupID"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["GroupID"])) : ALLGROUP;
//    $MesID   = (htmlspecialchars(@$_REQUEST["MesID"]) != '') ? ceil(htmlspecialchars(@$_REQUEST["MesID"])) : '';

    $Menu    = isset($_REQUEST["Menu"]) ? $_REQUEST["Menu"] : '';
    $GroupID = isset($_REQUEST["GroupID"]) ? ceil(htmlspecialchars($_REQUEST["GroupID"])) : ALLGROUP;
    $MesID   = isset($_REQUEST["MesID"]) ? ceil(htmlspecialchars($_REQUEST["MesID"])) : '';
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
    // Cookie取得
    $CookieArr   = GetCookie(CookieConf);
    $ThreadView  = (isset($CookieArr["ThreadView"]) && $CookieArr["ThreadView"] != '') ? ceil($CookieArr["ThreadView"]) : 1;
    $UpdateV     = (isset($CookieArr["UpdateV"]) && $CookieArr["UpdateV"] != '') ? ceil($CookieArr["UpdateV"]) : 7;
    $MessageRead = (isset($CookieArr["MessageRead"]) && $CookieArr["MessageRead"] != '') ? ceil($CookieArr["MessageRead"]) : 1;

    // グループ情報取得
    $conn   = ConnectSorizo();
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//    $sql    = "SELECT * FROM ".TGROUP." WHERE GroupID={$GroupID}";
//    $result = mysqli_query($conn, $sql);

    $sql    = "SELECT * FROM ".TGROUP." WHERE GroupID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $GroupID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $GroupName    = $row["GroupName"];
        $GroupModeID  = $row["GroupModeID"];
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//        $ReadOnly     = ($row["WritePassWord"] != '') ? true : false;
//        $ReadPassWord = ($row["ReadPassWord"] != '') ? $row["ReadPassWord"] : '';
//        $ResMail      = $row["ResMail"];

        $ReadOnly     = (!is_null($row["WritePassWord"])) ? true : false;
        $ReadPassWord = (!is_null($row["ReadPassWord"])) ? $row["ReadPassWord"] : '';
        $ResMail      = (!is_null($row["ResMail"])) ? $row["ResMail"] : '';
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
    }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
    mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
    mysqli_free_result($result);

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
//            $RPW = htmlspecialchars(@$_REQUEST["RPW"]);
//            $sn  = ($RPW == $ReadPassWord) ? 1 : (($RPW != '') ? 2 : 3);
        if (!isset($_SESSION["ReadPassWord"])) {
            $RPW = isset($_REQUEST["RPW"]) ? $_REQUEST["RPW"] : "";
            $sn  = ($RPW == $ReadPassWord) ? 1 : (($RPW == "") ? 2 : 3);
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
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
            case 2:
?>
                <HTML lang="ja">
                    <HEAD>
                        <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=x-sjis">
                        <title><?= $GLOBALS['MainTitle'] ?></title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
                    </HEAD>
                    <?= $GLOBALS['BodyVal'] ?>
                        グループ【"<?= $GroupName ?>"】のパスワードを入力してください<BR><BR>
                        <form ACTION="<?= ViewFileQ ?>" METHOD="POST">
                            <input TYPE="hidden" NAME="Menu" value="Mes">
                            <input TYPE="hidden" NAME="GroupID" value="<?= $GroupID ?>">
                            <input TYPE="hidden" NAME="MesID" value="<?= $MesID ?>">　パスワード
                            <input TYPE="password" NAME="RPW" size="20">
                            <input TYPE="SUBMIT" VALUE="入力" ALIGN="MIDDLE">
                        </form>
                    </BODY>
                </HTML>
<?php
                mysqli_close($conn);
                exit;
            case 3:
?>
                <HTML lang="ja">
                    <HEAD>
                        <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=x-sjis">
                        <title><?= $GLOBALS['MainTitle'] ?></title>
                    </HEAD>
                    <?= $GLOBALS['BodyVal'] ?>
                        グループ【"<?= $GroupName ?>"】のパスワードが違うので利用できません<BR><BR>
                    </BODY>
                </HTML>
<?php
                mysqli_close($conn);
                exit;
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
    if (($Menu == "" || $Menu == "Mes") && $MesID != '') {
        if ($MessageRead == 2) {
// 2020/02/13 t.maruyama 修正 ↓↓ 不具合の対応
//            $CookieReadNo = ($CookieArr["CookieReadNo"] != '') ? ceil($CookieArr["CookieReadNo"]) : 1;
            $CookieReadNo = (isset($CookieArr["CookieReadNo"]) && $CookieArr["CookieReadNo"] != '') ? ceil($CookieArr["CookieReadNo"]) : 1;
// 2020/02/13 t.maruyama 修正 ↑↑ 不具合の対応
            SetRead($MesID, $CookieReadNo, $ThreadView);
        }
    }

    echo '<HTML lang="ja">
            <HEAD>
                <META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=x-sjis">
                <title>'.$GLOBALS['MainTitle'].'</title>
            </HEAD>'.$GLOBALS['BodyVal'];

    // デフォルト画面
    switch ($Menu) {
        case "":
        case "Mes":
            if ($MesID != '') {
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                mysqli_query($conn, "UPDATE ".TMESSAGE." SET AccessCount=AccessCount+1 WHERE MessageID={$MesID}");

                $sql = "UPDATE ".TMESSAGE." SET AccessCount=AccessCount+1 WHERE MessageID = ?";
                $exec_stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($exec_stmt, 'i', $MesID);
                mysqli_stmt_execute($exec_stmt);
                mysqli_stmt_close($exec_stmt);
// 2020/01/09 t.maruyama 修正 ↑↑
                if ($ThreadView == 2) {
                    ShowOneMessage($MesID, $GroupID, 2);
                }
                else {
                    ShowTitleTree(ROOT, ALLMESSAGE, ROOT, FindRoot($MesID), $MesID, $GroupID, 1);
                }
                SetAccessLog("Message", $MesID);
            }
            else {
// 2022/02/21 Kentaro.Watanabe ↓↓ 平石理事長の意向で全面的に文面を書き換え
?>
<div style='margin-bottom:10px;'>
画面上部の「新規の質問を入力する」をクリックして質問を入力してください。<BR>
</div>
<div style='padding-left:1em; text-indent:-1em;'>
※ご回答までお時間をいただく場合がございます。<br>
ご質問の前に画面左側の「タイトル」や「メッセージの内容」にキーワードを入力して、過去の同様のお問い合わせを検索されることをお勧めします。<br>
（例）「圧縮記帳」や、「資産売却」、「収入保険」など
</div>

<BR>
<DIV style='margin:30px; padding:10px 20px; width:80%; border:1px #C0C080 solid; background-color:#FFFFD0; font:92%/150% Meiryo,メイリオ,sans-serif; color:#333;'>
  <div style='font-size:20px; line-height:130%; font-family:Meiryo,メイリオ,sans-serif; font-weight:bold; color:#ee0000;'>
ご質問の内容が、<u>ソリマチ「農業簿記」の操作的なご質問</u>の場合は、下記の方法にてお問い合わせを頂くよう、よろしくお願いいたします。
  </div>
  <div style='margin-top:15px; font-size:18px; line-height:150%; padding-left:1em; text-indent:-1em; font-weight:bold;'>
  ①農業簿記ソフトのメニュー画面の右側の「よくある質問を検索」にキーワードを入力して検索をしてください。
  </div>
  <div style='font-size:18px; line-height:130%; padding-left:5em; text-indent:-4em;'>
  （例）「圧縮記帳」や、「資産売却」、「収入保険」など
  </div>
  <div style='margin-top:15px; font-size:18px; line-height:150%; padding-left:1em; text-indent:-1em; font-weight:bold;'>
  ②弊社サポートセンターに問合せ
  </div>
  <div style='font-size:16px; line-height:130%; padding-left:5.5em; text-indent:-4em;'>
  <b>[TEL] 0258-31-5850</b><br>
  サービス時間：10:00～12:00、13:00～17:00<br>
  （土日祝日および弊社指定休日を除く）<br>
  ※確定申告時期は電話が込み合いますので、FAXかメールをご活用ください。
  </div>
  <div style='font-size:16px; line-height:130%; padding-left:5.5em; text-indent:-4em;'>
  <b>[FAX] 0258-31-5651</b><br>
  </div>
  <div style='font-size:16px; line-height:130%; padding-left:5.5em; text-indent:-4em;'>
  <b>[Mail]</b> メールでのお問い合わせはこちら<br>
  → <a href="https://member.sorimachi.co.jp/provisional/inquiry/" target="_blank">https://member.sorimachi.co.jp/provisional/inquiry/</a>
  </div>

</DIV>
<?php
// 2022/02/21 Kentaro.Watanabe ↑↑ 平石理事長の意向で全面的に文面を書き換え
            }
            break;

        case "MSum":
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "SELECT * FROM ".TSUMMESSAGE." WHERE SumID=".$_REQUEST["SumID"]." ORDER BY OrderID";
//            $result = mysqli_query($conn, $sql);

            $SumID = htmlspecialchars($_REQUEST["SumID"]);
            $sql = "SELECT * FROM ".TSUMMESSAGE." WHERE SumID = ? ORDER BY OrderID";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $SumID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ShowOneMessage($row["MessageID"], $GroupID, 1);
                    echo "<BR>";
                }
            }
            else {
                echo "このまとめに登録されているメッセージはありません<BR>";
            }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            break;

        case "Warn":
            echo "<font color='RED' SIZE='5'>【警告】</font><H2>".@$_REQUEST["Warn"]."</H2>";
            break;

        case "Info":
            echo "<font color='GREEN' SIZE='5'>【情報】</font><H2>".$_REQUEST["Warn"]."</H2>";
            break;

        case "AddNew":
            $TRCnt    = 0;
            $UName    = $CookieArr["UName"];
            $EMail    = $CookieArr["EMail"];

            $PassWord = $CookieArr["PassWord"];
            $thread   = ceil(@$_REQUEST["thread"]);

            if ($thread != NEWMESSAGE) {
                echo "【コメント入力】&nbsp（「<FONT COLOR=RED>*</FONT>」のついている項目は必須項目です。）<BR>";
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID={$thread}";
//                $result = mysqli_query($conn, $sql);

                $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ?";
                $stmt = mysqli_prepare($conn, $sql);
            // <2020/08/13> <VinhDao>
                // mysqli_stmt_bind_param($stmt, 'is', $thread);
                mysqli_stmt_bind_param($stmt, 'i', $thread);
            // <2020/08/13> <VinhDao>
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $TitleV = $row["Title"];
                    if (strtoupper(substr($TitleV, 0, 3)) != strtoupper("Re:")) {
                        $TitleV = "Re:".$TitleV;
                    }
                    
                    $Mess = ">".$row["Message"];
                    $Mess = str_replace(chr(10), chr(10).">", $Mess);
                    $Mess = str_replace("&quot;", '"', $Mess);
                    $Mess = str_replace("&amp;", "&", $Mess);
                    $Mess = str_replace("&gt;", ">", $Mess);
                    $Mess = str_replace("&lt;", "<", $Mess);
                    $Mess = str_replace("&lt;BR&gt;", "<BR>", $Mess);
                }
            }
            else {
                echo "【新規の質問入力】&nbsp（「<FONT COLOR='RED'>*</FONT>」のついている項目は必須項目です。）<BR>";
            }
            echo "<TABLE width='100%' BORDER=0 CELLPADDING='3' CELLSPACING='0'>";

            if ($GroupModeID == 2) {
                echo "<form ACTION='".AddBinFile."' METHOD='POST' ENCTYPE='multipart/form-data' TARGET='_top'>";
            }
            else {
                echo "<form ACTION='".AddFile."' METHOD='POST' TARGET='_top'>";
            }
            echo '  <input TYPE="hidden" NAME="Menu" value="AddNew2">
                    <input TYPE="hidden" NAME="thread" value="'.$thread.'">
                    <input TYPE="hidden" NAME="Redirect" value="'.MainFileQ.'">';
            if ($ReadPassWord != '') {
                echo '<input TYPE="hidden" NAME="ReadPassWord" value="'.$ReadPassWord.'">';
            }

            if ($thread != NEWMESSAGE) {
                echo '<input TYPE="hidden" NAME="GroupID" value="'.$row["GroupID"].'">';
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
                mysqli_free_result($result);
            }
            else {
                echo "<TR BGCOLOR='".ViewColorSet($TRCnt)."'><TD><input TYPE='hidden' NAME='GroupID' value='{$GroupID}'></TD></TR>";
            }

            $TRCnt++;
            echo "<TR BGCOLOR='".ViewColorSet($TRCnt)."'><TD ALIGN='CENTER'>氏名<FONT COLOR='RED'>&nbsp*</FONT></TD><TD><input TYPE='text' NAME='UName' size='20' VALUE='{$UName}'></TD></TR>";

            $TRCnt++;
            echo "<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                    <TD ALIGN='CENTER'>E-Mail&nbsp&nbsp</TD>";
            if ($thread != NEWMESSAGE) {
                echo "<TD><input TYPE='EMail' size='50' NAME='EMail' VALUE='{$EMail}'></TD>";
            }
            else {
                echo "	<TD>
                            <input TYPE='EMail' size='50' NAME='EMail' VALUE='{$EMail}'>
                            <FONT size='-2'>&nbspメールアドレスを入力すると、回答内容があなたにメールされます</FONT>
                        </TD>";
            }
            echo "</TR>";

            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD ALIGN='CENTER'>タイトル<FONT COLOR=RED>&nbsp*</FONT></TD>
                        <TD><input TYPE='text' NAME='Title' size='50' VALUE='".(($thread != NEWMESSAGE) ? $TitleV : '')."'></TD>
                    </TR>";

            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD ALIGN='CENTER'>メッセージ<FONT COLOR=RED>&nbsp*</FONT></TD>
                        <TD><textarea cols=".MessageWidth." rows='10' NAME='Message'>".(($thread != NEWMESSAGE) ? $Mess : '')."</textarea></TD>
                    </TR>";

            if ($GroupModeID == 2) {
                $TRCnt++;
                echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                            <TD ALIGN='CENTER'>添付ファイル</TD>
                            <TD><input TYPE='file' NAME='File'></TD>
                        </TR>";
            }

            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD ALIGN='CENTER'>パスワード</TD>
                        <TD><input TYPE='password' NAME='PassWord' size='20' VALUE='{$PassWord}'>";
            echo ($ReadOnly == true) ? "<BR>このグループは読み取り専用です。書き込み用のパスワードを入力してください。" : "<BR>パスワードを入力しておくと削除することができます。";
            echo "			<BR>パスワードは半角英数字でご入力ください。
                        </TD>
                    </TR>";

            if ($ResMail == 3 || $ResMail == 4) {
                $TRCnt++;
                echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                            <TD ALIGN='CENTER'>返信時メール</TD>
                            <TD><SELECT NAME='RetMail'>
                                <OPTION VALUE='0' SELECTED>送信しない
                                <OPTION VALUE='1'>このメッセージに対する返信のみ送信する
                                <OPTION VALUE='2'>このメッセージに対する返信すべてに送信する
                            </SELECT>
                            </TD>
                        </TR>";
            }

            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD ALIGN='CENTER'>　</TD>
                        <TD><input TYPE='SUBMIT' VALUE='登　録' ALIGN='MIDDLE'></TD>
                    </TR>
                    <input TYPE='hidden' NAME='URL' Value=''>
                </TABLE></form>";
            break;

        case "Delete1":
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID={$MesID} AND PassWord IS Not NULL";
//            $result = mysqli_query($conn, $sql);

            $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ? AND PassWord IS Not NULL";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $MesID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑

            if (mysqli_num_rows($result) > 0) {
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql = "SELECT * FROM ".TMESSAGE." WHERE thread={$MesID}";
//                $result2 = mysqli_query($conn, $sql);

                $sql = "SELECT * FROM ".TMESSAGE." WHERE thread = ?";
                $stmt2 = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt2, 'i', $MesID);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
// 2020/01/09 t.maruyama 修正 ↑↑

                if (mysqli_num_rows($result2) == 0 || mysqli_num_rows($result2) == false) {
                    echo "　No.{$MesID}　の削除<BR><BR>
                            <form ACTION='".MainFileQ."' METHOD='POST' TARGET='_top'>
                                <input TYPE='hidden' NAME='Menu' value='Delete'>
                                <input TYPE='hidden' NAME='GroupID' VALUE='".$row["GroupID"]."'>
                                <input TYPE='hidden' NAME='MesID' value='{$MesID}'>　　パスワード　
                                <input TYPE='password' NAME='PassWord' size='20' VALUE='{$PassWord}'>　
                                <input TYPE='SUBMIT' VALUE='削除' ALIGN='MIDDLE'>
                            </form>";
                }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                mysqli_stmt_close($stmt2);
// 2020/01/09 t.maruyama 追加 ↑↑
                mysqli_free_result($result2);
            }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            break;
    }

    mysqli_close($conn);
    echo "</BODY></HTML>";

    function ShowOneMessage($MesID, $GrpID, $ThreadView) {
        $Find1 = htmlspecialchars(@$_REQUEST["Find1"]);
        $Find2 = htmlspecialchars(@$_REQUEST["Find2"]);

        $conn      = ConnectSorizo();
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql       = "SELECT * FROM ".TMESSAGE." WHERE MessageID={$MesID}";
//        $resultMes = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ?";
        $stmtMes = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmtMes, 'i', $MesID);
        mysqli_stmt_execute($stmtMes);
        $resultMes = mysqli_stmt_get_result($stmtMes);
// 2020/01/09 t.maruyama 修正 ↑↑

        $TRCnt = 0;
        if (mysqli_num_rows($resultMes) > 0) {
            $row = mysqli_fetch_assoc($resultMes);
            $sql = "SELECT * FROM ".TGROUP." WHERE GroupID=".$row["GroupID"];
            $reulstGrp = mysqli_query($conn, CheckSQL($sql));

            echo "	<A NAME='{$MesID}'><TABLE BORDER='0' width='100%' CELLPADDING='3' CELLSPACING='0'>";

            $Title = $row["Title"];
            if ($Find1 != '') {
                $FindKey = explode("　", $Find1);
                foreach ($FindKey as $key => $value) {
                    $Title = str_replace($value, "<FONT COLOR=RED>".$value."</FONT>", $Title);
                }
            }
            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD>{$Title}　(No.{$MesID})</TD>
                        <TD COLSPAN=2 ALIGN='RIGHT'>&nbsp</TD>
                    </TR>";

            $TRCnt++;
            echo "	<TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                        <TD WIDTH='55%'>";

            if ($row["EMail"] != '') {
                echo "　　<A href='mailto:".$row["EMail"]."'>".$row["UName"]."</A>";
            }
            else {
                echo  "　　".$row["UName"];
            }

            $color = (strtotime( DateAdd($GLOBALS['UpdateV'], $row["UDate"]) ) >= strtotime( date('d') )) ? '#FF0000' : '#FF9999';
            echo "		</TD>
                        <TD ALIGN='RIGHT' WIDTH='30%'><FONT COLOR='{$color}'>".date('Y/m/d H:i:s', strtotime($row["UDate"]))."</FONT></TD>
                        <TD ALIGN='RIGHT'>　</TD>
                    </TR>
                </TABLE></A>";

            $Mess = '';
            $MessA = explode(chr(13).chr(10), $row['Message']);

            foreach($MessA as $key => $value) {
                $Mess .= ((strpos($value, '>') == 0 && strpos($value, '>') !== false) or (strpos($value, '&gt;') == 0 && strpos($value, '&gt;') !== false)) ? "<font color='blue'>" : "<font color='black'>";
                // $Mess .= (strpos($value, '&gt;') == 0 && strpos($value, '&gt;') !== false) ? "<FONT COLOR='BLUE'>" : "<FONT COLOR='BLACK'>";
                if (mb_strlen($value) > MessageWidth) {
                    $i = 0;
                    while ($i <= mb_strlen($value)) {
                        // ↓Kentaro.Watanabe Mod;
                        $Mess .= mb_substr($value, $i, MessageWidth+1)."<br>";
                        // $Mess .= mb_substr($value, $i, MessageWidth)."<br>";
                        // ↑Kentaro.Watanabe Mod;
                        $i += MessageWidth + 1;
                    }
                }
                else {
                    $Mess .= $value."<br>";
                }
                $Mess .= '</FONT>';
            }

            $Mess = str_replace('<br><br>', '<br>', $Mess);

            if ($Find2 != '') {
                $FindKey  = explode("　", $Find2);
                $Key2     = ConvertCharacter2to1byte($Find2);
                $FindKey2 = explode("　", $Key2);
                $i = 0;
                foreach ($FindKey as $key => $value) {
                    $Mess = str_replace($value, "<FONT COLOR='RED'>".$value."</FONT>", $Mess);
                    $Mess = str_replace($FindKey2[$i], "<FONT COLOR='RED'>".$FindKey2[$i]."</FONT>", $Mess);
                    $i++;
                }
            }

            if (strpos($Mess, "http://") != false) {
                $Mess1 = "";
                $w2 = $p2 = 0;
                while (strpos($Mess, "http://") != false) {
                    $p1 = strpos($Mess, "http://");
                    $w1 = substr($Mess, 0, $p1 - 1);
                    $w2 = substr($Mess, $p1 - 1);

                    if (strpos($w2, " ") != false) {
                        $p2 = strpos($w2, " ");
                    }
                    if (strpos($w2, "　") != false && strpos($w2, "　") < $p2) {
                        $p2 = strpos($w2, "　");
                    }
                    if (strpos($w2, chr(13)) != false && strpos($w2, chr(13)) < $p2) {
                        $p2 = strpos($w2, chr(13));
                    }
                    if (strpos($w2, "<") != false && strpos($w2, "<") < $p2) {
                        $p2 = strpos($w2, "<");
                    }

                    $Mess1 .= $w1."<A href='".substr($w2, 0, $p2-1)."' TARGET='_top'>".substr($w2, 0, $p2 - 1)."</A>";
                    $Mess  = substr($w2, $p2 - 1);
                }
                $Mess = $Mess1.substr($w2, $p2 - 1);
            }

            if ($row["Title"] != "削除" || $row["Message"] != "削除") {
                echo "<TABLE BORDER='1' WIDTH='100%' BORDERCOLOR='#009933' CELLPADDING='4' CELLSPACING='0'><TR><TD BGCOLOR='#FFFFFF'><PRE>".$Mess."</PRE></TD></TR></TABLE>";

                if ($GLOBALS['GroupModeID'] == 2) {
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                    $sql = "SELECT * FROM ".TUPLOAD." WHERE MessageID={$MesID} ORDER BY ImageID";
//                    $resultD = mysqli_query($conn, $sql);

                    $sql = "SELECT * FROM ".TUPLOAD." WHERE MessageID = ? ORDER BY ImageID";
                    $stmtD = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmtD, 'i', $MesID);
                    mysqli_stmt_execute($stmtD);
                    $resultD = mysqli_stmt_get_result($stmtD);
// 2020/01/09 t.maruyama 修正 ↑↑

                    if (mysqli_num_rows($resultD) > 0) {
                        echo "<TABLE BORDER='0' width='100%' CELLPADDING='4' CELLSPACING='0'>";
                        while ($row = mysqli_fetch_assoc($resultD)) {
                            $FName = $row["ImageFileName"];
                            $FExt = substr($FName, strrpos($FName, ".") + 1);

                            if ($FName != '') {
                                echo "<TR BGCOLOR='#FFFFFF'><TD>";
                                if (strtoupper($FExt) == "JPG" || strtoupper($FExt) == "Gif" || strtoupper($FExt) == "PNG") {
                                    echo "<IMG SRC='files/{$FName}'>";
                                }
                                else {
                                    echo "<A href='files/{$FName}' TARGET='_blank'>{$FName}</A>";
                                }
                                echo "</TD></TR>";
                            }
                        }
                        echo "</TABLE>";
                    }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                    mysqli_stmt_close($stmtD);
// 2020/01/09 t.maruyama 追加 ↑↑
                    mysqli_free_result($resultD);
                }

                $TRCnt++;
                echo "	<TABLE BORDER=0 width='100%' CELLPADDING='4' CELLSPACING='0'>
                            <TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                                <TD WIDTH='15%'><A HREF='".ViewFileQ."?Menu=AddNew&GroupID=".$row["GroupID"]."&thread={$MesID}' target='VIEW'>コメント</A></TD>";

                if ( $row["PassWord"] != '' ) {
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                    $sql = "SELECT * FROM ".TMESSAGE." WHERE thread={$MesID}";
//                    $resultD = mysqli_query($conn, $sql);

                    $sql = "SELECT * FROM ".TMESSAGE." WHERE thread = ?";
                    $stmtD = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmtD, 'i', $MesID);
                    mysqli_stmt_execute($stmtD);
                    $resultD = mysqli_stmt_get_result($stmtD);
// 2020/01/09 t.maruyama 修正 ↑↑

                    if (mysqli_num_rows($resultD) == 0 || mysqli_num_rows($resultD) == false) {
                        echo "<TD WIDTH='15%'><A HREF='".ViewFileQ."?Menu=Delete1&GroupID=".$row["GroupID"]."&MesID={$MesID}' target='VIEW'>削除</A></TD>";
                    }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                    mysqli_stmt_close($stmtD);
// 2020/01/09 t.maruyama 追加 ↑↑
                    mysqli_free_result($resultD);
                }
                echo "			<TD ALIGN='RIGHT'>Access:".$row["AccessCount"]."</TD>
                            </TR>
                        </TABLE>";
            }

            if ( $ThreadView == 2 ) {
                $TRCnt++;
                echo "	<TABLE BORDER=0 width='100%' CELLPADDING='4' CELLSPACING='0'>
                            <TR BGCOLOR='".ViewColorSet($TRCnt)."'>
                                <TD>";
                ShowTitleTree(ROOT, ALLMESSAGE, ROOT, FindRoot($MesID), $MesID, $row["GroupID"], 2);
                echo "			</TD>
                            </TR>
                        </TABLE>";
            }
        }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        mysqli_stmt_close($stmtMes);
// 2020/01/09 t.maruyama 追加 ↑↑
        mysqli_free_result($resultMes);
        mysqli_close($conn);
    }

    function ShowTitleTree( $Parent, $MessageThread, $Thread, $RootID, $MessageID, $GrpID, $ViewMode ) {
        $conn   = ConnectSorizo();
        $sql    = "SELECT * FROM ".TMESSAGE." WHERE GroupID={$GrpID} AND thread={$Parent} ORDER BY MessageID";
        $result = mysqli_query($conn, $sql);

        if ( mysqli_num_rows($result) > 0 ) {
            $CookieArr = GetCookie(CookieConf);
            while ( $row = mysqli_fetch_assoc($result) ) {
                $MesID = $row["MessageID"];
                if ( $Parent != ROOT || $RootID == ROOT || $MesID == $RootID ) {
                    if ($ViewMode == 2) {
                        echo str_repeat("&emsp;", $Thread);

                        if ($Thread != ROOT) {
                            echo MarkerThread;
                        }

                        if ($MesID == $MessageID) {
                            echo $row["Title"];
                        }
                        else {
                            echo "<a href='".ViewFileQ."?Menu=Mes&GroupID=".$GLOBALS['GroupID']."&MesID={$MesID}'>".$row["Title"]."</a>";
                        }

                        if ($GLOBALS['GroupModeID'] == 2) {
                            $sql = "SELECT ImageID FROM ".TUPLOAD." WHERE MessageID={$MesID}";
                            $result2 = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result2) > 0) {
                                echo IMGGBBS;
                            }
                            mysqli_free_result($result2);
                        }

                        if ($GLOBALS['MessageRead'] == 2) {
                            $CookieReadNo = ($CookieArr["CookieReadNo"] != '') ? ceil($CookieArr["CookieReadNo"]) : 1;

                            $b = false;
                            for ($i = 1; $i <= $CookieReadNo; $i++) {
                                $MesRead = $_COOKIE[CookieRead.$i]["MesRead"];
                                if (strpos($MesRead, ",{$MesID},") > 0) {
                                    $b = true;
                                    break;
                                }
                            }

                            if ($b == false) {
                                echo "<FONT COLOR=GREEN>[未読]</FONT>";
                            }
                        }

                        $color = (strtotime( DateAdd($GLOBALS['UpdateV'], $row["UDate"]) ) >= strtotime( date('d') )) ? '#FF0000' : '#FF9999';
                        echo "　[".$row["UName"]."]　
                                <FONT COLOR='{$color}'>".date('Y/m/d H:i:s', strtotime($row["UDate"]))."</FONT><BR>";
                    }
                    else {
                        echo "	<TABLE BORDER='0' width='100%' CELLPADDING='0' CELLSPACING='2'>
                                    <TR>
                                        <TD WIDTH='".($Thread * 10)."'>&nbsp;</TD>";
                        if ($Thread != ROOT) {
                            echo "<TD WIDTH='10' VALIGN='TOP' ALIGN='RIGHT'>".MarkerThread."</TD>";
                        }
                        echo "<TD>";
                        ShowOneMessage($MesID, $GrpID, 1);
                        echo "</TD></TR></TABLE>";
                    }
                    ShowTitleTree($MesID, $MessageThread, $Thread+1, $RootID, $MessageID, $GrpID, $ViewMode);

                    if ($Parent == ROOT) {
                        echo "<br>";
                    }
                }
            }
        }
        else {
            if ($Parent == ROOT && $Parent == $RootID) {
                echo "<BR>このグループに投稿されているメッセージはありません<BR><BR>";
            }
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }

    function SetRead($MesID, $CookieReadNo, $tv) {
        $b = false;
        for ($i = 1; $i <= $CookieReadNo; $i++) {
            $MesRead = GetCookie(CookieRead.$i, "MesRead");
            if (strpos($MesRead, ",{$MesID},") !== false) {
                $b = true;
                break;
            }
        }

        if ($b == false) {
            $MesRead = GetCookie(CookieRead.$CookieReadNo, "MesRead");
            if (mb_strlen($MesRead) > CookieMax) {
                $CookieReadNo++;
                $CookieArray = GetCookie(CookieConf);
                $CookieArray["CookieReadNo"] = $CookieReadNo;
                UpdateCookie(CookieConf, $CookieArray, $GLOBALS['CookiesExpires']);
                $MesRead = "";
            }
            $CookieArray2 = GetCookie(CookieRead);
            $CookieArray2["MesRead"] = "{$MesRead},{$MesID},";

            for ($i = 1; $i <= $CookieReadNo; $i++) {
                UpdateCookie(CookieRead.$i, $CookieArray2, $GLOBALS['CookiesExpires']);
            }
        }

        if ($tv == 1) {
            $conn = ConnectSorizo();
// 2020/01/09 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $result = mysqli_query($conn, "SELECT MessageID FROM ".TMESSAGE." WHERE thread={$MesID}");

            $sql = "SELECT MessageID FROM ".TMESSAGE." WHERE thread = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $MesID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/09 t.maruyama 修正 ↑↑

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    SetRead($row["MessageID"], $CookieReadNo, $tv);
                }
            }
// 2020/01/09 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/09 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            mysqli_close($conn);
        }
    }
?>
