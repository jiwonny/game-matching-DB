<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

$user_id = $_POST['user_id2'];
$check_password = $_POST['check_password'];
$check_query = "select * from belongs where user_id=$user_id and role='manager'";
$check_res = mysqli_query($conn, $check_query);
$check_count = mysqli_num_rows($check_res);

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added



//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_id = $user_id";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);

if($check_count != 0){
	msg("그룹의 매니저이므로 사용자 계정을 삭제할 수 없습니다.");
}
else if($password['password'] != $check_password){
	msg("비밀번호 일치 안함");
}
else{
	$ret1 = mysqli_query($conn, "delete from applications where user_id = $user_id");
	$ret2 = mysqli_query($conn, "delete from belongs where user_id = $user_id");
	
	$ret3 = mysqli_query($conn, "delete from users where user_id = $user_id");
}


if(!$ret1 || !$ret2 || !$ret3)
{
	//--added
	mysqli_query($conn, "rollback"); // application, belong, user 정보가 하나라도 삭제되지 않았을 시에 rollback
	//--added
	
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // application, belong, user 정보 모두 삭제 완료되었을 시 commit
	//--added
	
    s_msg ('성공적으로 삭제 되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=user_index.php'>";
}

?>
