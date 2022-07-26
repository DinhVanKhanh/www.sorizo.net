<?php
    $temp    = explode("/", $_SERVER["SCRIPT_NAME"]);
    $curDir  = $temp[count($temp) - 2];
    $dirFile = (trim($curDir) == 'softdown') ? '../..' : '../../..';

    require_once "{$dirFile}/lib/common.php";
    require_once "{$dirFile}/lib/login.php";
    require_once "{$dirFile}/lib/get_filesize.php";

    global $SORIZO_HOME;
    global $SORIMACHI_HOME;
    global $AdobeReaderDL_URL;
?>
<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta name="robots" content="noindex,nofollow">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <meta http-equiv="Content-Script-Type" content="text/javascript">
        <meta http-equiv="Imagetoolbar" content="no">
        <script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/general_1006.css">
        <link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
        <script type="text/javascript" src="/js/dd.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/overlib417/overlib.js"></script>
        <link rel="stylesheet" href="/css/sg_general.css" type="text/css">
        <link rel="stylesheet" href="/css/sg_list.css" type="text/css">
        <link rel="stylesheet" href="/css/sg_blue.css" type="text/css">
        <title>ソフトウェアダウンロード｜そり蔵ネット</title>
        <style type="text/css">
            a:hover { text-decoration: underline; }
        </style>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
    </head>
    <body id="general">
        <div id="oldHeader" background-color:#FFaaaa;>
            <h1>ソフトウェアダウンロード</h1>