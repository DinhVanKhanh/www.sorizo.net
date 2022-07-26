<?php
    require_once __DIR__ . '/login.php';
    define ("CL_ForReading", 1);

    global $CL_h_cnt;
    $CL_h_cnt = 0;
    global $CL_page_cnt;
    $CL_page_cnt = 5000;
    global $CL_page;
    $CL_page = 0;
    global $CL_page_e;
    global $CL_page_s;
    // 2020/01/14 t.maruyama 修正 ↓↓ PHP Notice: Undefined index が発生していたので修正
    //if (@$_POST["CL_page"] != 0) {
    if (isset($_POST["CL_page"]) && @$_POST["CL_page"] != 0) {
    // 2020/01/14 t.maruyama 修正 ↑↑
        $CL_page = @$_POST["CL_page"] + 1;
        $CL_page_e = $CL_page_cnt * $CL_page;
        $CL_page_s = $CL_page_e - $CL_page_cnt;
    }
    else {
        $CL_page_e = $CL_page_cnt;
        $CL_page_s = 0;
        $CL_page = 1;
    }

    // シリアルの上４桁とCSVの列の関係性を指定してください。
    function MenuCode($ipcode) {
        switch ($ipcode) {
            case "1013":        // 農業簿記9
                return 13;
            case "1014":        // 農業簿記10
                return 14;
            case "1015":        // 農業簿記11
                return 15;
            case "1020":        // 農作業日誌Ver6
                return 20;
            case "1021":        // 農業日誌V6
                return 21;
            case "1053":        // 簿記9JAバージョン
                return 16;
            case "1054":        // 簿記10JAバージョン
                return 17;
            case "1055":        // 簿記11JAバージョン
                return 18;
            case "1430":        // JA接続キット
                return 19;
        }
    }

    // カテゴリー別トップページ用のコンテンツ一覧を作成
    // 呼出 ReadCLHeaderTitle（CSVから必要な情報を取り出す）
    // 呼出 WriteCLHeaderTitle（取り出した情報をそれぞれHTMLの体裁にする）
    // 呼出 ReplaceCSVText（シリアルNoなど埋め込みが必要な情報の置き換え）
    function GetContentsList($Format, $CategoryNo) {
        global $CL_h_cnt;
        global $CL_page_e;
        global $CL_page_s;

        $listData = ReadCLTitle($CategoryNo);
        foreach ($listData as $aLine) {
            if (is_array($aLine)) {
                if ($CL_h_cnt >= $CL_page_e) {
                    break;
                }
                if (count($aLine) > 0) {
                    $CL_h_cnt++;
    // 2020/01/10 t.maruyama 修正 ↓↓ エラー修正
    //                if ($CL_h_cnt >= $NR_page_s) {
                    if ($CL_h_cnt >= $CL_page_s) {
    // 2020/01/10 t.maruyama 修正 ↑↑
                        if ($Format == "footer") {
                            WriteCLFooterTitle($aLine);
                        }
                        elseif ($Format == "category") {
                            WriteCLCategoryTitle($CategoryNo, $aLine);
                        }
                    }
                }
            }
        }
    }

    // カテゴリートップページ用のコンテンツ一覧を作成
    // CSV読み込み
    // GetContentsList($Format, $CategoryNo) から呼び出される
    function ReadCLTitle($CategoryNo) {
        $Today = date("Y/m/d");
        $Conn = ConnectSorizo();
        $sql = "SELECT * FROM SoriContents_List";
        $result = mysqli_query($Conn, $sql);
        $arrRet = array();

        // ログインしていない状態の場合
        if (GetLoginSerial() == "") {
            while ($res = mysqli_fetch_assoc($result)) {
                if ($res["公開フラグ"] == 0 &&
                    $res["FREE"] != "" &&
                    $res["カテゴリーNo."] == $CategoryNo) {
                    $res["Privilege"] = $res["FREE"];
                    $arrRet[] = $res;
                }
            }
        }
        // ログインしている状態の場合
        else {
            global $IntendedProductsCode;
            while ($res = mysqli_fetch_array($result)) {
                if ($res["公開フラグ"] == 0) {
                    // 保有確認製品かどうかを配列IntendedProductsCodeごとに判別していきます。それぞれの値をIPCodeとして処理しています。
                    foreach ($IntendedProductsCode as $IPCode) {
                        // 開発用デモシリアルを除いた際の処理
                        $ContentsHandling = "";
                        if (substr($IPCode, 0, 1) != "s") {
                            // IPCode毎の契約終了期間をCookieから取得
                            $CodeExpires = GetCookie($IPCode, f_expires);
                            // Cookieから何らかの日付を取得できた場合
                            if ($CodeExpires != "") {
                                $CodeExpiresLimit = DateAdd($CodeExpires, ExtentionDays);
                                // 契約終了日＋予備日を過ぎていないかどうかを確認し、OKの場合
                                if (compareDate($Today, $CodeExpiresLimit) == 1) {
                                    // コンテンツがカテゴリー番号に合致しているかどうかを確認
                                    if ($res["カテゴリーNo."] == $CategoryNo) {
                                        // 契約期間中のIPCodeに対応する列が「OK」かどうかを確認
                                        $code = $res[MenuCode($IPCode)];
                                        // onlineとnormalの分岐を追加
                                        $ContentsHandling = (
                                            $code == "ok" || $code == "alert" || $code == "show" || $code == "normal" || $code == "online"
                                        ) ? $code : "";
                                    }
                                    if ($ContentsHandling != "") {
                                        if (($code == "online" || $code == "normal") && isOnlineContents($IPCode, $code)) {
                                            $res["Privilege"] = $ContentsHandling;
                                            $arrRet[] = $res;
                                        } else if ($code == "ok" || $code == "alert" || $code == "show") {
                                            $res["Privilege"] = $ContentsHandling;
                                            $arrRet[] = $res;
                                        }
                                    }
                                    if ($ContentsHandling == "ok" || isOnlineContents($IPCode, $code)) {
                                        // OKなら無条件で表示できるのでここで抜ける
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        mysqli_free_result($result);
        mysqli_close($Conn);
        return $arrRet;
    }

    // オンラインソリクラ対応
    function isOnlineContents($IPCode, $code) {
        $loginSerial = substr(GetLoginSerial(), 0, 4);

        // ログインに利用したシリアルNoに付随する情報を利用する
        if ($loginSerial !== $IPCode) return false;

        /**
         * 引数
         * online:オンラインソリクラユーザーのみに表示
         * normal:通常ソリクラユーザーのみに表示
         */
        return isLoginSerialOnlineNormal($code);
    }

    // ヘッダー用のコンテンツ一覧を作成
    // HTML部分のはき出し
    // ContentsListFooter(CategoryNo) から呼び出される
    function WriteCLFooterTitle($arrLine) {
        $CLTargetWindow = ($arrLine["別ウィンドウ区分"] == "1") ? " target='_blank'" : "";
        $shortTitle = str_replace("\n", "&nbsp;", $arrLine["短縮タイトル"]);
        switch ($arrLine["Privilege"]) {
            case "ok":
            case "online":
            case "normal":
                echo "<dd><a href='".((strpos($arrLine["リンク先URL"], "www") !== false) ? $arrLine["リンク先URL"] : str_replace("asp", "php", $arrLine["リンク先URL"]))."'".$CLTargetWindow.">".$shortTitle."</a></dd>";
                break;
            case "alert":
                echo "<dd><a href='javascript:alert(".'"'.$arrLine["アラートメッセージ"].'"'.");'>".$shortTitle."</a></dd>";
                break;
            case "show":
                echo "<dd>".$shortTitle."</dd>";
                break;
        }
    }

    // ヘッダー用のコンテンツ一覧を作成
    // HTML部分のはき出し
    // ContentsListFooter(CategoryNo) から呼び出される
    function WriteCLCategoryTitle($CategoryNo, $arrLine) {
        $CategoryClass = "";
        switch ($CategoryNo) {
            case 1:
                $CategoryClass = "community";
                break;
            case 2:
                $CategoryClass = "support";
                break;
            case 3:
                $CategoryClass = "program";
                break;
            case 4:
                $CategoryClass = "service";
                break;
        }
        $shortTitle = $arrLine["短縮タイトル"];
        $CLTargetWindow = ($arrLine["別ウィンドウ区分"] == 1) ? " target='_blank'" : "";
        if ($arrLine["TOP非表示"] != 1) {
    ?>
            <section class="info <?= $CategoryClass ?>">
                <div class="box-mm">
                    <h3><?= str_replace("\n", "<br>", $arrLine["正式タイトル"]) ?></h3>
                    <p><?= str_replace("\n", "</p><p>", $arrLine["解説"]) ?></p>
                    <ul class="boxlink">
    <?php
            if ($arrLine["Privilege"] == "ok" || $arrLine["Privilege"] == "online" || $arrLine["Privilege"] == "normal") {
                echo '<li><a href="'.((strpos($arrLine["リンク先URL"], "www") !== false) ? $arrLine["リンク先URL"] : str_replace("asp", "php", $arrLine["リンク先URL"])).'" onfocus="this.blur()" '.$CLTargetWindow.'><span>'.$shortTitle.'</span></a></li>';
            }
            elseif ($arrLine["Privilege"] == "alert") {
                echo "<li><a href='javascript:alert(".'"'.$arrLine["アラートメッセージ"].'"'.");'><span>".$shortTitle."</span></a></li>";
            }
    ?>
                    </ul>
                </div>
            </section>
    <?php
        }
    }
?>