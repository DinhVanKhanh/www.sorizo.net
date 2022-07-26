<?php
    require_once '../lib/login.php';
    define("version", "1.00");                              // バージョン番号
    define("MainFile", "drm.php");                          // メインのファイル名
    define("GroupFile", "groups.php");                      // カテゴリ一覧のファイル名
    define("ListFile", "list.php");                         // 質問の一覧のファイル名
    define("ViewFile", "view.php");                         // Q&A表示のファイル名
    define("AddFile", "add.php");                           // 追加用のファイル名
    define("AddBinFile", "addbin.php");                     // アップロード追加用のファイル名
    define("TopFile", "top.php");                           // メイン画面の上部に表示されるファイル名
    
    // 掲示板の方のページアドレス
    define("MainFileQ", "drmq.php");                        // メインのファイル名
    define("GroupFileQ", "groupsq.php");                    // グループ一覧ファイル名
    define("ListFileQ", "listq.php");                       // リストのファイル名
    define("ViewFileQ", "viewq.php");                       // ビューのファイル名

    define("SorizoAdr", "https://www.sorizo.net/");   //  そり蔵ネットのトップアドレス
    define("AdminFile", "admin.php");                       // 管理用のファイル名
    define("UserHelpFile", "usrhelp.htm");                  // ユーザーヘルプファイル

    // 定数の設定
    define("ROOT", 0);
    define("ALLMESSAGE", 1);
    define("NEWMESSAGE", 0);
    define("COMMENTMESSAGE", 1);
    define("ALLGROUP", 1);
    
    // 森先生（クッキー）
    define("f_CookieON", "CookieON");

    mb_language( "ja" );
    date_default_timezone_set('Asia/Tokyo');

    // 共用変数
    $MainTitle = $TitleImg = $BodyVal = $InitMessage = $AdminMailto = $ReturnURL = $ReturnURLTitle = '';
    $ScriptTimeout = $SESSIONTIMEOUT = $CookiesExpires = $AccessLog = $SystemResMail = '';

    // 共用関数の定義
    function CheckSQ($s) {
        $i = strpos($s, "'");
        while ($i !== false) {
            $s = substr($s, 0, $i)."'".substr($s, $i);
            $i = strpos($i + 2, $s, "'");
        }
        return $s;
    }

    function CheckSQL($s) {
        return str_replace("|", "", $s);
    }

    function SmallChar($s) {
        $LS = "ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ１２３４５６７８９０ー－ｰ、，､。．｡／";
        $SS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890---,,,,,,/";
        $s1 = "";
        $length = strlen($s);
        for ($i = 0; $i < $length; $i++) {
// 2020/01/07 t.maruyama 修正 ↓↓ PHPにMid関数はないので。
//            $s1 .= (strpos($LS, substr($s, $i - 1, 1)) !== false) ? substr($SS, strpos($LS, substr($s, $i - 1, 1)), 1) : Mid($s, $i - 1, 1);
            $s1 .= (strpos($LS, substr($s, $i - 1, 1)) !== false) ? substr($SS, strpos($LS, substr($s, $i - 1, 1)), 1) : substr($s, $i - 1, 1);
// 2020/01/07 t.maruyama 修正 ↑↑
        }
        return $s1;
    }

    function GetSystemValue() {
        $Conn   = ConnectSorizo();
        $sql    = "SELECT * FROM ".TSYSTEM;
        $result = mysqli_query($Conn, $sql);
        $res    = mysqli_fetch_assoc($result);
        
        $GLOBALS['MainTitle']      = $res["MainTitle"];
        $GLOBALS['TitleImg']       = $res["TitleImg"];
        $GLOBALS['BodyVal']        = "<body BGCOLOR='#".$res["BodyColor"]."'>";
        $GLOBALS['InitMessage']    = $res["InitMessage"];
        $GLOBALS['AdminMailto']    = $res["AdminMailto"];
        $GLOBALS['ReturnURL']      = $res["ReturnURL"];
        $GLOBALS['ReturnURLTitle'] = $res["ReturnURLTitle"];
        $GLOBALS['ScriptTimeout']  = $res["ScriptTimeout"];
        $GLOBALS['SESSIONTIMEOUT'] = $res["SessionTimeout"];
        $GLOBALS['CookiesExpires'] = $res["CookiesExpires"];
        $GLOBALS['AccessLog']      = $res["AccessLog"];
        $GLOBALS['SystemResMail']  = $res["SystemResMail"];
        mysqli_free_result($result);
        mysqli_close($Conn);
    }

    function FindRoot($MessageID) {
        $Conn   = ConnectSorizo();
        $sql    = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ".$MessageID;
        $result = mysqli_query($Conn, CheckSQL($sql));
        $res    = mysqli_fetch_assoc($result);
        $i      = ($res["thread"] == 0) ? $MessageID : FindRoot($res["thread"]);
        mysqli_free_result($result);
        mysqli_close($Conn);
        return $i;
    }

    function SetAccessLog($AMenu, $AVal) {
        if ($GLOBALS['AccessLog'] != true) {
            return;
        }

// 2020/01/07 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql  = "INSERT INTO ".TLOG." (RemoteAddr, ADate, AccessMenu, AccessValue) VALUES (";
//        $sql .= "'".@$_SERVER["REMOTE_ADDR"]."',";
//        $sql .= "'".date("Y/m/d")." ".date("H:i:s")."',";
//        $sql .= "'".CheckSQ($AMenu)."',";
//        $sql .= ($AVal != "") ? "'".CheckSQ($AVal)."')" : "Null)";
//        $Conn = ConnectSorizo();
//        mysqli_query($Conn, $sql);

        $Conn = ConnectSorizo();
        $RemoteAddr = @$_SERVER["REMOTE_ADDR"];
        $ADate = date("Y/m/d")." ".date("H:i:s");

        $sql  = "INSERT INTO ".TLOG." (RemoteAddr, ADate, AccessMenu, AccessValue) "
            ."VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss',
            $RemoteAddr, $ADate, $AMenu, $AVal);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
