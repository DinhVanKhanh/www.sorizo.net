<?php include(dirname(__FILE__) . '/../core/download_common_agri.php'); ?>
<?php require_once '../core/softdown_header.php'; ?>
		<p>農業ソフトのプログラムアップデートをダウンロードすることができます。</p>
	</div>
	<div id="oldWrapper">
    <!-- メインコンテンツ（ここから）-->
    <table border="0" cellspacing="0" cellpadding="0" class="maintable">
      <tr>
        <td width="650">
          <div class="ptitle"><img src="/images/ptitle3_softwaredownload.gif"></div>
          <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto; text-align:left;">
            <tr>
              <td width="180" align="left" valign="top" style="padding-left:0px;"><img src="/images/pkg_nbkja11_w150.jpg" width="150" height="150"></td>
              <td width="470">
                <table width="470" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <div style="color:#404040; font:24px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">農業簿記<?php echo $prodVersion ?> JAバージョン</div>
                      <div style="color:#404040; font:20px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">Ver.<?php echo substr_replace($prodVersionSub, ".", 2, 0) ?>専用 サービスパック</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div style="margin:3px 0px 5px 0px; background-color:#808080;"><img src="images/midashi.gif" alt="ソフトウェアダウンロード" border="0"></div>
                    </td>
                  </tr>
                  <tr>
                    <td class="p080_130" style="padding-left:2px;">こちらから最新版のサービスパック、およびサービスパック用PDFマニュアルをダウンロードすることができます。ぜひご利用ください。
                      <br><font color="#FF0000">※インストール前に必ず「農業簿記<?php echo $prodVersion ?> JAバージョン」のバージョン情報をお確かめください。</font></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="2" nowrap height="5"></td>
            </tr>
            <tr>
              <td colspan="2" style="padding:25px 0px;">
                <table border="0" cellspacing="1" cellpadding="0" width="650">
                  <tr>
                    <th nowrap class="list_g2">ファイル内容</th>
                    <th nowrap class="list_g2">解説</th>
                    <th nowrap class="list_g2" width="12%">形式</th>
                    <th nowrap class="list_g2">Download</th>
                  </tr>
                  <tr>
                    <td nowrap class="list_g1"><b>「農業簿記<?php echo $prodVersion ?> JAバージョン」<br>Ver.<?php echo substr_replace($prodVersionSub, ".", 2, 0) ?> 専用サービスパック</b></td>
                    <td class="list_g1w">いくつかの機能を改善した最新版です。<b><u>Version <?php echo substr_replace($prodVersionSub, ".", 2, 0) ?>.xx<!--～11.0x.xx-->がインストールされているお客様のみ導入可能</u></b>です。</td>
                    <td class="list_g1w">プログラム[.exe]
                      <br>（<?php echo getFileSizeFromURL(getSpDownloadUrl_agri()) ?>）</td>
                    <td class="list_g1w" align="center">
                      <a href=<?php echo getSpDownloadUrl_agri(); ?> target="_blank"><img src="../images/btn_download_r_a.gif" onMouseover="this.src='../images/btn_download_r_b.gif'" onMouseout="this.src='../images/btn_download_r_a.gif'" border="0" alt="プログラムファイルダウンロード"></a>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap class="list_g1"><b>PDFマニュアル</b></td>
                    <td class="list_g1w">制限事項・注意事項・インストール方法につきましては、本マニュアルをご覧ください。</td>
                    <td class="list_g1w">マニュアル
                      <br>[PDF]
                      <br>
                    </td>
                    <td class="list_g1w" align="center">
                      <a href=<?php echo getSpManualUrl_agri() ?> target="_blank"><img src="../images/btn_download_y_a.gif" onMouseover="this.src='../images/btn_download_y_b.gif'" onMouseout="this.src='../images/btn_download_y_a.gif'" border="0" alt="PDFマニュアルダウンロード"></a>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="4" class="p075_130" style="padding:8px;"> ※PDFマニュアルをご覧いただく場合は、<a href="<?= $AdobeReaderDL_URL ?>" target="_blank">Adobe Reader</a> が必要です。 </td>
                  </tr>
                </table>
                <div style="margin-top:60px; border-left:3px #008800 solid; padding:5px 4px 1px 4px; font:normal 100%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;"><b>他のバージョンをお使いの方</b></div>
                <div style="font:normal 90%/180% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">
                  <div style="margin:10px 0 0 20px;"><u>Ver.11.03.xx</u> をお使いの方は [ <a href="../ja11_1103/"><b>こちら</b></a> ]</div>
                  <div style="margin:10px 0 0 20px;"><u>Ver.11.02.xx</u> をお使いの方は [ <a href="../ja11_1102/"><b>こちら</b></a> ]</div>
                  <div style="margin:10px 0 0 20px;"><u>Ver.11.00.xx</u> をお使いの方は [ <a href="../ja11_1100/"><b>こちら</b></a> ]</div>
                </div>
              </td>
            </tr>
          </table>
          <!--PRODUCTS(START)-->
        </td>
      </tr>
    </table>

<?php require_once '../core/softdown_footer.php'; ?>