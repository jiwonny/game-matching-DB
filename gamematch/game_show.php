<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if (array_key_exists("game_id", $_GET)) {
    $game_id = $_GET["game_id"];
    $query = "select * from games natural join company where game_id = $game_id";
    $res = mysqli_query($conn, $query);
    $game = mysqli_fetch_assoc($res);
    if (!$game) {
        msg("해당 게임이 존재하지 않습니다.");
    }
    
    $group_query = "select * from games natural join groups natural join users natural join belongs where game_id = '$game_id' and role='manager'";
    $group_res = mysqli_query($conn, $group_query);
}
?>
    <div id="game-section" class="container">
    	<div class="game-card" style="width: 100%; border: solid 0.6px gray; border-radius : 6px; padding: 15px 25px 15px 25px;">
    		<div class="row" style="margin: 15px 0px 0px 0px;">
    			<div class="col-5" style="padding-left : 0px; font-weight: bold; font-size: 20px;">#<?= $game['game_id'] ?>&nbsp; <?= $game['game_name'] ?></div>
	        		<!--<div class="col-7 d-flex justify-content-end">-->
		        	<!--	<button onclick="location.href='game_form.php?game_id=<?#=$game['game_id']?>'" class="btn btn-outline-dark" style="margin-right: 15px;">수정</button>-->
		        		<!--<button class="btn btn-outline-danger">삭제</button>-->
	        		<!--</div>-->
        	</div>
    		<hr/>
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">장르</div>
    			<div><?=$game['game_type']?></div>
    		</div>

    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">게임소개</div>
    			<div><?=$game['g_introduction']?></div>
    		</div>
    		
    			
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">게임회사</div>
    			<div><?=$game['company_name']?> ( <?=$game['c_introduction']?> )</div>
    		</div>
    		
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">생성시간</div>
    			<div><?=$game['g_created_time']?></div>
    		</div>
    		
    	</div>
    </div>
    
   
    <div id= "group-section" class="container" style="margin-top: 40px;">
    	<div class="d-flex justify-content-end" style="margin: 20px 0px 20px 20px;">
			<button type="button" class="btn btn-outline-primary" onclick="location.href='group_form.php?game_id=<?=$game['game_id']?>'">그룹 새로 등록하기</button>
		</div>
	
		<table class="table">
		  <thead>
		    <tr>
		      <th scope="col" width="5%">#</th>
		      <th scope="col" width="20%">그룹 이름</th>
		      <th scope="col" width="30%">그룹 소개</th>
		      <th scope="col" width="15%">매니저 이름</th>
		      <th scope="col" width="20%">그룹 생성시간</th>
		      <th scope="col" width="10%">그룹 가입</th>
		    </tr>
		  </thead>
		  <tbody>
			  <?
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($group_res)) {
		            echo "<tr>";
		            echo "<th scope='row'>{$row_index}</th>";
		            echo "<td><a class='table-link' href='group_show.php?group_id={$row['group_id']}'>{$row['group_name']}</a></td>";
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
    <script>
	 //   $(document).ready(function(){
		//     $('.table tr').click(function(){
		//         window.location = $(this).attr('href');
		//         return false;
		//     });
		// });
		
		
    </script>
<? include("footer.php") ?>