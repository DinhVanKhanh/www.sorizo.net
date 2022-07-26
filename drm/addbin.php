<?php
// ↓↓ <2020/11/19> <VinhDao> <修正>
    // include "common.php";
    // include "drbbs.php";
    // include "../lib/common.php";
    require_once __DIR__ . "/common.php";
    require_once __DIR__ . "/drbbs.php";
    require_once __DIR__ . "/../lib/common.php";
// ↑↑ <2020/11/19> <VinhDao> <修正>
    GetSystemValue();
    WriteLog(true);

    $Conn         = ConnectSorizo();
    $Menu         = htmlspecialchars( @$_POST["Menu"] );
    $RedirectAddr = htmlspecialchars( @$_POST["Redirect"] );
    $CookieArr    = GetCookie(CookieConf);

    if ($Menu == "AddNew2") {
        $GroupID  = intval(htmlspecialchars( @$_POST["GroupID"] ));
        $PassWord = htmlspecialchars( @$_POST["PassWord"] );
        $UName    = htmlspecialchars( @$_POST["UName"] );
        $EMail    = is_null(htmlspecialchars(@$_POST["EMail"])) ? " " : $_POST["EMail"];
        $Title    = htmlspecialchars( @$_POST["Title"] );
        $Message  = @$_POST["Message"];
        $RPW      = htmlspecialchars( @$_POST["ReadPassWord"] );

        // 必ずチェック
        if ($Title != '' || $UName != '' || $Message != '') {
            mysqli_close($Conn);
            header("Location: ".$RedirectAddr."Menu=".$Menu."GroupID=".$GroupID."&VieMenu=Warn&ViewWarn=タイトルと名前、メッセージは必ず入力してください");
            exit;
        }

// 2019/12/30 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//        $sql = "SELECT * FROM ".TGROUP." WHERE GroupID={$GroupID}";
//        $RS  = mysqli_query($Conn, $sql);

        $sql = "SELECT * FROM ".TGROUP." WHERE GroupID = ?";
        $stmt = mysqli_prepare($Conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i',$GroupID);
        mysqli_stmt_execute($stmt);
        $RS = mysqli_stmt_get_result($stmt);
// 2019/12/30 t.maruyama 修正 ↑↑
        if ( mysqli_num_rows($RS) > 0 ) {
            $row               = mysqli_fetch_assoc($RS);
            $MaxUploadFileSize = $row['MaxUploadFileSize'];
            $ReadOnly          = !is_null($row['WritePassWord']) ? $row['WritePassWord'] : "";
            $ReadPassWord      = !is_null($row['ReadPassWord']) ? $row['ReadPassWord'] : "";
            $ResMail           = $row['ResMail']; // admin.php 「グループ設定」の「メール通知」で設定する値
            $RetMail           = "0"; // viewq.php から来る「返信時メール」の値
// 2019/12/30 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
            mysqli_stmt_close($stmt);
// 2019/12/30 t.maruyama 追加 ↑↑

            if ($ResMail == 3 || $ResMail == 4) {
                $RetMail = htmlspecialchars( @$_POST["RetMail"]);
            }
            elseif ($ReadOnly != '' && $ReadOnly != $PassWord) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&VieMenu=Warn&ViewWarn=このグループは読み取り専用です。<br>書き込み用のパスワードが正しくありません。");
                exit;
            }
            elseif ($ReadPassWord != '' && $ReadPassWord != $RPW) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&VieMenu=Warn&ViewWarn=このグループを利用するにはパスワードが必要です。<br>パスワードが正しくありません。");
                exit;
            }
            elseif ($ResMail >= 3 && $RetMail != "0" && (strpos($EMail, "@") === false || strpos($EMail, ".") === false)) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&VieMenu=Warn&ViewWarn=返信時メールを選択した場合にはメールアドレスを入力してください。");
                exit;
            }
            elseif ($_FILES['file']['size'] > ($MaxUploadFileSize * 1000)) {
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&VieMenu=Warn&ViewWarn=添付ファイルのサイズは".$MaxUploadFileSize."KB以下にしてください");
                exit;
            }
            else {
                $URL    = htmlspecialchars( @$_POST["URL"] );
                $thread = htmlspecialchars( @$_POST["thread"] );

                // Cookie更新
                $CookieArr["UName"] = $UName;
                if (!is_null($EMail)) {
                    $CookieArr["EMail"] = $EMail;
                }
                if (!is_null($URL)) {
                    $CookieArr["URL"] = $URL;
                }
                if (!is_null($PassWord)) {
                    $CookieArr["PassWord"] = $PassWord;
                }
                UpdateCookie(CookieConf, $CookieArr, $GLOBALS['CookiesExpires']);

                // Mori_DrbbsMessage更新
                ValidateMess($Message);

// 2019/12/30 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql  = "INSERT INTO ".TMESSAGE." (Uname,EMail,RetMail,Title,Message,";
//                $sql .= !is_null($URL) ? "URL, " : "";
//                $sql .= !is_null($PassWord) ? "PassWord, " : "";
//                $sql .= "thread,GroupID,RemoteAddr,UDate,AccessCount) VALUES ('".CheckSQ($Uname)."',";
//                $sql .= !is_null($EMail) ? "'".CheckSQ($EMail)."', " : "";
//                $sql .= ($ResMail == 3 || $ResMail == 4) ? CheckSQ($RetMail).", " : "0, ";
//                $sql .= "'".CheckSQ($Title)."', '".CheckSQ($Message)."', ";
//                $sql .= !is_null($URL) ? "'".CheckSQ($URL)."', "      : "";
//                $sql .= !is_null($PassWord) ? "'".CheckSQ($PassWord)."', " : "";
//                $sql .= "{$thread}, {$GroupID}, '".$_SERVER["REMOTE_ADDR"]."', '".date("Y/m/d h:i").":00', 0)";
//                mysqli_query($Conn, CheckSQL($sql));

                $ResMailValue = ($ResMail == 3 || $ResMail == 4) ? $RetMail : 0;
                $RemoteAddr = $_SERVER["REMOTE_ADDR"];
                $UDate = date("Y/m/d H:i").":00";
                $ac = 0;
                $sql  = "INSERT INTO ".TMESSAGE.
                                " (Uname, EMail ,RetMail, Title, ".
                                " Message, URL, PassWord, thread, ".
                                " GroupID, RemoteAddr, UDate, AccessCount)".
                                " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ssissssiissi',
                                $UName, $EMail, $ResMailValue, $Title,
                                $Message, $URL, $PassWord, $thread,
                                $GroupID, $RemoteAddr, $UDate, $ac);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
