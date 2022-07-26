<?php
	require_once __DIR__ . '/../../lib/login.php';
	require_once __DIR__ . '/../../lib/common.php';
    global $SORIMACHI_HOME, $SORIZO_HOME;

	// ログインチェック
    CheckIntendedProduct("all");	// ソリクラ会員かどうかを見ます

// ↓↓　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828>
    // お客様コード
    // $vUserID = $_POST["UserID"];
    // if ( isset($_POST['getUser']) and $vUserID <> '' ) {
    //     //if ( !preg_match( '/^\d{4}\-\d{6}\-\d{2}$/', $_POST['UserID'] ) ) {
    //     //    $vErrorMsg = '「お客様コード」の項目が正しく入力されていません。';
    //     //    goto SkipGetInfoUser;
    //     //}

    //     $user = GetAPIData('users',
    //         '{
    //             "users": {
    //                 "fields": "post_no, tel1, fax, busyo_nm, user_nm, mail, adr_area, adr_addr, pref_nm, adr_city",
    //                 "data": [
    //                     {
    //                         "name": "user_cd",
    //                         "value": "' . str_replace( '-', '', $vUserID ) . '",
    //                         "operator": "="
    //                     }
    //                 ]
    //             }
    //         }', 'GET');

    //     if ( GetFirstByField($user, 'error') != '' ) {
    //         $vErrorMsg = '該当するユーザーが存在しません。';
    //         goto Result;
    //     }

    //     $user = $user['users'][0];
    //     if ( $user['post_no'] != '' ) {
    //         list($_POST['Zip_1'], $_POST['Zip_2']) = explode('-', $user['post_no']);
    //     }

    //     if ( $user['tel1'] != '' and strlen($user['tel1']) >= 10 ) {
    //         list($_POST['Tel_1'], $_POST['Tel_2'], $_POST['Tel_3']) = explode('-', $user['tel1']);
    //     }

    //     if ( $user['fax'] != '' and strlen($user['fax']) >= 10 ) {
    //         list($_POST['Fax_1'], $_POST['Fax_2'], $_POST['Fax_3']) = explode('-', $user['fax']);
    //     }

    //     $_POST['Address_1'] = trim($user['pref_nm'] . '' . $user['adr_city']);
    //     $_POST['Address_2'] = trim($user['adr_area'] . '' . $user['adr_addr']);
    //     $_POST['Dept'] = $user['busyo_nm'];
    //     $_POST['Charge'] = $user['user_nm'];
    //     $_POST['Email'] = $user['mail'];
    //     // $vUserID = addHyphen( str_replace( '-', '', $vUserID ), 12 );
    // }

    // SkipGetInfoUser:

	// このフォームの[登録情報を表示]ボタンが押された場合
	// $vSerialNo = GetLoginSerial();
	// $prod = GetAPIData('prod', '{
	// 	"sral": {
	// 		"fields": "shin_rnm",
	// 		"query": "sral_no=\'' . $vSerialNo . '\'"
	// 	}
    // }', 'GET');

	// $vProductName = '';
    // if ( isset($prod['prod'][0]) 
    // and $prod['prod'][0]['sral'][0]['shin_rnm'] <> '' ) {
	// 	$vProductName = $prod['prod'][0]['sral'][0]['shin_rnm'];
    // }

	// 変更箇所
	// if ( $_POST["ChAddress"] == "1" )
	// 	$vChChecked[0] = "checked";

	// if ( $_POST["ChTel"] == "1" )
	// 	$vChChecked[1] = "checked";

	// if ( $_POST["ChFax"] == "1" )
	// 	$vChChecked[2] = "checked";

	// if ( $_POST["ChEmail"] == "1" )
	// 	$vChChecked[3] = "checked";

	// if ( $_POST["ChDept"] == "1" )
	// 	$vChChecked[4] = "checked";

	// if ( $_POST["ChCharge"] == "1" )
    //     $vChChecked[5] = "checked";
    // Result:

    // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
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

    // Error
    $vErrorMsg = '';
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
            // // シリアルNo
            // $vSerialNo = $_POST['SerialNo'];

            // // 製品名
            // $vProductName = $_POST['ProductName'];
        // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞

        // お客様コード
        $vUserID = trim($_POST["UserID"]);

        // 住所
        $address1 = trim($_POST['Address_1']);
        $address2 = trim($_POST['Address_2']);

        // 郵便番号
        $zip1 = trim($_POST['Zip_1']);
        $zip2 = trim($_POST['Zip_2']);

        // Tel
        $tel1 = trim($_POST['Tel_1']);
        $tel2 = trim($_POST['Tel_2']);
        $tel3 = trim($_POST['Tel_3']);

        // Fax
        $fax1 = trim($_POST['Fax_1']);
        $fax2 = trim($_POST['Fax_2']);
        $fax3 = trim($_POST['Fax_3']);

        // 部署名
        $dept = trim($_POST['Dept']);

        // 担当者名
        $charge = trim($_POST['Charge']);

        // E-Mail
        $email = trim($_POST['Email']);

        // 変更箇所
        if ( trim($_POST["ChAddress"]) == "1" )
            $vChChecked[0] = "checked";

        if ( trim($_POST["ChTel"]) == "1" )
            $vChChecked[1] = "checked";

        if ( trim($_POST["ChFax"]) == "1" )
            $vChChecked[2] = "checked";

        if ( trim($_POST["ChEmail"]) == "1" )
            $vChChecked[3] = "checked";

        if ( trim($_POST["ChDept"]) == "1" )
            $vChChecked[4] = "checked";

        if ( trim($_POST["ChCharge"]) == "1" )
            $vChChecked[5] = "checked";

        // このフォームの[登録情報を表示]ボタンが押された場合
        if ( isset($_POST['getUser']) ) {
            // 「お客様コード」が入力されていない場合
            $test    = trim( str_replace('-', '', $vUserID) );
            if ( empty($test) ) {
            // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.6のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                // $vErrorMsg .= '「お客様コード」は入力してください。<br>';
                $vErrorMsg .= 'お客様コードが入力されていません。';
            // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.6のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                goto Result;
            }
            elseif ( !preg_match('/^\d{8}$/', $test) and !preg_match('/^\d{12}$/', $test) ) {
            // ↓↓　＜2020/09/11＞　＜VinhDao＞　＜No.10のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞
                // $vErrorMsg .= 'お客様コードは正しく入力しません。<br>';
                $vErrorMsg .= 'お客様コードが正しく入力されていません。入力内容をご確認ください。<br>';
            // ↑↑　＜2020/09/11＞　＜VinhDao＞　＜No.10のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞
                goto Result;
            }

            $json = '{
                "users": {
                    "fields": "post_no, tel1, fax, busyo_nm, user_nm, mail, adr_area, adr_addr, pref_nm, adr_city",
                    "data": [
                        {
                            "name": "user_cd",
                            "value": "' . str_replace( '-', '', $vUserID ) . '",
                            "operator": "="
                        }
                    ]
                },
                "sral": {
                    "fields": "sral_no"
                }
            }';
            $json = str_replace(["\r","\n"," "], "", $json);
            $user = GetAPIData('users' , $json, 'GET');
            // $response = $user;
            // if ( !empty($user['user']) ) {
            //     $response['user'] = encryptData($response['user']);
            // }

        // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.7のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
            // Write log JSON
            file_put_contents(
                __DIR__ . '/../../../../data/logs/www_sorizo/TFP-json-log-' . date('Y-m') . '.txt',
                [
                    'Title' => date('Y/m/d') . ', ' . date('H:i:s') . ', change_user_mailform, ' . getClientIP() . "\r\n",
                    'Request' => 'Request: ' . EncryptData( $json ) . "\r\n",
                    'Response' => 'Response: ' . EncryptData( json_encode($user, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT) ) . "\r\n\r\n"
                ],
                FILE_APPEND | LOCK_EX
            );
        // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.7のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞

            // 「お客様コード」が存在しない場合
            if ( !isset( $user['users'] ) or GetFirstByField($user, 'error') != '' ) {
            // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.6のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                // $vErrorMsg .= '該当するユーザーが存在しません。';
                $vErrorMsg .= 'お客様コードが確認できません。入力内容をご確認ください。';
            // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.6のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
                goto Result;
            }
            $user = $user['users'][0];

            // お客様コード」と「シリアルNo.」の組み合わせが正しくない場合
            if ( strpos(serialize($user['sral']), $vSerialNo) === false ) {
                $vErrorMsg .= 'シリアルNo.とお客様コードの情報が一致しません。';
                goto Result;
            }

            // 郵便番号
            if ( $user['post_no'] != '' ) {
                list($zip1, $zip2) = explode('-', $user['post_no']);
            }

            // Tel
            if ( $user['tel1'] != '' and strlen($user['tel1']) >= 10 ) {
                list($tel1, $tel2, $tel3) = explode('-', $user['tel1']);
            }

            // Fax
            if ( $user['fax'] != '' and strlen($user['fax']) >= 10 ) {
                list($fax1, $fax2, $fax3) = explode('-', $user['fax']);
            }

            // 住所
            $address1 = trim($user['pref_nm'] . '' . $user['adr_city']);
            $address2 = trim($user['adr_area'] . '' . $user['adr_addr']);

            // 部署名
            $dept = $user['busyo_nm'];

            // 担当者名
            $charge = $user['user_nm'];

            // E-Mail
            $email = $user['mail'];
        }
    }
    else {
        // ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
            // // シリアルNo
            // $vSerialNo = GetLoginSerial();
            // $prod = GetAPIData('prod', '{
            //     "sral": {
            //         "fields": "shin_rnm",
            //         "query": "sral_no=\'' . $vSerialNo . '\'"
            //     }
            // }', 'GET');

            // // 製品名
            // $vProductName = '';
            // if ( isset($prod['prod'][0]) 
            // and $prod['prod'][0]['sral'][0]['shin_rnm'] <> '' ) {
            //     $vProductName = $prod['prod'][0]['sral'][0]['shin_rnm'];
            // }
        // ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞

        $vUserID = '';
        // 住所
        $address1 = $address2 ='';

        // 郵便番号
        $zip1 = $zip2 = '';

        // Tel
        $tel1 = $tel2 = $tel3 = '';

        // Fax
        $fax1 = $fax2 = $fax3 = '';

        // 部署名
        $dept = '';

        // 担当者名
        $charge = '';

        // E-Mail
        $email = '';

        // 変更箇所
        $vChChecked = ['', '', '', '', '', ''];
    }
    Result:
// ↑↑　<8/31/2020> <VinhDao> <No.3のHP合同PRJ-www_sorizo-運用_20200828>
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
    <script type="text/javascript">
        function clearInput() {
            document.forms['myForm']['ChAddress'].checked = false;
            document.forms['myForm']['ChTel'].checked     = false;
            document.forms['myForm']['ChFax'].checked     = false;
            document.forms['myForm']['ChEmail'].checked   = false;
            document.forms['myForm']['ChDept'].checked    = false;
            document.forms['myForm']['ChCharge'].checked  = false;

            document.forms['myForm']['UserID'].value    = '';
            document.forms['myForm']['Zip_1'].value     = '';
            document.forms['myForm']['Zip_2'].value     = '';
            document.forms['myForm']['Address_1'].value = '';
            document.forms['myForm']['Address_2'].value = '';
            document.forms['myForm']['Tel_1'].value     = '';
            document.forms['myForm']['Tel_2'].value     = '';
            document.forms['myForm']['Tel_3'].value     = '';
            document.forms['myForm']['Fax_1'].value     = '';
            document.forms['myForm']['Fax_2'].value     = '';
            document.forms['myForm']['Fax_3'].value     = '';
            document.forms['myForm']['Dept'].value      = '';
            document.forms['myForm']['Charge'].value    = '';
            document.forms['myForm']['Email'].value     = '';
        }

        function getUser(form) {
            form.action = 'mailform.php';
            let node = document.createElement('input');
            node.setAttribute('type', 'hidden');
            node.setAttribute('name', 'getUser');
            node.setAttribute('value', '1');

            if ( confirm('ユーザー情報を取得しますか？') ) {
                form.appendChild(node);
                form.submit();
            }
        }
    </script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>

