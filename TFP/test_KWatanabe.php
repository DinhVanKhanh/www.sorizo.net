<?php
    require_once dirname(__FILE__)."/../../common_files/STFSApiAccess.php";

    // Get user data (user_cd)
    $hasData1 = false;
    if (isset($_POST["ID1"])) {
        $json = "{'users':{'data':[{'name':'user_cd','value':'".$_POST["ID1"]."','operator':'='}]}}";
        $data1 = GetAPIData("users", $json, "GET");
        if (is_array($data1)) {
            if ($data1["total_count"] > 0) {
                $data1 = $data1["users"][0];
                $hasData1 = true;
            }
            else {
                $data1 = $data1["message"];
            }
        }
        $data1 = ($data1 == "") ? "データが見つかりません。" : $data1;
    }

    // Get product data (sral_no)
    $hasData2 = false;
    if (isset($_POST["ID2"])) {
        $json = "{'sral':{'data':[{'name':'sral_no','value':'".$_POST["ID2"]."','operator':'='}]}}";
        $data2 = GetAPIData("sral", $json, "GET");
        if (is_array($data2)) {
            if ($data2["total_count"] > 0) {
                $data2 = $data2["sral"][0];
                $hasData2 = true;
            }
            else {
                $data2 = $data2["message"];
            }
        }
        $data2 = ($data2 == "") ? "データが見つかりません。" : $data2;
    }

    // Test: Get data by input JSON (full JSON string)
    $data3 = "";
    if (isset($_POST["json"])) {
        $method = (isset($_POST["method"])) ? $_POST["method"] : "GET";
        $api = str_replace(array("'", '"', ":", " ", "\r\n", "["), "", $_POST["json"]);
        $api = explode("{", $api)[1];
        $data3 = GetAPIData($api, $_POST["json"], $method);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Testing API</title>
    <style>
    .container { 
        height: 800px;
        position: relative;
        border: 3px solid black; 
    }
    .center {
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }
    </style>
</head>
<body class="container">
    <div class="center">
        <div style="height:100px; width:300px; background-color:lightblue; border:1px solid black; padding:5px">
            <form action="" method="POST">
                <input type="text" name="ID1" id="ID1" placeholder="Input user_cd" value="<?= @$_POST["ID1"] ?>" />
                <input type="submit" name="Submit1" id="Submit1" value="Submit" />
            </form><br>
            <?php if ($hasData1) { ?>
            <div id="user_nm">+ User name   : <?= $data1["user_nm"] ?></div>
            <div id="kai_nm">+ Company name: <?= $data1["kai_nm"] ?></div>
            <?php }
            else { ?>
            <div id="error1" style="color:red"><?= $data1 ?></div>
            <?php } ?>
        </div>
        <br><br>
        <div style="height:100px; width:300px; background-color:lightblue; border:1px solid black; padding:5px">
            <form action="" method="POST">
                <input type="text" name="ID2" id="ID2" placeholder="Input sral_no" value="<?= @$_POST["ID2"] ?>" />
                <input type="submit" name="Submit2" id="Submit2" value="Submit" />
            </form><br>
            <?php if ($hasData2) { ?>
            <div id="shin_cd">+ Product code: <?= $data2["shin_cd"] ?></div>
            <div id="shin_nm">+ Product name: <?= $data2["shin_nm"] ?></div>
            <?php }
            else { ?>
            <div id="error2" style="color:red"><?= $data2 ?></div>
            <?php } ?>
        </div>
        <br><br>
        <div style="height:450px; width:800px; background-color:lightblue; border:1px solid black; padding:5px">
            <form action="" method="POST">
                <select name="method" id="method">
                    <option value="GET" selected>GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
                <input type="submit" name="Submit3" id="Submit3" value="Submit" />
                <br><br>
                <textarea style="height:200px; width:99%;" name="json" id="json" placeholder="Input JSON string" value="<?= @$_POST["json"] ?>"></textarea>
            </form>
            <div style="margin:2px; height:200px; width:99%; background-color:white; overflow-y:scroll">
                <?php
                    if ($data3 != "") {
                        echo "<pre>";
                        print_r($data3);
                        echo "</pre>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>