<?php
    include "common.php";
    include "drbbs.php";
    include "loginchkdrm.php";
    GetSystemValue();
    WriteLog(true);

    $Conn    = ConnectSorizo();
    $Menu    = htmlspecialchars( @$_REQUEST["Menu"] );
    $GroupID = (htmlspecialchars( @$_REQUEST["GroupID"] ) != "") ? intval(htmlspecialchars( @$_REQUEST["GroupID"] )) : ALLGROUP;
    $MesID   = (htmlspecialchars( @$_REQUEST["MesID"] ) != "") ? intval(htmlspecialchars( @$_REQUEST["MesID"] )) : 0;

    // Cookie更新
    $CookieArr = GetCookie(CookieConf);
    $CookieArr[f_CookieON] = "ON";

    $ViewMenu = $ViewWarn = $ListMenu = $ListWarn = '';
    switch ($Menu) {
        case "AddNew2":
            $ViewMenu = htmlspecialchars( @$_REQUEST["ViewMenu"] );
            $ViewWarn = @$_REQUEST["ViewWarn"];
            break;

        case "Delete":
            $PassWord = htmlspecialchars( @$_REQUEST["PassWord"] );
// 2020/01/07 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//            $sql      = "SELECT * FROM ".TMESSAGE." WHERE MessageID=".$MesID." AND PassWord='".CheckSQ($PassWord)."'";
//            $result   = mysqli_query($Conn, $sql);

            $sql = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ? AND PassWord = ?";
            $stmt = mysqli_prepare($Conn, $sql);
            mysqli_stmt_bind_param($stmt, 'is', $MesID,$PassWord);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
// 2020/01/07 t.maruyama 修正 ↑↑

            if ( mysqli_num_rows($result) > 0 ) {
// 2020/01/07 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql         = "SELECT * FROM ".TMESSAGE." WHERE thread=".$MesID;
//                $temp_result = mysqli_query($Conn, $sql);

                $sql = "SELECT * FROM ".TMESSAGE." WHERE thread=".$MesID;
                $temp_stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($temp_stmt, 'i', $MesID);
                mysqli_stmt_execute($temp_stmt);
                $temp_result = mysqli_stmt_get_result($temp_stmt);
// 2020/01/07 t.maruyama 修正 ↑↑

                if ( mysqli_num_rows($temp_result) < 1 ) {
// 2020/01/07 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                    $sql = "DELETE FROM ".TUPLOAD." WHERE MessageID=".$MesID;
//                    mysqli_query($Conn, CheckSQL($sql));
//                    $sql = "DELETE FROM ".TSUMMESSAGE." WHERE MessageID=".$MesID;
//                    mysqli_query($Conn, CheckSQL($sql));
//
//                    $sql = "UPDATE ".TMESSAGE." SET UName='-',EMail=NULL,Title='削除',Message='削除',URL=NULL,RetMail=0 WHERE MessageID=".$MesID;
//                    mysqli_query($Conn, CheckSQL($sql));

                    $sql = "DELETE FROM ".TUPLOAD." WHERE MessageID = ?";
                    $exec_stmt = mysqli_prepare($Conn, $sql);
                    mysqli_stmt_bind_param($exec_stmt, 'i', $MesID);
                    mysqli_stmt_execute($exec_stmt);
                    mysqli_stmt_close($exec_stmt);

                    $sql = "DELETE FROM ".TSUMMESSAGE." WHERE MessageID = ?";
                    $exec_stmt = mysqli_prepare($Conn, $sql);
                    mysqli_stmt_bind_param($exec_stmt, 'i', $MesID);
                    mysqli_stmt_execute($exec_stmt);
                    mysqli_stmt_close($exec_stmt);

                    $sql = "UPDATE ".TMESSAGE." SET UName = '-', EMail = NULL, Title = '削除', Message = '削除', URL = NULL, RetMail = 0 WHERE MessageID = ?";
                    $exec_stmt = mysqli_prepare($Conn, $sql);
                    mysqli_stmt_bind_param($exec_stmt, 'i', $MesID);
                    mysqli_stmt_execute($exec_stmt);
                    mysqli_stmt_close($exec_stmt);
// 2020/01/07 t.maruyama 修正 ↑↑

                    $Menu     = "";
                    $ViewMenu = "Info";
                    $ViewWarn = "メッセージを削除しました";
                }
                else {
                    $ViewMenu = "Warn";
                    $ViewWarn = "このメッセージにはすでにコメントが登録されているため削除できません。削除したい場合には管理者へ連絡してください。";
                }
// 2020/01/07 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                mysqli_stmt_close($temp_stmt);
// 2020/01/07 t.maruyama 追加 ↑↑
                mysqli_free_result($temp_result);

                SetAccessLog("Delete", $MesID);
                $MesID = "";
            }
            else {
                $ViewMenu = "Warn";
                $ViewWarn = "パスワードが違うので削除できませんでした";
            }
// 2020/01/07 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2020/01/07 t.maruyama 追加 ↑↑
            mysqli_free_result($result);
            break;

        case "conf2":
            if ( htmlspecialchars( @$_REQUEST["ConfMenu"] ) == "規定値に戻す" ) {
                $sql = "SELECT * FROM ".TSYSTEM;
                $result = mysqli_query($Conn, $sql);
                $row = mysqli_fetch_assoc($result);

                $CookieArr["DefMenu"]     = $row["DefMenu"];
                $CookieArr["psIchiran"]   = $row["psIchiran"];
                $CookieArr["psNyuryoku"]  = $row["psNyuryoku"];
                $CookieArr["UpdateV"]     = $row["UpdateV"];
                $CookieArr["ThreadView"]  = $row["ThreadView"];
                $CookieArr["ThreadOrder"] = $row["ThreadOrder"];
                $CookieArr["MessageRead"] = $row["MessageRead"];
                UpdateCookie(CookieConf, $CookieArr, $GLOBALS['CookiesExpires']);
                mysqli_free_result($result);
            }
            else {
                $psIchiran = $psNyuryoku = $UpdateV = $ThreadView = $ThreadOrder = $MessageRead = "";

                $DefMenu     = htmlspecialchars( @$_REQUEST["DefMenu"] );
                $psIchiran   = intval(SmallChar(htmlspecialchars( @$_REQUEST["psIchiran"] )));
                $psNyuryoku  = intval(SmallChar(htmlspecialchars( @$_REQUEST["psNyuryoku"] )));
                $UpdateV     = intval(SmallChar(htmlspecialchars( @$_REQUEST["UpdateV"] )));
                $ThreadView  = intval(SmallChar(htmlspecialchars( @$_REQUEST["ThreadView"] )));
                $ThreadOrder = intval(SmallChar(htmlspecialchars( @$_REQUEST["ThreadOrder"] )));
                $MessageRead = intval(SmallChar(htmlspecialchars( @$_REQUEST["MessageRead"] )));

                if ( $psIchiran != "" && $psNyuryoku != "" && $UpdateV != "" ) {
                    $CookieArr["DefMenu"]     = $DefMenu;
                    $CookieArr["psIchiran"]   = $psIchiran;
                    $CookieArr["psNyuryoku"]  = $psNyuryoku;
                    $CookieArr["UpdateV"]     = $UpdateV;
                    $CookieArr["ThreadView"]  = $ThreadView;
                    $CookieArr["ThreadOrder"] = $ThreadOrder;
                    $CookieArr["MessageRead"] = $MessageRead;
                    UpdateCookie(CookieConf, $CookieArr, $GLOBALS['CookiesExpires']);
                    $Menu = "";
                }
                else {
                    $ListMenu = "Warn";
                    $ListWarn = "入力が正しくありません";
                }
            }
            break;

        default:
            SetAccessLog("Default", "");
            break;
    }
