<?php
    require_once 'common.php';
    require_once 'drbbs.php';
    require_once '../lib/common.php';
    GetSystemValue();
    WriteLog(true);

    $userType  = GetCookie(CookieConf, "UpdateV");
    $userType2 = GetCookie(CookieConf, "MessageRead");

    // システム情報
    $conn        = ConnectSorizo();
    $result      = mysqli_query($conn, "SELECT * FROM ".TSYSTEM);
    $row         = mysqli_fetch_assoc($result);
    $UpdateV     = ($userType != "") ? round($userType) : $row["UpdateV"];
    $MessageRead = ($userType2 != "") ? round($userType2) : $row["MessageRead"];
    mysqli_close($conn);
?>

    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta http-equiv="Content-Type" content="text/html;charset=x-sjis">
            <title><?= $GLOBALS['MainTitle'] ?></title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
        </head>
        <form name="frmSearch" ACTION="list.php" METHOD="POST" target="LIST" onSubmit="return">
            <input TYPE="hidden" NAME="Menu" value="Find2">
            <input TYPE="hidden" NAME="GroupID" value="<?= ALLGROUP ?>">
            <?= $GLOBALS['BodyVal'] ?>
                <table>
                    <tr>
                        <td rowspan="2"><img src="<?= $GLOBALS['TitleImg'] ?>" height="65" width="250">&nbsp</td>
                        <td valign="top" style="padding-top:19px;"><input TYPE="text" NAME="FindFAQ" size="50" Value=""><div style="margin-top:3px;"><font size="-2">スペース区切りで複数キーワード指定可能</font></div></td>
                        <td valign="top" style="padding-top:20px;"><input TYPE="image" src="images/mksearch.png" height="20" ALIGN="texttop" onMouseover="this.src='images/mksearch2.png'" onMouseout="this.src='images/mksearch.png'" alt="検索"></td>
                        <td width="20"></td>
                        <td valign="top" style="padding-top:20px;" rowspan="2"><a href="drmq.php" target="_top" title="森先生に質問する"><img src="images/gobbs.png" onMouseover="this.src='images/gobbs2.png'" onMouseout="this.src='images/gobbs.png'" alt="森先生に質問する" border="0"></a></td>
                        <td width="20"></td>
                        <td rowspan="2">
                        <div style="margin-bottom:5px;"><a href="javascript:void(0)" onClick="javascript:window.open('caution.htm', '', 'width=600,height=350')" title="利用上の注意"><img src="images/mkcurtion.png" border="0"></a></div>
                        <div style="margin-bottom:5px;"><a href="javascript:void(0)" onClick="javascript:window.open('aboutdrm.htm', '', 'width=540,height=620')" title="森先生とは"><img src="images/mkdrm.png" border="0"></a></div>
                        </td>
                    </tr>
                    <tr><td colspan="2" VALIGN="top"></td></tr>
                </table>
            </body>
        </form>
    </html>
