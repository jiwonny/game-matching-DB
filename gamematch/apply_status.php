<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
  
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

// //parameter 받기
$application_id = $_GET['application_id'];
$manager_id = $_POST['manager_id'];
$check_password = $_POST['check_password'];
$apply_status = $_POST['apply_status'];
// //parameter 받기 끝

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added


$user_query = "select * from applications natural join users where application_id='$application_id'";
$user_res = mysqli_query($conn, $user_query);
$user = mysqli_fetch_array($user_res);
$user_id = $user['user_id'];
$group_id = $user['group_id'];


//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_id = '$manager_id'";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);

if($user['status'] =='accepted') msg("이미 승인된 가입신청서입니다.");
else if($user['status'] == 'denied') msg("이미 거절된 가입신청서입니다.");
else if ($password['password'] != $check_password){
	msg("매니저 계정과 비밀번호가 일치하지 않습니다. 그룹의 매니저만 접근 가능한 권한입니다.");
}
else{
	if($apply_status === 'accept'){
		$ret1 = mysqli_query($conn, "update applications SET status='accepted' WHERE application_id = '$application_id'");
		$ret2 = mysqli_query($conn, "insert into belongs (user_id, group_id, role, b_created_time) values('$user_id', '$group_id', 'member', NOW())");
	}
	else if($apply_status === 'deny'){
		$ret1 = mysqli_query($conn, "update applications SET status='denied' WHERE application_id = $application_id");
	
	}
}



if(!$ret1)
{
	//--added
	mysqli_query($conn, "rollback"); // application status deny/accept 로 바꾸는 것 실패. rollback
	//--added
	echo mysqli_error($conn);
    msg('Query 1 Error : '.mysqli_error($conn));
}
else if(!$ret2 && $apply_status === 'accept'){
	//--added
	mysqli_query($conn, "rollback"); // application status 이미 accept 된 것에 대해 status 수정 실패. rollback
	//--added
	echo mysqli_error($conn);
    msg('Query 2 Error : '.mysqli_error($conn));
}
else
{
	if($apply_status === 'accept')
    {   
    	//--added
    	mysqli_query($conn, "commit"); // application status accept 로 바꾸는 것 성공. commit
    	//--added
    	s_msg ('가입승인을 완료하였습니다');
    	echo "<meta http-equiv='refresh' content='0; url=apply_show.php?application_id=$application_id'>";
    }
    else if($apply_status === 'deny'){
    	//--added
    	mysqli_query($conn, "commit"); // application status deny 로 바꾸는 것 성공. commit
    	//--added
    	s_msg ('가입거절을 완료하였습니다');
    	echo "<meta http-equiv='refresh' content='0; url=apply_show.php?application_id=$application_id'>";
    }    	
   
}

?>

