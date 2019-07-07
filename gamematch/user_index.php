<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$query = "select * from users";
$res = mysqli_query($conn, $query);
if (!$res) {
     die('Query Error : ' . mysqli_error());
}

?>

<div class="container">
	<div class="d-flex justify-content-end" style="margin: 20px 0px 20px 20px;">
		<button type="button" class="btn btn-outline-primary" onclick="location.href='user_form.php'">사용자 등록하기</button>
	</div>
	<table class="table mem-table">
	  <thead>
	    <tr data-href="">
	      <th scope="col" width="10%">#</th>
	      <th scope="col" width="30%">사용자 닉네임</th>
	      <th scope="col" width="60%">사용자 소개글</th>
	    </tr>
	  </thead>
	  <tbody>
		  <?
	        $row_index = 1;
	        while ($row = mysqli_fetch_array($res)) {
	            echo "<tr data-href='user_show.php?user_id={$row['user_id']}'>";
	            echo "<th scope='row'>{$row_index}</th>";
	            echo "<td>{$row['user_name']}</td>";
	            echo "<td>{$row['introduction']}</td>";
	            echo "</tr>";
	            $row_index++;
	        }
	        ?>
      </tbody>
	</table>
</div>

<script>
 $(document).ready(function(){
	    $('.mem-table tbody tr').click(function(){
	    	//console.log($(this).data('href'));
	        window.location = $(this).data('href');
	       
	    });
	});
</script>
