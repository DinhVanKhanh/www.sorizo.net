<?php
    if (session_id() == '') {
        session_start();
    }
    require_once '../../../lib/login.php';
    require_once '../../../lib/common.php';

    global $LogFileDirectory;
    global $SP_DOWNLOAD_SERVER;
    global $SP_DOWNLOAD_SERVER_AWS;
    global $ListDL;

    $dir = @$_GET["dir"];
    $f = @$_GET["f"];
    $TargetUser = @$_GET["target"];
    $TopPageClass = @$_SESSION["TopPageClass"];
    $isManual = (@$_GET["option"] == "manual") ? true : false;

    if (prmCheckLocal($dir)) {
        exit;
    }
    if (prmCheckLocal($f)) {
        exit;
    }
    if (prmCheckLocal($TargetUser)) {
        exit;
    }

    $hasTarget = ($TargetUser != "" || $TopPageClass != "") ? true : false;
    $FileName = $LogFileDirectory . "www_sorizo/download/download_log_softdown.txt";

    if (!is_array($ListDL)) {
        $ListDL = array("gbk01"     => array("series"   => "1_00_00",
                                             "name"     => "漁業簿記V1",
                                             "redirect" => "download_files/FBK1Update_060208.EXE",
                                             "manual"   => "download_files/FBK1Update_060208.pdf",
                                             "SP"       => "SP"),
                        "gbk02_201" => array("series"   => "2_01_00",
                                             "name"     => "漁業簿記2",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/fbk2sp2036080.exe",
                                             "manual"   => "download_files/fsh_boki2_update.pdf",
                                             "SP"       => "SP"),
                        "ja07_703"  => array("series"   => "JA7_03_00",
                                             "name"     => "農業簿記7JAバージョン",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/ja7sp7196080.exe",
                                             "manual"   => "download_files/boki7jaupdate.pdf",
                                             "SP"       => "SP"),
                        "ja07_705"  => array("series"   => "JA7_05_xx",
                                             "name"     => "農業簿記7JAバージョン",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/ja7sp7291190.exe",
                                             "manual"   => "download_files/bokija705update.pdf",
                                             "SP"       => "SP-7291190"),
                        "ja09_0902" => array("series"   => "9_02_00",
                                             "name"     => "農業簿記9JA",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag9/ja9sp9056041.exe",
                                             "manual"   => "download_files/manual_install_ja0902.pdf",
                                             "SP"       => "SP-9056041"),
                        "ja09_0904" => array("series"   => "9_04_00",
                                             "name"     => "農業簿記9JA",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag9/bk9sp9068051.exe",
                                             "manual"   => "download_files/manual_install_ja0904.pdf",
                                             "SP"       => "SP-9068051"),
                        "ja10_1001" => array("series"   => "10_01_00",
                                             "name"     => "農業簿記10JA",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag10/ja10sp0222091.exe",
                                             "manual"   => "download_files/manual_install_ja1001.pdf",
                                             "SP"       => "SP-0222091"),
                        "ja10_1002" => array("series"   => "10_02_00",
                                             "name"     => "農業簿記10JA",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag10/ja10sp0264091.exe",
                                             "manual"   => "download_files/manual_install_ja1002.pdf",
                                             "SP"       => "SP-0264091"),
                    // ↓↓　<2020/05/08> <VinhDao> <農業簿記11JA>
                        "ja11_1100" => array("series"  => "11_00_00",
                                            "name"     => "農業簿記11JA",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/ja11sp1042191.exe",
                                            "manual"   => "download_files/manual_install_ja1100.pdf",
                                            "SP"       => "SP-1042191"),
                        "ja11_1101" => array("series"   => "11_01_00",
                                            "name"     => "農業簿記11JA",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/ja11sp1152102.exe",
                                            "manual"   => "download_files/manual_install_ja1101.pdf",
                                            "SP"       => "SP-1152102"),
                    // ↑↑　<2020/05/08> <VinhDao> <農業簿記11JA>
                        // ↓Kentaro.Watanabe add; 2020/12/23
                        "ja11_1102" => array("series"   => "11_02_00",
                                            "name"     => "農業簿記11JA",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/ja11sp1192012.exe",
                                            "manual"   => "download_files/manual_install_ja1102.pdf",
                                            "SP"       => "SP-1192012"),
                        // ↑Kentaro.Watanabe add; 2020/12/23
                        "kumikanv2_200" => array("series" => "KUM2SP017021.exe",
                                             "name"     => "クミカンV2",
                                             "redirect" => "download_files/KUM2SP017021.exe",
                                             "SP"       => "Ver2-02SP"),
                        "lv_kumikanv203" => array("series" => "KUM2SP031031.exe",
                                             "name"     => "クミカンV2",
                                             "redirect" => "download_files/KUM2SP031031.exe",
                                             "SP"       => "Ver2-03LVUP"),
                        "lv_ren110" => array("series"   => "RENSP011031.exe",
                                             "name"     => "れん太郎",
                                             "redirect" => "download_files/RENSP011031.exe",
                                             "manual"   => "download_files/rentarou_operationmanual.pdf",
                                             "SP"       => ($isManual) ? "操作" : "Ver1-10LVUP"),
                        "nbk05p_516" => array("series"  => "5_16_00",
                                             "name"     => "農業経営簿記Ver.5プラス",
                                             "redirect" => "download_files/BK516Update5.EXE",
                                             "manual"   => "download_files/BK516Update.pdf",
                                             "SP"       => "SP"),
                        "nbk06_602" => array("series"   => "6_02_00",
                                             "name"     => "農業簿記V6",
                                             "redirect" => "download_files/BK6Update02_0308.EXE",
                                             "manual"   => "download_files/bk6update02.pdf",
                                             "SP"       => "SP"),
                        "nbk06_603" => array("series"   => "6_03_00",
                                             "name"     => "農業簿記V6",
                                             "redirect" => "download_files/BK6Update03_0731.EXE",
                                             "manual"   => "download_files/bk6update03.pdf",
                                             "SP"       => "SP"),
                        "nbk07_703" => array("series"   => "7_03_00",
                                             "name"     => "農業簿記7",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS."ag7/bk7sp7186080.exe",
                                             "manual"   => "download_files/boki7update.pdf",
                                             "SP"       => "SP"),
                        "nbk09_0901" => array("series"  => "9_01_00",
                                             "name"     => "農業簿記9",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag9/bk9sp9042041.exe",
                                             "manual"   => "download_files/manual_install_nbk0901.pdf",
                                             "SP"       => "SP-9042041"),
                        "nbk09_0902" => array("series"  => "9_02_00",
                                             "name"     => "農業簿記9",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag9/bk9sp9056041.exe",
                                             "manual"   => "download_files/manual_install_nbk0902.pdf",
                                             "SP"       => "SP-9056041"),
                        "nbk09_0903" => array("series"  => "9_03_00",
                                             "name"     => "農業簿記9",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag9/bk9sp9068051.exe",
                                             "manual"   => "download_files/manual_install_nbk0903.pdf",
                                             "SP"       => "SP-9068051"),
                        "nbk10_1000" => array("series"  => "10_00_00",
                                             "name"     => "農業簿記10",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag10/bk10sp0302181.exe",
                                             "manual"   => "download_files/manual_install_nbk1000.pdf",
                                             "SP"       => "SP-0302181"),
                        "nbk10_1001" => array("series"  => "10_01_00",
                                             "name"     => "農業簿記10",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag10/bk10sp0332091.exe",
                                             "manual"   => "download_files/manual_install_nbk1001.pdf",
                                             "SP"       => "SP-0332091"),
                        "nbk10_1002" => array("series"  => "10_02_00",
                                             "name"     => "農業簿記10",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag10/bk10sp0374091.exe",
                                             "manual"   => "download_files/manual_install_nbk1002.pdf",
                                             "SP"       => "SP-0374091"),
// 2020/03/02 t.maruyama 修正 ↓↓ 農業簿記11ダウンロード対応
                        "nbk11_1100" => array("series"  => "11_00_00",
                                            "name"     => "農業簿記11",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/bk11sp1082191.exe",
                                            "manual"   => "download_files/manual_install_nbk1100.pdf",
                                            "SP"       => "SP-1082191"),
                    // ↓↓　<2020/05/08> <VinhDao> <nbk11_1101を変更する>
                        "nbk11_1101" => array("series"  => "11_01_00",
                                            "name"     => "農業簿記11",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/bk11sp1312102.exe",
                                            "manual"   => "download_files/manual_install_nbk1101.pdf",
                                            "SP"       => "SP-1312102"),
                        // "nbk11_1101" => array("series"  => "11_01_00",
                        //                     "name"     => "農業簿記11",
                        //                     "redirect" => $SP_DOWNLOAD_SERVER_AWS."ag11/bk11sp1121002.exe",
                        //                     "manual"   => "download_files/manual_install_nbk1101.pdf",
                        //                     "SP"       => "SP-1121002"),
                    // ↑↑　<2020/05/08> <VinhDao> <nbk11_1101を変更する>
// 2020/03/02 t.maruyama 修正 ↑↑ 農業簿記11ダウンロード対応
                        // ↓Kentaro.Watanabe add; 2020/12/23
                        "nbk11_1102" => array("series"  => "11_02_00",
                                            "name"     => "農業簿記11",
                                            "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag11/bk11sp1352012.exe",
                                            "manual"   => "download_files/manual_install_nbk1102.pdf",
                                            "SP"       => "SP-1352012"),
                        // ↑Kentaro.Watanabe add; 2020/12/23
                        "nbk10_ja_ot" => array("series" => "10_00_00",
                                             "name"     => "接続キット-JA大分版",
                                             "redirect" => "download_files_ot/BK10spKit.exe",
                                             "manual"   => "download_files_ot/manual_nbk1002_jakit.pdf",
                                             "SP"       => "PRG(接続キット)"),
                    // ↓↓　<2020/05/08> <VinhDao> <接続キット-JA愛知県版>
                        "nbk11_ja_ac" => array("series" => "11_00_00",
                                            "name"     => "接続キット-JA愛知県版",
                                            "redirect" => "download_files_ac/BK11spKit.exe",
                                            "manual"   => "download_files_ac/manual_nbk_jakit.pdf",
                                            "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/05/08> <VinhDao> <接続キット-JA愛知県版>
                    // ↓↓　<2020/05/08> <VinhDao> <接続キット-JA広島県版>
                        "nbk11_ja_hs" => array("series" => "11_00_00",
                                            "name"     => "接続キット-JA広島県版",
                                            "redirect" => "download_files_hs/BK11spKit.exe",
                                            "manual"   => "download_files_hs/manual_nbk_jakit.pdf",
                                            "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/05/08> <VinhDao> <接続キット-JA広島県版>
                    // ↓↓　<2020/05/08> <VinhDao> <接続キット-JA大分県版>
                        "nbk11_ja_ot" => array("series" => "11_00_00",
                                             "name"     => "接続キット-JA大分版",
                                             "redirect" => "download_files_ot/BK11spKit.exe",
                                             "manual"   => "download_files_ot/manual_nbk_jakit.pdf",
                                             "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/05/08> <VinhDao> <接続キット-JA大分県版>
                    // ↓↓　<2020/05/08> <VinhDao> <接続キット-JA山形県版>
                        "nbk11_ja_ym" => array("series" => "11_01_00",
                                        "name"     => "接続キット-JA山形県版",
                                        "redirect" => "download_files_ym/BK11spKit.exe",
                                        "manual"   => "download_files_ym/manual_nbk_jakit.pdf",
                                        "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/05/08> <VinhDao> <接続キット-JA山形県版>
                    // ↓↓　<2020/10/22> <Kentaro.Watanabe> <接続キット-JA長野県版>
                        "nbk11_ja_nn" => array("series" => "11_01_00",
                                        "name"     => "接続キット-JA長野県版",
                                        "redirect" => "download_files_nn/BK11spKit.exe",
                                        "manual"   => "download_files_nn/manual_nbk_jakit.pdf",
                                        "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/10/22> <Kentaro.Watanabe> <接続キット-JA長野県版>
                    // ↓↓　<2020/05/08> <VinhDao> <農業簿記11-JA北海道版>
                        "nbk11_jahkd" => array("series" => "11_01_00",
                                        "name"     => "農業簿記11-JA北海道版",
                                        "redirect" => "download_files/BK11spKit.exe",
                                        "manual"   => "download_files/manual_nbk_jahkd.pdf",
                                        "SP"       => "PRG(接続キット)"),
                    // ↑↑　<2020/05/08> <VinhDao> <農業簿記11-JA北海道版>
                        "nbk10_jahkd" => array("series" => "10_00_00",
                                             "name"     => "農業簿記10-JA北海道版",
                                             "redirect" => "download_files/BK10spKit.exe",
                                             "manual"   => "download_files/manual_nbk1000_jahkd.pdf"),
                        "nns06"     => array("series"   => "6_00_00",
                                             "name"     => "農作業日誌V6",
                                             "redirect" => "download_files/NS6Update.EXE",
                                             "manual"   => "download_files/NS6update.pdf",
                                             "SP"       => "SP"),
                        "nns06p"    => array("series"   => "6_50_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => "download_files/NS6PUpdate16.EXE",
                                             "manual"   => "download_files/nisshiV6plusupdate.pdf",
                                             "SP"       => "SP"),
                        "nns06p_653" => array("series"  => "6_53_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => "download_files/NS6P53Update1.EXE",
                                             "manual"   => "download_files/nisshiV6plusupdate653.pdf",
                                             "SP"       => "SP"),
                        "nns06p_655" => array("series"  => "6_55_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/ns6p55update6.exe",
                                             "manual"   => "pdf/ns6p55update6.pdf",
                                             "SP"       => "SP"),
                        "nns06p_660" => array("series"  => "6_60_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/ns6p044150.exe",
                                             "manual"   => "pdf/ns6p044150.pdf",
                                             "SP"       => "SP"),
                        "nns06p_670" => array("series"  => "6_70_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/ns6p054160.exe",
                                             "manual"   => "pdf/ns6p054160.pdf",
                                             "SP"       => "SP"),
                        "nns06p_680" => array("series"  => "6_80_00",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => $SP_DOWNLOAD_SERVER_AWS . "ag7/NS6P102260.EXE",
                                             "manual"   => "pdf/NS6P102260.pdf",
                                             "SP"       => "SP"),
                        "nns06p_mobile" => array("series" => "MobileNissiUpdate",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => "download_files/MobileNissiUpdate.exe",
                                             "manual"   => "",
                                             "SP"       => "モバイル作業日誌対応SP"),
// 2020/03/03 t.maruyama 修正 ↓↓ 不具合修正
//                        "nns06p_sagawa" => array("series" => "03211102",
//                                            "name"     => "農業日誌V6プラス",
//                                            "redirect" => "download_files/NS6PPCUP03211102.exe",
//                                            "SP"       => "郵便番号辞書SP"),

                        "nns06p_sagawa" => array("series" => "NS6PSTUP024011",
                                            "name"     => "農業日誌V6プラス",
                                            "redirect" => "download_files/ns6pstup024011.exe",
                                            "SP"       => "佐川急便送り状書式アップデート"),
// 2020/03/03 t.maruyama 修正 ↑↑ 不具合修正
                        "ren_nbk08_renkeiop" => array("series" => "1_05_00",
                                             "name"     => "れん太郎連携オプション（農業簿記8対応版）",
                                             "redirect" => "download_files/setup.exe",
                                             "manual"   => "download_files/nbk08_rnt_renkeiop.pdf",
                                             "SP"       => "SP"),
                        "ren105"    => array("series"   => "1.05.00",
                                             "name"     => "ソリマチれん太郎",
                                             "redirect" => "download_files/RenUpdate06_0227.EXE",
                                             "manual"   => "download_files/RenUpdate06.pdf",
                                             "SP"       => "SP"),
                        "ren107"    => array("series"   => "1.07.00",
                                             "name"     => "ソリマチれん太郎",
                                             "redirect" => "download_files/RenUpdate07_0227.EXE",
                                             "manual"   => "download_files/RenUpdate07.pdf",
                                             "SP"       => "SP"),
                        "postalcode" => array("series"  => "NS6PPCUP03211102.exe",
                                             "name"     => "農業日誌V6プラス",
                                             "redirect" => "download_files/NS6PPCUP03211102.exe",
                                             "SP"       => "郵便番号辞書SP")
                  );
    }
    if (!is_array($ListDL[$dir])) {
        $ListDL[$dir] = array();
    }
    if ($dir == "nbk06") {
        $ListDL[$dir]["name"] = "農業簿記V6";
        switch ($f) {
            case "prg1":
                $ListDL[$dir]["series"] = "6_00_00";
                $ListDL[$dir]["redirect"] = "download_files/BK6Update12.EXE";
                $ListDL[$dir]["SP"] = "SP";
                break;
            case "prg2":
                $ListDL[$dir]["series"] = "6_01_00";
                $ListDL[$dir]["redirect"] = "download_files/BK6Update31.EXE";
                $ListDL[$dir]["SP"] = "SP";
                break;
            case "m1":
                $ListDL[$dir]["series"] = "6_00_00";
                $ListDL[$dir]["redirect"] = "download_files/bokiV6update12.pdf";
                $ListDL[$dir]["SP"] = "マニュアル(SP)";
                break;
            case "m2":
                $ListDL[$dir]["series"] = "6_00_00";
                $ListDL[$dir]["redirect"] = "download_files/bokiV6update.pdf";
                $ListDL[$dir]["SP"] = "詳細マニュアル";
                break;
            case "m3":
                $ListDL[$dir]["series"] = "6_01_00";
                $ListDL[$dir]["redirect"] = "download_files/bokiV6update31.pdf";
                $ListDL[$dir]["SP"] = "マニュアル(SP)";
                break;
        }
    }
    if (strpos($dir, "nbk08_080") !== false) {
        $ListDL[$dir]["name"] = "農業簿記8";
        switch ($f) {
            case "prg1":
                $ListDL[$dir]["series"] = "8_02_00";
                $ListDL[$dir]["redirect"] = "http://www.sorimachi.on.arena.ne.jp/sp/ag8/bk8sp8144021.exe";
                $ListDL[$dir]["SP"] = "SP-8144021";
                break;
            case "prg2":
                $ListDL[$dir]["series"] = "8_01_01";
                $ListDL[$dir]["redirect"] = "http://www.sorimachi.on.arena.ne.jp/sp/ag8/bk8sp8154021.exe";
                $ListDL[$dir]["SP"] = "SP-8154021";
                break;
            case "prg3":
                $ListDL[$dir]["series"] = "8_00_00";
                $ListDL[$dir]["redirect"] = "http://www.sorimachi.on.arena.ne.jp/sp/ag8/bk8sp8164021.exe";
                $ListDL[$dir]["SP"] = "SP-8164021";
                break;
            case "mn1":
                $ListDL[$dir]["series"] = "8_02_00";
                $ListDL[$dir]["redirect"] = "download_files/manual_install_nbk0802.pdf";
                $ListDL[$dir]["SP"] = "マニュアル(SP)";
                break;
            case "mn2":
                $ListDL[$dir]["series"] = "8_01_01";
                $ListDL[$dir]["redirect"] = "download_files/manual_install_nbk0801.pdf";
                $ListDL[$dir]["SP"] = "マニュアル(SP)";
                break;
            case "mn3":
                $ListDL[$dir]["series"] = "8_00_00";
                $ListDL[$dir]["redirect"] = "download_files/manual_install_nbk0800.pdf";
                $ListDL[$dir]["SP"] = "マニュアル(SP)";
                break;
            case "mn4":
                $ListDL[$dir]["series"] = "8_02_00";
                $ListDL[$dir]["redirect"] = "download_files/manual_add_h23kaisei.pdf";
                $ListDL[$dir]["SP"] = "平成23年度追加マニュアル";
                break;
        }

        if ($isManual) {
            $ListDL[$dir]["series"] = "8_00_00";
            $ListDL[$dir]["SP"] = "SP3";
            $ListDL[$dir]["manual"] = ($TargetUser == "member") ? "nbk0800update_m.pdf" : (($TargetUser == "all" || $TargetUser == "") ? "nbk0800update_a.pdf" : "");
        }
    }
    if ($dir == "nbk10_ja_ot" || $dir == "nbk10_jahkd") {
        $FileName = $LogFileDirectory . "www_sorizo/download/download_log_softdown_" . str_replace("nbk10_", "", $dir) . ".txt";
        $ListDL[$dir]["SP"] = ($isManual) ? "" : "PRG(接続キット)";
    }

    if ($dir == "tatsujin_ja08_renkeiop") {
        $ListDL[$dir]["series"] = "8_00_00";
        $ListDL[$dir]["SP"] = ($isManual) ? "" : "プログラム";
        switch ($f) {
            case "prg1":
            case "m1":
                $ListDL[$dir]["name"] = "法人税の達人（平成22年度版） from 農業簿記8JAバージョン";
                $ListDL[$dir]["redirect"] = "download_files/HJ22Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ22Setup_Manual.pdf";
                break;
            case "prg2":
            case "m2":
                $ListDL[$dir]["name"] = "法人税の達人 from 農業簿記8JAバージョン(減価償却)";
                $ListDL[$dir]["redirect"] = "download_files/HJ21Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ21Setup_Manual.pdf";
                break;
            case "prg3":
            case "m3":
                $ListDL[$dir]["name"] = "内訳概況書の達人 from 農業簿記8JAバージョン(内訳書)";
                $ListDL[$dir]["redirect"] = "download_files/UG13Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG13Setup_Manual.pdf";
                break;
            case "prg4":
            case "m4":
                $ListDL[$dir]["name"] = "内訳概況書の達人（平成16年度以降用） from 農業簿記8JAバージョン(概況書)";
                $ListDL[$dir]["redirect"] = "download_files/UG16Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG16Setup_Manual.pdf";
                break;
        }
        goto Proccess;
    }

    if ($dir == "tatsujin_ja09_renkeiop") {
        $ListDL[$dir]["series"] = "JA9_00_00";
        $ListDL[$dir]["SP"] = ($isManual) ? "" : "プログラム";
        switch ($f) {
            case "prg1":
            case "m1":
                $ListDL[$dir]["name"] = "法人税の達人（平成22年度版） from 農業簿記9JAバージョン";
                $ListDL[$dir]["redirect"] = "download_files/HJ22JA9Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ22JA9Setup_Manual.pdf";
                break;
            case "prg2":
            case "m2":
                $ListDL[$dir]["name"] = "法人税の達人 from 農業簿記9JAバージョン(減価償却)";
                $ListDL[$dir]["redirect"] = "download_files/HJ21JA9Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ21JA9Setup_Manual.pdf";
                break;
            case "prg3":
            case "m3":
                $ListDL[$dir]["name"] = "内訳概況書の達人 from 農業簿記9JAバージョン(内訳書)";
                $ListDL[$dir]["redirect"] = "download_files/UG13JA9Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG13JA9Setup_Manual.pdf";
                break;
            case "prg4":
            case "m4":
                $ListDL[$dir]["name"] = "内訳概況書の達人（平成16年度以降用） from 農業簿記9JAバージョン(概況書)";
                $ListDL[$dir]["redirect"] = "download_files/UG16JA9Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG16JA9Setup_Manual.pdf";
                break;
        }
        goto Proccess;
    }

    if ($dir == "tatsujin_ja10_renkeiop") {
        $ListDL[$dir]["series"] = "JA10_00_00";
        $ListDL[$dir]["SP"] = ($isManual) ? "" : "プログラム";
        switch ($f) {
            case "prg1":
            case "m1":
                $ListDL[$dir]["name"] = "法人税の達人（平成22年度版） from 農業簿記10JAバージョン";
                $ListDL[$dir]["redirect"] = "download_files/HJ21JA10Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ21JA10Setup_Manual.pdf";
                break;
            case "prg2":
            case "m2":
                $ListDL[$dir]["name"] = "法人税の達人 from 農業簿記10JAバージョン(減価償却)";
                $ListDL[$dir]["redirect"] = "download_files/HJ19JA10Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/HJ19JA10Setup_Manual.pdf";
                break;
            case "prg3":
            case "m3":
                $ListDL[$dir]["name"] = "内訳概況書の達人 from 農業簿記10JAバージョン(内訳書)";
                $ListDL[$dir]["redirect"] = "download_files/UG13JA10Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG13JA10Setup_Manual.pdf";
                break;
            case "prg4":
            case "m4":
                $ListDL[$dir]["name"] = "内訳概況書の達人（平成16年度以降用） from 農業簿記10JAバージョン(概況書)";
                $ListDL[$dir]["redirect"] = "download_files/UG16JA10Setup.exe";
                $ListDL[$dir]["manual"] = "download_files/UG16JA10Setup_Manual.pdf";
                break;
        }
        goto Proccess;
    }

// ↓↓　<2020/08/04> <VinhDao> <tatsujin_ja11_renkeiop>
    if ($dir == "tatsujin_ja11_renkeiop") {
        $ListDL[$dir]["series"] = "JA11_00_00";
        $ListDL[$dir]["SP"] = ($isManual) ? "" : "プログラム";
        switch ($f) {
            case "prg1":
            case "m1":
                $ListDL[$dir]["name"]     = "法人税の達人（平成22年度版） from 農業簿記11JAバージョン";
                $ListDL[$dir]["redirect"] = "download_files/JA11HJ21Setup.exe";
                $ListDL[$dir]["manual"]   = "download_files/JA11HJ21Setup_Manual.pdf";
                break;
            case "prg2":
            case "m2":
                $ListDL[$dir]["name"]     = "法人税の達人 from 農業簿記11JAバージョン(減価償却)";
                $ListDL[$dir]["redirect"] = "download_files/JA11HJ19Setup.exe";
                $ListDL[$dir]["manual"]   = "download_files/JA11HJ19Setup_Manual.pdf";
                break;
            case "prg3":
            case "m3":
                $ListDL[$dir]["name"]     = "内訳概況書の達人 from 農業簿記11JAバージョン(内訳書)";
                $ListDL[$dir]["redirect"] = "download_files/JA11UG13Setup.exe";
                $ListDL[$dir]["manual"]   = "download_files/JA11UG13Setup_Manual.pdf";
                break;
            case "prg4":
            case "m4":
                $ListDL[$dir]["name"]     = "内訳概況書の達人（平成16年度以降用） from 農業簿記11JAバージョン(概況書)";
                $ListDL[$dir]["redirect"] = "download_files/JA11UG16Setup.exe";
                $ListDL[$dir]["manual"]   = "download_files/JA11UG16Setup_Manual.pdf";
                break;
        }
        goto Proccess;
    }
// ↑↑　<2020/08/04> <VinhDao> <tatsujin_ja11_renkeiop>

    // if (strpos($dir, "tatsujin_") !== false) {
    //     $ListDL[$dir]["series"] = "JA10_00_00";
    //     $ListDL[$dir]["SP"] = ($isManual) ? "" : "プログラム";
    //     $num = $str = "";
    //     switch ($dir) {
    //         case "tatsujin_ja08_renkeiop":
    //             $str = "";
    //             $num = 8;
    //             break;
    //         case "tatsujin_ja09_renkeiop":
    //             $str = "JA9";
    //             $num = 9;
    //             break;
    //         case "tatsujin_ja10_renkeiop":
    //             $str = "JA10";
    //             $num = 10;
    //             break;
    //     }
    //     switch ($f) {
    //         case "prg1":
    //         case "m1":
    //             $ListDL[$dir]["name"] = "法人税の達人（平成22年度版） from 農業簿記".$num."JAバージョン";
    //             $ListDL[$dir]["redirect"] = "download_files/HJ21".$str."Setup.exe";
    //             $ListDL[$dir]["manual"] = "download_files/HJ21".$str."Setup_manual.pdf";
    //             break;
    //         case "prg2":
    //         case "m2":
    //             $ListDL[$dir]["name"] = "法人税の達人 from 農業簿記".$num."JAバージョン(減価償却)";
    //             $ListDL[$dir]["redirect"] = "download_files/HJ19".$str."Setup.exe";
    //             $ListDL[$dir]["manual"] = "download_files/HJ19".$str."Setup_manual.pdf";
    //             break;
    //         case "prg3":
    //         case "m3":
    //             $ListDL[$dir]["name"] = "内訳概況書の達人 from 農業簿記".$num."JAバージョン(内訳書";
    //             $ListDL[$dir]["redirect"] = "download_files/UG13".$str."Setup.exe";
    //             $ListDL[$dir]["manual"] = "download_files/UG13".$str."Setup_manual.pdf";
    //             break;
    //         case "prg4":
    //         case "m4":
    //             $ListDL[$dir]["name"] = "内訳概況書の達人（平成16年度以降用） from 農業簿記".$num."JAバージョン(概況書)";
    //             $ListDL[$dir]["redirect"] = "download_files/UG16".$str."Setup.exe";
    //             $ListDL[$dir]["manual"] = "download_files/UG16".$str."Setup_manual.pdf";
    //             break;
    //     }
    // }
    
// ↓↓　<2020/08/04> <VinhDao> <syntaxを変換する>
    // $csv_body  = date("Y/m/d").",".date("H:i:s").",".$ListDL[$dir]["series"].",".$ListDL[$dir]["name"].",";
    // $csv_body .= (($isManual) ? "マニュアル(" . $ListDL[$dir]["SP"] . ")," : $ListDL[$dir]["SP"] . ",") . @$_SERVER["REMOTE_ADDR"] . "," . GetLoginSerial() . "," . (($hasTarget) ? $TopPageClass . "," . $TargetUser . "," : "");
    // file_put_contents($FileName, $csv_body.PHP_EOL, FILE_APPEND | LOCK_EX);

    Proccess:
// ↓↓　＜2020/09/11＞　＜VinhDao＞　＜No.11のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞
    // $csv_body = (($isManual) ? "マニュアル(" . $ListDL[$dir]["SP"] . ")," : $ListDL[$dir]["SP"] . ",") .
    //             $_SERVER["REMOTE_ADDR"] . "," . GetLoginSerial() . "," . 
    //             (($hasTarget) ? $TopPageClass . "," . $TargetUser . "," : "");

    $csv_body = (($isManual) ? "マニュアル(" . $ListDL[$dir]["SP"] . ")," : $ListDL[$dir]["SP"] . ",") .
                getClientIP() . "," . GetLoginSerial() . "," . 
                (($hasTarget) ? $TopPageClass . "," . $TargetUser . "," : "");
// ↑↑　＜2020/09/11＞　＜VinhDao＞　＜No.11のHP合同PRJ-www_sorizo-運用_20200911を修正する。＞
    file_put_contents(
        $FileName, 
        [
            "date"   => date('Y/m/d\,'),
            "time"   => date('H:i:s\,'),
            "series" => $ListDL[$dir]["series"] . ",",
            "name"   => $ListDL[$dir]["name"] . ",",
            "content" => $csv_body . PHP_EOL
        ],
        FILE_APPEND | LOCK_EX
    );
// ↑↑　<2020/08/04> <VinhDao> <syntaxを変換する>

    $link = ($isManual) ? $ListDL[$dir]["manual"] : $ListDL[$dir]["redirect"];
    header("Location: " . (!preg_match('/^http.+/', $link) ? "../" . $dir . "/" : "") . $link);


// SQLインジェクション対応
function prmCheckLocal($str) {
    $regex = '/[^a-zA-Z0-9\-_]/';
    return preg_match($regex, $str);
}

?>