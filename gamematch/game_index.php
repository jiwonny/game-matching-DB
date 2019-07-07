<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$query = "select * from games natural join company";
$res = mysqli_query($conn, $query);
if (!$res) {
     die('Query Error : ' . mysqli_error());
}

?>

<div class="container">
	<!--<div class="d-flex justify-content-end" style="margin: 20px 0px 20px 20px;">-->
	<!--	<button type="button" class="btn btn-outline-primary" onclick="location.href='game_form.php'">게임 새로 등록하기</button>-->
	<!--</div>-->

	<div class="row">
		<?
	    $row_index = 1;
	    while ($row = mysqli_fetch_array($res)) {
	    	echo "<div class='col-3'>";
	        echo "<div class='card' style='width:15rem; margin: 15px;'>";
	        echo "<div class='card-body'>";
	        echo "<h5 class='card-title' style='font-weight: bold;'>{$row['game_name']}</h5>";
	        echo "<p class='card-text' style='font-size: 14px;'>{$row['game_type']}</p>";
	        echo "<p class='card-text' style='font-size: 13px;'>{$row['company_name']}</p>";
	        echo "<p class='card-text' style='font-size: 12px; color: gray; font-style: italic;'>{$row['g_created_time']}</p>";
	        echo "<a href ='game_show.php?game_id={$row['game_id']}' class='btn btn-primary'>그룹 보러가기</a>";
	        echo "</div>";
	        echo "</div>";
    		echo "</div>";
	        $row_index++;
	    }
	    ?>
	</div>
</div>
