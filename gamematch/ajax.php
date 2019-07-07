<?php
header('Content-Type: application/json'); 
header("Content-Type:text/html;charset=utf-8");
       
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

// 1. 데이터베이스에서 데이터를 가져옴
$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$check = $_POST["val"];
$output = array("able" => 0);
if ($res = mysqli_query($conn, 'SELECT user_name FROM users')) {
    // 2. 데이터베이스로부터 반환된 데이터를
    // 객체 형태로 가공함
    while($row = mysqli_fetch_array($res)){
	    if($row['user_name'] == $check || $check == ""){
	        $output["able"] = 1;
	        break;
	     }
    }
}

  
// 3, 4 최종 결과 데이터를 JSON 스트링으로 변환 후 출력
echo(json_encode($output));
?>

