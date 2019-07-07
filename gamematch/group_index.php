<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$query = "select * from groups natural join belongs natural join games natural join users where role = 'manager'";
$res = mysqli_query($conn, $query);
if (!$res) {
     die('Query Error : ' . mysqli_error());
}

?>

<div class="container">
	<div class="d-flex justify-content-end" style="margin: 20px 0px 20px 20px;">
		<button type="button" class="btn btn-outline-primary" onclick="location.href='group_form.php'">그룹 새로 등록하기</button>
	</div>
	
	<table class="table">
	  <thead>
	    <tr>
	      <th scope="col" width="5%">#</th>
	      <th scope="col" width="15%">그룹 이름</th>
	      <th scope="col" width="15%">게임 이름</th>
	      <th scope="col" width="20%">그룹 소개</th>
	      <th scope="col" width="15%">매니저 이름</th>
	      <th scope="col" width="20%">그룹 생성시간</th>
	      <th scope="col" width="10%">그룹 가입</th>
	    </tr>
	  </thead>
	  <tbody>
		  <?
	        $row_index = 1;
	        while ($row = mysqli_fetch_array($res)) {
	            echo "<tr>";
	            echo "<th scope='row'>{$row_index}</th>";
	            echo "<td><a class='table-link' href='group_show.php?group_id={$row['group_id']}'>{$row['group_name']}</a></td>";
	            echo "<td>{$row['game_name']}</td>";
	            echo "<td>{$row['gr_introduction']}</td>";
	            echo "<td>{$row['user_name']}</td>";
	            echo "<td>{$row['gr_created_time']}</td>";
	            echo "<td><a href ='apply_form.php?group_id={$row['group_id']}' class='btn btn-primary'>가입</a></td>";
	            echo "</tr>";
	            $row_index++;
	        }
	        ?>
      </tbody>
	</table>
</div>
