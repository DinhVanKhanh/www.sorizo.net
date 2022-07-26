<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';
    require_once '../lib/contents_list.php';
    require_once '../lib/participate_senryu_common.php';

    global $SenryuThemeYYYYMM;

    $ThemeYYYYMM = (int)trim(@$_GET["tym"]);
    // 値が6桁以外なら処理終了
    if (strlen($ThemeYYYYMM) != 6) {
        exit;
    }

    // 現在のテーマ以降の場合はトップページへ飛ばす
    if ((int)str_replace("-", "", $SenryuThemeYYYYMM) <= $ThemeYYYYMM || $ThemeYYYYMM < 201012) {
        header("Location: senryu.php");
    }

    $thMonth = substr($ThemeYYYYMM, 4, 2);
    $thYear  = substr($ThemeYYYYMM, 0, 4);
    $NextThemeYYYYMM = $ThemeYYYYMM + 1;
    $PrevThemeYYYYMM = $ThemeYYYYMM - 1;
    if ($thMonth == "01") {
        $PrevThemeYYYYMM = ((int)$thYear - 1)."12";
    }
    elseif ($thMonth == "12") {
        $NextThemeYYYYMM = ((int)$thYear + 1)."01";
    }

    // ログイン状態かどうかを判別するフラグを便宜上入れておきます。
    $serial = GetLoginSerial();
    if ($serial != "") {
        $ShowLoginSerial = substr($serial, 0, 4)."-".substr($serial, 4, 4)."-".substr($serial, 8, 5);
        if (Check14($serial)) {
            $val = CalcSerial_16($serial);
            $ShowLoginSerial = substr($val, 0, 4)."-".substr($val, 4, 4)."-".substr($val, 8, 5)."-".substr($val, 13, 3);
        }
    }
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="農業,簿記,新着情報,サポート">
    <meta name="description" content="そり蔵川柳">
    <title>そり蔵川柳&nbsp;トップ｜そり蔵ネット</title>
    <link rel="stylesheet" type="text/css" href="/common/css/gloval.css" media="all">
    <link rel="stylesheet" type="text/css" href="/common/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/common/css/size.css" media="all">
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
    <script type="text/javascript" src="/js/dd.js"></script>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/bbct/aes.js"></script>
    <script type="text/javascript" src="/js/bbct/aesprng.js"></script>
    <script type="text/javascript" src="/js/bbct/armour.js"></script>
    <script type="text/javascript" src="/js/bbct/entropy.js"></script>
    <script type="text/javascript" src="/js/bbct/lecuyer.js"></script>
    <script type="text/javascript" src="/js/bbct/md5.js"></script>
    <script type="text/javascript" src="/js/bbct/scramble.js"></script>
    <script type="text/javascript" src="/js/bbct/stegodict.js"></script>
    <script type="text/javascript" src="/js/bbct/utf-8.js"></script>
    <script type="text/javascript" src="/js/participate.js"></script>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>
