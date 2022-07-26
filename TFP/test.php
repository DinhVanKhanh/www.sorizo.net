<?php
    require_once dirname(__FILE__)."/../../common_files/STFSApiAccess.php";
//    require_once "test_STFSApiAccess.php";

    $json = "";
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
    /*
    if (isset($_POST["json"])) {
        $method = (isset($_POST["method"])) ? $_POST["method"] : "GET";
        $api = str_replace(array("'", '"', ":", " ", "\r\n", "["), "", $_POST["json"]);
        $api = explode("{", $api)[1];
        if (isset($_POST['API']) && preg_match('/^[a-zA-Z]+$/', $_POST['API']) != false) {
            $warn = '<span style="color:red">*</span>';
        }
        $api = 
        $data3 = GetAPIData($api, $_POST["json"], $method);
    }*/

    $warnAPI = $getRequest = '';
    $getAPI  = 'placeholder="users"';
    if (isset($_POST['API'])) {
        if (preg_match('/^\w+$/', $_POST['API']) != false) {
            $getAPI = 'value="'.$_POST['API'].'"';
            if (isset($_POST["json"]) && trim($_POST['json']) != '') {
                $getRequest = $_POST['json'];
                $method     = (isset($_POST["method"])) ? $_POST["method"] : "GET";
                $data3      = GetAPIData($_POST['API'], $_POST['json'], $method);
            }
            else {
                $getRequest = 'Please input the request';
            }
        }
        else {
            $warnAPI = '<span style="color:red">*</span>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Testing API</title>
        <style>
            .container { 
                height: auto;
                border: 3px solid black;
                margin: 5px;
            }
            div.center {
                margin:20px auto;
            }
        </style>
    </head>
    <body class="container">
        <div class="center">
            <?="TFP Server is connect to ... [".$STFSApiAccessURI."]" ?>
            <table align="center" border='0' cellpadding="5" cellspacing="1">
                <tr>
                    <td>
                        <div style="height:100px; width:300px; background-color:lightblue; border:1px solid black; padding:5px">
                            <form action="" method="POST">
                                <input type="text" name="ID1" id="ID1" placeholder="Input user_cd" value="<?= @$_POST["ID1"] ?>" />
                                <input type="submit" name="Submit1" id="Submit1" value="Submit" />
                            </form><br>
                            <?php if ($hasData1) { ?>
                            <div id="user_nm">+ User name   : <?= $data1["user_nm"]." // ".$json." // " ?></div>
                            <div id="kai_nm">+ Company name: <?= $data1["kai_nm"] ?></div>
                            <?php }
                            else { ?>
                            <div id="error1" style="color:red"><?= $data1 ?></div>
                            <?php } ?>
                        </div>
                    </td>
                    <td>
                        <div style="height:100px; width:300px; background-color:lightblue; border:1px solid black; padding:5px; float:right">
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
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div style="height:auto; width:800px; background-color:lightblue; border:1px solid black; padding:5px">
                            <form action="" method="POST">
                                API: <input type="text" name="API" id="API" <?= $getAPI ?> style="width:200px"><?= $warnAPI ?>
                                <span style="margin-left:40px; ">Method:</span>
                                <select name="method" id="method">
                                    <option value="GET" selected>GET</option>
                                    <option value="POST">POST</option>
                                    <option value="PUT">PUT</option>
                                    <option value="DELETE">DELETE</option>
                                </select>
                                <input type="submit" name="Submit3" id="Submit3" value="Submit"/>
                                <br><br>
                                <textarea style="height:300px; width:99%;" name="json" id="json" placeholder="Input JSON string"><?= $getRequest ?></textarea>
                            </form>
                            
                            <h3>Result:</h3>
                            <div style="margin:2px; min-height:500px; width:99%; background-color:white; overflow-y:scroll">
                                <?php
                                    if ($data3 != "") {
                                        echo "<pre>";
                                        print_r($data3);
                                        echo "</pre>";
                                    }
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>