// 2020/01/07 t.maruyama 修正 ↑↑
        mysqli_close($Conn);
    }

    function GetIcon($gid) {
        switch ($gid) {
            case 1:
                $sImgPath = "ico_gr1.gif";
                break;
            case 2:
                $sImgPath = "ico_gr2.gif";
                break;
            case 3:
                $sImgPath = "ico_gr3.gif";
                break;
            case 4:
                $sImgPath = "ico_gr4.gif";
                break;
            default:
                $sImgPath = "ico_gr5.gif";
                break;
        }
        return "<img src='".$sImgPath."' width='16' height='16'>";
    }

    function ViewColorSet($lngCnt) {
        return (($lngCnt % 2) == 1) ? "#FFFFCC" : "#FFFF99";
    }

    function ListColorSet($lngCnt) {
        return (($lngCnt % 2) == 1) ? "white" : "whitesmoke";
    }

    function ListColorSet2($lngCnt) {
        return (($lngCnt % 2) == 1) ? "#EEEEFF" : "#DDEEFF";
    }

    function CnvMsg2View($MesStr) {
// 2020/01/07 t.maruyama 修正 ↓↓ 置換対象の文字の指定が間違っている。
//        $Mess = str_replace('', "&quot;", $MesStr);
        $Mess = str_replace('"', "&quot;", $MesStr);
// 2020/01/07 t.maruyama 修正 ↑↑
        $Mess = str_replace("&","&amp;", $Mess);
        $Mess = str_replace(">","&gt;", $Mess);
        $Mess = str_replace("<","&lt;", $Mess);
        $Mess = str_replace("\n", "<br>", $Mess);

        $key1 = urldecode(@$_REQUEST["Find2"]);
        if ($key1 != "") {
            $FindKey1 = explode(chr(32), $key1);
            $key2 = ConvertCharacter2to1byte($key1);
            $FindKey2 = explode(chr(32), $key2);
            $i = 0;
            foreach ($FindKey1 as $aKey) {
                $Mess = str_replace($aKey, "<font COLOR=RED>".$aKey."</font>", $Mess);
                $Mess = str_replace($FindKey2[$i], "<font COLOR=RED>".$FindKey2[$i]."</font>", $Mess);
                $i++;
            }
        }
        return $Mess;
    }

    function ValidateMess(&$Message) {
        $Message = str_replace("&", "&amp;", $Message);
// 2020/01/07 t.maruyama 修正 ↓↓ 置換対象の文字の指定が間違っている。
//        $Message = str_replace('""', '&quot;', $Message);
        $Message = str_replace('"', "&quot;", $Message);
// 2020/01/07 t.maruyama 修正 ↑↑
        $Message = str_replace(">", "&gt;", $Message);
        $Message = str_replace("<", "&lt;", $Message);
        $Message = str_replace("<br/>", "&lt;BR&gt;", $Message);
    }

    function MessMailSend($Page, $thread, $lvl) {
        $Conn   = ConnectSorizo();

// 2020/01/07 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql    = "SELECT * FROM ".TMESSAGE." WHERE MessageID=".$thread;
//        $result = mysqli_query($Conn, CheckSQL($sql));

        $sql    = "SELECT * FROM ".TMESSAGE." WHERE MessageID = ?";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $thread);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
