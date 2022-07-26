<?php
	// require_once __DIR__ . "/../../../common_files/includes/debug/config.inc.php";
	require_once __DIR__ . '/../../lib/common.php';
	require_once __DIR__ . '/../../lib/login.php';
	require_once __DIR__ . '/../../../common_files/smtp_mail.php';
	global $SORIMACHI_HOME, $SORIZO_HOME;

	// ログインチェック
	CheckIntendedProduct("all");	// ソリクラ会員かどうかを見ます

	// 送信用の設定
	global $WEBSERVER_FLG;
	if ( $WEBSERVER_FLG == 0 ) {
		// 本番AWSサーバー用の設定
		// $MAIL_TO = "sori_information@mail.sorimachi.co.jp";  // 送信元
		$MAIL_FROM = "sori_information@mail.sorimachi.co.jp";  // 送信元
		// AWS-TESTサーバー用の設定(2020/08/28 Kentaro.Watanabe)
		$MAIL_TO   = "k_watanabe@mail.sorimachi.co.jp";  // 送信先
		// $MAIL_FROM = "k_watanabe@mail.sorimachi.co.jp";  // 送信元
		// $MAIL_SERV = "mail.sorimachi.co.jp";             // メールサーバ
	}
	else {
		// テストサーバー用の設定
		$MAIL_TO   = "vinh-dao@mail.sorimachi.co.jp";  // 送信先
		$MAIL_FROM = "k_watanabe@mail.sorimachi.co.jp";  // 送信元
		// $MAIL_SERV = "mail.sorimachi.co.jp";             // メールサーバ
	}

	#region オプションの値
	$MAIL_OPTIONAL = new stdClass;

	// Add BCC
	$MAIL_OPTIONAL->BCC = array(
		['address' => 'k_watanabe@mail.sorimachi.co.jp', 'name' => '渡邉 健太郎'],
		['address' => 'haruka-suganuma@mail.sorimachi.co.jp', 'name' => '菅沼 晴香']
		// ['address' => 'hanchung@mail.sorimachi.co.jp', 'name' => 'Hân Hóng Hách']
	);
	#endregion

	// ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞
        // // 製品名
        // $vProductName = trim($_POST["ProductName"]);

        // // シリアルNo
        // $vSerialNo = trim($_POST["SerialNo"]);

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
	$vUserID = trim($_POST["UserID"]);

	// 変更箇所
	$vChChecked = trim($_POST["ChChecked"] ?? '');

	// 郵便番号
	$vZip = trim($_POST["Zip"] ?? '');

	// 住所（上下段）
	$vAddress[0] = trim($_POST["Address_1"] ?? '');
	$vAddress[1] = trim($_POST["Address_2"] ?? '');

	// 電話
	$vTel = trim($_POST["Tel"] ?? '');

	// FAX
	$vFax = trim($_POST["Fax"] ?? '');

	// 部署名
	$vDept = trim($_POST["Dept"] ?? '');

	// 担当者名
	$vCharge = trim($_POST["Charge"] ?? '');

	// E-Mail
	$vEmail = trim($_POST["Email"] ?? '');

	// 入力確認(FAXと部署は非必須)
	$vErrMsg = "";

	// 製品名
	if ( $vProductName == "" )
	  $vErrMsg .= "「製品名」の項目が入力されていません。<br>";
  
	  // シリアルNo
	if (strlen($vSerialNo) == 0 )
	  $vErrMsg .= "「シリアルNo」の項目が入力されていません。<br>";
  
	// お客様コード
