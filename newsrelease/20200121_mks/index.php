<?php

// noを渡された場合のリダイレクト対応
// $sim = @$_GET["sim"];
// 
// if (prmCheckLocal($sim)) {
//     exit;
// }

$LinkURL = "https://www.sorimachi.co.jp/newsrelease/20200121_mks_f/";
header("location: ".$LinkURL);
// echo $LinkURL;
exit;

// SQLインジェクション対応
function prmCheckLocal($str) {
    $regex = '/[^a-zA-Z0-9\-]/';
    return preg_match($regex, $str);
}

?>