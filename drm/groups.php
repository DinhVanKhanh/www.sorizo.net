<?php
    require_once "common.php";
    require_once "drbbs.php";
    require_once "../lib/common.php";
    GetSystemValue();
    WriteLog(true);
?>
    <!DOCTYPE html>
    <HTML lang="ja">
        <head>
            <meta HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=x-sjis">
            <title><?= $GLOBALS['MainTitle'] ?></title>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/lib/header_gtag_ga4.php'); ?>
        </head>
        <?= $GLOBALS['BodyVal'] ?>

        <body>
            <table BGCOLOR="whitesmoke" BORDER="1" WIDTH="100%" CELLPADDING="3" CELLSPACING="0">
                <tr><td><img src="images/search.gif">&nbsp森先生に聞きたい質問を探してください。<br>回答が見つからない場合、右上の「森先生に質問する」ボタンを押してください。</td></tr>
            </table>
            <HR><br>
            <?php
                $conn   = ConnectSorizo();
                $sql    = "SELECT * FROM ".TCATMST." ORDER BY ".CCATID;
                $result = mysqli_query($conn, $sql);
                if ( mysqli_num_rows($result) > 0 ) {
                    $TopGrp = 1;
                    echo "<table>";
                    while ( $row = mysqli_fetch_assoc($result) ) {
                        if ( ($row["CatID"] % 100) == 0 ) {
                            if ( $TopGrp != 1 ) {
                                echo "<tr><td><br></td></tr>";
                            }
                            else {
                                $TopGrp = 0;
                            }
                            echo "<tr style=\"font-weight:bold\"><td>◎</td><td>";
                            echo $row["CatName"];
                        }
                        else {
                            echo "<tr><td>".BlueBall."</td><td>";
                            echo "<a HREF='".ListFile."?Menu=&GroupID=".$row["CatID"]."' TARGET=\"LIST\" TITLE=\"".$row["CatName"]."\">".$row["CatName"]."</a>";
                        }
                        echo "</td></tr>";
                    }
                    echo "</table>";
                }
                else {
                    echo "　グループはありません";
                }
                mysqli_free_result($result);
                mysqli_close($conn);
            ?>
        </body>
    </html>