<body id="general">
    <div id="oldHeader">
        <h1>ユーザー登録内容変更</h1>
        <p>ユーザー登録情報の変更はこちらから</p>
    </div>
    <div id="oldWrapper">

        <!-- メインコンテンツ（ここから）-->
        <form method="POST" id="myForm" action="mailkakunin.php">
			<div class="pagetitle">ユーザー登録内容変更届</div>

            <!-- エラー表示（ここから） -->
			<div class="alertMessage"><?= $vErrorMsg ?></div>

            <!-- 入力フォーム（ここから）-->
            <table width="600" class="old-table" border="0" cellspacing="0" cellpadding="0" summary="formlayout">
                <tr>
                    <th>製品名</th>
                    <td><span><?= $vProductName ?></span></td>
                </tr>
                <tr>
                    <th>シリアルNo</th>
                    <td><span><?= $vSerialNo ?></span></td>
                </tr>
                <tr>
                    <th>お客様コード</th>
                    <td>
                        <input name="UserID" type="text" value="<?= $vUserID ?>" size="14" maxlength="14">
                        <input name="GetUserInfo" type="button" onclick="getUser(document.getElementById('myForm'));" value="シリアルNo.とお客様コードから登録情報を取得" title="登録情報をフォームにセットします">
                    </td>
                </tr>
                <tr>
                    <th>変更箇所</th>
                    <td>
                        <input name="ChAddress" type="checkbox" <?= $vChChecked[0] ?> value="1">住所
                        <input name="ChTel" type="checkbox" <?= $vChChecked[1] ?> value="1">TEL
                        <input name="ChFax" type="checkbox" <?= $vChChecked[2] ?> value="1">FAX
                        <input name="ChEmail" type="checkbox" <?= $vChChecked[3] ?> value="1">E-Mail
                        <input name="ChDept" type="checkbox" <?= $vChChecked[4] ?> value="1">部署名
                        <input name="ChCharge" type="checkbox" <?= $vChChecked[5] ?> value="1">担当者名
                    </td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    <td>
                        <input name="Zip_1" type="text" value="<?= $zip1 ?>" maxlength="3" size="5">－
                        <input name="Zip_2" type="text" value="<?= $zip2 ?>" maxlength="4" size="6">
                    </td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td>
                        <input name="Address_1" type="text" value="<?= $address1 ?>" size="80" maxlength="20"><br>
                        <input name="Address_2" type="text" value="<?= $address2 ?>" size="80" maxlength="20">
                    </td>
                </tr>
                <tr>
                    <th>TEL</th>
                    <td>
                        <input name="Tel_1" type="text" value="<?= $tel1 ?>" size="6" maxlength="5" class="num">－
                        <input name="Tel_2" type="text" value="<?= $tel2 ?>" size="6" maxlength="4" class="num">－
                        <input name="Tel_3" type="text" value="<?= $tel3 ?>" size="6" maxlength="4" class="num">
                    </td>
                </tr>
                <tr>
                    <th>FAX</th>
                    <td>
                        <input name="Fax_1" type="text" value="<?= $fax1 ?>" size="6" maxlength="5" class="num">－
                        <input name="Fax_2" type="text" value="<?= $fax2 ?>" size="6" maxlength="4" class="num">－
                        <input name="Fax_3" type="text" value="<?= $fax3 ?>" size="6" maxlength="4" class="num">
                    </td>
                </tr>
                <tr>
                    <th>部署名</th>
                    <td><input name="Dept" type="text" value="<?= $dept ?>" size="50" maxlength="30"></td>
                </tr>
                <tr>
                    <th>担当者名</th>
                    <td><input name="Charge" type="text" value="<?= $charge ?>" size="50" maxlength="20"></td>
                </tr>
                <tr>
                    <th>E-Mail</th>
                    <td><input name="Email" type="text" value="<?= $email ?>" size="50" maxlength="120"></td>
                </tr>
            </table>
            <p>
                <input name="SendUserInfo" type="submit" value="　次へ　" title="確認フォームを表示します">
		        <input name="ResetUserInfo" type="button" onclick="clearInput();" value="　リセット　" title="入力情報をすべてリセットします">
            </p><br>
            <!-- 入力フォーム（ここまで）-->
            <p>※社名変更・名義変更に関しては、証明書の提出が必要になります。<br>詳細はソリマチサポートセンター（ＴＥＬ：0258-31-5850）までお問い合わせください。</p>
        </form>
        <!-- メインコンテンツ（ここまで）-->
    </div>
    <div id="oldFooter">
        <ul>
            <li><a href="<?= $SORIZO_HOME ?>" onfocus="this.blur()" target="_blank">そり蔵ネット トップ</a></li>
            <li><a href="<?= $SORIMACHI_HOME ?>" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
        </ul>

        <p>Copyright&copy;&nbsp;Sorimachi&nbsp;Co.,Ltd.&nbsp;All&nbsp;rights&nbsp;reserved.</p>
    </div>
</body>

</html>