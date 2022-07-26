<?php
    if (@$_POST["serial_key"] != "") {
        WriteRequestedURL();
        header("/sorikuranet_callup.php?mode=hkn&serial_key=".htmlspecialchars($_POST["serial_key"]));
    }

    require_once '../core/softdown_header.php';
    if ($GLOBALS['WEBSERVER_FLG'] == 0) {
        CheckIntendedProduct("1430");
    }
?>
        <p>農業ソフトのプログラムアップデートをダウンロードすることができます。</p>
    </div>
    <div id="oldWrapper">
        <!-- メインコンテンツ（ここから）-->
        <table border="0" cellspacing="0" cellpadding="0" class="maintable">
            <tr>
                <td width="650">
                    <center>
                        <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
                            <tr> 
                                <td nowrap width="170" align="left" style="padding-left:2px;"><img src="images/pkg_nbk10_ja_ot.gif"></td>
                                <td width="480">
                                    <table width="480" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>
                                                <div style="color:#404040; font:24px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">「接続キット 大分県版」</div>
                                                <div style="color:#404040; font:20px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">　変換プログラム（農業簿記10.02以降用）</div>
                                            </td>
                                        </tr>
                                        <tr><td style="padding:5px 0 0 2px; font:normal 90%/140% Meiryo,メイリオ,'ＭＳ Ｐゴシック',sans-serif; border-top:1px #808080 dotted;">本プログラムをダウンロードすることで、「接続キット 大分県版」のサービスをご利用いただくことができるようになります。</td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:15px;"><div style="font:normal 16px/140% Meiryo,メイリオ,'ＭＳ Ｐゴシック',sans-serif; padding-left:1em; text-indent:-1em;"><span style="color:#f30; font-weight:bold; font-size:18px; text-decoration:underline;">※本プログラムは農業簿記10.02以降用です。</span><br>農業簿記のバージョンが10.01の方は、必ず先に「<a href="/usersupport/levelup/nbk100200/index.php"><b>こちらのページ</b></a>」から、または2019年2月上旬にお届けしている最新版のCD-ROMから、農業簿記10.02のインストールを行なってください。</div></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:40px 0 10px 0px;">
                                    <div>
                                        <table width="650" class="list_g1">
                                            <tr>
                                                <th colspan="4" class="list_g0" id="olupd">最新版をご利用いただくための、「農業簿記10」オンラインアップデート操作方法</th>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="list_g0"><img src="images/scr_install.png" width="650"></td>
                                            </tr>
                                            <tr><td nowrap colspan="2" height="80"></td></tr>
                                            <tr>
                                                <th colspan="4" class="list_g0" id="kit">変換プログラムのダウンロードはこちら</th>
                                            </tr>
                                            <tr>
                                                <th class="list_g1" style="border:1px #bbb solid;">ファイル内容</th>
                                                <th class="list_g1" style="border:1px #bbb solid;">形式・データ容量</th>
                                                <th class="list_g1" style="border:1px #bbb solid;">Download</th>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid;"><div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;">「接続キット 大分県版」変換プログラム</div></td>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid;"><div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;">プログラムファイル<!--（2.1MB）--></div></td>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid; text-align:center;"><div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;"><a href="/usersupport/softdown/core/download.php?dir=<?= $curDir ?>" target="_blank"><img src="/images/btn_down_g.gif" onMouseover="this.src='/images/btn_down_r.gif'" onMouseout="this.src='/images/btn_down_g.gif'" border="0" alt="プログラムファイルダウンロード"></a></div></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid;"><div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;">「接続キット 大分県版」マニュアル</div></td>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid;"><div style="font:normal 14px/20px Meiryo,メイリオ,sans-serif;">PDFファイル</div></td>
                                                <td style="padding:5px; background-color:#fff; border:1px #bbb solid; text-align:center;"><div style="font:bold 14px/20px Meiryo,メイリオ,sans-serif;"><a href="/usersupport/softdown/core/download.php?dir=<?= $curDir ?>&option=manual" target="_blank"><img src="/images/btn_down_g.gif" onMouseover="this.src='/images/btn_down_r.gif'" onMouseout="this.src='/images/btn_down_g.gif'" border="0" alt="プログラムファイルダウンロード"></a></div></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="padding:5px; color:#444; font:normal 80%/130% Meiryo,MS UI Gothic,osaka,sans-serif;">
                                                ※PDFマニュアルをご覧いただく場合は、<a href="<?= $AdobeReaderDL_URL ?>" target="_blank">Adobe Reader</a> が必要です。
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr><td nowrap colspan="2" height="80"></td></tr>
                        </table>
                    </center>
                </td>
            </tr>
        </table>
<?php require_once '../core/softdown_footer.php'; ?>
