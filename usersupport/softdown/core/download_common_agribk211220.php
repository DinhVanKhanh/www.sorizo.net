<?php

// オンラインアップデート対象製品

    require_once dirname(__FILE__) .'/../../../lib/login.php';
    require_once dirname(__FILE__) .'/../../../lib/common.php';

    global $LogFileDirectory;
    global $SP_DOWNLOAD_SERVER_AWS;
    global $ListDL;

    $FileName = $LogFileDirectory . "www_sorizo/download/download_log_softdown.txt";
    $prodCode = getProdCode(getProdCodeByURL());       // 表示している製品コード(xx99_9090 -> xx)
    $prodVersion = getProdVersion(getProdCodeByURL()); // 表示している製品のバージョン(xx99_9090 -> 99)
    $prodVersionSub = str_replace("_", "", strstr(getProdCodeByURL(), '_')); // 表示している製品のバージョンのサブバージョン(xx99_9090 -> 9090)

    
    // EXEファイルのパス取得
    function getSpDownloadUrl_agri() {
        global $SP_DOWNLOAD_SERVER_AWS, $prod_sp_info, $prodCode, $prodVersion;

        $prod = "";
        switch ($prodCode) {
            case 'nbk': // 農業簿記
                $prod = "bk";
                break;
            case 'ja':  // JA版
                $prod = "ja";
                break;
            default :
                $prod = "";
                break;
        }

        $fileName = $prod_sp_info[getProdCodeByURL()]["sp_code_no"]. ".exe";
        return $SP_DOWNLOAD_SERVER_AWS . "ag". $prodVersion. "/". $prod. $prodVersion. "sp". $fileName. "";
    }

    // マニュアルPDFのパス取得
    function getSpManualUrl_agri () {
        global $prodCode, $prodVersionSub;
        return "../pdf_mn_sp/manual_install_". $prodCode. $prodVersionSub. ".pdf";
    }

    /**
     * ダウンロードファイル更新日
     *   引数：SPダウンロードディレクトリ名(例 ja11_1102)
     * */ 
    function getSpLastUpdateDate_agri($pc) {
        global $prod_sp_info;
        if (empty($pc)) $pc = getProdCodeByURL(); // バージョン付きの情報に上書き
        return $prod_sp_info[$pc]["last_up_date"];
    }

    // 製品バージョン(一覧ページ用 戻り値：xx.xx)
    function getSeries($pc) {
        $v = substr($pc, -4, 4);
        return substr_replace($v, ".", 2, 0);
    }

    // 製品バージョン(一覧ページ用 戻り値：xx.xx.xx)
    function getSeriesSub($pc) {
        global $prod_sp_info;
        $v = getSeries($pc);
        return $v. ".". $prod_sp_info[$pc]["series"];
    }


    
    /**
     * 以下はSMBでcommon.phpに記述している関数
     * sorizo側のcommon.phpに手を加えないためにここに表記
     */
    
    // URLから製品コードを取得(親ディレクトリ名)
    function getProdCodeByURL() {
        // ドメイン以下のパス
        $script_name = $_SERVER['SCRIPT_NAME'];
        $path_array = explode("/", $script_name);

        $path_array_size = count($path_array);
        $cSSymbol = $path_array[$path_array_size - 2];

        return $cSSymbol;
    }
    
    // 製品コード取得
    function getProdCode($prod){
        if(preg_match('/[a-z]+/', $prod, $matches)) {
            return $matches[0];
        } else {
            return "";
        }
    }

    // 製品バージョン取得
    function getProdVersion($prod){
        if(preg_match('/[0-9]+/', $prod, $matches)) {
            return $matches[0];
        } else {
            return "";
        }
    }

    function getFileSizeFromURL(string $url) {
        $header = get_headers($url, 1);
        return formatSizeUnits( $header['Content-Length'] );
    }

    function formatSizeUnits($bytes){
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        }
        else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    // ここまで

/*
* バージョンごとのダウンロードページ用のリスト
* 
*   ・last_up_date : 最終更新日
*   ・sp_code_no   : サービスパックのコード
*   ・series       ：バージョンの端数(xx.xx ".XX" )
*/  
    $prod_sp_info = array(
        "ja11_1102" => [
            "last_up_date" => "2021/11/24",
            "sp_code_no"   => "1291112",
            "series"       => "00",
        ],
        "ja11_1101" => [
            "last_up_date" => "2021/06/14",
            "sp_code_no"   => "1246012",
            "series"       => "00",
        ],
        "ja11_1100" => [
            "last_up_date" => "2019/12/17",
            "sp_code_no"   => "1042191",
            "series"       => "00",
        ],
        "nbk11_1102" => [
            "last_up_date" => "2021/12/17",
            "sp_code_no"   => "1472112",
            "series"       => "00",
        ],
        "nbk11_1101" => [
            "last_up_date" => "2021/12/17",
            "sp_code_no"   => "1482112",
            "series"       => "00",
        ],
        "nbk11_1100" => [
            "last_up_date" => "2019/12/18",
            "sp_code_no"   => "1082191",
            "series"       => "00",
        ],
    );
?>