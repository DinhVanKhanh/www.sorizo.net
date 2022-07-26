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
        $Title    = htmlspecialchars( @$_REQUEST["Title"] );
        $Message  = @$_REQUEST["Message"];

        // 必ずチェック
        if ( $Title == "" || $UName == "" || $Message == "" ) {
            mysqli_close($Conn);
            header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=このグループは読み取り専用です。<br/>書き込み用のパスワードが正しくありません。");
            exit;
        }

        $sql = "SELECT * FROM ".TGROUP." WHERE GroupID=".$GroupID;
        $result = mysqli_query($Conn, $sql);
        if ( mysqli_num_rows($result) > 0 ) {
            $row = mysqli_fetch_assoc($result);
            $ReadOnly     = !is_null($row['WritePassWord']) ? $row['WritePassWord'] : "";
            $ReadPassWord = !is_null($row['ReadPassWord'])  ? $row['ReadPassWord']  : "";
            $RPW          = !is_null($row['ReadPassWord'])  ? htmlspecialchars(@$_REQUEST['ReadPassWord']) : "";
            $ResMail      = $row['ResMail'];
            $RetMail      = "0";

            if ( $ResMail == 3 || $ResMail == 4 ) {
                $RetMail = htmlspecialchars(@$_REQUEST["RetMail"]);
                $EMail   = ($EMail == "") ? " " : $EMail;
            }

            if ( $ReadOnly != "" && $ReadOnly != $PassWord ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=このグループは読み取り専用です。<br/>書き込み用のパスワードが正しくありません。");
                exit;
            }
            elseif ( $ReadPassWord != "" && $ReadPassWord != $RPW ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=このグループを利用するにはパスワードが必要です。<br/>パスワードが正しくありません。");
                exit;
            }
            elseif ( $ResMail >= 3 && $RetMail != "0" && (strpos($EMail, "@") === false || strpos($EMail, ".") === false) ) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=返信時メールを選択した場合にはメールアドレスを入力してください。");
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

                // Mori_DrbbsMessage更新
                ValidateMess($Message);

                $sql  = "INSERT INTO ".TMESSAGE." (UName,";
                $sql .= ($EMail != "") ? "EMail," : "";
                $sql .= "RetMail, Title, Message,";
                $sql .= ($URL != "") ? "URL," : "";
                $sql .= ($PassWord != "") ? "PassWord," : "";
                $sql .= "thread, GroupID, RemoteAddr, UDate, AccessCount) VALUES ('".CheckSQ($UName)."',";
                $sql .= ($EMail != "") ? "'".CheckSQ($EMail)."'," : "";
                $sql .= ($ResMail == 3 || $ResMail == 4) ? CheckSQ($RetMail)."," : "0,";
                $sql .= "'".CheckSQ($Title)."','".CheckSQ($Message)."',";
                $sql .= ($URL != "") ? "'".CheckSQ($URL)."'," : "";
                $sql .= ($PassWord != "") ? "'".CheckSQ($PassWord)."'," : "";
                $sql .= $thread.",".$GroupID.",'".$_SERVER["REMOTE_ADDR"]."', '".date("Y/m/d h:i").":00',0)";
                mysqli_query($Conn, CheckSQL($sql));

                $sql         = "SELECT MessageID FROM ".TMESSAGE." ORDER BY MessageID DESC";
                $temp_result = mysqli_query($Conn, $sql);
                $row         = mysqli_fetch_assoc($temp_result);
                $MesID       = $row["MessageID"];
                mysqli_free_result($temp_result);

                $sql = "UPDATE ".TMESSAGE." SET OrderID=".$MesID." WHERE MessageID=".FindRoot($MesID);
                mysqli_query($Conn, $sql);
                SetAccessLog("Add", $MesID);

                // メール通知
                if ( $ResMail == 2 || $ResMail == 3 || $ResMail == 4 ) {
                    $MailSubject = "Message From Dr.森";
                    $MM  = "Title: ".$Title."<br/><br/>";
                    $MM .= "Message:<br/>".$Message."<br/><br/>";
                    $SN  = $_SERVER["SCRIPT_NAME"];
                    $SN  = substr($SN, 0, strpos($SN, "/") + 1);
                    $MM  = "http://".$_SERVER["SERVER_NAME"].$SN.MainFileQ."?Menu=&GroupID=".$GroupID."&MesID=".$MesID;

                    $sql         = "SELECT * FROM ".TSYSTEM;
                    $temp_result = mysqli_query($Conn, $sql);
                    $row         = mysqli_fetch_assoc($temp_result);
                    $MailServer  = $row["MailServer"];
                    $MailFrom    = $row["MailFrom"];
                    mysqli_free_result($temp_result);

                    if ( $ResMail == 2 || $ResMail == 3 ) {
                        $sql         = "SELECT * FROM ".TMAILOWNER." WHERE GroupID=".$GroupID;
                        $temp_result = mysqli_query($Conn, $sql);
                        $MailMessage = "掲示板【".$GLOBALS['MainTitle']."】にメッセージが投稿されました。<br/><br/>".$MM;
                        if ( mysqli_num_rows($temp_result) > 0 ) {
                            while ( $row = mysqli_fetch_assoc($temp_result) ) {
// 2020/02/12 t.maruyama 修正 ↓↓ AWS環境でメール送信できない不具合の対応
//                                mail($row["MailAddr"], $MailSubject, $MailMessage, "From: ".$MailFrom);
                                send_mail_PHPMailer($row["MailAddr"], $MailSubject, $MailMessage, "From: ".$MailFrom);
// 2020/02/12 t.maruyama 修正 ↑↑ AWS環境でメール送信できない不具合の対応
                            }
                        }
                        mysqli_free_result($temp_result);
                    }

                    if ( ($ResMail == 3 || $ResMail == 4) && $thread != 0 ) {
                        $MailMessage = "掲示板【".$GLOBALS['MainTitle']."】に投稿したメッセージに、コメントが投稿されました。<br/><br/>".$MM;
                        MessMailSend('addo', $thread, 1);
                    }
                }

                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Info&ViewWarn=メッセージを追加しました");
                exit;
            }
        }
        else {
            header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=メッセージを追加するグループが見つかりません");
            exit;
        }
        mysqli_free_result($result);
    }
    else {
        header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=&ViewMenu=&ViewWarn=");
        exit;
    }
    mysqli_close($Conn);
?>
