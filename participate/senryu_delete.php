<?php
    require_once '../lib/common.php';
    require_once '../lib/participate_senryu_common.php';
?>
<html>
<body>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center" valign="middle">
            <table width="450" border="0" cellspacing="0" cellpadding="0">
                <tr>
<?php

    $Conn = ConnectSorizo();
// 2020/01/10 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//    $sql = "UPDATE SoriSenryu_Senryu SET P_DeleteDate = '".date("Y-m-d H:i:s")."' WHERE ID = ".@$_POST["ID"];
//    if (mysqli_query($Conn, $sql)) {

    $DeleteDate = date("Y-m-d H:i:s");
    $ID = @$_POST["ID"];

    $sql = "UPDATE SoriSenryu_Senryu SET P_DeleteDate = ? WHERE ID = ?";
    $stmt = mysqli_prepare($Conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $DeleteDate, $ID);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($Conn);
// 2020/01/10 t.maruyama 修正 ↑↑
?>
                    <td width="150" valign="middle"><img src="images_senryu/illust_matteru_w120.gif"></td>
                    <td valign="middle">
                        <div style="color:#C00000; font:bold 92%/130% 'ＭＳ Ｐゴシック',sans-serif;">ありがとうございます。<br>削除を受け付けました。</div>
<?php
    }
    // SQLが異常終了したら追加の破棄とエラー内容の表示をします
    else {
// 2020/01/10 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
    mysqli_stmt_close($stmt);
    mysqli_close($Conn);
// 2020/01/10 t.maruyama 追加 ↑↑
        prcSqlError($sql);
?>
                    <td width="150" valign="middle"><img src="images_senryu/illust_arara_w120.gif"></td>
                    <td valign="middle">
                        <div style="color:#C00000; font:bold 92%/130% 'ＭＳ Ｐゴシック',sans-serif;">申し訳ございません。<br>エラーが発生しました。</div>
                        <div style="margin-top:10px; font:80%/150% 'ＭＳ Ｐゴシック',sans-serif;">投稿システムでエラーが発生した模様です。おそれ入りますがしばらく時間をおいて再度お試しください。</div>
<?php } ?>
                        <div style="margin-top:10px; font:80%/150% 'ＭＳ Ｐゴシック',sans-serif;">
                            [ <a href="/participate/senryu.php"><b>投稿コーナー「そり蔵川柳」に戻る</b></a> ]
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<?php mysqli_close($Conn); ?>