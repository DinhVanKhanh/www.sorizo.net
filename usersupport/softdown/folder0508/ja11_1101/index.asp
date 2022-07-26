<%@ LANGUAGE="VBScript" %>
<% Option Explicit %>
<!-- #include virtual="/lib/common.inc" -->
<!-- #include virtual="/lib/login.inc" -->
<!-- #include virtual="/lib/get_filesize.inc" -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">
<head>
<meta name="robots" content="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Imagetoolbar" content="no">
<script type="text/javascript" charset="utf-8" src="/common/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" charset="utf-8" src="/common/js/gloval.js"></script>
<link rel="stylesheet" type="text/css" href="/css/general_1006.css">
<link rel="stylesheet" type="text/css" href="/common/css/old-gloval.css" media="all">
<script type="text/javascript" src="/js/dd.js"></script>
<script type="text/javascript" src="/js/common.js"></script>
<script type="text/javascript" src="/js/overlib417/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<link rel="stylesheet" href="/css/sg_general.css" type="text/css">
<link rel="stylesheet" href="/css/sg_list.css" type="text/css">
<link rel="stylesheet" href="/css/sg_blue.css" type="text/css">
<title>ソフトウェアダウンロード｜そり蔵ネット</title>
<style type="text/css">
<!--
a:hover { text-decoration: underline; }
-->
</style>
</head>

<body id="general">
	<div id="oldHeader" background-color:#FFaaaa;>
		<h1>ソフトウェアダウンロード</h1>
		<p>農業ソフトのプログラムアップデートをダウンロードすることができます。</p>
	</div>
	<div id="oldWrapper">

<!-- メインコンテンツ（ここから）-->

<table border="0" cellspacing="0" cellpadding="0" class="maintable">
  <tr>
    <td width="650">

<div class="ptitle"><img src="/images/ptitle3_softwaredownload.gif"></div>

      <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto;">
        <tr> 
          <td width="180" align="left" valign="top" style="padding-left:0px;"><img src="/images/pkg_nbkja11_w150.jpg" width="150" height="150"></td>
          <td width="470">
<table width="470" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<div style="color:#404040; font:24px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">農業簿記11 JAバージョン</div>
<div style="color:#404040; font:20px/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif; font-weight:bold;">Ver.11.01専用 サービスパック</div>
    </td>
  </tr>
  <tr><td><div style="margin:3px 0px 5px 0px; background-color:#808080;"><img src="images/midashi.gif" alt="ソフトウェアダウンロード" border="0"></div></td></tr>
  <tr><td class="p080_130" style="padding-left:2px;">こちらから最新版のサービスパック、およびサービスパック用PDFマニュアルをダウンロードすることができます。ぜひご利用ください。<br><font color="#FF0000">※インストール前に必ず「農業簿記11 JAバージョン」のバージョン情報をお確かめください。</font></td></tr>
</table>
    </td>
  </tr>
  <tr><td colspan="2" nowrap height="5"></td></tr>
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
          <td nowrap class="list_g1"><b>「農業簿記11 JAバージョン」<br>Ver.11.01 専用サービスパック</b></td>
          <td class="list_g1w">いくつかの機能を改善した最新版です。<b><u>Version 11.01.xx<!--～11.0x.xx-->がインストールされているお客様のみ導入可能</u></b>です。</td>
          <td class="list_g1w">プログラム[.exe]<br>（5.30MB）</td>
          <td class="list_g1w" align="center"><a href="download.asp?f=prg1" target="_blank"><img src="../images/btn_download_r_a.gif" onMouseover="this.src='../images/btn_download_r_b.gif'" onMouseout="this.src='../images/btn_download_r_a.gif'" border="0" alt="プログラムファイルダウンロード"></a></td>
        </tr>
        <tr>
          <td nowrap class="list_g1"><b>PDFマニュアル</b></td>
          <td class="list_g1w">制限事項・注意事項・インストール方法につきましては、本マニュアルをご覧ください。</td>
          <td class="list_g1w">マニュアル<br>[PDF]<br>（<%= GetFileSize("\usersupport\softdown\ja11_1101\download_files\manual_install_ja1101.pdf") %>）</td>
          <td class="list_g1w" align="center"><a href="download.asp?f=mn1" target="_blank"><img src="../images/btn_download_y_a.gif" onMouseover="this.src='../images/btn_download_y_b.gif'" onMouseout="this.src='../images/btn_download_y_a.gif'" border="0" alt="PDFマニュアルダウンロード"></a></td>
        </tr>
        <tr>
          <td colspan="4" class="p075_130" style="padding:8px;">
※PDFマニュアルをご覧いただく場合は、<a href="<%= AdobeReaderDL_URL %>" target="_blank">Adobe Reader</a> が必要です。
          </td>
        </tr>
      </table>

<div style="margin-top:60px; border-left:3px #008800 solid; padding:5px 4px 1px 4px; font:normal 100%/130% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;"><b>他のバージョンをお使いの方</b></div>
<div style="font:normal 90%/180% Meiryo,メイリオ,'ＭＳ Ｐゴシック',osaka,sans-serif;">
<div class="id0_1" style="margin:10px 0 0 20px;">
<u>Ver.11.00.xx</u> をお使いの方は [ <a href="../ja11_1100/"><b>こちら</b></a> ]<br>
</div>
</div>

    </td>
  </tr>
</table>
<!--PRODUCTS(START)-->

    </td>
  </tr>
</table>

<!-- メインコンテンツ（ここまで）-->

	</div>
	<div id="oldFooter">
		<ul>
			<li><a href="<%= SORIZO_HOME %>top.asp" onfocus="this.blur()" target="_blank">そり蔵ネット トップ</a></li>
			<li><a href="<%= SORIMACHI_HOME %>" onfocus="this.blur()" target="_blank">ソリマチ株式会社</a></li>
		</ul>
<!-- #include virtual="/lib/footer_simple.inc" -->
</body>
</html>