//	if (!preg_match('/^\d{4}\-\d{6}\-\d{2}$/', $vUserID))
//	  $vErrMsg .= "「お客様コード」の項目が正しく入力されていません。<br>";

	// 郵便番号
	if ($vZip == "") 
		$vErrMsg .= "「郵便番号」の項目が不正です。<br>";

	// 住所上段
	if ( $vAddress[0] == "" )
		$vErrMsg .= "「住所」の項目が入力されていません。<br>";

	// TEL
	if ($vTel == "") 
		$vErrMsg .= "「TEL番号」の項目が入力されていません。<br>";

	// 担当者
	if ( $vCharge == "" )
		$vErrMsg .= "「担当者」の項目が入力されていません。<br>";

	// Email
	if ( $vEmail == "" )
		$vErrMsg .= "「E-Mail」の項目が入力されていません。<br>";

	// エラーがなければメール送信
	if ($vErrMsg == "")
		$vErrMsg = SendMail();

	// メール送信
	function SendMail() {
	// ↓↓　＜2020/12/08＞　＜VinhDao＞　＜オプションの値を追加＞
		// Mail
		// global $MAIL_SERV, $MAIL_TO, $MAIL_FROM;
		global $MAIL_SERV, $MAIL_TO, $MAIL_FROM, $MAIL_OPTIONAL;
	// ↑↑　＜2020/12/08＞　＜VinhDao＞　＜オプションの値を追加＞

		// 情報
		global $vProductName, $vSerialNo, $vUserID, $vChChecked, $vZip;
		global $vAddress, $vTel, $vFax, $vDept, $vCharge, $vEmail;

		$mailfrom = "ユーザー登録内容変更届<" . $MAIL_FROM . ">";
		$subj = "ユーザー登録内容変更届";

		$body = "[製品名]：\t" . $vProductName . "\r\n" .
				"[シリアルNo]：\t" . $vSerialNo . "\r\n" .
				"[お客様コード]：\t" . $vUserID . "\r\n" .
				"[変更箇所]：\t" . $vChChecked . "\r\n" .
				"[郵便番号]：\t" . $vZip . "\r\n" .
				"[住所1]：\t" . $vAddress[0] . "\r\n" .
				"[住所2]：\t" . $vAddress[1] . "\r\n" .
				"[TEL番号]:\t" . $vTel . "\r\n" .
				"[FAX番号]:\t" . $vFax . "\r\n" .
				"[部署名]：\t" . $vDept . "\r\n" .
				"[担当者名]：\t" . $vCharge . "\r\n" .
				"[メールアドレス]:\t" . $vEmail . "\r\n\r\n" .
				"※そり蔵ネットから送信\r\n";
	// ↓↓　＜2020/12/08＞　＜VinhDao＞　＜オプションの値を追加＞
		// if ( !send_mail_PHPMailer($MAIL_TO, $subj, $body, $mailfrom) ) {
		if ( !send_mail_PHPMailer($MAIL_TO, $subj, $body, $mailfrom, $MAIL_OPTIONAL) ) {
	// ↑↑　＜2020/12/08＞　＜VinhDao＞　＜オプションの値を追加＞
			return 'メールの送信でエラーが発生しました。';
		}
		return 'メールが送信されました。';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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
		<div class="pageTitle">ユーザー登録内容変更届け　送信</div><?php
		if ( $vErrMsg <> '' and $vErrMsg <> 'メールが送信されました。' ): ?>
			<!-- 送信エラーメッセージ（ここから）-->
			<div class="alertMessage"><?= $vErrMsg ?></div>
			<form id='form' method="post" action="mailform.php">
			<!-- ↓↓　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
				<!-- <input type="hidden" name="ProductName" value="<p?= $vProductName ?>">
                <input type="hidden" name="SerialNo" value="<p?= $vSerialNo ?>"> -->
			<!-- ↑↑　＜2020/09/09＞　＜VinhDao＞　＜No.4のHP合同PRJ-www_sorizo-運用_20200909を修正する。＞ -->
				<input type="hidden" name="UserID" value="<?= $vUserID ?>"><?php
				if ( $vZip <> "" ): 
					$vZip = explode('-', $vZip); ?>
					<input type="hidden" name="Zip_1" value="<?= $vZip[0] ?>">
                	<input type="hidden" name="Zip_2" value="<?= $vZip[1] ?>"><?php
				endif; ?>

                <input type="hidden" name="Address_1" value="<?= $vAddress[0] ?>">
				<input type="hidden" name="Address_2" value="<?= $vAddress[1] ?>"><?php
				if ( $vTel <> "" ): 
					$vTel = explode('-', $vTel); ?>
					<input type="hidden" name="Tel_1" value="<?= $vTel[0] ?>">
					<input type="hidden" name="Tel_2" value="<?= $vTel[1] ?>">
					<input type="hidden" name="Tel_3" value="<?= $vTel[2] ?>"><?php
				endif;

				if ( $vChChecked <> '' ):
					$vChChecked = explode(', ', $vChChecked);
					foreach ($vChChecked as $check) {
						switch ( trim($check) ) {
							case '住所':
								echo '<input type="hidden" name="ChAddress" value="1"/>';
								break;
							
							case 'TEL':
								echo '<input type="hidden" name="ChTel" value="1"/>';
								break;

							case 'FAX':
								echo '<input type="hidden" name="ChFax" value="1"/>';
								break;

							case 'E-Mail':
								echo '<input type="hidden" name="ChEmail" value="1"/>';
								break;

							case '部署名':
								echo '<input type="hidden" name="ChDept" value="1"/>';
								break;

							case '担当者名':
								echo '<input type="hidden" name="ChCharge" value="1"/>';
								break;
						}
					}
				endif;

				if ( $vFax <> "" ): 
					$vFax = explode('-', $vFax); ?>
					<input type="hidden" name="Fax_1" value="<?= $vFax[0] ?>">
					<input type="hidden" name="Fax_2" value="<?= $vFax[1] ?>">
					<input type="hidden" name="Fax_3" value="<?= $vFax[2] ?>"><?php
				endif; ?>

                <input type="hidden" name="Dept" value="<?= $vDept ?>">
                <input type="hidden" name="Charge" value="<?= $vCharge ?>">
                <input type="hidden" name="Email" value="<?= $vEmail ?>">
                <?= $type ?>
			</form>

			<p>
				送信時にエラーが発生しました。<br>恐れ入りますがメールアドレスなどを再度ご確認いただくか、〔戻る〕ボタンで<br>確認画面に戻り、確認画面を下記までＦＡＸいただきますようお願いいたします。<br><br>
				<strong>ソリマチ サポート＆サービスセンター(FAX：03-5791-4350)</strong>
			</p>
			<input type="submit" form="form" value="　再入力　">
			<input type="submit" form="form" formaction="mailkakunin.php" value="　戻る　">
			<!-- 送信エラーメッセージ（ここまで）--><?php

		else: ?>
			<!-- 送信完了メッセージ（ここから）-->
			<p>
				ユーザー登録情報の内容変更が完了いたしました。<br>
				お手続きありがとうございました。<br>
				<br><br><br><br><br><br>
			</p>
			<!-- 送信完了メッセージ（ここまで）--><?php
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