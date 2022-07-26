<?php
    require_once __DIR__ . '/../../lib/common.php';
    require_once __DIR__ . '/../../lib/login.php';
    global $SORIMACHI_HOME, $SORIZO_HOME;

    // ログインチェック
    CheckIntendedProduct("all");    // ソリクラ会員かどうかを見ます

    // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
        // // 製品名
        // $vProductName = trim($_POST["ProductName"] ?? '');

        // // シリアルNo
        // $vSerialNo = trim($_POST["SerialNo"] ?? '');

        // シリアルNo
        $vSerialNo = GetLoginSerial();
        $prod = GetAPIData('prod', '{
            "sral": {
                "fields": "shin_rnm",
                "query": "sral_no=\'' . $vSerialNo . '\'"
            }
        }', 'GET');

        // 製品名
        $vProductName = '';
        if ( isset($prod['prod'][0]) 
        and $prod['prod'][0]['sral'][0]['shin_rnm'] <> '' ) {
            $vProductName = $prod['prod'][0]['sral'][0]['shin_rnm'];
        }
    // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞

    // お客様コード
    $vUserID = trim($_POST["UserID"] ?? '');

    // 変更箇所
    $type = "";
    if ($_POST["ChAddress"] == "1") {
        $vChChecked[0] = "住所";
        $type .= '<input type="hidden" name="ChAddress" value="1"/>';
    }

    if ($_POST["ChTel"] == "1") {
        $vChChecked[1] = "TEL";
        $type .= '<input type="hidden" name="ChTel" value="1"/>';
    }

    if ($_POST["ChFax"] == "1") {
        $vChChecked[2] = "FAX";
        $type .= '<input type="hidden" name="ChFax" value="1"/>';
    }

    if ($_POST["ChEmail"] == "1") {
        $vChChecked[3] = "E-Mail";
        $type .= '<input type="hidden" name="ChEmail" value="1"/>';
    }

    if ($_POST["ChDept"] == "1") {
        $vChChecked[4] = "部署名";
        $type .= '<input type="hidden" name="ChDept" value="1"/>';
    }

    if ($_POST["ChCharge"] == "1") {
        $vChChecked[5] = "担当者名";
        $type .= '<input type="hidden" name="ChCharge" value="1"/>';
    }

    // 郵便番号
    $vZip[0] = trim($_POST["Zip_1"] ?? '');
    $vZip[1] = trim($_POST["Zip_2"] ?? '');

    // 住所（上下段）
    $vAddress[0] = trim($_POST["Address_1"] ?? '');
    $vAddress[1] = trim($_POST["Address_2"] ?? '');

    // TEL
    $vTel[0] = trim($_POST["Tel_1"] ?? '');
    $vTel[1] = trim($_POST["Tel_2"] ?? '');
    $vTel[2] = trim($_POST["Tel_3"] ?? '');

    // FAX
    $vFax[0] = trim($_POST["Fax_1"] ?? '');
    $vFax[1] = trim($_POST["Fax_2"] ?? '');
    $vFax[2] = trim($_POST["Fax_3"] ?? '');

    // 部署名
    $vDept = trim($_POST["Dept"] ?? '');

    // 担当者名
    $vCharge = trim($_POST["Charge"] ?? '');

    // E-Mail
    $vEmail = trim($_POST["Email"] ?? '');

    // 入力確認(FAXと部署は非必須)
    $vErrMsg = "";

    // 製品名
    if ($vProductName == "")
        $vErrMsg .= "「製品名」の項目が入力されていません。<br>";

    
// ↓↓　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828>
    // シリアルNo
    // if (strlen($vSerialNo) == 0)
    //     $vErrMsg .= "「シリアルNo」の項目が入力されていません。<br>";

    $test = trim( str_replace('-', '', $vSerialNo) );
    if ( empty($test) )
        $vErrMsg .= "「シリアルNo」の項目が入力されていません。<br>";
    elseif ( !preg_match('/^\d{16}$/', $test) )
        $vErrMsg .= '「シリアルNo」は無効です。<br>';

    // お客様コード
    $test = trim( str_replace('-', '', $vUserID) );
    if ( empty($test) )
        $vErrMsg .= '「お客様コード」は入力されていません。<br>';
    elseif ( !preg_match('/^\d{8}$/', $test) and !preg_match('/^\d{12}$/', $test) )
        $vErrMsg .= '「お客様コード」は無効です。<br>';
    
