<?php
    require_once "common.php";
    require_once "drbbs.php";
    require_once "../lib/common.php";
    GetSystemValue();
    WriteLog(true);

    $Menu      = htmlspecialchars( @$_REQUEST["Menu"] );
    $CookieArr = GetCookie(CookieConf);
    /*
    $Conn   = ConnectSorizo();
    $result = mysqli_query($Conn, "SELECT * FROM ".TSYSTEM);
    $row    = mysqli_fetch_assoc($result);

    $UpdateV     = ($CookieArr["UpdateV"] != "") ? intval($CookieArr["UpdateV"]) : $row["UpdateV"];
    $MessageRead = ($CookieArr["MessageRead"] != "") ? intval($CookieArr["MessageRead"]) : $row["MessageRead"];
    mysqli_free_result($result);
    mysqli_close($Conn);*/

    echo "<!DOCTYPE html>
            <HTML lang='ja'>
                <head>
                    <meta HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html;charset=x-sjis\">
                    <title>".$GLOBALS['MainTitle']."</title>
";

include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php');

    echo "
                </head>".$GLOBALS['BodyVal']."<img src='".$GLOBALS['TitleImg']."' WIDTH='250' height='65'><br><br>";

    if ( $Menu == "" ) { ?>
        <form name="frmSearch" ACTION="listq.php" METHOD="POST" target="LIST">
            <input TYPE="hidden" NAME="Menu" value="Find2">
            <input TYPE="hidden" NAME="GroupID" value="<?= ALLGROUP; ?>">
            <table bgcolor="#22418B" width="100%">
                <tr><td><font color="#FFFFFF"><strong>検索</strong></font></td></tr>
                <tr>
                    <td bgcolor="#F4F2C0">
                        <input name="FindGroup" value="<?= ALLGROUP; ?>" type="hidden">
                        <table>
                            <tr><td><strong>投稿者の氏名</strong>：</td></tr>
                            <tr><td><input type="text" name="UName" size="20"></td></tr>
                            <tr><td><strong>タイトル</strong>：</td></tr>
                            <tr><td><input type="text" name="Title" size="30"></td></tr>
                            <tr><td><strong>メッセージの内容</strong>：<br/><font size="-2">スペース区切りで複数キーワード指定可能</font></td></tr>
                            <tr><td><input type="text" name="Message" size="30"></td></tr>
                            <tr><td><input type="submit" value="検索開始" align="middle"></td></tr>
                            <tr><td></td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
<?php
    }
    echo "  <HR><br>
            <p><a HREF=".MainFile." TARGET=\"_top\">元のページ</a>へ戻る</p>";
?>
