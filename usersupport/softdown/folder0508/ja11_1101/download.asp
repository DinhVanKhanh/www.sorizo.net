<%@ LANGUAGE="VBScript" %>
<!-- #include virtual="/lib/common.inc" -->
<!-- #include virtual="/lib/login.inc" -->
<%

	Dim serial_no
	serial_no = GetLoginSerial

	Dim rq_file
	Dim v_version
	Dim v_SPnumber
	Dim v_filename

	rq_file = Request.Querystring("f")

	Select Case rq_file

		Case "prg1"
			v_version = "11_01_00"
			v_SPnumber = "1127002"
			v_filetype = "SP-" & v_SPnumber
			v_filename = "http://sorimachi-download.s3-ap-northeast-1.amazonaws.com/sp/ag11/ja11sp" & v_SPnumber & ".exe"

		Case "mn1"
			v_version = "11_01_00"
			v_SPnumber = "1127002"
			v_filetype = "マニュアル(SP)"
			v_filename = "download_files/manual_install_ja1101.pdf"

	End Select

	'ファイル書き込み
	myDate = Date
	myTime = Time

	Dim ip
	ip = request.ServerVariables("REMOTE_ADDR")

	csv_body = myDate & "," & myTime & "," & v_version & ",農業簿記11JA," & v_filetype & "," & ip & "," & serial_no & ","

	Set FS = CreateObject("Scripting.FileSystemObject")
	FileName = LogFileDirectory & "af_download\download_log_softdown.txt"
	'--- ファイルを開く（追記モード、存在しないときは新規作成） ---
	Set TS = FS.OpenTextFile(FileName,8,True)

	'--- 文字列の書き込み ---
	TS.WriteLine(csv_body)

	'--- ファイルを閉じる ---
	TS.Close

'EXE表示
Response.Redirect v_filename

%>