// 2019/12/30 t.maruyama 修正 ↑↑

                $sql   = "SELECT MessageID FROM ".TMESSAGE." ORDER BY MessageID DESC";
                $RS2   = mysqli_query($Conn, $sql);
                $row   = mysqli_fetch_assoc($RS2);
                $MesID = $row["MessageID"];
                mysqli_free_result($RS2);

// 2019/12/30 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                $sql = "UPDATE ".TMESSAGE." SET OrderID={$MesID} WHERE MessageID=".FindRoot($MesID);
//                mysqli_query($Conn, $sql);

                $rootID = FindRoot($MesID);
                $sql = "UPDATE ".TMESSAGE." SET OrderID = ? WHERE MessageID = ? ";
                $stmt = mysqli_prepare($Conn, $sql);
                mysqli_stmt_bind_param($stmt, 'ii', $MesID, $rootID);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                SetAccessLog("Add", $MesID);
// 2019/12/30 t.maruyama 修正 ↑↑

                // Mori_DrbbsUpload更新
                if ($_FILES['file']['size'] > 0) {
                    $FName = $_FILES['file']['name'];
                    $FName = substr($FName, strrpos($FName, "\\"));
                    $FExt  = substr($FName, strrpos($FName, ".") - 1);
                    $FName = substr($FName, 0, strlen($FName) - strlen($FExt));
                    $SPath = realpath($_SERVER['PHP_SELF']);
                    $SPath = substr($SPath, 0, strrpos($SPath, "\\") + 1);
                    $SPath = opendir($SPath."files\\");

                    $FNo = "";
                    $VNo = 0;
                    if (file_exists($SPath.$FName.$FNo.$FExt)) {
                        while ( $file = readdir($SPath) ) {
                            $VNo++;
                            if ( $VNo == 10 ) {
                                mysqli_free_result($RS);
                                mysqli_close($Conn);
                                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=ファイル名の重複により添付ファイルをアップロードできませんでした");
                                exit;
                            }
                            $FNo = "[".$VNo."]";
                        }
                    }

// 2019/12/30 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                    $sql = "INSERT INTO ".TUPLOAD." (MessageID, ImageFileName) VALUES (".$MesID.", '".CheckSQ($FName.$FNo.$FExt)."')";
//                    mysqli_query($Conn, CheckSQL($sql));

                    $ImageFileName = $FName.$FNo.$FExt;
                    $sql = "INSERT INTO ".TUPLOAD." (MessageID, ImageFileName) VALUES (?, ?)";
                    $stmt = mysqli_prepare($Conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'is', $MesID, $ImageFileName);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
// 2019/12/30 t.maruyama 修正 ↑↑
                    move_uploaded_file($_FILES['file']['tmp_name'], $SPath.$FName.$FNo.$FExt);
                }

                // メール通知
                if ( $ResMail == 2 || $ResMail == 3 || $ResMail == 4 ) {
                    $MailSubject = "Message From DST BBS";
                    $MM  = "Title: ".$Title."<br/>"."<br/>";
                    $MM .= "Message:"."<br/>".$Message."<br/>"."<br/>";
// 2019/12/30 t.maruyama 修正 ↓↓ $SNが参照エラーになっていたので修正。$_SERVER['PHP_SELF']ではなく$_SERVER['SCRIPT_NAME']を使用する。
//                    $SN  = substr($SN, 0, strrpos($_SERVER['PHP_SELF'], "/") + 1);

                    $SN = $_SERVER['SCRIPT_NAME'];
                    $SN  = substr($SN, 0, strrpos($SN, "/") + 1);
// 2019/12/30 t.maruyama 修正 ↑↑
                    $MM .= "http://".$_SERVER["SERVER_NAME"].$SN.MainFile."Menu=GroupID=".$GroupID."MesID=".$MesID;

                    $sql        = "SELECT * FROM ".TSYSTEM;
                    $RS2        = mysqli_query($Conn, $sql);
                    $row        = mysqli_fetch_assoc($RS2);
                    $MailServer = $row["MailServer"];
                    $MailFrom   = $row["MailFrom"];
                    mysqli_free_result($RS2);

                    if ( $ResMail == 2 || $ResMail == 3 ) {
// 2019/12/30 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                        $sql = "SELECT * FROM ".$TMAILOWNER." WHERE GroupID=".$GroupID;
//                        $RS2 = mysqli_query($Conn, $sql);

                        $sql = "SELECT * FROM ".TMAILOWNER." WHERE GroupID = ?";
                        $stmt = mysqli_prepare($Conn, $sql);
                        mysqli_stmt_bind_param($stmt, 'i',$GroupID);
                        mysqli_stmt_execute($stmt);
                        $RS2 = mysqli_stmt_get_result($stmt);
// 2019/12/30 t.maruyama 修正 ↑↑

                        $MailMessage = "掲示板【".$MainTitle."】にメッセージが投稿されました。"."<br/>"."<br/>".$MM;
                        if ( mysqli_num_rows($RS2) > 0 ) {
                            while ( $row = mysqli_fetch_assoc($RS2) ) {
// 2020/02/12 t.maruyama 修正 ↓↓ AWS環境でメール送信できない不具合の対応
//                                mail($row["MailAddr"], $MailSubject, $MailMessage, "From: ".$MailFrom);
                                send_mail_PHPMailer($row["MailAddr"], $MailSubject, $MailMessage, "From: ".$MailFrom);
// 2020/02/12 t.maruyama 修正 ↑↑ AWS環境でメール送信できない不具合の対応
                            }
                        }
// 2019/12/30 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                        mysqli_stmt_close($stmt);
// 2019/12/30 t.maruyama 追加 ↑↑
                        mysqli_free_result($RS2);
                    }

                    if ( ($ResMail == 3 || $ResMail == 4) && $thread != 0 ) {
                        $MailMessage = "掲示板【".$MainTitle."】に投稿したメッセージに、コメントが投稿されました。"."<br/>"."<br/>".$MM;
                        MessMailSend('addbin', $thread, 1);
                    }
                }

                mysqli_free_result($RS);
                mysqli_close($Conn);
                header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=ファイル名の重複により添付ファイルをアップロードできませんでした");
                exit;
            }
        }
        else {
            mysqli_free_result($RS);
            mysqli_close($Conn);
            header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=".$GroupID."&ViewMenu=Warn&ViewWarn=メッセージを追加するグループが見つかりません");
            exit;
        }
    }
    else {
        mysqli_close($Conn);
        header("Location: ".$RedirectAddr."?Menu=".$Menu."&GroupID=&ViewMenu=&ViewWarn=");
        exit;
    }
?>
