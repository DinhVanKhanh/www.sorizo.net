<?php
    // １つのデータベース（データソース）で複数の掲示板を設置したい場合には、テーブル名に別名を付けてここを変更する。
    define("TSYSTEM", "Mori_DrbbsSystem");
    define("TMESSAGE", "Mori_DrbbsMessage");
    define("TGROUP", "Mori_DrbbsGroup");
    define("TUPLOAD", "Mori_DrbbsUpload");
    define("TMAILOWNER", "Mori_DrbbsMailOwner");
    define("TSUM", "Mori_DrbbsSum");
    define("TSUMMESSAGE", "Mori_DrbbsSumMessage");
    define("TLOG", "Mori_DrbbsLog");
    define("TCATMST", "Mori_DrCatgMst");
    define("TFAQDATA", "Mori_DrFAQData");
    define("CCATID", "CatID");
    define("CCATID1", "CatID1");
    define("CCATID2", "CatID2");

    // １つのWebサーバに複数の掲示板を設置したい場合にはここを変更する。
    define("CookieConf", "Drbbs");  // 共通設定用
    define("CookieRead", "DrbbsR"); // 既読保存用
    define("CookieMax", 5000);      // クッキーサイズの最大値
    define("CookiesExpires", 100);  // クッキーの有効期限

    // スレッドのイメージ設定
    define("MarkerThread", "<img SRC='images/mkthread.gif'>");

    // グループ一覧のデフォルトイメージ
    define("BlueBall", "<img SRC='images/blueball.gif' WIDTH=14 HEIGHT=14>");

    // グループの種類のイメージ設定
    define("IMGGBBS", "<img SRC='images/pict.gif' WIDTH=14 HEIGHT=14 TITLE='画像ファイルなどの添付ができます'>");
    define("IMGRONLY", "<img SRC='images/ronly.gif' WIDTH=14 HEIGHT=14 TITLE='読み取り専用です'>");
    define("IMGLOCK", "<img SRC='images/lock.gif' WIDTH=14 HEIGHT=14 TITLE='パスワードが必要です'>");

    // メッセージの表示幅
    define("MessageWidth", 49);
?>