// ↑↑　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828>

    $vChecked = false;    // 変更箇所

    // 2020/12/08 Kentaro.Watanabe mod; (array)を追加
    // foreach ($vChChecked as $tmp) {
    foreach ((array)$vChChecked as $tmp) {
        if ($tmp <> "") {
            $vChecked = true;
            break;
        }
    }

    if (!$vChecked)
        $vErrMsg .= "「変更箇所」の項目が選択されていません。<br>";

    // 郵便番号
    if (strlen($vZip[0]) <> 3 or strlen($vZip[1]) <> 4)
        $vErrMsg .= "「郵便番号」の項目が不正です。<br>";

    // 住所上段
    if ($vAddress[0] == "")
        $vErrMsg .= "「住所」の項目が入力されていません。<br>";

    // TEL
    if (strlen($vTel[0]) == 0 or strlen($vTel[1]) == 0 or strlen($vTel[2]) == 0)
        $vErrMsg .= "「TEL番号」の項目が入力されていません。<br>";

    // 担当者
    if ($vCharge == "")
        $vErrMsg .= "「担当者」の項目が入力されていません。<br>";

    // Email
    if ($vEmail == "")
        $vErrMsg .= "「E-Mail」の項目が入力されていません。<br>";

    // 記号を変換
    function quoteChg($encChange)
    {
        $encChange = str_replace(" ", "&nbsp;", $encChange);
        $encChange = str_replace('""', "&quot;", $encChange);
        $encChange = str_replace("'", "''", $encChange);
        $encChange = str_replace("&", "&amp;", $encChange);
        $encChange = str_replace("<", "&lt;", $encChange);
        $encChange = str_replace(">", "&gt;", $encChange);
        return $encChange;
    }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta http-equiv="Content-Script-Type" content="text/javascript">
    <meta http-equiv="Imagetoolbar" content="no">
    <meta name="Robots" content="noindex, nofollow">
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
    <link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/base.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style.css">
    <script type="text/javascript" src="/js/dd.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <title>ユーザー登録内容変更｜そり蔵ネット</title>
    <style type="text/css">
        a:hover {
            text-decoration: underline;
        }
    </style>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>

