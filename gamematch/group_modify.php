<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
  
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

//parameter 받기
$group_id = $_POST['group_id'];
$group_name = $_POST['group_name'];
$gr_introduction = $_POST['gr_introduction'];
$game_id = $_POST['game_id'];
$user_name = trim($_POST['user_name']); 
$check_password = $_POST['password'];
//parameter 받기 끝

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added


//user query 처리
$query = "select * from users where user_name = '$user_name'";
$res = mysqli_query($conn, $query);
$user = mysqli_fetch_array($res);
$user_id = $user['user_id'];

//password 복호화
$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_name = '$user_name'";
$password_res = mysqli_query($conn, $password_query);
$password = mysqli_fetch_array($password_res);

//그룹이름 중복 확인
$double_query = "select * from groups where group_name = '$group_name'";
$double_res = mysqli_query($conn, $double_query);
$double = mysqli_fetch_array($double_res);
$double_count = mysqli_num_rows($double_res);

//없는 사용자라면?
if(mysqli_num_rows($res) == 0){
	msg("존재하지 않는 사용자입니다 닉네임을 확인해주세요");
}
else if($double['group_id'] != $group_id && $double_count != 0){
	msg("이미 존재하는 그룹 이름입니다. 다른 그룹이름을 입력하세요");
}
else if ($password['password'] != $check_password){
	msg("비밀번호 일치 안함");
}
else{
	$ret1 = mysqli_query($conn, "update groups set game_id = '$game_id', group_name = '$group_name', gr_introduction = '$gr_introduction' where group_id = $group_id");
}


if(!$ret1)
{
	//--added
	mysqli_query($conn, "rollback"); // group 정보가 수정되지 않았으면, rollback
	//--added
	
	echo mysqli_error($conn);
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // group 정보가 수정 완료되었다면 commit
	//--added
	
    s_msg ('성공적으로 게임그룹이 수정되었습니다');
    echo "<meta http-equiv='refresh' content='0;url=group_index.php'>";
}

?>
