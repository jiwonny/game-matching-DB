<?php
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
  
$conn = dbconnect($host,$dbid,$dbpass,$dbname);

$user_name = trim($_POST['user_name']);
$password = $_POST['password'];
$introduction = $_POST['introduction'];
$password_confirm= $_POST['password_confirm'];

//--added
mysqli_query($conn, "set autocommit = 0");	// autocommit 해제
mysqli_query($conn, "set transaction isolation level serializable");	// isolation level 설정
mysqli_query($conn, "begin");	// begins a transation
//--added


//닉네임 중복 확인
$double_query = "select * from users where user_name = '$user_name'";
$double_res = mysqli_query($conn, $double_query);
$double_count = mysqli_num_rows($double_res);


if($double_count != 0){
	msg("이미 존재하는 닉네임입니다. 다른 닉네임을 입력하세요");
}//password confirmation
else if($password != $password_confirm){
	msg('비밀번호를 다시 확인해주세요');
}
else{
    $ret = mysqli_query($conn, "insert into users (user_name, password, introduction) values('$user_name', HEX(AES_ENCRYPT('$password', (SHA2('password',512)))), '$introduction')");
}



if(!$ret)
{
	//--added
	mysqli_query($conn, "rollback"); // user 정보 등록에 실패하였을 경우 rollback
	//--added
	
	echo mysqli_error($conn);
    msg('Query Error : '.mysqli_error($conn));
}
else
{
	//--added
	mysqli_query($conn, "commit"); // user 정보 등록에 성공하였을 경우 commit
	//--added
	
    s_msg ('성공적으로 사용자 등록 되었습니다');
    echo "<meta http-equiv='refresh' content='0; url=user_index.php'>";
}

?>

