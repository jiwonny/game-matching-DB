<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);

if (array_key_exists("user_id", $_GET)) {
    $user_id = $_GET["user_id"];
    $user_query = "select * from users where user_id = $user_id";
    $user_res = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_res);
    if (!$user) {
        msg("해당 사용자가 존재하지 않습니다.");
    }
    
    $apply_query = "select * from users natural join applications natural join groups where user_id = $user_id";
    $apply_res = mysqli_query($conn, $apply_query);
    
    $manager_query = "select * from users natural join games natural join belongs natural join groups where user_id = $user_id and role='manager'";
	$manager_res = mysqli_query($conn, $manager_query);
	
}
?>
	<div class="container">
		<div class="row">
			<div class="col-6" style="margin-bottom: 20px;font-size: 20px; font-weight: bold;"><?=$user['user_name']?>님의 프로필 정보</div>
			
			<div class="col-6 d-flex justify-content-end">
	    		<form name="user_show" action="" method="post" class="fullwidth user_form" onsubmit="user_prompt()">
	    			<input type="hidden" name="user_id2" value="<?=$user['user_id']?>"/>
	    			<input type="hidden" class="check_password" name="check_password" value=""/>
	    			
	    			<button class="modify-button user-btn btn btn-outline-dark" style="margin-right: 15px;">프로필 수정</button>
	    			<button class="delete-button user-btn btn btn-outline-danger">프로필 삭제</button>
		    	</form>
		    </div>
		</div>
		
		<hr/>
		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
			<div style="width: 100px; font-weight: bold;">닉네임 정보</div>
			<div>#<?=$user['user_id']?>&nbsp; <?=$user['user_name']?></div>
		</div>
		
		<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
			<div style="width: 100px; font-weight: bold;">소개</div>
			<div><?=$user['introduction']?></div>
		</div>
		
		<div id="manage-section" style="margin-top: 50px;">
			<div style="margin: 15px 0px 15px 0px; font-size: 17px; font-weight: bold;">- <?=$user['user_name']?>님이 Manager인 그룹 목록</div>
			<table class="table mem-table">
			  <thead>
			    <tr>
			      <th scope="col" width="10%">#</th>
			      <th scope="col" width="30%">그룹이름</th>
			      <th scope="col" width="30%">게임이름</th>
			      <th scope="col" width="30%">그룹 생성시간</th> 
			    </tr>
			  </thead>
			  <tbody>
				  <?
					// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
			        $row_index = 1;
			        while ($row = mysqli_fetch_array($manager_res)) {
			            echo "<tr data-href='group_show.php?group_id={$row['group_id']}'>";
			            echo "<th scope='row'>{$row_index}</th>";
			        	echo "<td>{$row['group_name']}</td>";
			            echo "<td>{$row['game_name']}</td>";
			            echo "<td>{$row['gr_created_time']}</td>";
			            echo "</tr>";
			            $row_index++;
			        }
			        ?>
		      </tbody>
			</table>
		</div>
	
		<div id="apply-section" style="margin-top: 50px;">
			<div style="margin: 15px 0px 15px 0px; font-size: 17px; font-weight: bold;">- <?=$user['user_name']?>님의 가입신청 목록</div>
			<table class="table mem-table">
			  <thead>
			    <tr>
			      <th scope="col" width="10%">상태</th>
			      <th scope="col" width="25%">그룹이름</th>
			     <th scope="col" width="40%">가입사유</th>
			      <th scope="col" width="25%">가입요청시간</th> 
			    </tr>
			  </thead>
			  <tbody>
				  <?
					// mysqli_data_seek( $user_res, 0 ); //쿼리 재사용
			        $row_index = 1;
			        while ($row = mysqli_fetch_array($apply_res)) {
			            echo "<tr data-href='apply_show.php?application_id={$row['application_id']}'>";
			            if($row['status']==='wait')
			            	echo "<td style='color: green; font-weight: bold;'>대기</td>";
			            else if($row['status'] === 'denied')
			            	echo "<td style='color: red; font-weight: bold;'>거절</td>";
			        	else echo "<td style='color: blue; font-weight: bold;'>승인</td>";
			        	echo "<td>{$row['group_name']}</td>";
			            echo "<td>{$row['description']}</td>";
			            echo "<td>{$row['a_created_time']}</td>";
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
		    $('.mem-table tbody tr').click(function(){
		    	//console.log($(this).data('href'));
		        window.location = $(this).data('href');
		       
		    });
	});
	
	
	$(".user-btn").click(function user_prompt(){
		if($(this).hasClass('delete-button')){
			if(confirm("정말 삭제하시겠습니까?")==true){
	     		var passinput = prompt("'<?=$user['user_name']?>'에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
				if(passinput == null) return false;
				else{
					$(".user_form").attr('action', 'user_delete.php');
					$(".check_password").val(passinput);
					return true;
				}
    		}else return false;
		}
		else if($(this).hasClass('modify-button')){
			var passinput = prompt("'<?=$user['user_name']?>'에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
			if(passinput == null) return false;
			else{
				$(".user_form").attr('action', 'user_form.php?user_id=<?=$user['user_id']?>');
				$(".check_password").val(passinput);
				return true;
			}
		}
     	
    });
    	
	 //function deleteConfirm(user_id) {
  //      if (confirm("정말 삭제하시겠습니까?") == true){    //확인
  //          window.location = "user_delete.php?user_id=" + user_id;
  //      }else{   //취소
  //          return;
  //      }
  //  }
	</script>
	
<? include("footer.php") ?>