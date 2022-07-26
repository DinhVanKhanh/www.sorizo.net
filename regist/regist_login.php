<?php

// noを渡された場合のリダイレクト対応
$id = @$_GET["ID"];
if (prmCheckLocal($id)) {
    exit;
}

$LinkURL = "https://member.sorimachi.co.jp/reg-f/";
header("location: ".$LinkURL);
// echo $LinkURL;
exit;

// SQLインジェクション対応
function prmCheckLocal($str) {
    $regex = '/[^a-zA-Z0-9\-]/';
    return preg_match($regex, $str);
}

?>