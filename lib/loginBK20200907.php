<?php
    require_once dirname(__FILE__)."/../../common_files/STFSApiAccess.php";

    // Cookieを消す
    function DeleteCookies() {
        // 保有製品一覧の配列から抜き出したシリアル4桁の各値（s000は含みません）
        // まず保有確認製品の値を全て削除します（1日前のCookieとして保存し、ブラウザ側で削除させる）
        global $PossessedProductsCode;
        foreach ($PossessedProductsCode as $PPcode) {
            UpdateCookie($PPcode, "", -1);
        }
        // ログインシリアルの値も一度消します。
        UpdateCookie(LoginProduct, "", -1);
    }

    // ユーザー専用のコンテンツを表示するためにCookieをチェックします。
    // Input : 製品シリアルの先頭4桁、もしくは先頭3桁の配列
    // Output: ログインした製品のシリアルＮｏ（Cookieより）
    function GetLoginSerial() {
        return GetCookie(LoginProduct, f_serialno);
    }

    // 会員専用ページを表示するためのチェックを行います。
    // 表示対象製品のいずれかの契約終了日が未来日付であれば表示可とします。
    // 必ず common.php も一緒にインクルードする必要があります。
    function CheckIntendedProduct($code) {
        $FlagCodeExpires = false;
        $Today           = date("Y/m/d");
        $code            = strtolower(trim($code));

        // コードの指定がないので、誰でも閲覧ＯＫとみなす（できればこの指定は無しで）
        if ($code == "") {
            // 何も処理せず、ページをそのまま表示します。
            $FlagCodeExpires = true;
        }
        // 製品を問わず、ソリマチクラブ会員であればよい場合
        elseif ($code == "all") {
            // ログインした製品の契約終了日がきちんと入っているかどうかを確認する
            $CodeExpires = GetCookie(LoginProduct, f_expires);
            if ($CodeExpires == "") {
                // 日付の値が入っていない（ログインできていない）ので、ページ表示はせずエラーとする
                WriteRequestedURL();
                header("Location: /error.php?err=cr001");
                exit;
            }
            else {
                // 日付が入っている場合は今日の日付と照らし合わせて表示するか否かを判断する
                $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays); // 保守契約が切れるXX日後
                if (compareDate($Today, $CodeExpiresLimit) == 1) {
                    $FlagCodeExpires = true;
                    ReadRequestedURL();
                }
                else {
                    WriteRequestedURL();
                    header("Location: /error.php?err=cr002");
                    exit;
                }
            }
        }
        // 製品毎に、ソリマチクラブ会員かどうかを見る場合（カンマ区切りで入れること）
        else {
            $ArrayCodeExpires = explode(",", $code);

            // ログインした製品の契約終了日がきちんと入っているかどうかを確認する
            $CodeExpires = GetCookie(LoginProduct, f_expires);
            if ($CodeExpires == "") {
                // 日付の値が入っていない（ログインできていない）ので、ページ表示はせずエラーとする
                WriteRequestedURL();
                header("Location: /error.php?err=cr003");
                exit;
            }
            else {
                // ログイン可能製品(ページ毎に指定された値)毎に処理を行います。
                foreach ($ArrayCodeExpires as $ArrayCode) {
                    // ログイン対象製品の契約終了日があるかどうかを確認していきます。
                    if (strlen($ArrayCode) == 3) {
                        // 4桁目に0から9までそれぞれ追加して、該当するCookieを見ていきます。
                        for ($i = 0; $i <= 9; $i++) {
                            $CodeExpires3 = GetCookie($ArrayCode.$i, f_expires);
                            if ($CodeExpires3 != "") {
                                $CodeExpires3Limit = DateAdd($CodeExpires3, ExtentionDays);
                                if (compareDate($Today, $CodeExpires3Limit) == 1) {
                                    $FlagCodeExpires = true;
                                    ReadRequestedURL();
                                    // URLがなければそのまま
                                    break;
                                }
                            }
                        }
                        if ($FlagCodeExpires == true) {
                            break;
                        }
                    }
                    elseif (strlen($ArrayCode) == 4) {
                        $CodeExpires = GetCookie($ArrayCode, f_expires);
                        if ($CodeExpires != "") {
                            $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays);
                            if (compareDate($Today, $CodeExpiresLimit) == 1) {
                                $FlagCodeExpires = true;
                                ReadRequestedURL();
                                // URLがなければそのまま
                                break;
                            }
                        }
                    }
                }
            }
        }
        if ($FlagCodeExpires == false) {
            WriteRequestedURL();
            header("Location: /error.php?err=cr004");
            exit;
        }
    }

    // FAQは処理が異なる場合が多いため、別処理に
    // 表示対象製品の契約終了日が未来日付であれば表示可とします。
    function CheckIntendedProductFaq($code) {
        $FlagCodeExpires = false;
        $Today           = date("Y/m/d");
        $code            = strtolower(trim($code));

        // コードの指定がないので、誰でも閲覧ＯＫとみなす（できればこの指定は無しで）
        if ($code == "") {
            // 何も処理せず、ページをそのまま表示します。
            $FlagCodeExpires = true;
        }
        // 製品を問わず、ソリマチクラブ会員であればよい場合（FAQは恐らくない）
        elseif ($code == "all") {
            // ログインした製品の契約終了日がきちんと入っているかどうかを確認する
            $CodeExpires = GetCookie(LoginProduct, f_expires);
            if ($CodeExpires == "") {
                // 日付の値が入っていない（ログインできていない）ので、ページ表示はせずエラーとする
                header("Location: /usersupport/faq/qa_login.php?err=cr101&prd=");
                exit;
            }
            else {
                // 日付が入っている場合は今日の日付と照らし合わせて表示するか否かを判断する
                $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays); // 保守契約が切れるXX日後
                if (compareDate($Today, $CodeExpiresLimit) == 1) {
                    $FlagCodeExpires = true;
                }
                else {
                    $FlagCodeExpires = false;
                    header("Location: /usersupport/faq/qa_login.php?err=cr102&prd=");
                    exit;
                }
            }
        }
        // 製品毎に、ソリマチクラブ会員かどうかを見る場合
        else {
            $ArrayCodeExpires = explode(",", $code);

            // ログインした製品の契約終了日がきちんと入っているかどうかを確認する
            $CodeExpires = GetCookie(LoginProduct, f_expires);
            if ($CodeExpires == "") {
                // 日付の値が入っていない（ログインできていない）ので、ページ表示はせずエラーとする
                header("Location: /usersupport/faq/qa_login.php?err=cr103&prd=");
                exit;
            }
            else {
                // ログイン可能製品(ページ毎に指定された値)毎に処理を行います。
                foreach ($ArrayCodeExpires as $ArrayCode) {
                    // ログイン対象製品の契約終了日があるかどうかを確認していきます。
                    if (strlen($ArrayCode) == 3) {
                        // 4桁目に0から9までそれぞれ追加して、該当するCookieを見ていきます。
                        for ($i = 0; $i <= 9; $i++) {
                            $CodeExpires3 = GetCookie($ArrayCode.$i, f_expires);
                            if ($CodeExpires3 != "") {
                                $CodeExpires3Limit = DateAdd($CodeExpires3, ExtentionDays);
                                if (compareDate($Today, $CodeExpires3Limit) == 1) {
                                    // 4桁のいずれかで契約終了日が未来のものがあればOKとする
                                    // 実際にはちょっと違うが、現実的に考えてOKと見做す
                                    $FlagCodeExpires = true;
                                    break;
                                }
                            }
                        }
                        if ($FlagCodeExpires == true) {
                            break;
                        }
                    }
                    elseif (strlen($ArrayCode) == 4) {
                        $CodeExpires = GetCookie($ArrayCode, f_expires);
                        if ($CodeExpires != "") {
                            $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays);
                            if (compareDate($Today, $CodeExpiresLimit) == 1) {
                                $FlagCodeExpires = true;
                                break;
                            }
                        }
                    }
                }
            }
        }

        if ($FlagCodeExpires == false) {
            header("Location: /usersupport/faq/qa_login.php?err=cr104&prd=");
            exit;
        }
    }

    function CheckIntendedProductFaqLogin($code) {
        $Today = date("Y/m/d");
        $code  = strtolower(trim($code));
        $ret   = -1;

        // ログインした製品の契約終了日がきちんと入っているかどうかを確認する
        $CodeExpires = GetCookie(LoginProduct, f_expires);
        if ($CodeExpires != "") {
            $CodeExpires = GetCookie($code, f_expires);
            if ($CodeExpires != "") {
                $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays);
                $ret = (compareDate($Today, $CodeExpiresLimit) == 1) ? 0 : -2;
            }
            else {
                $ret = -3;
            }
        }
        return $ret;
    }

    // 会員専用のコンテンツを場合分けして表示するためにCookieをチェックします。
    //  Input :code
    //         製品シリアルの先頭4桁、もしくは先頭3桁の配列
    //  Output:1 該当製品が保守契約期間中
    //         0 該当製品のユーザー（保守契約はない、もしくは切れている）
    //        -1 該当製品を持っていない
    //  付記  :ログインしていない状態でも使用される場合が多いので処理には十分注意すること
    function CheckUserStatusByProduct($code) {
        $FlagCodeSerialExpires = false;
        $Today                 = date("Y/m/d");
        $code                  = strtolower(trim($code));
        $ret                   = -1;

        // 製品を問わず、いずれかの製品で保守契約があるかどうかを見る
        if ($code == "all") {
            $cookieArr = GetCookie(LoginProduct);

            if (!empty($cookieArr)) {
                $CodeExpires = $cookieArr[f_expires];
                $CodeSerial  = $cookieArr[f_serialno];

                // 日付がない場合
                if ($CodeExpires == "") {
                    $ret = ($CodeSerial == "") ? -1 : 0;
                } else {
                    // 日付が入っている場合は今日の日付と照らし合わせて表示するか否かを判断する
                    // シリアルは当然入っているものとして処理する
                    $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays); // 保守契約が切れるXX日後
                    $ret = (compareDate($Today, $CodeExpiresLimit) == 1) ? 1 : 0;
                }
            }
        }
        // 製品毎に、保守契約／ユーザーを見る
        else {
            $ArrayCodeExpires = explode(",", $code);

            // ログイン可能製品(ページ毎に指定された値)毎に処理を行います。
            foreach ($ArrayCodeExpires as $ArrayCode) {
                // ログイン対象製品の契約終了日があるかどうかを確認していきます。
                if (strlen($ArrayCode) == 3) {
                    // 4桁目に0から9までそれぞれ追加して、該当するCookieを見ていきます。
                    for ($i = 0; $i <= 9; $i++) {
                        $cookieArr = GetCookie($ArrayCode.$i);
                        if (!empty($cookieArr)) {
                            $CodeExpires3 = $cookieArr[f_expires];
                            $CodeSerial3  = $cookieArr[f_serialno];

                            // 契約終了日の値が入っている
                            if ($CodeExpires3 != "") {
                                $CodeExpires3Limit = DateAdd($CodeExpires3, ExtentionDays);
                                if (compareDate($Today, $CodeExpires3Limit) == 1) {
                                    $ret = 1;
                                    break;
                                } elseif ($CodeSerial3 != "") {
                                    $FlagCodeSerialExpires = true; // 何もしません（値を保持）
                                }
                            } elseif ($CodeSerial3 != "") {
                                $FlagCodeSerialExpires = true;     // 何もしません（値を保持）
                            }
                        }
                        if ($ret == 1) {
                            break;
                        }
                    }
                }
                elseif (strlen($ArrayCode) == 4) {
                    $cookieArr = GetCookie($ArrayCode);
                    if ( !empty($cookieArr) ) {
                        $CodeExpires = $cookieArr[f_expires];
                        $CodeSerial  = $cookieArr[f_serialno];

                        //契約終了日の値が入っている
                        if ($CodeExpires != "") {
                            $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays);
                            if (compareDate($Today, $CodeExpiresLimit) == 1) {
                                $ret = 1;
                                break;
                            }
                            elseif ($CodeSerial != "") {
                                $FlagCodeSerialExpires = true;     // 製品はあるが期限後
                            }
                        }
                        elseif ($CodeSerial != "") {
                            $FlagCodeSerialExpires = true;         // 製品はあるが期限後
                        }
                    }
                }
            }
            if ($ret != 1) {
                $ret = ($FlagCodeSerialExpires == true) ? 0 : -1;
            }
        }
        return $ret;
    }

    // リダイレクトする際のアドレスを記録します。
    // リダイレクトアドレス（現在地点）を記録します。
    function WriteRequestedURL() {
        $cookieArr = GetCookie(sorizo);
        $temp = $_SERVER["REQUEST_URI"];
        $cookieArr[f_url] = ($temp == "") ? "index.php" : $temp;
        UpdateCookie(sorizo, $cookieArr);
    }

    // リダイレクトアドレスがあればそちらに移動します。なければスルーします。
    function ReadRequestedURL() {
        $RequestedURL = GetCookie(sorizo, f_url);
        if ($RequestedURL != "") {
            DeleteRequestedURL();
            header("Location: ".$RequestedURL);
            exit;
        }
    }

    // リダイレクトアドレスを削除します。
    function DeleteRequestedURL() {
        $cookieArr = GetCookie(sorizo);
        $cookieArr[f_url] = "";
        UpdateCookie(sorizo, $cookieArr);
    }

    // Cookieの書き込みや読み込みを含めて
    // ログイン全般に関するツールを集めたインクルードファイルです。
    // 必ず common.php も一緒にインクルードする必要があります。
    function WriteCookies($serial_no) {
        global $PossessedProductsCode;
        $Today = date("Y/m/d");

        // Cookieを消す
        DeleteCookies();

        // シリアルNo.から顧客コード,顧客情報 を取得します。
        $json = '{
                    "sral":{
                        "data":[
                            {"name":"sral_no","value":"'.$serial_no.'","operator":"="}
                        ]
                    }
                }';
        $user = GetAPIData("users", $json, "GET");
        $user_cd = $pref_nm = "";

        if ( empty( $user['users'] ) 
            or GetFirstByField($user, 'error') != '' ) {
            echo "Userがありません。";
            exit;
        }

        $user_cd = $user["users"][0]["user_cd"];
        $pref_nm = $user["users"][0]["pref_nm"];

        $json = '{
                    "prod":{
                        "data":[{"name":"user_cd","value":"'.$user_cd.'","operator":"="}],
                        "fields":"prod_no"
                    },
                    "sral":{
                        "fields":"sral_no"
                    }
                }';
        $prod = GetAPIData("prod", $json, "GET");

        if ( empty( $prod['prod'] ) 
            or GetFirstByField($prod, 'error') != '' ) {
            echo 'userはprodがありません。';
            exit;
        }

        $prod = $prod["prod"];
        foreach ($prod as $aProd) {
            $sral = $aProd["sral"][0]["sral_no"];
            $nm_sral = substr($sral, 0, 4);
        // ↓↓　<2020/08/20> <VinhDao>
            if ( preg_match('/\D+/', $nm_sral) ) {
                continue;
            }
        // ↑↑　<2020/08/20> <VinhDao>

        // ↓↓　<2020/08/31> <VinhDao> <No.2のHP合同PRJ-www_sorizo-運用_20200828>
            if ( in_array($nm_sral, $PossessedProductsCode) ) {
                UpdateCookie($nm_sral, [
                    f_serialno => $sral,
                    f_expires  => "2099/01/01"
                ], 365);
            }
        // ↑↑　<2020/08/31> <VinhDao> <No.2のHP合同PRJ-www_sorizo-運用_20200828>
        }

        if (substr($serial_no, 0, 4) == "1430") {
            $cookieArr = array(f_serialno => $serial_no,
                               f_expires  => "2099/01/01",
                               f_userpref => $pref_nm);
            UpdateCookie(LoginProduct, $cookieArr);
        }

        $json = '{
                    "ky":{
                        "data":[{"name":"user_cd","value":"'.$user_cd.'","operator":"="}]
                    },
                    "ky_prod":{},
                    "ky_his":{}
                }';
        $ky = GetAPIData("ky", $json, "GET");
        if ( empty( $ky['ky'] ) 
            or GetFirstByField($ky, 'error') != '' ) {
            echo 'prodはkyがありません。';
            exit;
        }

        $ky = $ky["ky"];
        $numKy = count($ky);
        for ($i = 0; $i < $numKy; $i++) {
            if (is_array($ky[$i]) && is_array($ky[$i]["ky_prod"]) && is_array($ky[$i]["ky_prod"][0]["sral"])) {
                $sral_no = $ky[$i]["ky_prod"][0]["sral"][0]["sral_no"];
                $numHis = count($ky[$i]["ky_his"]);
                $ky_e_ymd = 0;
                // 保有確認製品の内容にも上書きします。（複数持っている場合に備えて）
                for ($k = 0; $k < $numHis; $k++) {
                    $temp = (is_array($ky[$i]["ky_his"][$k])) ? $ky[$i]["ky_his"][$k]["ky_e_ymd"] : "";
                    if (strtotime($ky_e_ymd) < strtotime($temp)) {
                        $ky_e_ymd = $temp;
                    }
                }
                $codeK = substr($sral_no, 0, 4);
                if (in_array($codeK, $PossessedProductsCode)) {
                    $cookieArr = GetCookie($codeK);
                    if ((count($cookieArr) > 0 && strtotime($cookieArr[f_expires]) < strtotime($ky_e_ymd)) || empty($cookieArr)) {
                        $cookieArr[f_serialno] = $sral_no;
                        $cookieArr[f_expires]  = (is_null($ky_e_ymd)) ? "" : $ky_e_ymd;
                        UpdateCookie(substr($sral_no, 0, 4), $cookieArr, CookiesSaveDays);
                    }
                }
                if ($sral_no == $serial_no) {
                    $cookieArr = array(f_serialno => $serial_no,
                                       f_expires  => $ky_e_ymd,
                                       f_userpref => $pref_nm);
                    UpdateCookie(LoginProduct, $cookieArr, CookiesSaveDays);
                }
            }
        }
        // ログインした際にサーバー側のCSVにログを書き込みます。
        WriteLog();
    }

// ↓↓　<2020/06/08> <VinhDao> <function「compareDate」を追加します。>
    if ( !function_exists('compareDate') ) {
        /**
            * Compare Date
            @param datetime $date1
            @param datetime $date2
            @return int -1: $date1 > $date2
                        0: $date1 = $date2
                        1: $date1 < $date2)
        **/

        function compareDate( $date1, $date2 ) {
            $date1 = date_create( $date1 );
            $date2 = date_create( $date2 );
            $diff = date_diff($date1, $date2);
            $diff = (int)$diff->format("%R%a");
            if ( $diff > 0 ) {
                return 1;
            }
            elseif ($diff == 0) {
                return 0;
            }
            else {
                return -1;
            }
        }
    }
// ↑↑　<2020/06/08> <VinhDao> <function「compareDate」を追加します。>
?>