?>
    <!DOCTYPE html>
    <HTML lang="ja">
        <head>
            <meta HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=x-sjis">
            <title><?= $GLOBALS['MainTitle'] ?></title>
        </head>

        <FRAMESET COLS="25%,*">
            <FRAME SRC="<?= GroupFileQ ?>?GroupID=<?= $GroupID ?>" NAME="GROUP" MARGINWIDTH="5" MARGINHEIGHT="5" SCROLLING="AUTO">
            <FRAMESET ROWS="40%,*">
                <FRAME SRC="<?= ListFileQ ?>?Menu=<?= $ListMenu ?>&GroupID=<?= $GroupID ?>&Warn=<?= $ListWarn ?>" NAME="LIST" MARGINWIDTH="5" MARGINHEIGHT="5" SCROLLING="AUTO">
                <FRAME SRC="<?= ViewFileQ ?>?Menu=<?= $ViewMenu ?>&GroupID=<?= $GroupID ?>&MesID=<?= $MesID ?>&Warn=<?= $ViewWarn ?>" NAME="VIEW" MARGINWIDTH="5" MARGINHEIGHT="5" SCROLLING="AUTO">
            </FRAMESET>
        </FRAMESET>
        <NOFRAME>この掲示板はフレーム専用です</NOFRAME>
    </html>
