<?php
    include "common.php";
    include "drbbs.php";
    include "../lib/common.php";
    GetSystemValue();
    WriteLog(true);

    $Conn         = ConnectSorizo();
    $Menu         = htmlspecialchars( @$_REQUEST["Menu"] );
    $RedirectAddr = htmlspecialchars( @$_REQUEST["Redirect"] );
    $CookieArr    = GetCookie(CookieConf);

    if ($Menu == "AddNew2") {
        $GroupID  = intval(htmlspecialchars(@$_REQUEST["GroupID"]));
        $PassWord = htmlspecialchars( @$_REQUEST["PassWord"] );
        $UName    = htmlspecialchars( @$_REQUEST["UName"] );
        $EMail    = htmlspecialchars( @$_REQUEST["EMail"] );
        $Title    = htmlentities( @$_REQUEST["Title"] );
        $Message  = htmlentities( @$_REQUEST["Message"] );

        // 必ずチェック
        if ( $Title == "" || $UName == "" || $Message == "" ) {
            mysqli_close($Conn);
            header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=タイトルと氏名、メッセージは必ず入力してください"); //.EncryptURL("タイトルと名前、メッセージは必ず入力してください", "shift_jis"));
            exit;
        }

// 2019/12/27 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql = "SELECT * FROM ".TGROUP." WHERE GroupID=".$GroupID;
//        $result = mysqli_query($Conn, $sql);

        $sql = "SELECT * FROM ".TGROUP." WHERE GroupID= ?";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $GroupID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
// 2019/12/27 t.maruyama 修正 ↑↑
        if ( mysqli_num_rows($result) > 0 ) {
            $row = mysqli_fetch_assoc($result);
            $ReadOnly     = !is_null($row['WritePassWord']) ? $row['WritePassWord'] : "";
            $ReadPassWord = !is_null($row['ReadPassWord'])  ? $row['ReadPassWord']  : "";
            $RPW          = !is_null($row['ReadPassWord'])  ? htmlspecialchars(@$_REQUEST['ReadPassWord']) : "";
            $ResMail      = $row['ResMail']; // admin.php 「グループ設定」の「メール通知」で設定する値
            $RetMail      = "0"; // viewq.php から来る「返信時メール」の値
// 2019/12/27 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2019/12/27 t.maruyama 追加 ↑↑

            if ( $ResMail == 3 || $ResMail == 4 ) {
                $RetMail = @$_REQUEST['RetMail'];
                $EMail   = ($EMail == "") ? " " : $EMail; 
            }

            if ( $ReadOnly != "" && $ReadOnly != $PassWord ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=このグループは読み取り専用です。<br/>書き込み用のパスワードが正しくありません。"); //.EncryptURL("このグループは読み取り専用です。<br/>書き込み用のパスワードが正しくありません。"));
                exit;
            } 
            elseif ( $ReadPassWord != "" && $ReadPassWord != $RPW ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=このグループを利用するにはパスワードが必要です。<br/>パスワードが正しくありません。"); //.EncryptURL("このグループを利用するにはパスワードが必要です。<br/>パスワードが正しくありません。"));
                exit;
            } 
            elseif ( $ResMail >= 3 && $RetMail != "0" && (strpos($EMail, "@") === false || strpos($EMail, ".") === false) ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=返信時メールを選択した場合にはメールアドレスを入力してください。"); //.EncryptURL("返信時メールを選択した場合にはメールアドレスを入力してください。"));
                exit;
            }
            else {
                $EMail  = ($EMail == " ") ? "" : $EMail;
                $URL    = htmlspecialchars( @$_REQUEST["URL"] );
                $thread = intval(htmlspecialchars( @$_REQUEST["thread"] ));

                // Cookie更新
                $CookieArr["UName"] = $UName;
                if ($EMail != "") { 
                    $CookieArr["EMail"] = $EMail;
                }
                if ($URL != "") {
                    $CookieArr["URL"] = $URL;
                }
                if ($PassWord != "") {
                    $CookieArr["PassWord"] = $PassWord; 
                }
                UpdateCookie(CookieConf, $CookieArr, $GLOBALS['CookiesExpires']);

                // Mori_DrbbsMessage更新
// 2019/12/27 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql  = "INSERT INTO ".TMESSAGE." (UName,";
//                $sql .= ($EMail != "") ? "EMail," : "";
//                $sql .= "RetMail, Title, Message,";
//                $sql .= ($URL != "")  ? "URL," : "";
//                $sql .= ($PassWord != "") ? "PassWord," : "";
//                $sql .= "thread, GroupID, RemoteAddr, UDate, AccessCount) VALUES ('".CheckSQ($UName)."',";
//                $sql .= ($EMail != "") ? "'".CheckSQ($EMail)."'," : "";
//                $sql .= ($ResMail == 3 || $ResMail == 4) ? CheckSQ($RetMail)."," : "0,";
//                $sql .= "'".CheckSQ($Title)."', '".CheckSQ($Message)."',";
//                $sql .= ($URL != "") ? "'".CheckSQ($URL)."'," : "";
//                $sql .= ($PassWord != "") ? "'".CheckSQ($PassWord)."'," : "";
//                $sql .= $thread.",".$GroupID.",'".$_SERVER["REMOTE_ADDR"]."', '".date("Y/m/d H:i").":00',0)";
//                mysqli_query($Conn, CheckSQL($sql));

                $ResMailValue = ($ResMail == 3 || $ResMail == 4) ? $RetMail : 0;
                $RemoteAddr = $_SERVER["REMOTE_ADDR"];
                $UDate = date("Y/m/d H:i").":00";
                $ac = 0;
                $sql  = "INSERT INTO ".TMESSAGE.
                                " (UName, EMail, RetMail, Title, ".
                                "  Message, URL, PassWord, thread, ".
                                "  GroupID, RemoteAddr, UDate, AccessCount)".
                                " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ssissssiissi',
                                $UName, $EMail, $ResMailValue, $Title,
                                $Message, $URL, $PassWord, $thread,
                                $GroupID, $RemoteAddr, $UDate, $ac);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
// 2019/12/27 t.maruyama 修正 ↑↑

                $sql         = "SELECT MessageID FROM ".TMESSAGE." ORDER BY MessageID DESC";
                $temp_result = mysqli_query($Conn, $sql);
                $row         = mysqli_fetch_assoc($temp_result);
                $MesID       = $row["MessageID"];
                mysqli_free_result($temp_result);

// 2019/12/27 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql = "UPDATE ".TMESSAGE." SET OrderID=".$MesID." WHERE MessageID=".FindRoot($MesID);
//                mysqli_query($Conn, $sql);

                $rootID = FindRoot($MesID);
                $sql = "UPDATE ".TMESSAGE." SET OrderID = ? WHERE MessageID = ? ";
                $stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $MesID, $rootID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
// 2019/12/27 t.maruyama 修正 ↑↑
                SetAccessLog("Add", $MesID);

                // メール通知
                if ( $ResMail == 2 || $ResMail == 3 || $ResMail == 4 ) {
                    $sql         = "SELECT * FROM ".TSYSTEM;
                    $temp_result = mysqli_query($Conn, $sql);
                    $row         = mysqli_fetch_assoc($temp_result);
                    $MailServer  = $row["MailServer"];
                    $MailFrom    = $row["MailFrom"];
                    $MailSubject = "「教えて！森先生」からのメッセージです";

                    $headers .= "From: $MailFrom\r\n";

                    $MailMessage = file_get_contents('mail.txt');
                    $MailMessage = str_replace('$Title', $Title, $MailMessage);
                    $MailMessage = str_replace('$Message', $Message, $MailMessage);
                    $MailMessage = str_replace('$MainTitle', $GLOBALS['MainTitle'], $MailMessage);
                    $MailMessage = str_replace('SorizoAdr', SorizoAdr, $MailMessage);
                    $sendmail_params  = "-f{$MailFrom}";
                    mysqli_free_result($temp_result);

                    if ( $ResMail == 2 || $ResMail == 3 ) {
// 2019/12/27 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $sql         = "SELECT * FROM ".TMAILOWNER." WHERE GroupID=".$GroupID;
//                        $temp_result = mysqli_query($Conn, $sql);

                        $sql         = "SELECT * FROM ".TMAILOWNER." WHERE GroupID = ?";
                        $stmt = mysqli_prepare($Conn, $sql);
                        mysqli_stmt_bind_param($stmt, 'i', $GroupID);
                        mysqli_stmt_execute($stmt);
                        $temp_result = mysqli_stmt_get_result($stmt);
// 2019/12/27 t.maruyama 修正 ↑↑
                        $MailMessage1 = html_entity_decode( str_replace('$header', "【".$GLOBALS['MainTitle']."】にメッセージが投稿されました。", $MailMessage) );

                        if ( mysqli_num_rows($temp_result) > 0 ) {
                            while ( $row = mysqli_fetch_assoc($temp_result) ) {
// 2020/02/12 t.maruyama 修正 ↓↓ AWS環境でメール送信できない不具合の対応
//                                mb_send_mail($row["MailAddr"], $MailSubject,  $MailMessage1, $headers, $sendmail_params);
                                send_mail_PHPMailer($row["MailAddr"], $MailSubject,  $MailMessage1, $headers, $sendmail_params);
// 2020/02/12 t.maruyama 修正 ↑↑ AWS環境でメール送信できない不具合の対応
                            }
                        }
// 2019/12/27 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2019/12/27 t.maruyama 追加 ↑↑
                        mysqli_free_result($temp_result);
                    }

                    if ( $thread != 0 ) {
                        $MailMessage2 = html_entity_decode( str_replace('$header', "【".$GLOBALS['MainTitle']."】に投稿したメッセージに、回答が投稿されました。", $MailMessage) );
                        MessMailSend('add', $thread, 1);
                    }
                }

                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Info&ViewWarn=メッセージを追加しました"); //.EncryptURL("メッセージを追加しました"));
            }
        }
        else {
            header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=メッセージを追加するグループが見つかりません"); //.EncryptURL("メッセージを追加するグループが見つかりません"));
        }
        mysqli_free_result($result);
    }
    else {
        header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=&ViewMenu=&ViewWarn=");
    }
    mysqli_close($Conn);
?>
