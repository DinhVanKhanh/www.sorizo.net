<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';

    if (session_id() == '') {
        session_start();
    }

    global $LogFileDirectory;
    global $ListPRG;

    $dir = @$_GET["dir"];
    $TargetUser = @$_GET["target"];

    if (!is_array($ListPRG)) {
        $ListDL = array("kumi201903" => array("PCSP" => "KUM204.zip",
                                              "name" => "クミカンV2",
                                              "ver"  => "Ver2_04install"),
                        "ren201903"  => array("PCSP" => "REN111.zip",
                                              "name" => "れん太郎",
                                              "ver"  => "Ver1_11install"),
                  );
    }

    $myMonth = (int)date("m");
    $myYear = (int)date("Y");
    // 3か月ごとに更新するため、グルーピングします。
    // 3,4,5月
    if ($myMonth >= 3 && $myMonth < 6) {
        $myMonth = 3;
    }
    // 6,7,8月
    elseif ($myMonth >= 6 && $myMonth < 9) {
        $myMonth = 6;
    }
    // 9,10,11月
    elseif ($myMonth >= 9 && $myMonth < 12) {
        $myMonth = 9;
    }
    // 12月
    elseif ($myMonth == 12) {
        $myMonth = 12;
    }
    // 1,2月
    elseif ($myMonth < 3) {
        $myMonth = 12;
        $myYear--;
    }

    $isKumi = ((strpos($dir, "kumi") !== false) ? true : false);
    $FolderName = (($isKumi) ? "kumi" : "ren").$myYear.(($myMonth < 10) ? "0" : "").$myMonth;
    $Password   = ($myYear + $myMonth).(($isKumi) ? "kumikan" : "rentaro").(10 - $myMonth + 2);

    if (trim(@$_POST["dlpassword"]) == "") {
        echo "ダウンロードの際はパスワードが必要です。<br><a href='javascript:history.back();'>戻る</a>";
        exit;
    }
    elseif (@$_POST["dlpassword"] != $Password) {
        echo "パスワードが違います。入力内容をご確認ください。<br><a href='javascript:history.back();'>戻る</a>";
        exit;
    }

    // ログ記録用に取得する値
    $csv_body = date("Y/m/d").",".date("H:i:s").",".$ListPRG[$dir]["PCSP"].",".$ListPRG[$dir]["name"].",".$ListPRG[$dir]["ver"].",".@$_SERVER["REMOTE_ADDR"].",".GetLoginSerial().",".$FolderName.",,";
    $FileName = $LogFileDirectory."af_download\download_log_softdown.txt";
    file_put_contents($FileName, $csv_body.PHP_EOL, FILE_APPEND | LOCK_EX);
    
    header("Location: download_files/".$ListPRG[$dir]["PCSP"]);
?>