function showDetail(id){
    var detail = document.getElementById(id);
    if (detail.style.display == 'none') {
        //元々は'none'（非表示）の場合、表示させる
        detail.style.display = 'block';
    }
}

function closeDetail(id){
    var detail = document.getElementById(id);
    if (detail.style.display == 'block') {
        //元々は'none'（非表示）の場合、表示させる
        detail.style.display = 'none';
    }
}


// 入力チェックを行ないます。
// チェックボックスだけを確認します。

function checkVote() {

	var n;
	for (i=0; i<3; i++) if (document.vote["rating"][i].checked) n = i+1;
	if (n == undefined) {
		window.alert("送信していただく場合は、A～Cのいずれかにチェックをお願いいたします。");
	}
	else {
		document.vote.submit();
	}

}