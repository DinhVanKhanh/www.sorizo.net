<?php
    // require_once $_SERVER["DOCUMENT_ROOT"]."/lib/webserver_flg.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."/lib/common.php";
    global $DLLogFileDirectory;

    $DownloadID = @$_POST["id"];
    $serial_no  = @$_POST["serial_no"];
    $prod       = @$_POST["prod"];

    // 製品コードとバージョンに分割する
    $prodCode = preg_replace('/[^a-z]/', '', $prod);
    $ver = preg_replace('/[^0-9]/', '', $prod);

    $y = (new DateTime())->format('Y');
    $LOGS_DIR = 'www_sorizo/download/';
    $log_file = "";
        
    // 値渡しが無ければログを残してログイン画面に戻る
    if(!isset($DownloadID) || !isset($serial_no)) {
        $url = "https://www.sorizo.net/";
        header("Location: ". $url);
        // echo "<script>location.href = './';</script>";
    }

    $DownloadFileName = $DownloadFileTitle = "";
    switch ($DownloadID) {
        case 1:
            $DownloadFileName = $prod;
            $DownloadFileTitle = "農業簿記11_令和3年_年末レベルアップ版(Ver11.03.00)";
            $log_file = $LOGS_DIR. 'download_log_'. $y. ".txt";
            break;
    }

    $FileName = $DLLogFileDirectory. $log_file;

    $csv_body = date("Y/m/d").",".
                date("H:i:s").",".
                $serial_no. ",".
                $DownloadFileTitle.",".
                "download,".
                $DownloadFileName;
                getClientIP().",".
    file_put_contents($FileName, $csv_body.PHP_EOL, FILE_APPEND | LOCK_EX);

    // ファイルダウンロード
    fileDownload($prodCode, $ver);
?>