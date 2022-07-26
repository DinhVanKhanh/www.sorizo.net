<?php
	require_once '../../../lib/common.php';
	require_once '../../../lib/login.php';

	global $LogFileDirectory;
	$serial_no = GetLoginSerial();

	$rq_file = $_GET["f"];

	switch ($rq_file){
		case "prg1":
			$v_version = "11_03_00";
			$v_SPnumber = "1171012";
			$v_filetype = "SP-1171012" . $v_SPnumber;
			$v_filename = "https://sorimachi-download.s3-ap-northeast-1.amazonaws.com/sp/ag11/ja11sp1171012.exe";
		break;
		case "mn1":
			$v_version = "11_03_00";
			$v_SPnumber = "1171012";
			$v_filetype = "マニュアル(SP)";
			$v_filename = "download_files/manual_install_ja1103.pdf";
		break;
	}

	//ファイル書き込み
	$myDate = date("n/j/Y");
	$myTime = date("g:i:s A");

	$ip = $_SERVER["REMOTE_ADDR"];

	$csv_body = $myDate . "," . $myTime . "," . $v_version . ",農業簿記11JA," . $v_filetype . "," . $ip . "," . $serial_no . ",";

	$FileName = $LogFileDirectory . "af_download\download_log_softdown.txt";
	//--- ファイルを開く（追記モード、存在しないときは新規作成） ---
	$TS = fopen($FileName, 'a+');

	//--- 文字列の書き込み ---
	fwrite($TS,$csv_body);
	
	//--- ファイルを閉じる ---
	fclose($TS);

	//EXE表示
	header("Location:".$v_filename);

?>