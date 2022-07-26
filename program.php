<?php
    require_once 'lib/common.php';
    require_once 'lib/login.php';
    require_once 'lib/contents_list.php';
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no" />
    <meta name="keywords" content="農業,簿記,日誌,ソリマチ,ソフト,サポート" />
    <meta name="description" content="そり蔵ネットはソリマチクラブ会員情報サイトです。" />
    <title>プログラム更新｜そり蔵ネット</title>
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
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
</head>
<body id="logout">
    <?php require_once 'lib/header_general.php'; ?>
    <article id="list" class="clearfix">
        <nav class="clearfix">
            <h1><a href="/index.php" onfocus="this.blur()">プログラム更新<span><img src="/common/images/box-icon-back-home.png" width="45" height="45" border="0"></span></a></h1>
            <?php require_once 'lib/nav_general.php'; ?>
        </nav>
        <section id="listMain" class="program">
            <div class="box-lm">
                <h2><span>更新プログラムの<br>ダウンロード各種</span></h2>
            </div>
        </section>
        <?php
// 2020/02/26 t.maruyama 追加 ↓↓ ASP版（program.asp）に加えられた変更を反映
        // ★コメント化し、contents_listに移行(2020/01/24)
/*
        // 農業簿記11の保守契約がある場合のみ表示
        if (CheckUserStatusByProduct("1015") == 1) {
            $LocalPrmPref = $_GET["pref"] ?? '';
//            // JA都道府県版のシリアル（1430-xxxx-xxxxx）を持っていない場合のみ表示(2019/12/4)
//            if (CheckUserStatusByProduct("1430") == 1)
//            // 住所の先頭の３文字が「北海道」「愛知県」「大分県」の場合はボタンを表示しない。※そのほかの都道府県ではボタンを表示(2020/01/22)
//            if (CheckUserStatusByProduct("1430") == 1 && ($LocalPrmPref == "北海道" || $LocalPrmPref == "愛知県" || $LocalPrmPref == "大分県"))
            // 住所の先頭の３文字が「北海道」の場合はボタンを表示しない。※そのほかの都道府県ではボタンを表示(2020/01/23)
            if (CheckUserStatusByProduct("1430") == 1 && $LocalPrmPref == "北海道")
            {
                // このケースでは表示しない
            }
            else {
            ?>
                <section class="info program">
                    <div class="box-mm">
                        <h3>農業簿記11<br>レベルアップ版</h3>
                        <p>令和元年分の申告に対応したレベルアップ版「農業簿記11 令和元年 年末レベルアップ版（Ver.11.01.00）」をダウンロードできます。</p>
                        <ul class="boxlink">
                            <li><a href="/usersupport/levelup/nbk110100/" onfocus="this.blur()"><span>簿記11 レベルアップ</span></a>
                            </li>
                        </ul>
                    </div>
                </section>
            <?php
            }
        }
*/
// 2020/02/26 t.maruyama 追加 ↑↑
        ?>

        <?php
            $cookieArr = GetCookie(LoginProduct);
            GetContentsList("category", 3);

        //　↓↓ <2020/07/31> <VinhDao> <ログインシリアルが"s000"の場合>
            if ( isset($cookieArr[f_serialno]) and $cookieArr[f_serialno] == "1015118000009287" ) {
                // if ( (strtoupper(substr($cookieArr[f_serialno], 0, 4)) == "S000") && strlen($LocalPrmPref) != 2) { 
        //　↑↑ <2020/07/31> <VinhDao> <ログインシリアルが"s000"の場合> ?>
                <section class="info program">
                    <div class="box-mm">
                        <h3>★社内専用★<br>JA都道府県版確認</h3>
                        <p>各都道府県別の変換プログラムはこちら（営業担当者専用です）</p>
                        <ul class="boxlink">
                            <li><a href="/usersupport/softdown/nbk11_ja_all/" onfocus="this.blur()"><span>★社内専用★各都道府県版</span></a></li>
                        </ul>
                    </div>
                </section><?php
            }

            // JA都道府県版のシリアル（1430-xxxx-xxxxx-xxx）をお持ちの場合
            // ※もともと契約期間を持たないシリアルNo.なので、契約の有無にかかわらずに表示
            if (CheckUserStatusByProduct("1430") == 1) {
                // JA各都道府県版の表示を確認するために、パラメーターを許可
                $LocalPrmPref = $_GET["pref"] ?? '';
                $LocalUserPref = "";
                if (strlen($LocalPrmPref) == 2) {
                    switch ($LocalPrmPref) {
                        case "ot":
                            $LocalUserPref = "大分県";
                            break;

                        case "hk":
                            $LocalUserPref = "北海道";
                            break;

                    // 2019/12/27 t.maruyama 追加 ↓↓ 広島県版の処理が抜けていたので追加
                        case "hs":
                            $LocalUserPref = "広島県";
                            break;
                    // 2019/12/27 t.maruyama 追加 ↑↑

                        case "ac":
                            $LocalUserPref = "愛知県";
                            break;

                    //　↓↓ <2020/07/31> <VinhDao> <追加>
                        case "ym":
                            $LocalUserPref = "山形県";
                            break;
                    //　↑↑ <2020/07/31> <VinhDao> <追加>
                    //　↓↓ <2020/10/22> <Kentaro.Watanabe> <追加>
                        case "nn":
                            $LocalUserPref = "長野県";
                            break;
                    //　↑↑ <2020/10/22> <Kentaro.Watanabe> <追加>
                    }
                }
                else {
                    $LocalUserPref = $cookieArr[f_userpref];
                }

                // 都道府県別に表示を変える。都道府県は住所の先頭の３文字から判別
                // 2019/12/04 簿記10用を削除
                switch ($LocalUserPref) {
                    case "北海道": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>農業簿記11<br>JA北海道版</h3>
                                <p>「農業簿記11 JA北海道版」変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_jahkd/" onfocus="this.blur()" ><span>農業簿記11 JA北海道版</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;

                    case "大分県": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>接続キット<br>大分県版<br>(農業簿記11)</h3>
                                <p>「接続キット 大分県版」農業簿記11専用の変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_ja_ot/" onfocus="this.blur()" ><span>接続キット 大分県(簿記11)</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;

                    case "広島県": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>接続キット<br>広島県版<br>(農業簿記11)</h3>
                                <p>「接続キット 広島県版」農業簿記11専用の変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_ja_hs/" onfocus="this.blur()" ><span>接続キット 広島県(簿記11)</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;

                    case "愛知県": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>接続キット<br>愛知県版<br>(農業簿記11)</h3>
                                <p>「接続キット 愛知県版」農業簿記11専用の変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_ja_ac/" onfocus="this.blur()" ><span>接続キット 愛知県(簿記11)</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;

                    case "山形県": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>接続キット<br>山形県版<br>(農業簿記11)</h3>
                                <p>「接続キット 山形県版」農業簿記11専用の変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_ja_ym/" onfocus="this.blur()" ><span>接続キット 山形県(簿記11)</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;

                    case "長野県": ?>
                        <section class="info program">
                            <div class="box-mm">
                                <h3>接続キット<br>長野県版<br>(農業簿記11)</h3>
                                <p>「接続キット 長野県版」農業簿記11専用の変換プログラムがダウンロードできます。</p>
                                <ul class="boxlink">
                                    <li><a href="/usersupport/softdown/nbk11_ja_nn/" onfocus="this.blur()" ><span>接続キット 長野県(簿記11)</span></a></li>
                                </ul>
                            </div>
                        </section><?php
                        break;
                }
            }
        ?>
    </article>
    <footer>
        <div id="linkBox" class="clearfix">
            <ul><li><a href="/index.php">HOME</a></li></ul>
            <?php require_once 'lib/footer_general.php'; ?>
    </footer>
    <p id="toTop"><a href="#general" onfocus="this.blur()">▲</a></p><div id="pos"></div>
</body>
</html>