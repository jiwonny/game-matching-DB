<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

 
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

$user_name = trim($_POST['user_name']);
//parameter 받기 끝

//user query 처리

$password = $_POST['password'];
$introduction = $_POST['introduction'];
$password_confirm= $_POST['password_confirm'];

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transation isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation

//--added

//password confirmation
if($password != $password_confirm){
	msg('비밀번호를 다시 확인해주세요');
}
else{
	$ret = mysqli_query($conn, "update users set password = HEX(AES_ENCRYPT('$password', (SHA2('password',512)))), introduction = '$introduction' where user_name = '$user_name'");
}



if(!$ret)
{
	//--added
	mysqli_query($conn, "rollback"); // user 정보가 성공적으로 수정되지 않았을 경우 rollback
	//--added
	
	
	echo mysqli_error($conn);
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // user 정보가 수정되는 것 실패했을 때 commit
	//--added
	
    s_msg ('성공적으로 사용자 정보가 수정 되었습니다');
    echo "<meta http-equiv='refresh' content='0; url=user_index.php'>";
}



?>

