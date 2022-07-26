<?php
    require_once '/../../lib/common.php';
    require_once '/../../lib/login.php';

    $serial_no = GetLoginSerial();
    
    //ファイル書き込み
    $myDate = date("n/j/Y");
	$myTime = date("g:i:s A");

    $ip = $_SERVER["REMOTE_ADDR"];
    $download_address ="";

    switch($_GET["f"]){
        case "prg1":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,法人税の達人（平成22年度版） from 農業簿記11JAバージョン,プログラム," . $ip . "," . $serial_no . ",";
		    $download_address = "download_files/JA11HJ21Setup.exe";
        break;
        case "m1":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,法人税の達人（平成22年度版） from 農業簿記11JAバージョン,マニュアル," . $ip . "," . $serial_no . ",";
            $download_address = "download_files/JA11HJ21Setup_manual.pdf";
        break;
        case "prg2":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,法人税の達人 from 農業簿記11JAバージョン(減価償却),プログラム," . $ip . "," . $serial_no . ",";
            $download_address = "download_files/JA11HJ19Setup.exe";
        break;
        case "m2":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,法人税の達人 from 農業簿記11JAバージョン(減価償却),マニュアル," . $ip . "," . $serial_no . ",";
            $download_address = "download_files/JA11HJ19Setup_manual.pdf";
        break;
        case "prg3":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,内訳概況書の達人 from 農業簿記11JAバージョン(内訳書),プログラム," . $ip . "," . $serial_no . ",";
		    $download_address = "download_files/JA11UG13Setup.exe";
        break;
        case "m3":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,内訳概況書の達人 from 農業簿記11JAバージョン(内訳書),マニュアル," . $ip . "," . $serial_no . ",";
		    $download_address = "download_files/JA11UG13Setup_manual.pdf";
        break;
        case "prg4":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,内訳概況書の達人（平成16年度以降用） from 農業簿記11JAバージョン(概況書),プログラム," . $ip . "," . $serial_no . ",";
		    $download_address = "download_files/JA11UG16Setup.exe";
        break;
        case "m4":
            $csv_body = $myDate . "," . $myTime . ",JA11_00_00,内訳概況書の達人（平成16年度以降用） from 農業簿記11JAバージョン(概況書),マニュアル," . $ip . "," . $serial_no . ",";
            $download_address = "download_files/JA11UG16Setup_manual.pdf";
        break;
    }
    
	$FileName = $LogFileDirectory . "af_download\download_log_softdown.txt";
	//--- ファイルを開く（追記モード、存在しないときは新規作成） ---
	$TS = fopen($FileName, 'a+');

	//--- 文字列の書き込み ---
	fwrite($TS,$csv_body);

	//--- ファイルを閉じる ---
	fclose($TS);

	//EXE表示
	header("Location:".$download_address);
?>