<body id="logout">
    <?php require_once '../lib/header_general.php'; ?>
    <article id="home" class="clearfix">
        <nav class="clearfix">
            <h1><a href="/index.php" onfocus="this.blur()">そり蔵川柳&nbsp;アーカイブス<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once '../lib/nav_general.php'; ?>
        </nav>
        <section id="contents" class="community">
            <div class="box-contents">
                <hgroup class="clearfix">
                    <p><a href="/community.php" onfocus="this.blur()">↑そり蔵公民館</a></p>
                    <h2><img src="images_senryu/maintitle_<?= $thYear ?>-<?= $thMonth ?>.gif" alt="<?php
 if ($ThemeYYYYMM == "202302") { ?>2023年2月のお題：紙/寿司/暦<?php };
 if ($ThemeYYYYMM == "202301") { ?>2023年1月のお題：家族/酒/おみくじ<?php };
 if ($ThemeYYYYMM == "202212") { ?>2022年12月のお題：マフラー/暖炉/風邪<?php };
 if ($ThemeYYYYMM == "202211") { ?>2022年11月のお題：電車/窓/水<?php };
 if ($ThemeYYYYMM == "202210") { ?>2022年10月のお題：お米/新聞/かかし<?php };
 if ($ThemeYYYYMM == "202209") { ?>2022年9月のお題：風/動物/指<?php };
 if ($ThemeYYYYMM == "202208") { ?>2022年8月のお題：波/帰省/雲<?php };
 if ($ThemeYYYYMM == "202207") { ?>2022年7月のお題：空/笹/野菜<?php };
 if ($ThemeYYYYMM == "202206") { ?>2022年6月のお題：ビール/魚/靴<?php };
 if ($ThemeYYYYMM == "202205") { ?>2022年5月のお題：屋根/休暇/ご飯<?php };
 if ($ThemeYYYYMM == "202204") { ?>2022年4月のお題：散歩/手紙/巣<?php };
 if ($ThemeYYYYMM == "202203") { ?>2022年3月のお題：財布/木/数学<?php };
 if ($ThemeYYYYMM == "202202") { ?>2022年2月のお題：雪/暖房/手紙<?php };
 if ($ThemeYYYYMM == "202201") { ?>2022年1月のお題：だるま/夢/お年玉<?php };
 if ($ThemeYYYYMM == "202112") { ?>2021年12月のお題：マスク/大掃除/咳<?php };
 if ($ThemeYYYYMM == "202111") { ?>2021年11月のお題：冬支度/パソコン/将棋<?php };
 if ($ThemeYYYYMM == "202110") { ?>2021年10月のお題：読書/実り/テレビ<?php };
 if ($ThemeYYYYMM == "202109") { ?>2021年09月のお題：リモコン/きのこ/スマホ<?php };
 if ($ThemeYYYYMM == "202108") { ?>2021年08月のお題：エアコン/写真/扇子<?php };
 if ($ThemeYYYYMM == "202107") { ?>2021年07月のお題：氷/麦茶/日傘<?php };
 if ($ThemeYYYYMM == "202106") { ?>2021年06月のお題：カタツムリ/雨/長靴<?php };
 if ($ThemeYYYYMM == "202105") { ?>2021年05月のお題：新茶/田植え/大空<?php };
 if ($ThemeYYYYMM == "202104") { ?>2021年04月のお題：蝶/新学期/弁当<?php };
 if ($ThemeYYYYMM == "202103") { ?>2021年03月のお題：あられ/桃/花<?php };
 if ($ThemeYYYYMM == "202102") { ?>2021年02月のお題：チョコ/猫/申告<?php };
 if ($ThemeYYYYMM == "202101") { ?>2021年01月のお題：元旦/初詣/丑（うし）<?php };
 if ($ThemeYYYYMM == "202012") { ?>2020年12月のお題：クリスマス/鍋/雪<?php };
 if ($ThemeYYYYMM == "202011") { ?>2020年11月のお題：収穫/柿/椿<?php };
 if ($ThemeYYYYMM == "202010") { ?>2020年10月のお題：秋/ハロウィン/紅葉<?php };
