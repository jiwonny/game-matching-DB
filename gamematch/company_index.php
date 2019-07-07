<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$query = "select * from company";
$res = mysqli_query($conn, $query);
if (!$res) {
     die('Query Error : ' . mysqli_error());
}

?>

<div class="container">
	<table class="table">
	  <thead>
	    <tr>
	      <th scope="col" width="20%">#</th>
	      <th scope="col" width="30%">회사 이름</th>
	      <th scope="col" width="50%">회사 소개</th>
	    </tr>
	  </thead>
	  <tbody>
		  <?
	        $row_index = 1;
	        while ($row = mysqli_fetch_array($res)) {
	            echo "<tr>";
	            echo "<th scope='row'>{$row_index}</th>";
	            echo "<td>{$row['company_name']}</a></td>";
	            echo "<td>{$row['c_introduction']}</td>";
	            echo "</tr>";
	            $row_index++;
	        }
	        ?>
      </tbody>
	</table>
</div>
