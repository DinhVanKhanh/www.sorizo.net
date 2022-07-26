<?php
    require_once '../../../lib/common.php';
    require_once '../../../lib/login.php';
    // 【要注意】 「接続キット 愛知県版」専用のダウンロード指定ファイルです。
    
    $serial_no = GetLoginSerial();
    global $LogFileDirectory;

    $rq_file = $_GET["f"];

    switch ($rq_file){
        case "prg1":
            $v_version = "11_00_00";
            $v_filetype = "PRG(接続キット)";
            $v_filename = "download_files_ac/BK11spKit.exe";
        break;
        case "mn1":
            $v_version = "11_00_00";   
            $v_filetype = "マニュアル";
            $v_filename = "download_files_ac/manual_nbk_jakit.pdf";
        break;
    }

	//ファイル書き込み
	$myDate = date("n/j/Y");
	$myTime = date("g:i:s A");

	$ip = $_SERVER["REMOTE_ADDR"];

	$csv_body = $myDate . "," . $myTime . "," . $v_version . ",接続キット-JA愛知県版," . $v_filetype . "," . $ip . "," . $serial_no . ",";

	$FileName = $LogFileDirectory . "af_download\download_log_softdown_ja_ac.txt";
	//--- ファイルを開く（追記モード、存在しないときは新規作成） ---
	$TS = fopen($FileName, 'a+');

	//--- 文字列の書き込み ---
	fwrite($TS,$csv_body);

	//--- ファイルを閉じる ---
	fclose($TS);

	//EXE表示
	header("Location:".$v_filename);
?>