// 2020/01/07 t.maruyama 修正 ↑↑
        $row    = mysqli_fetch_assoc($result);

        $mailSend = $row["EMail"];

        if ( $Page == 'add' ) {
            if ( $lvl == 1 || $row["RetMail"] == 2 ) {
                if ( strpos($mailSend, "@") !== false && strpos($mailSend, ".") !== false ) {
// 2020/02/12 t.maruyama 修正 ↓↓ AWS環境でメール送信できない不具合の対応
//                    mb_send_mail($mailSend, $GLOBALS['MailSubject'], $GLOBALS['MailMessage2'], $GLOBALS['headers'], $GLOBALS["sendmail_params"]);
                    send_mail_PHPMailer($mailSend, $GLOBALS['MailSubject'], $GLOBALS['MailMessage2'], $GLOBALS['headers'], $GLOBALS["sendmail_params"]);
// 2020/02/12 t.maruyama 修正 ↑↑ AWS環境でメール送信できない不具合の対応
                }
            }
        }
        else {
            if ( ($row["RetMail"] == 1 && $lvl == 1) || $row["RetMail"] == 2 ) {
// 2020/02/12 t.maruyama 修正 ↓↓ AWS環境でメール送信できない不具合の対応
//                mb_send_mail($mailSend, $GLOBALS['MailSubject'], $GLOBALS['MailMessage2'], $GLOBALS['headers'], $GLOBALS["sendmail_params"]);
                send_mail_PHPMailer($mailSend, $GLOBALS['MailSubject'], $GLOBALS['MailMessage2'], $GLOBALS['headers'], $GLOBALS["sendmail_params"]);
// 2020/02/12 t.maruyama 修正 ↑↑ AWS環境でメール送信できない不具合の対応
            }
        }
// 2020/01/07 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
        mysqli_stmt_close($stmt);
// 2020/01/07 t.maruyama 追加 ↑↑
        mysqli_free_result($result);
        mysqli_close($Conn);
    }

    function EncryptURL($string) {
        return urlencode(mb_convert_encoding($string, "shift_jis"));
    }

    function ConvertCharacter2to1byte($str) {
        return preg_replace_callback(
                "/[\x{ff01}-\x{ff5e}]/u",
                function($c) {
                    // convert UTF-8 sequence to ordinal value
                    $code = ((ord($c[0][0])&0xf)<<12)|((ord($c[0][1])&0x3f)<<6)|(ord($c[0][2])&0x3f);
                    return chr($code-0xffe0);
                },
                $str);
    }
?>