<body id="general">
    <div id="oldHeader">
        <h1>ユーザー登録内容変更</h1>
        <p>ユーザー登録情報の変更はこちらから</p>
    </div>
    <div id="oldWrapper">
        <!-- メインコンテンツ（ここから）-->
        <div class="pageTitle">ユーザー登録内容変更　確認画面</div>
        <form method="post" id="myForm" action="mailform.php">
        <!-- ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
            <!-- ↓↓　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828> -->
                <!-- <input type="hidden" name="ProductName" value="<p?= $vProductName ?>">
                <input type="hidden" name="SerialNo" value="<p?= $vSerialNo ?>"> -->
            <!-- ↑↑　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828> -->
        <!-- ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
            <input type="hidden" name="UserID" value="<?= $vUserID ?>">
            <input type="hidden" name="Zip_1" value="<?= $vZip[0] ?>">
            <input type="hidden" name="Zip_2" value="<?= $vZip[1] ?>">
            <input type="hidden" name="Address_1" value="<?= $vAddress[0] ?>">
            <input type="hidden" name="Address_2" value="<?= $vAddress[1] ?>">
            <input type="hidden" name="Tel_1" value="<?= $vTel[0] ?>">
            <input type="hidden" name="Tel_2" value="<?= $vTel[1] ?>">
            <input type="hidden" name="Tel_3" value="<?= $vTel[2] ?>">
            <input type="hidden" name="Fax_1" value="<?= $vFax[0] ?>">
            <input type="hidden" name="Fax_2" value="<?= $vFax[1] ?>">
            <input type="hidden" name="Fax_3" value="<?= $vFax[2] ?>">
            <input type="hidden" name="Dept" value="<?= $vDept ?>">
            <input type="hidden" name="Charge" value="<?= $vCharge ?>">
            <input type="hidden" name="Email" value="<?= $vEmail ?>">
            <?= $type ?>
        </form><?php

        if ($vErrMsg <> "") : ?>
            <!-- エラーメッセージ（ここから）-->
            <div class="alertMessage"><?= $vErrMsg ?></div>
            <p>〔戻る〕ボタンか、ブラウザの戻るボタンで入力フォームに戻り、修正してください。</p>
            <input type="submit" form="myForm" value="戻る">
            <!-- エラーメッセージ（ここまで）--><?php

                                else : ?>
            <!-- 確認フォーム（ここから）-->
            <p>こちらの内容でよろしい場合は、〔送信〕ボタンをクリックして送信してください。<br>
                修正される場合は、〔戻る〕ボタンか、ブラウザの戻るボタンで入力フォームに戻り、修正してください。</p>
            <table width="600" class="old-table" border="0" cellspacing="0" cellpadding="0" summary="formlayout">
                <tr>
                    <th>製品名</th>
                    <td><?= $vProductName ?></td>
                </tr>
                <tr>
                    <th>シリアルNo</th>
                    <td><?= $vSerialNo ?></td>
                </tr>
                <tr>
                    <th>お客様コード</th>
                    <td><?= $vUserID ?></td>
                </tr>
                <tr>
                    <th>変更箇所</th>
                    <td><?= join(', ', $vChChecked) ?></td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    <td><?= join("-", $vZip) ?></td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td><?= $vAddress[0] ?>　<?= $vAddress[1] ?></td>
                </tr>
                <tr>
                    <th>TEL</th>
                    <td><?= join("-", $vTel) ?></td>
                </tr>
                <tr>
                    <th>FAX</th>
                    <td><?= str_replace("--", "", join("-", $vFax)) ?></td>
                </tr>
                <tr>
                    <th>部署名</th>
                    <td><?= $vDept ?></td>
                </tr>
                <tr>
                    <th>担当者名</th>
                    <td><?= $vCharge ?></td>
                </tr>
                <tr>
                    <th>E-Mail</th>
                    <td><?= $vEmail ?></td>
                </tr>
            </table>
            <br>

            <form method="post" action="mailsosin.php" onSubmit="return confirm('送信してよろしいですか？')">
            <!-- ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
                <!-- <input type="hidden" name="ProductName" value="<p?= quoteChg($vProductName) ?>"> -->
                <!-- <input type="hidden" name="SerialNo" value="<p?= $vSerialNo ?>"> -->
            <!-- ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
                <input type="hidden" name="UserID" value="<?= $vUserID ?>">
                <input type="hidden" name="ChChecked" value="<?= join(', ', $vChChecked) ?>">
                <input type="hidden" name="Zip" value="<?= join("-", $vZip) ?>">
                <input type="hidden" name="Address_1" value="<?= quoteChg($vAddress[0]) ?>">
                <input type="hidden" name="Address_2" value="<?= quoteChg($vAddress[1]) ?>">
                <input type="hidden" name="Tel" value="<?= join("-", $vTel) ?>">
                <input type="hidden" name="Fax" value="<?= str_replace("--", "", join("-", $vFax)) ?>">
                <input type="hidden" name="Dept" value="<?= quoteChg($vDept) ?>">
                <input type="hidden" name="Charge" value="<?= quoteChg($vCharge) ?>">
                <input type="hidden" name="Email" value="<?= $vEmail ?>">
                <div align="center">
                    <input type="submit" form="myForm" value="　戻る　" title="戻って入力し直します">
                    <input type="submit" value="　送信　" title="送信します">
                </div>
            </form>
            <!-- 確認フォーム（ここまで）--><?php
        endif; ?>
        <!-- メインコンテンツ（ここまで）-->
    </div>
    <div id="oldFooter">
        <ul>
            <li><a href="<?= $SORIZO_HOME ?>" onfocus="this.blur()" target="_blank">そり蔵ネット トップ</a></li>
            <li><a href="<?= $SORIMACHI_HOME ?>" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
        </ul>

        <p>Copyright&copy;&nbsp;Sorimachi&nbsp;Co.,Ltd.&nbsp;All&nbsp;rights&nbsp;reserved.</p>
</body>

</html>