?>" border="0"></h2>
                </hgroup>
                <div style="margin:10px auto;">
                    <a href="senryu_archives.php?tym=<?= $NextThemeYYYYMM ?>"><font style="font-size:80%;">≪</font><?= substr($NextThemeYYYYMM, 0, 4) ?>年<?= substr($NextThemeYYYYMM, 4, 2) ?>月</a>
                    　｜　
                    <?php if ($ThemeYYYYMM > 201012) { ?>
                    <a href="senryu_archives.php?tym=<?= $PrevThemeYYYYMM ?>"><?= substr($PrevThemeYYYYMM, 0, 4) ?>年<?= substr($PrevThemeYYYYMM, 4, 2) ?>月<font style="font-size:80%;">≫</font></a>
                    <?php } ?>
                </div>

                <div id="senyuArticles" class="clearfix">
                    <h3>皆さんからの投稿を見る</h3>
                    <?php
                        // 掲載ナンバー（連番）のカウントを始めます。
                        $keisai_no = 0;
                        $Conn = ConnectSorizo();
                        $sql = "SELECT * FROM SoriSenryu_Senryu ORDER BY ID desc";
                        $result = mysqli_query($Conn, $sql);
                        while ($res = mysqli_fetch_assoc($result)) {
                            // お題の月に該当しているかどうかを判断します。
                            if ($res["P_Theme"] == substr($ThemeYYYYMM, 0, 4)."-".substr($ThemeYYYYMM, 4, 2)) {
                                // 管理者による許可が行われており、投稿者による削除が行われていない場合は掲載します。
                                // M_Judge　0:掲載確認待ち、1:掲載OK、2:掲載不可
                                if (($res["M_Judge"] == 0 || $res["M_Judge"] == 1) && is_null($res["P_DeleteDate"])) {
                                    // 掲載ナンバーに１足します。
                                    $keisai_no++;
                                    // 都道府県、ペンネームの値を初期化します。
                                    $Pref = ($res["P_Pref"] == "") ? "" : $res["P_Pref"]."　";
                                    // 投稿者名が入力されていればその名前を、無ければ匿名扱いとします。
                                    $OnlineName = ($res["P_OnlineName"] == "") ? "匿名" : htmlspecialchars($res["P_OnlineName"]);

                                    // フィールド値の表示：掲載内容を表示します。
                                    echo (($keisai_no % 4) == 1) ? "<ul class='clearfix'>" : "";
                                    echo "<li>";
                                    echo "<form method='post' name='senryu_applause".$keisai_no."' action='senryu_applause.php' style='margin:0; padding:0;'>";
                                    echo "<input type='hidden' name='ID' value='".$res["ID"]."' >";
                                    echo "<div class='senryu-box'>".htmlspecialchars($res["P_Message"])."<span>".$Pref.$OnlineName."</span></div>";

                                    // ログインしていない状態の場合
                                    echo "<button class='senryu-btn-off' onClick='javascript.disp()'><span>".ApplauseCount($res["ID"])."</span>拍手</button>";
                                    echo "</form></li>";
                                    echo (($keisai_no % 4) == 0) ? "</ul>" : "";
                                }
                            }
                        }
                        mysqli_free_result($result);
                        mysqli_close($Conn);

                        echo (($keisai_no % 4) != 0) ? "</ul>" : "";
                        echo ($keisai_no == 0) ? "現在、掲載されている投稿はありません。" : "";
                    ?>
                </div>
                <br>
                <h3>そり蔵川柳　アーカイブス</h3>
                <ul class="links">
                <?php if ($SenryuThemeYYYYMM >= "2023-02") { ?>
                    <li><a href="senryu_archives.php?tym=202302">2023/02&nbsp;紙&nbsp;/&nbsp;寿司&nbsp;/&nbsp;暦</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2023-01") { ?>
                    <li><a href="senryu_archives.php?tym=202301">2023/01&nbsp;家族&nbsp;/&nbsp;酒&nbsp;/&nbsp;おみくじ</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-12") { ?>
                    <li><a href="senryu_archives.php?tym=202212">2022/12&nbsp;マフラー&nbsp;/&nbsp;暖炉&nbsp;/&nbsp;風邪</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-11") { ?>
                    <li><a href="senryu_archives.php?tym=202211">2022/11&nbsp;電車&nbsp;/&nbsp;窓&nbsp;/&nbsp;水</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-10") { ?>
                    <li><a href="senryu_archives.php?tym=202210">2022/10&nbsp;お米&nbsp;/&nbsp;新聞&nbsp;/&nbsp;かかし</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-09") { ?>
                    <li><a href="senryu_archives.php?tym=202209">2022/09&nbsp;風&nbsp;/&nbsp;動物&nbsp;/&nbsp;指</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-08") { ?>
                    <li><a href="senryu_archives.php?tym=202208">2022/08&nbsp;波&nbsp;/&nbsp;帰省&nbsp;/&nbsp;雲</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-07") { ?>
                    <li><a href="senryu_archives.php?tym=202207">2022/07&nbsp;空&nbsp;/&nbsp;笹&nbsp;/&nbsp;野菜</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-06") { ?>
                    <li><a href="senryu_archives.php?tym=202206">2022/06&nbsp;ビール&nbsp;/&nbsp;魚&nbsp;/&nbsp;靴</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-05") { ?>
                    <li><a href="senryu_archives.php?tym=202205">2022/05&nbsp;屋根&nbsp;/&nbsp;休暇&nbsp;/&nbsp;ご飯</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-04") { ?>
                    <li><a href="senryu_archives.php?tym=202204">2022/04&nbsp;散歩&nbsp;/&nbsp;手紙&nbsp;/&nbsp;巣</a></li>
                <?php } ?>
                <?php if ($SenryuThemeYYYYMM >= "2022-03") { ?>
                    <li><a href="senryu_archives.php?tym=202203">2022/03&nbsp;財布&nbsp;/&nbsp;木&nbsp;/&nbsp;数学</a></li>
                <?php } ?>
                    <li><a href="senryu_archives.php?tym=202202">2022/02&nbsp;雪&nbsp;/&nbsp;暖房&nbsp;/&nbsp;手紙</a></li>
                    <li><a href="senryu_archives.php?tym=202201">2022/01&nbsp;だるま&nbsp;/&nbsp;夢&nbsp;/&nbsp;お年玉</a></li>
                    <li><a href="senryu_archives.php?tym=202112">2021/12&nbsp;マスク&nbsp;/&nbsp;大掃除&nbsp;/&nbsp;咳</a></li>
                    <li><a href="senryu_archives.php?tym=202111">2021/11&nbsp;冬支度&nbsp;/&nbsp;パソコン&nbsp;/&nbsp;将棋</a></li>
                    <li><a href="senryu_archives.php?tym=202110">2021/10&nbsp;読書&nbsp;/&nbsp;実り&nbsp;/&nbsp;テレビ</a></li>
                    <li><a href="senryu_archives.php?tym=202109">2021/09&nbsp;リモコン&nbsp;/&nbsp;きのこ&nbsp;/&nbsp;スマホ</a></li>
                    <li><a href="senryu_archives.php?tym=202108">2021/08&nbsp;エアコン&nbsp;/&nbsp;写真&nbsp;/&nbsp;扇子</a></li>
                    <li><a href="senryu_archives.php?tym=202107">2021/07&nbsp;氷&nbsp;/&nbsp;麦茶&nbsp;/&nbsp;日傘</a></li>
                    <li><a href="senryu_archives.php?tym=202106">2021/06&nbsp;カタツムリ&nbsp;/&nbsp;雨&nbsp;/&nbsp;長靴</a></li>
                    <li><a href="senryu_archives.php?tym=202105">2021/05&nbsp;新茶&nbsp;/&nbsp;田植え&nbsp;/&nbsp;大空</a></li>
                    <li><a href="senryu_archives.php?tym=202104">2021/04&nbsp;蝶&nbsp;/&nbsp;新学期&nbsp;/&nbsp;弁当</a></li>
                    <li><a href="senryu_archives.php?tym=202103">2021/03&nbsp;あられ&nbsp;/&nbsp;桃&nbsp;/&nbsp;花</a></li>
                    <li><a href="senryu_archives.php?tym=202102">2021/02&nbsp;チョコ&nbsp;/&nbsp;猫&nbsp;/&nbsp;申告</a></li>
                    <li><a href="senryu_archives.php?tym=202101">2021/01&nbsp;元旦&nbsp;/&nbsp;初詣&nbsp;/&nbsp;丑（うし）</a></li>
                    <li><a href="senryu_archives.php?tym=202012">2020/12&nbsp;クリスマス&nbsp;/&nbsp;鍋&nbsp;/&nbsp;雪</a></li>
                    <li><a href="senryu_archives.php?tym=202011">2020/11&nbsp;収穫&nbsp;/&nbsp;柿&nbsp;/&nbsp;椿</a></li>
                    <li><a href="senryu_archives.php?tym=202010">2020/10&nbsp;秋&nbsp;/&nbsp;ハロウィン&nbsp;/&nbsp;紅葉</a></li>
                    <li><a href="senryu_archives.php?tym=202009">2020/09&nbsp;月見&nbsp;/&nbsp;運動会&nbsp;/&nbsp;栗</a></li>
                    <li><a href="senryu_archives.php?tym=202008">2020/08&nbsp;夏&nbsp;/&nbsp;太陽&nbsp;/&nbsp;体操</a></li>
                    <li><a href="senryu_archives.php?tym=202007">2020/07&nbsp;短冊&nbsp;/&nbsp;蛍&nbsp;/&nbsp;海</a></li>
                    <li><a href="senryu_archives.php?tym=202006">2020/06&nbsp;父の日&nbsp;/&nbsp;梅雨&nbsp;/&nbsp;紫陽花</a></li>
                    <li><a href="senryu_archives.php?tym=202005">2020/05&nbsp;母の日&nbsp;/&nbsp;連休&nbsp;/&nbsp;柏餅</a></li>
                    <li><a href="senryu_archives.php?tym=202004">2020/04&nbsp;入学&nbsp;/&nbsp;桜&nbsp;/&nbsp;咲く</a></li>
                    <li><a href="senryu_archives.php?tym=202003">2020/03&nbsp;春&nbsp;/&nbsp;だんご&nbsp;/&nbsp;つくし</a></li>
                    <li><a href="senryu_archives.php?tym=202002">2020/02&nbsp;かまくら&nbsp;/&nbsp;豆&nbsp;/&nbsp;恵方</a></li>
                    <li><a href="senryu_archives.php?tym=202001">2020/01&nbsp;新年&nbsp;/&nbsp;スキー&nbsp;/&nbsp;羽子板</a></li>
                    <li><a href="senryu_archives.php?tym=201912">2019/12&nbsp;忘年&nbsp;/&nbsp;毛布&nbsp;/&nbsp;おでん</a></li>
                    <li><a href="senryu_archives.php?tym=201911">2019/11&nbsp;銀杏&nbsp;/&nbsp;落ち葉&nbsp;/&nbsp;大根</a></li>
                    <li><a href="senryu_archives.php?tym=201910">2019/10&nbsp;トンボ&nbsp;/&nbsp;かぼちゃ&nbsp;/&nbsp;秋桜</a></li>
                    <li><a href="senryu_archives.php?tym=201909">2019/09&nbsp;すすき&nbsp;/&nbsp;松茸&nbsp;/&nbsp;萩</a></li>
                    <li><a href="senryu_archives.php?tym=201908">2019/08&nbsp;太陽&nbsp;/&nbsp;山&nbsp;/&nbsp;祭り</a></li>
                    <li><a href="senryu_archives.php?tym=201907">2019/07&nbsp;青空&nbsp;/&nbsp;天の川&nbsp;/&nbsp;風鈴</a></li>
                    <li><a href="senryu_archives.php?tym=201906">2019/06&nbsp;傘&nbsp;/&nbsp;衣替え&nbsp;/&nbsp;虹</a></li>
                    <li><a href="senryu_archives.php?tym=201905">2019/05&nbsp;子供&nbsp;/&nbsp;兜&nbsp;/&nbsp;新緑</a></li>
                    <li><a href="senryu_archives.php?tym=201904">2019/04&nbsp;ランドセル&nbsp;/&nbsp;花粉&nbsp;/&nbsp;山菜</a></li>
                    <li><a href="senryu_archives.php?tym=201903">2019/03&nbsp;春&nbsp;/&nbsp;開花&nbsp;/&nbsp;芽</a></li>
                    <li><a href="senryu_archives.php?tym=201902">2019/02&nbsp;恋&nbsp;/&nbsp;春菊&nbsp;/&nbsp;節分</a></li>
                    <li><a href="senryu_archives.php?tym=201901">2019/01&nbsp;おせち&nbsp;/&nbsp;日の出&nbsp;/&nbsp;いのしし</a></li>
                    <li><a href="senryu_archives.php?tym=201812">2018/12&nbsp;年末&nbsp;/&nbsp;冬眠&nbsp;/&nbsp;平成</a></li>
                    <li><a href="senryu_archives.php?tym=201811">2018/11&nbsp;もみじ&nbsp;/&nbsp;芋&nbsp;/&nbsp;木枯らし</a></li>
                    <li><a href="senryu_archives.php?tym=201810">2018/10&nbsp;新米&nbsp;/&nbsp;スポーツ&nbsp;/&nbsp;衣替え</a></li>
                    <li><a href="senryu_archives.php?tym=201809">2018/09&nbsp;月&nbsp;/&nbsp;食&nbsp;/&nbsp;稲</a></li>
                    <li><a href="senryu_archives.php?tym=201808">2018/08&nbsp;ひまわり&nbsp;/&nbsp;盆踊り&nbsp;/&nbsp;さんさん</a></li>
                    <li><a href="senryu_archives.php?tym=201807">2018/07&nbsp;約束&nbsp;/&nbsp;星空&nbsp;/&nbsp;花火</a></li>
                    <li><a href="senryu_archives.php?tym=201806">2018/06&nbsp;雨&nbsp;/&nbsp;梅&nbsp;/&nbsp;あざやか</a></li>
                    <li><a href="senryu_archives.php?tym=201805">2018/05&nbsp;鯉&nbsp;/&nbsp;母&nbsp;/&nbsp;メロン</a></li>
                    <li><a href="senryu_archives.php?tym=201804">2018/04&nbsp;新しい&nbsp;/&nbsp;緑&nbsp;/&nbsp;いちご</a></li>
                    <li><a href="senryu_archives.php?tym=201803">2018/03&nbsp;種まき&nbsp;/&nbsp;卒業&nbsp;/&nbsp;開幕</a></li>
                    <li><a href="senryu_archives.php?tym=201802">2018/02&nbsp;マラソン&nbsp;/&nbsp;わさび&nbsp;/&nbsp;甘い</a></li>
                    <li><a href="senryu_archives.php?tym=201801">2018/01&nbsp;福&nbsp;/&nbsp;キャベツ&nbsp;/&nbsp;初詣</a></li>
                    <li><a href="senryu_archives.php?tym=201712">2017/12&nbsp;鍋&nbsp;/&nbsp;贈り物&nbsp;/&nbsp;かぼちゃ</a></li>
                    <li><a href="senryu_archives.php?tym=201711">2017/11&nbsp;芸術&nbsp;/&nbsp;れんこん&nbsp;/&nbsp;ポカポカ</a></li>
                    <li><a href="senryu_archives.php?tym=201710">2017/10&nbsp;体育&nbsp;/&nbsp;いろどり&nbsp;/&nbsp;イタズラ</a></li>
                    <li><a href="senryu_archives.php?tym=201709">2017/09&nbsp;みちびき&nbsp;/&nbsp;優勝&nbsp;/&nbsp;すずしい</a></li>
                    <li><a href="senryu_archives.php?tym=201708">2017/08&nbsp;風鈴&nbsp;/&nbsp;盆踊り&nbsp;/&nbsp;観察</a></li>
                    <li><a href="senryu_archives.php?tym=201707">2017/07&nbsp;ラッキー&nbsp;/&nbsp;日焼け&nbsp;/&nbsp;ドキドキ</a></li>
                    <li><a href="senryu_archives.php?tym=201706">2017/06&nbsp;種まき&nbsp;/&nbsp;和菓子&nbsp;/&nbsp;きらきら</a></li>
                    <li><a href="senryu_archives.php?tym=201705">2017/05&nbsp;てんとう虫&nbsp;/&nbsp;新茶&nbsp;/&nbsp;さわやか</a></li>
                    <li><a href="senryu_archives.php?tym=201704">2017/04&nbsp;新じゃが&nbsp;/&nbsp;つばめ&nbsp;/&nbsp;わくわく</a></li>
                    <li><a href="senryu_archives.php?tym=201703">2017/03&nbsp;クッキー&nbsp;/&nbsp;花粉&nbsp;/&nbsp;植え付け</a></li>
                    <li><a href="senryu_archives.php?tym=201702">2017/02&nbsp;鬼&nbsp;/&nbsp;ストーブ&nbsp;/&nbsp;ふきのとう</a></li>
                    <li><a href="senryu_archives.php?tym=201701">2017/01&nbsp;今年の抱負&nbsp;/&nbsp;みかん&nbsp;/&nbsp;防除</a></li>
                    <li><a href="senryu_archives.php?tym=201612">2016/12&nbsp;年の瀬&nbsp;/&nbsp;手紙&nbsp;/&nbsp;べたがけ</a></li>
                    <li><a href="senryu_archives.php?tym=201611">2016/11&nbsp;七五三&nbsp;/&nbsp;解禁&nbsp;/&nbsp;初霜</a></li>
                    <li><a href="senryu_archives.php?tym=201610">2016/10&nbsp;遠足&nbsp;/&nbsp;柿&nbsp;/&nbsp;衣替え</a></li>
                    <li><a href="senryu_archives.php?tym=201609">2016/09&nbsp;秋刀魚&nbsp;/&nbsp;うさぎ&nbsp;/&nbsp;梨</a></li>
                    <li><a href="senryu_archives.php?tym=201608">2016/08&nbsp;桃&nbsp;/&nbsp;プール&nbsp;/&nbsp;ゴーヤ</a></li>
                    <li><a href="senryu_archives.php?tym=201607">2016/07&nbsp;きゅうり&nbsp;/&nbsp;滝&nbsp;/&nbsp;うなぎ</a></li>
                    <li><a href="senryu_archives.php?tym=201606">2016/06&nbsp;長靴&nbsp;/&nbsp;メロン&nbsp;/&nbsp;父</a></li>
                    <li><a href="senryu_archives.php?tym=201605">2016/05&nbsp;子供&nbsp;/&nbsp;田んぼ&nbsp;/&nbsp;新茶</a></li>
                    <li><a href="senryu_archives.php?tym=201604">2016/04&nbsp;ツツジ&nbsp;/&nbsp;うそ&nbsp;/&nbsp;ソラマメ</a></li>
                    <li><a href="senryu_archives.php?tym=201603">2016/03&nbsp;ひな祭&nbsp;/&nbsp;門出&nbsp;/&nbsp;菜の花</a></li>
                    <li><a href="senryu_archives.php?tym=201602">2016/02&nbsp;恋心&nbsp;/&nbsp;申告&nbsp;/&nbsp;うるう年</a></li>
                    <li><a href="senryu_archives.php?tym=201601">2016/01&nbsp;申(サル)&nbsp;/&nbsp;抱負&nbsp;/&nbsp;小松菜</a></li>
                    <li><a href="senryu_archives.php?tym=201512">2015/12&nbsp;南瓜&nbsp;/&nbsp;土寄せ&nbsp;/&nbsp;クリスマス</a></li>
                    <li><a href="senryu_archives.php?tym=201511">2015/11&nbsp;夫婦&nbsp;/&nbsp;芋ほり&nbsp;/&nbsp;冬支度</a></li>
                    <li><a href="senryu_archives.php?tym=201510">2015/10&nbsp;新酒&nbsp;/&nbsp;秋桜(コスモス)&nbsp;/&nbsp;ハロウィン</a></li>
                    <li><a href="senryu_archives.php?tym=201509">2015/09&nbsp;ぶどう&nbsp;/&nbsp;防災&nbsp;/&nbsp;夜</a></li>
                    <li><a href="senryu_archives.php?tym=201508">2015/08&nbsp;猛暑&nbsp;/&nbsp;朝顔&nbsp;/&nbsp;甲子園</a></li>
                    <li><a href="senryu_archives.php?tym=201507">2015/07&nbsp;鮎&nbsp;/&nbsp;夕立&nbsp;/&nbsp;かき氷</a></li>
                    <li><a href="senryu_archives.php?tym=201506">2015/06&nbsp;蛍&nbsp;/&nbsp;あじさい&nbsp;/&nbsp;さくらんぼ</a></li>
                    <li><a href="senryu_archives.php?tym=201505">2015/05&nbsp;つばめ&nbsp;/&nbsp;苗代&nbsp;/&nbsp;若葉</a></li>
                    <li><a href="senryu_archives.php?tym=201504">2015/04&nbsp;イチゴ&nbsp;/&nbsp;期待&nbsp;/&nbsp;よもぎ</a></li>
                    <li><a href="senryu_archives.php?tym=201503">2015/03&nbsp;たんぽぽ&nbsp;/&nbsp;木の芽&nbsp;/&nbsp;レモン</a></li>
                    <li><a href="senryu_archives.php?tym=201502">2015/02&nbsp;水仙&nbsp;/&nbsp;雪&nbsp;/&nbsp;チョコレート</a></li>
                    <li><a href="senryu_archives.php?tym=201501">2015/01&nbsp;初詣&nbsp;/&nbsp;もち&nbsp;/&nbsp;たき火</a></li>
                    <li><a href="senryu_archives.php?tym=201412">2014/12&nbsp;コタツ&nbsp;/&nbsp;みかん&nbsp;/&nbsp;手袋</a></li>
                    <li><a href="senryu_archives.php?tym=201411">2014/11&nbsp;きのこ&nbsp;/&nbsp;どんぐり&nbsp;/&nbsp;霧</a></li>
                    <li><a href="senryu_archives.php?tym=201410">2014/10&nbsp;銀杏&nbsp;/&nbsp;りんご&nbsp;/&nbsp;運動会</a></li>
                    <li><a href="senryu_archives.php?tym=201409">2014/09&nbsp;案山子&nbsp;/&nbsp;とんぼ&nbsp;/&nbsp;梨</a></li>
                    <li><a href="senryu_archives.php?tym=201408">2014/08&nbsp;浴衣&nbsp;/&nbsp;ひまわり&nbsp;/&nbsp;蝉</a></li>
                    <li><a href="senryu_archives.php?tym=201407">2014/07&nbsp;星&nbsp;/&nbsp;枝豆&nbsp;/&nbsp;花火</a></li>
                    <li><a href="senryu_archives.php?tym=201406">2014/06&nbsp;かっぱ&nbsp;/&nbsp;父&nbsp;/&nbsp;梅</a></li>
                    <li><a href="senryu_archives.php?tym=201405">2014/05&nbsp;休暇&nbsp;/&nbsp;田植え&nbsp;/&nbsp;新緑</a></li>
                    <li><a href="senryu_archives.php?tym=201404">2014/04&nbsp;新しい&nbsp;/&nbsp;たけのこ&nbsp;/&nbsp;お花見</a></li>
                    <li><a href="senryu_archives.php?tym=201403">2014/03&nbsp;定植&nbsp;/&nbsp;芽吹き&nbsp;/&nbsp;桜</a></li>
                    <li><a href="senryu_archives.php?tym=201402">2014/02&nbsp;告白&nbsp;/&nbsp;豆まき&nbsp;/&nbsp;ふきのとう</a></li>
                    <li><a href="senryu_archives.php?tym=201401">2014/01&nbsp;お年玉&nbsp;/&nbsp;馬&nbsp;/&nbsp;白菜</a></li>
                    <li><a href="senryu_archives.php?tym=201312">2013/12&nbsp;プレゼント&nbsp;/&nbsp;ゆず&nbsp;/&nbsp;団らん</a></li>
                    <li><a href="senryu_archives.php?tym=201311">2013/11&nbsp;感謝&nbsp;/&nbsp;初霜&nbsp;/&nbsp;収穫</a></li>
                    <li><a href="senryu_archives.php?tym=201310">2013/10&nbsp;紅葉&nbsp;/&nbsp;読書&nbsp;/&nbsp;稲刈り</a></li>
                    <li><a href="senryu_archives.php?tym=201309">2013/09&nbsp;月見&nbsp;/&nbsp;残暑&nbsp;/&nbsp;種まき</a></li>
                    <li><a href="senryu_archives.php?tym=201308">2013/08&nbsp;花火&nbsp;/&nbsp;海&nbsp;/&nbsp;夏野菜</a></li>
                    <li><a href="senryu_archives.php?tym=201307">2013/07&nbsp;夏休み&nbsp;/&nbsp;願いごと&nbsp;/&nbsp;水</a></li>
                    <li><a href="senryu_archives.php?tym=201306">2013/06&nbsp;傘&nbsp;/&nbsp;父&nbsp;/&nbsp;虫よけ</a></li>
                    <li><a href="senryu_archives.php?tym=201305">2013/05&nbsp;お休み&nbsp;/&nbsp;母&nbsp;/&nbsp;田植え</a></li>
                    <li><a href="senryu_archives.php?tym=201304">2013/04&nbsp;種&nbsp;/&nbsp;入学&nbsp;/&nbsp;桜</a></li>
                    <li><a href="senryu_archives.php?tym=201303">2013/03&nbsp;春&nbsp;/&nbsp;卒業&nbsp;/&nbsp;いちご</a></li>
                    <li><a href="senryu_archives.php?tym=201302">2013/02&nbsp;申告&nbsp;/&nbsp;鬼&nbsp;/&nbsp;土（づくり）</a></li>
                    <li><a href="senryu_archives.php?tym=201301">2013/01&nbsp;初夢&nbsp;/&nbsp;巳&nbsp;/&nbsp;剪定</a></li>
                    <li><a href="senryu_archives.php?tym=201212">2012/12&nbsp;忘れる&nbsp;/&nbsp;掃除&nbsp;/&nbsp;南瓜</a></li>
                    <li><a href="senryu_archives.php?tym=201211">2012/11&nbsp;夫婦&nbsp;/&nbsp;霜&nbsp;/&nbsp;収穫(収穫祭)</a></li>
                    <li><a href="senryu_archives.php?tym=201210">2012/10&nbsp;新米&nbsp;/&nbsp;スポーツ&nbsp;/&nbsp;衣替え</a></li>
                    <li><a href="senryu_archives.php?tym=201209">2012/09&nbsp;月&nbsp;/&nbsp;食&nbsp;/&nbsp;稲</a></li>
                    <li><a href="senryu_archives.php?tym=201208">2012/08&nbsp;金メダル&nbsp;/&nbsp;里（郷）&nbsp;/&nbsp;西瓜</a></li>
                    <li><a href="senryu_archives.php?tym=201207">2012/07&nbsp;節電&nbsp;/&nbsp;ビール&nbsp;/&nbsp;草刈り</a></li>
                    <li><a href="senryu_archives.php?tym=201206">2012/06&nbsp;虹&nbsp;/&nbsp;時間</a></li>
                    <li><a href="senryu_archives.php?tym=201205">2012/05&nbsp;休日・休み&nbsp;/&nbsp;輪</a></li>
                    <li><a href="senryu_archives.php?tym=201204">2012/04&nbsp;新・新しい&nbsp;/&nbsp;旅</a></li>
                    <li><a href="senryu_archives.php?tym=201203">2012/03&nbsp;卒業</a></li>
                    <li><a href="senryu_archives.php?tym=201202">2012/02&nbsp;申告</a></li>
                    <li><a href="senryu_archives.php?tym=201201">2012/01&nbsp;初（はつ）</a></li>
                    <li><a href="senryu_archives.php?tym=201112">2011/12&nbsp;贈り物</a></li>
                    <li><a href="senryu_archives.php?tym=201111">2011/11&nbsp;秋</a></li>
                    <li><a href="senryu_archives.php?tym=201110">2011/10&nbsp;収穫&nbsp;/&nbsp;本</a></li>
                    <li><a href="senryu_archives.php?tym=201109">2011/09&nbsp;月&nbsp;/&nbsp;歩く</a></li>
                    <li><a href="senryu_archives.php?tym=201108">2011/08&nbsp;まつり</a></li>
                    <li><a href="senryu_archives.php?tym=201107">2011/07&nbsp;うちわ</a></li>
                    <li><a href="senryu_archives.php?tym=201106">2011/06&nbsp;笑顔</a></li>
                    <li><a href="senryu_archives.php?tym=201105">2011/05&nbsp;母の日&nbsp;/&nbsp;鯉のぼり</a></li>
                    <li><a href="senryu_archives.php?tym=201104">2011/04&nbsp;春&nbsp;/&nbsp;絆</a></li>
                    <li><a href="senryu_archives.php?tym=201103">2011/03&nbsp;ムシ&nbsp;/&nbsp;消費税</a></li>
                    <li><a href="senryu_archives.php?tym=201102">2011/02&nbsp;申告</a></li>
                    <li><a href="senryu_archives.php?tym=201101">2011/01&nbsp;今年の抱負</a></li>
                    <li><a href="senryu_archives.php?tym=201012">2010/12&nbsp;年の瀬&nbsp;/&nbsp;雪</a></li>
                </ul>
            </div>
        </section>
    </article>
    <footer>
        <div id="linkBox" class="clearfix">
            <ul>
                <li><a href="/index.php">HOME</a>&nbsp;&gt;&nbsp;<a href="/community.php">そり蔵公民館</a>&nbsp;&gt;&nbsp;そり蔵川柳&nbsp;アーカイブス</li>
            </ul>
            <?php require_once '../lib/footer_general.php'; ?>
        </div>
    </footer>
    <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p>
    <div id="pos"></div>
</body>
</html>