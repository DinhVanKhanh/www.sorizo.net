<?php 
    require_once '../core/softdown_header.php';
    $PCSPFilename = "REN111.zip";
?>
        <p>農業ソフトのプログラムアップデートをダウンロードすることができます。</p>
    </div>
    <div id="oldWrapper">
        <!-- メインコンテンツ（ここから）-->
        <table border="0" cellspacing="0" cellpadding="0" class="maintable">
            <tr>
                <td width="650">
                    <div class="ptitle"><img src="/images/ptitle3_softwaredownload.gif"></div>
                    <center>
                        <table width="600" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
                            <tr> 
                                <td nowrap width="170" align="left" style="padding-left:2px;"><img src="images/pkg_ren.gif" width="150"></td>
                                <td width="430">
                                    <table width="430" border="0" cellspacing="0" cellpadding="0">
                                        <tr><td style="color:#404040; font:bold 120%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">「れん太郎」<br>　最新版プログラム</td></tr>
                                        <tr><td style="padding:3px 0px 10px 0px; text-align:right;"><img src="images/midashi.gif" alt="ソフトウェアダウンロード" border="0"></td></tr>
                                        <tr><td style="padding-left:12px; color:#404040; font:normal 90%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">Microsoft&nbsp;Windows&reg;10 に対応する最新版プログラムです。</td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:30px 0 10px 0px;">
                                    <table width="600" class="list_g1">
                                        <tr><th colspan="4" class="list_g0">ダウンロードはこちらから</th></tr>
                                        <tr>
                                            <th class="list_g1">プログラム</th>
                                            <th class="list_g1">内容</th>
                                            <th class="list_g1">Download</th>
                                        </tr>
                                        <tr>
                                            <td class="list_g1" rowspan="1"><b>「れん太郎」<br>　最新版プログラム</b></td>
                                            <td class="list_g1w">プログラムファイル<br>（ZIPファイル、6.13MB）</td>
                                            <td class="list_g1w" align="center">
                                                <div style="font-family:Meiryo;">パスワードを入力してください&nbsp;<span style="color:#FF3300;">※</span></div>
                                                <form name="pg_download" action="/core/download_prg.php?dir=<?= $curDir ?>" method="post">
                                                    <div style="margin-bottom:5px;"><input type="text" name="dlpassword" size="16" style="font-family:verdana; font-size:18px; border:3px #ccc solid;"></div>
                                                    <div style="margin-bottom:5px;"><input type="submit" value="ダウンロード" style="background-color:#f80; color:#fff; border:0; font-family:Meiryo; padding:5px 25px; font-size:14px; font-weight:bold; cursor:pointer; border-radius:12px;"></div>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="padding:5px; color:#FF3300; font:normal 80%/130% Meiryo UI,MS UI Gothic,osaka,sans-serif;">
                                                <div style="padding-left:1em; text-indent:-1em;">※パスワード欄には、サポートセンターよりご案内させていただいたダウンロードパスワードを入力し、ダウンロードボタンをクリックしてください。</div>
                                                <div style="padding-left:1em; text-indent:-1em;">※なお、ダウンロードパスワードは定期的に変更されますので、本ページのご案内の後、速やかにプログラムをダウンロードしていただくようお願いいたします。</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:5px 0px;">
                                    <div style="margin:3em 0 1em 0; border-left:2px #008800 solid; border-bottom:1px #D0D0D0 solid; padding:3px 4px; font:normal 100%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;"><b>Version 1.11 のレベルアップ項目</b></div>
                                    <div style="font:normal 90%/140% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">
                                        <div class="id1_2" style="margin-top:10px;"><font color="#008800">◆</font><b>Microsoft&nbsp;Windows&reg;10 に対応しました。</b></div>
                                    </div><br><br>
                                    <div style="margin:3em 0 1em 0; border-left:2px #008800 solid; border-bottom:1px #D0D0D0 solid; padding:3px 4px; font:normal 100%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;"><b>インストール、及びバージョン確認方法（画面は Windows 10 の場合）</b></div>
                                    <div style="font:normal 90%/160% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">
                                        <div class="id0_3">【1】 上の表のダウンロードボタンをクリックして、プログラムファイル（<?= $PCSPFilename ?>）をダウンロードします。その際、デスクトップなどの分かりやすい場所にファイルを保存してください。</div>
                                        <div class="id3" style="margin-top:5px; margin-bottom:2em;"><img src="images/scr001.gif" width="550px"></div>

                                        <div class="id0_3">【2】 ダウンロードが完了したら「ファイルを開く」ボタンをクリックしてください。フォルダの解凍が始まります。</div>
                                        <div class="id3" style="margin-top:5px; margin-bottom:2em;"><img src="images/scr002.gif" width="550px"></div>

                                        <div class="id0_3">【3】 解凍されたフォルダを表示し、Setup.exeをダブルクリックしてください。</div>
                                        <div class="id3" style="margin-top:5px; margin-bottom:2em;"><img src="images/scr003.gif" width="550px"></div>

                                        <div class="id0_3">【4】 プログラムインストールの画面が表示されますので、[次へ]をクリックし、画面に従ってインストールを行なってください。</div>
                                        <div class="id3" style="margin-top:5px; margin-bottom:2em;"><img src="images/scr004.gif" width="550px"></div>

                                        <div class="id0_3">【5】 メッセージやウィンドウが消えたらインストールは完了です。インストールが完了すると「れん太郎」はバージョンが「1.11」になります。確認する際は「れん太郎」を起動してください。</div>
                                        <div class="id3" style="margin-top:5px; margin-bottom:2em;"><img src="images/scr005.gif"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
        </table>
<?php require_once '../core/softdown_footer.php'; ?>