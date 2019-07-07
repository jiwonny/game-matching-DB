<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if (array_key_exists("group_id", $_GET)) {
    $group_id = $_GET["group_id"];
    $query = "select * from groups natural join games natural join belongs natural join users where group_id= '$group_id' and role = 'manager'";
    $res = mysqli_query($conn, $query);
    $group = mysqli_fetch_assoc($res);
    if (!$group) {
        msg("해당 그룹이 존재하지 않습니다.");
    }
    
    $wait_query = "select * from users natural join applications where group_id = '$group_id' and status='wait'";
    $wait_res = mysqli_query($conn, $wait_query);
    
    $accept_query = "select * from users natural join belongs where group_id = '$group_id'";
    $accept_res = mysqli_query($conn, $accept_query);
}

?>
    <div id="group-section" class="container">
    	<div class="game-card" style="width: 100%; border: solid 0.6px gray; border-radius : 6px; padding: 15px 25px 15px 25px;">
    		<div class="row" style="margin: 15px 0px 0px 0px;">
    			<div class="col-5" style="padding-left : 0px; font-weight: bold; font-size: 20px;">GROUP #<?= $group['group_id'] ?>&nbsp; <?= $group['group_name'] ?></div>
	        		<div class="col-7 d-flex justify-content-end">
		        		<button onclick="location.href='group_form.php?group_id=<?=$group['group_id']?>'" class="btn btn-outline-dark" style="margin-right: 15px;">수정</button>
		        		
		        		<form name="group_show" action="group_delete.php" method="post" class="fullwidth" onsubmit="delete_prompt()">
		        			<input type="hidden" name="group_id" value="<?=$group['group_id']?>"/>
		        			<input type="hidden" name="manager_id" value="<?=$group['user_id']?>"/>
				            <input class="check_password" type="hidden" name="check_password" value=""/>
		        			<button id="delete-button" class="btn btn-outline-danger">삭제</button>
		        		</form>
		        		
	        		</div>
        	</div>
    		<hr/>
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">게임</div>
    			<div><?=$group['game_name']?></div>
    		</div>

			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">매니저</div>
    			<div><?=$group['user_name']?></div>
    		</div>
    		
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">그룹 소개</div>
    			<div><?=$group['gr_introduction']?></div>
    		</div>
    		
    		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
    			<div style="width: 100px; font-weight: bold;">생성시간</div>
    			<div><?=$group['gr_created_time']?></div>
    		</div>
    		
    	</div>
    
    	<div class="d-flex justify-content-end" style="margin: 20px 0px 0px 0px;">
			<a href='apply_form.php?group_id=<?=$group['group_id']?>' class='btn btn-primary'>그룹 가입하기</a>
		</div>
    </div>
	
	
	<div id= "member-section" class="container" style="margin-top: 20px;">
		<div style="margin: 15px 0px 15px 0px; font-size: 20px; font-weight: bold;">- 그룹 멤버 목록</div>
		<table class="table mem-table" style="width: 40%;">
		  <thead>
		    <tr>
		      <th scope="col" width="20%">#</th>
		      <th scope="col" width="80%">사용자 닉네임</th>
		    </tr>
		  </thead>
		  <tbody>
			  <?
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($accept_res)) {
		            echo "<tr data-href='user_show.php?user_id={$row['user_id']}'>";
		            echo "<th scope='row'>{$row_index}</th>";
		            echo "<td>{$row['user_name']}</td>";
		            echo "</tr>";
		            $row_index++;
		        }
		        ?>
	      </tbody>
		</table>
	</div>
	
	
	<div id= "wait-section" class="container" style="margin-top: 60px;">
		<div style="margin: 15px 0px 15px 0px; font-size: 20px; font-weight: bold;">- 그룹 가입 대기 목록</div>
		<table class="table mem-table">
		  <thead>
		    <tr>
		      <th scope="col" width="15%">상태</th>
		      <th scope="col" width="15%">사용자 닉네임</th>
		      <th scope="col" width="45%">가입사유</th>
		      <th scope="col" width="25%">가입요청시간</th>
		    </tr>
		  </thead>
		  <tbody>
			  <?
				// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
		        $row_index = 1;
		        while ($row = mysqli_fetch_array($wait_res)) {
		            echo "<tr data-href='apply_show.php?application_id={$row['application_id']}'>";
		            echo "<td style='color: green; font-weight: bold;'>대기</td>";
		            echo "<td>{$row['user_name']}</td>";
		            echo "<td>{$row['description']}</td>";
		            echo "<td>{$row['a_created_time']}</td>";
		            echo "</tr>";
		            $row_index++;
		        }
		        ?>
	      </tbody>
		</table>
	</div>
	
    <script>
	     $("#delete-button").click(function delete_prompt(){
	     	if(confirm("정말 삭제하시겠습니까?")==true){
	     		var passinput = prompt("'<?=$group['group_name']?>'의 매니저 '<?=$group['user_name']?>'에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
				if(passinput == null) return false;
				else{
					$(".check_password").val(passinput);
					return true;
				}
	     	}else return false;
	    	
    	});
		
   	    $(document).ready(function(){
		    $('.mem-table tbody tr').click(function(){
		    	//console.log($(this).data('href'));
		        window.location = $(this).data('href');
		       
		    });
		});
	</script>
<? include("footer.php") ?>