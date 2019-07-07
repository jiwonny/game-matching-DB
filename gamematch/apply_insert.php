<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
  
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

//parameter 받기
$group_id = $_POST['group_id'];
$description = $_POST['description'];
$user_name = trim($_POST['user_name']); 
$check_password = $_POST['password'];
//parameter 받기 끝

mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation

//user query 처리
$query = "select * from users where user_name = '$user_name'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_array($res);
$user_id = $user['user_id'];


//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_name = '$user_name'";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);

//닉네임 belong 중복 확인
$double_query1 = "select * from belongs where user_id = $user_id and group_id = '$group_id'";
$double_res1 = mysqli_query($conn, $double_query1);
$double_count1 = mysqli_num_rows($double_res1);


//닉네임 application 중복 확인
$double_query2 = "select * from applications where user_id = '$user_id' and group_id = '$group_id'";
$double_res2 = mysqli_query($conn, $double_query2);
$double2 = mysqli_fetch_array($double_res2);
$double_count2 = mysqli_num_rows($double_res2);

//없는 사용자라면?
if(mysqli_num_rows($res) == 0){
	msg("존재하지 않는 사용자입니다 닉네임을 확인해주세요");
}
else if($double_count1 != 0){
	msg("이미 그룹에 가입되어 있습니다.");
}
else if($double_count2 != 0){
	if($double2['status'] === 'wait') msg("이미 신청되었으며, 승인 대기중입니다. 기다려주세요~");
	else if($double2['status'] === 'denied') msg("이미 승인 거절되었습니다. 다른 그룹에 신청해주세요");
}
else if ($password['password'] != $check_password){
	msg("비밀번호 일치 안함");
}
else{
	$ret1 = mysqli_query($conn, "insert into applications (user_id, group_id, description, a_created_time, status) values('$user_id', '$group_id', '$description', NOW(), 'wait')");
}


if(!$ret1)
{
	//--added
	mysqli_query($conn, "rollback"); // application 등록 실패. 수행 이전으로 rollback
	//--added
	
	echo mysqli_error($conn);
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // application 등록 성공. commit
    //--added
    s_msg ('성공적으로 그룹 가입 신청이 되었습니다.');
    echo "<meta http-equiv='refresh' content='0;url=group_show.php?group_id=$group_id'>";
}

?>