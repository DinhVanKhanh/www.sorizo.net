function ch_syosai_on(img)
{
	img.src = "/images/shosai2.gif";
}

function ch_syosai_off(img)
{
	img.src = "/images/shosai1.gif";
}

function gopagewithSN(url)
{
	document.frmSendSN.action = url;
	document.frmSendSN.submit();
}

function OpenCustomWindow(url,winwidth,winheight,wintarget)
{
	var win = window.open(url,wintarget,'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=' + winwidth + ',height=' + winheight);
}


// 源泉徴収票ダウンロード用
// 掲載形式によって表示内容を変更する
function ChangeMailInput() {
	v=0;
	for (i=0; i<2; i++) {
		if (document.emailcheck["mailmagazine"][i].checked) { v = i+1; }
	}
//	msg = v + "番目がチェックされたですよ。";
//	alert(msg);

	if (v == 2) {
		ChangeEssentials('hidden');
	}
	else {
		ChangeEssentials('visible');
	}
}

// 源泉徴収票ダウンロード用
// 掲載形式によって必須マークの表示を変更する
function ChangeEssentials(param){
	document.getElementById("essentials1").style.visibility = param;	// メール1
	document.getElementById("essentials2").style.visibility = param;	// メール2
}



// 源泉徴収票ダウンロード用
function gensenFrmSubmit(){
	emailCheck(emailcheck);
//	document.emailcheck.onsubmit();
	}

// 源泉徴収票ダウンロード用
function check_sub(mytype, obj, txt) {
	if (mytype == -1) {
		obj.innerHTML = txt;
	}
	else if (mytype == 0) {
		obj.innerText = txt;
	}
	else {
		obj.textContent = txt;
	}
}


// ユーザー登録と同じ仕組みを使用する
// 2009/06/05 Oniki add
// 全角・半角チェック
function checkString(str, checkType){
	switch(checkType){
		case "HANKAKU":
			for(i=0; i < str.length; i++){
				//全角(半角カタカナも)が含まれていたらfalseを返す
				var c = str.charCodeAt(i);
				if(!((c>=0x0 && c<0x81) || (c==0xf8f0) || (c>=0xff61 && c<0xffa0) || (c>=0xf8f1 && c<0xf8f4))){
					return false;
				}
			}
			break;
		case "ZENKAKU":
			for(i=0; i < str.length; i++){
				var c = str.charCodeAt(i);
				//半角が含まれていたらfalseを返す
				if((c>=0x0 && c<0x81) || (c==0xf8f0) || (c>=0xff61 && c<0xffa0) || (c>=0xf8f1 && c<0xf8f4)){
					return false;
				}
			}
			break;
	}
return true;
}



// 源泉徴収票ダウンロード用
function emailCheck(obj) {

	var v=0;
	for (i=0; i<2; i++) {
		if (document.emailcheck["mailmagazine"][i].checked) { v = i+1; }
	}

	var rtn = true;
	var mytype;
	var tmp = document.getElementById("id_name");
	if((document.getElementById) && navigator.appName.indexOf("Netscape") > -1) {
		mytype = -1;
	}
	else if (typeof tmp.innerText != "undefined") {
		mytype = 0;
	}
	else {
		mytype = 1;
	}


	// 氏名
	tmp = document.getElementById("id_name")
	if (obj.name.value == "") {
		check_sub(mytype, tmp, "＊氏名は必須項目です");
		rtn = false;
	}
	else { check_sub(mytype, tmp, ""); }


	// メールアドレス
	tmp = document.getElementById("id_email1")

	// メールの２箇所の整合性
	if (obj.email1.value != obj.email2.value) {
		check_sub(mytype, tmp, "＊確認用e-mailアドレスと一致していません");
		rtn = false;
	}
	else {
		if (v == 1) {
			if (obj.email1.value == "" | obj.email2.value == "") {
				check_sub(mytype, tmp, "＊e-mailアドレスは2箇所とも必須項目です\n＊メール情報について「受け取る」を選択されている場合は、メールアドレスのご入力が必要です");
				rtn = false;
			}
			else {
				// 入力形式確認
				// dataEmail1 = obj.email1.value.match(/^\S+@\S+\.\S+$/);
				dataEmail1 = obj.email1.value.match(/^[A-Za-z0-9]+[\w\.-]*@[\w\.-]+\.\w{2,}$/);
				if (!dataEmail1) {
					check_sub(mytype, tmp, "＊e-mailアドレスの入力内容をご確認ください\n＊メール情報について「受け取る」を選択されている\n　場合は、メールアドレスのご入力が必要です");
					rtn = false;
				}
				else if (obj.email1.value.indexOf(",",0) != -1) {
					check_sub(mytype, tmp, "＊カンマは使用できません\n＊メール情報について「受け取る」を選択されている\n　場合は、メールアドレスのご入力が必要です");
					rtn = false;
				}
				else {
					var mobileStr=["docomo.ne.jp","ezweb.ne.jp","softbank.ne.jp","vodafone.ne.jp"];
					//↑携帯禁止ワードを指定
					for (var i=0;i<mobileStr.length;i++){
						if (obj.email1.value.indexOf(mobileStr[i]) != -1) {
							check_sub(mytype, tmp, "＊パソコン用のe-mailアドレスをご入力ください\n＊メール情報について「受け取る」を選択されている\n　場合は、メールアドレスのご入力が必要です");
							rtn = false;
							break;
						}
						else { check_sub(mytype, tmp, ""); }
					}
				}
			}
		}
		else {
			if (obj.email1.value != "") {
				// 入力形式確認
				// dataEmail1 = obj.email1.value.match(/^\S+@\S+\.\S+$/);
				dataEmail1 = obj.email1.value.match(/^[A-Za-z0-9]+[\w\.-]*@[\w\.-]+\.\w{2,}$/);
				if (!dataEmail1) {
					check_sub(mytype, tmp, "＊e-mailアドレスの入力内容をご確認ください");
					rtn = false;
				}
				else if (obj.email1.value.indexOf(",",0) != -1) {
					check_sub(mytype, tmp, "＊カンマは使用できません");
					rtn = false;
				}
				else { check_sub(mytype, tmp, ""); }
			}
			else { check_sub(mytype, tmp, ""); }
		}
	}



	// メールマガジンの購読（チェックボックス）
	// tmp = document.getElementById("id_mailmagazine")
	// var mmflug
	// for (i=0; i<2; i++) if (document.emailcheck["mailmagazine"][i].checked) mmflug = i+1;
	// if (mmflug == undefined) {
	//	check_sub(mytype, tmp, "＊メール情報の承諾について必ず選択してください");
	//	rtn = false;
	// }
	// else { check_sub(mytype, tmp, ""); }



	//【最終処理】 問題があればfalseを返す。なければsubmitする。
	if (rtn == false) {
		alert("恐れ入りますがもう一度入力内容をご確認ください。");
		return rtn;
	}
	else {
		document.emailcheck.submit();
	}


}


