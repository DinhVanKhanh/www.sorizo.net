<?php
	require_once '../../../lib/common.php';
	require_once '../../../lib/login.php';

	$serial_no = GetLoginSerial();
	global $LogFileDirectory;
	$rq_file = $_GET["f"];

	switch ($rq_file){
		case "prg1":
			$v_version = "11_01_00";
			$v_SPnumber = "1237002";
			$v_filetype = "SP-" . $v_SPnumber;
			//v_filename = "http://www.sorimachi.on.arena.ne.jp/sp/ag11/bk11sp" & v_SPnumber & ".exe"
			//v_filename = "http://www.sorimachi.co.jp/usersupport/products_support/softdownload/sp/ag11/bk11sp" & v_SPnumber & ".exe"
			$v_filename = $SP_DOWNLOAD_SERVER_AWS . "ag11/bk11sp" . $v_SPnumber . ".exe";
		break;
		case "mn1":
			$v_version = "11_01_00";
			$v_SPnumber = "1237002";
			$v_filetype = "マニュアル(SP)";
			$v_filename = "download_files/manual_install_nbk1101.pdf";
		break;
	}

	//ファイル書き込み
	$myDate = date("n/j/Y");
	$myTime = date("g:i:s A");

	$ip = $_SERVER["REMOTE_ADDR"];

	$csv_body = $myDate . "," . $myTime . "," . $v_version . ",農業簿記11," . $v_filetype . "," . $ip . "," . $serial_no . ",";

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