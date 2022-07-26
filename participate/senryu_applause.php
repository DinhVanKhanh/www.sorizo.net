<?php
    require_once '../lib/common.php';
    require_once '../lib/login.php';
    require_once '../lib/participate_senryu_common.php';

    // ログイン状態かどうかを判別するフラグを便宜上入れておきます。
    $tmpLoginFlg = "no";
    $serial = GetLoginSerial();
    if ($serial != "") {
        $tmpLoginFlg = "yes";
    }

?>
<html>
<body>
    <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" valign="middle">
                <table width="450" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                    <?php
                        $Now = date("Y-m-d H:i:s");
                        $A_Serial = @$_POST["A_Serial"];
                        $A_ApplauseID = @$_POST["ID"];
                        $IPAddress = @$_SERVER["REMOTE_ADDR"];

                        // ログインしていない場合は拍手はできません
                        if ($tmpLoginFlg != "yes") {
                            echo "<td width='150' valign='middle'><img src='images_senryu/illust_arere_w120.gif'></td>";
                            echo "<td valign='middle'>";
                            echo "<div style='color:#C00000; font:bold 92%/130% Meiryo,sans-serif;'>拍手するときには、<br>ログインしてくださいね♪</div>";
                            echo "<div style='margin-top:10px; font:80%/150% Meiryo,sans-serif;'>[ <a href='/participate/senryu.php'><b>投稿コーナー「そり蔵川柳」に戻る</b></a> ]</div>";
                        }
                        // すでに拍手を送っていたらゴメンナサイ！
                        elseif ($A_Serial == "" || SentApplauseChk($A_ApplauseID, $A_Serial) >= 1) {
                            echo "<td width='150' valign='middle'><img src='images_senryu/illust_arigato_w120.gif'></td>";
                            echo "<td valign='middle'>";
                            echo "<div style='color:#C00000; font:bold 92%/130% Meiryo,sans-serif;'>すでに拍手を送信しています！</div>";
                            echo "<div style='margin-top:10px; font:80%/150% Meiryo,sans-serif;'>[ <a href='/participate/senryu.php'><b>投稿コーナー「そり蔵川柳」に戻る</b></a> ]</div>";
                        }
                        // 拍手を送っていなければ書き込み可！
                        else {
                            $Conn = ConnectSorizo();
// 2020/01/10 t.maruyama 修正 ↓↓ セキュリティ対策のためプリペアードステートメント化
//                            $sql = "INSERT INTO SoriSenryu_Applause(A_Date,A_Serial,A_ApplauseID,IPAddress)
//                                        VALUES( '".$Now."','".$A_Serial."',".$A_ApplauseID.",'".$IPAddress."')";
//                            if (mysqli_query($Conn, $sql)) {

                            $sql = "INSERT INTO SoriSenryu_Applause(A_Date,A_Serial,A_ApplauseID,IPAddress)
                                        VALUES( ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($Conn, $sql);
                            mysqli_stmt_bind_param($stmt, 'ssis', $Now,$A_Serial, $A_ApplauseID, $IPAddress);
                            if (mysqli_stmt_execute($stmt)) {
// 2020/01/10 t.maruyama 修正 ↑↑
                               echo "<td width='150' valign='middle'><img src='images_senryu/illust_arigato_w120.gif'></td>";
                               echo "<td valign='middle'>";
                               echo "<div style='color:#C00000; font:bold 92%/130% Meiryo,sans-serif;'>ありがとうございます！<br>拍手いただきました！</div>";
                            }
                            else {
                               prcSqlError($sql);
                               echo "<td width='150' valign='middle'><img src='images_senryu/illust_arere_w120.gif'></td>";
                               echo "<td valign='middle'>";
                               echo "<div style='color:#C00000; font:bold 92%/130% Meiryo,sans-serif;'>申し訳ございません。<br>エラーが発生しました。</div>";
                               echo "<div style='margin-top:10px; font:80%/150% Meiryo,sans-serif;'>投稿システムでエラーが発生した模様です。おそれ入りますがしばらく時間をおいて再度お試しください。</div>";
                            }
                            echo "<div style='margin-top:10px; font:80%/150% Meiryo,sans-serif;'>[ <a href='/participate/senryu.php'><b>投稿コーナー「そり蔵川柳」に戻る</b></a> ]</div>";
// 2020/01/10 t.maruyama 追加 ↓↓ セキュリティ対策のためプリペアードステートメント化
                            mysqli_stmt_close($stmt);
// 2020/01/10 t.maruyama 追加 ↑↑
                            mysqli_close($Conn);
                        }
                    ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>