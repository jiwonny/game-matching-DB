<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수
?>
<div class="container">
    <?
    $conn = dbconnect($host, $dbid, $dbpass, $dbname);
    $company_query = "select * from company";
    $game_query = "select * from games natural join company";
    $group_query = "select * from groups natural join belongs natural join games natural join users";
    $user_query = "select * from users";
    
    if (array_key_exists("search_keyword", $_POST)) {  // array_key_exists() : Checks if the specified key exists in the array
        $search_keyword = $_POST["search_keyword"];
       // $company_query = $company_query . " where company_name like '%$search_keyword%' or c_introduction like '%$search_keyword%'";
        $game_query =  $game_query . " where company_name like '%$search_keyword%' or game_name like '%$search_keyword%' or g_introduction like '%$search_keyword%'";
        $group_query = $group_query . " where role = 'manager' and (game_name like '%$search_keyword%' or group_name like '%$search_keyword%' or gr_introduction like '%$search_keyword%')";
        $user_query = $user_query . " where user_name like '%$search_keyword%' or introduction like '%$search_keyword%'";
    }
    
    $company_res = mysqli_query($conn, $company_query);
    $game_res = mysqli_query($conn, $game_query);
    $group_res = mysqli_query($conn, $group_query);
    $user_res = mysqli_query($conn, $user_query);
    
    // if (!$company_res) {
    //      die('Query Error 1: ' . mysqli_error());
    // }
    // else 
    if (!$game_res) {
         die('Query Error 2: ' . mysqli_error());
    }
    else if(!$group_res){
    	 die('Query Error 3: ' . mysqli_error());
    }
    else if(!$user_res){
    	die('Query Error 2: ' . mysqli_error());
    }
    ?>
    
    <h3>"<?=$search_keyword?>"으로 검색 결과</h3>

	<div id="game-section" style="margin-top: 50px;">
		<div style="margin: 15px 0px 15px 0px; font-size: 15px; font-weight: bold;">- "GAME" 내 검색결과</div>
		<table class="table s-table">
		  <thead>
		    <tr>
		      <th scope="col" width="5%">#</th>
		      <th scope="col" width="20%">게임이름</th>
		      <th scope="col" width="35%">게임소개</th>
		      <th scope="col" width="20%">회사이름</th>
		      <th scope="col" width="20%">게임 생성시간</th>
		    </tr>
		  </thead>
		  <tbody>
			  <?
				// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($game_res)) {
		            echo "<tr data-href='game_show.php?game_id={$row['game_id']}'>";
		            echo "<th scope='row'>{$row_index}</th>";
		        	echo "<td>{$row['game_name']}</td>";
		            echo "<td>{$row['g_introduction']}</td>";
		            echo "<td>{$row['company_name']}</td>";
		            echo "<td>{$row['g_created_time']}</td>";
		            echo "</tr>";
		            $row_index++;
		        }
		        ?>
	      </tbody>
		</table>
	</div>
	
	<div id="game-section" style="margin-top: 50px;">
		<div style="margin: 15px 0px 15px 0px; font-size: 15px; font-weight: bold;">- "GROUP" 내 검색결과</div>
		<table class="table s-table">
		  <thead>
		    <tr>
		      <th scope="col" width="5%">#</th>
		      <th scope="col" width="15%">그룹이름</th>
		      <th scope="col" width="15%">게임이름</th>
		      <th scope="col" width="15%">매니저</th>
		      <th scope="col" width="30%">그룹소개</th>
		      <th scope="col" width="20%">그룹 생성시간</th>
		    </tr>
		  </thead>
		  <tbody>
			  <?
				// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($group_res)) {
		            echo "<tr data-href='group_show.php?group_id={$row['group_id']}'>";
		            echo "<th scope='row'>{$row_index}</th>";
		        	echo "<td>{$row['group_name']}</td>";
		            echo "<td>{$row['game_name']}</td>";
		            echo "<td>{$row['user_name']}</td>";
		            echo "<td>{$row['gr_introduction']}</td>";
		            echo "<td>{$row['g_created_time']}</td>";
		            echo "</tr>";
		            $row_index++;
		        }
		        ?>
	      </tbody>
		</table>
	</div>
	
	<div id="game-section" style="margin-top: 50px;">
		<div style="margin: 15px 0px 15px 0px; font-size: 15px; font-weight: bold;">- "USER" 내 검색결과</div>
		<table class="table s-table">
		  <thead>
		    <tr>
		      <th scope="col" width="20%">#</th>
		      <th scope="col" width="30%">사용자이름</th>
		      <th scope="col" width="50%">사용자 소개</th>
		   
		    </tr>
		  </thead>
		  <tbody>
			  <?
				// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($group_res)) {
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
	
	
</div>

<script>
	 $(document).ready(function(){
		    $('.s-table tbody tr').click(function(){
		    	//console.log($(this).data('href'));
		        window.location = $(this).data('href');
		       
		    });
	});
</script>
<? include("footer.php") ?>
