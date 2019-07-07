<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
  
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

//parameter 받기
$application_id = $_POST['application_id'];
$check_password = $_POST['password'];
//parameter 받기 끝

//user query 처리
$user_id = $_POST['user_id'];

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added

$wait_query = "select * from applications where application_id = $application_id";
$wait_res = mysqli_query($conn, $wait_query);
$wait = mysqli_fetch_array($wait_res);


//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_id = $user_id";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);


if($wait['status'] != 'wait'){

	msg("신청서가 대기 상태가 아니므로, 삭제가 불가능합니다.");
}
else if($password['password'] != $check_password){

	msg("비밀번호 일치 안함");
}
else{
	$ret1 = mysqli_query($conn, "delete from applications where application_id = $application_id");
}


if(!$ret1)
{
	//--added
	mysqli_query($conn, "rollback");  // application 삭제 실패
	//--added
	
	echo mysqli_error($conn);
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
    mysqli_query($conn, "commit");  // application 삭제 성공
    //--added
    s_msg ('성공적으로 가입 신청서 삭제가 완료되었습니다.');
    echo "<meta http-equiv='refresh' content='0;url=group_show.php?group_id={$wait['group_id']}'>";
}

?>