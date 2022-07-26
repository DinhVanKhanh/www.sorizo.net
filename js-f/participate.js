function CheckSenryuRegist() {
	if (document.senryu1007.P_Message.value == "") {
		window.alert("川柳が入力されていません！");
	}
	else {
		flag = confirm("この内容で投稿します。よろしいですか？");
		if (flag) document.senryu1007.submit();
	}
}

function SendApplause(obj) {
	obj.submit();
}

function SenryuDelete(obj) {
	flag = confirm("この投稿を削除してよろしいですか？\n削除した場合は、いただいた拍手もすべて消えてしまいます。");
	if (flag) obj.submit();
}

