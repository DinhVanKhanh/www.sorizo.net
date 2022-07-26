<?php
    require_once __DIR__ . '/lib/login.php';
    require_once __DIR__ . '/lib/common.php';
    global $IntendedProductsCode;
// 2019/12/27 t.maruyama 追加 ↓↓ $CallupModeが参照エラーになっていたので修正
    $CallupMode = "";
// 2019/12/27 t.maruyama 追加 ↑↑

    if ( !empty( $_GET["serial_key"] ) ) {
        $serial_no  = $_GET["serial_key"];
        $CallupMode = !empty($_GET["mode"]) ? $_GET["mode"] : "prd";
    }
    // トップページからログインして来た場合
    elseif ( !empty( $_POST["serial_key"] ) ) {
        $serial_no = $_POST["serial_key"];
        $CallupMode = "sorikuranet_top";
    }
    // qa_loginからログインしてきた場合
    elseif ( !empty( $_POST["faq_jump"] ) ) {
        $serial_no = $_POST["serial_key"];
        $CallupMode = "qalogin";
    }
    // それ以外の場合（Sessionが切れている場合はここに飛ばす）
    else {
        $serial_no = GetLoginSerial();
        // Cookieにログインシリアルが保存されている場合
        if ($serial_no != "") {
            $CallupMode = "sorizo_pages";
        }
        // Cookieにログインシリアルが保存されていない場合（期限切れ含む）
        else {
            header("Location: index.php?err=cu001&mode=".$CallupMode);
            exit;
        }
    }

    // どこから来たかを常に判断しておきます。
    $ArrayRefererURL = explode("?", $_SERVER["HTTP_REFERER"]);
    $RedirectURL = $ArrayRefererURL[0];

    $serial_no = trim($serial_no);
    // 大文字の場合は小文字に、全角の場合は半角にします
    $serial_no = mb_convert_kana($serial_no, "rnaskhc");

    // ハイフンを除きます
    $serial_no = str_replace("-", "", $serial_no);

// ↓↓　<2020/07/31> <VinhDao> <認証の時、シリアルNoを置き換えます>
    if ( preg_match('/\w+/', $serial_no) ) {
        $serial_no = strtolower($serial_no);
        switch ( substr($serial_no, 0, 10) ) {
            case "s000agribk":
                $serial_no = "1015118000009287";
                break;
            
            case "s000agrija":
                $serial_no = "1055116000009267";
                break;
        }
    }
// ↑↑　<2020/07/31> <VinhDao> <認証の時、シリアルNoを置き換えます>

    // 13桁か16桁か20桁でない場合はエラーを表示します。
    $lenSerial = strlen($serial_no);
    if ($lenSerial != 13 && $lenSerial != 16 && $lenSerial != 20) {
        header("Location: index.php?err=cu001&mode=".$CallupMode);
        exit;
    }

    // 16桁対象製品である場合
    if (Check14($serial_no)) {
        // セキュリティコードが正しいかどうかを判別
        if (CheckSecurityCode($serial_no) != 0) {
            // エラー表示
            header("Location: index.php?err=cu002&mode=".$CallupMode);
            exit;
        }
    }

    // ログイン対象の製品かどうかをチェック
    // 念の為配列かどうかを確認
    $IntendedProductFlg = false;
    if (is_array($IntendedProductsCode)) {
        // 配列をそれぞれチェック
        foreach ($IntendedProductsCode as $IPCode) {
            // 該当すればOKとする
            if (substr($serial_no, 0, 4) == $IPCode) {
                $IntendedProductFlg = true;
                break;
            }
        }
    }
    if ($IntendedProductFlg == false) {
        // ログインしていた情報を念の為消します
        DeleteCookies();
        header("Location: index.php?err=cu003&mode=".$CallupMode);
        exit;
    }

    // シリアルＮｏの存在確認

    // 契約期間を参照
    $refSerial = RefDateExtention($serial_no);
    if ($refSerial != 0) {
        // FAQのトップページから来たと思われる場合は処理を変えます
        if ( !empty( $_POST["faq_jump"] ) ) {
            header("Location: /usersupport/faq/ERR.php?errlevel=cu101");
            exit;
        }
        else {
            // ログインしていた情報を念の為消しておきます
            DeleteCookies();
            switch ($refSerial) {
                case -1:
                    header("Location: index.php?err=cu004&mode=".$CallupMode);
                    exit;

                case -2:
                    header("Location: index.php?err=cu005&mode=".$CallupMode);
                    exit;

                case -3:
                    header("Location: index.php?err=cu006&mode=".$CallupMode);
                    exit;

                case -4:
                    header("Location: index.php?err=cu008&mode=".$CallupMode);
                    exit;

                default:
                    header("Location: index.php?err=cu007&mode=".$CallupMode);
                    exit;
            }
        }
    }

    // (1) Cookieにログイン情報その他を記録し、
    // 2020/08/21 ここでエラーが発生する
    WriteCookies($serial_no);

    // (2) リダイレクトアドレスがあるかどうかを確認して各ページへ移動します。
    ReadRequestedURL();

    // (3) リダイレクトアドレスがない場合はFAQとそうでない場合に分けて処理します。
    // FAQ（qa_loginから来た場合）
    if ( !empty( $_POST["faq_jump"] ) ) {
        $FaqCodePage = $MovieDirectory = "";
        if (isset($_POST["prd"]) and $_POST["faq_jump"] == $_POST["prd"]) {
            if (isset($_POST["mvflg"]) and $_POST["mvflg"] == "yes") {
                $MovieDirectory = "movie/";
            }
            if ( !empty( $_POST["code"] ) ) {
                $FaqCodePage = substr($_POST["code"], 0, 4) . "-" . substr($_POST["code"], 4, 4) . ".php";
            }
        }
        header("Location: /usersupport/faq/products_faq/".@$_POST["faq_jump"]."/".$MovieDirectory.$FaqCodePage);
        exit;
    }
    // それ以外の場合はトップページへ
    else {
        header("Location: /index.php");
        exit;
    }
?>