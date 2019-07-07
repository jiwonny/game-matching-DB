<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host,$dbid,$dbpass,$dbname);

// //parameter 받기
$group_id = $_POST['group_id'];
$manager_id = $_POST['manager_id'];
$check_password = $_POST['check_password'];
// //parameter 받기 끝

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added

//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_id = '$manager_id'";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);


if ($password['password'] != $check_password){
	msg("비밀번호 일치 안함");
}
else{
	$ret1 = mysqli_query($conn, "delete from belongs where group_id = $group_id ");
	$ret2 = mysqli_query($conn, "delete from applications where group_id = $group_id");
	$ret3 = mysqli_query($conn, "delete from groups where group_id = $group_id");
}


if(!$ret1 || !$ret2 || !$ret3)
{
	//--added
	mysqli_query($conn, "rollback"); // belong, application, group 에 대해서 하나라도 실패 했을 시. 수행 전으로 rollback
	//--added
	
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // belong, application, group 에 대해서 모두 삭제 성공하였을 때, commit
	//--added
	
    s_msg ('성공적으로 삭제 되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=group_index.php'>";
}

?>
