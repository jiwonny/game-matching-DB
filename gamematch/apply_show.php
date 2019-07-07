<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);


if (array_key_exists("application_id", $_GET)) {
    $application_id = $_GET["application_id"];
    $query = "select * from applications natural join groups natural join users natural join games where application_id = $application_id";
    $res = mysqli_query($conn, $query);
    $application = mysqli_fetch_array($res);
    if(!$application) {
        msg("가입신청서가 존재하지 않습니다.");
    }
    $manager_query =  "select * from belongs natural join users natural join (select application_id, group_id from applications) as ap2 where application_id=$application_id and role='manager'";
	$manager_res = mysqli_query($conn, $manager_query);
	$manager = mysqli_fetch_array($manager_res);
}

?>
	<div class="container">
		<div class="game-card" style="width: 100%; border: solid 0.6px gray; border-radius : 6px; padding: 15px 25px 15px 25px;">
			<div class="row" style="margin: 15px 0px 0px 0px;">
				<div class="col-9" style="padding-left : 0px; font-weight: bold; font-size: 20px;">APPLICATION #<?= $application['application_id'] ?>&nbsp; <?= $application['user_name'] ?> 님의 가입 신청서</div>
        		<div class="col-3 d-flex justify-content-end">
	        		<?if($application['status'] === 'wait'){
	        				echo "<button data-href='apply_form.php?application_id={$application['application_id']}' class='btn btn-outline-dark btn-apply-modify' style='margin-right: 15px;'>수정</button>";
	        				
	        				echo "<form name='apply_show' action='apply_delete.php' method='post' class='fullwidth' onsubmit='delete_prompt()'>";
	        				echo "<input type='hidden' name='application_id' value='{$application['application_id']}'/>";
	        				echo "<input type='hidden' name='user_id' value='{$application['user_id']}'/>";
	        				echo "<input type='hidden' class='check_password' name='password' value=''/>";
	        				
	        				echo "<button class='delete-button btn btn-outline-danger'>삭제</button>";
	        				echo "</form>";
		        		}
	        		 else{
	        			echo "<button disabled class='btn btn-outline-dark' style='margin-right: 15px;'>수정</button>";
	        			echo "<button disabled class='btn btn-outline-danger'>삭제</button>";
	        			}
	        		?>
        		</div>
	    	</div>
			<hr/>
			
			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
				<div style="width: 120px; font-weight: bold;">게임</div>
				<div><?=$application['game_name']?></div>
			</div>
	
			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
				<div style="width: 120px; font-weight: bold;">그룹</div>
				<div><?=$application['group_name']?></div>
			</div>
			
			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
				<div style="width: 120px; font-weight: bold;">그룹 가입 사유</div>
				<div><?=$application['description']?></div>
			</div>
			
			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
				<div style="width: 120px; font-weight: bold;">가입요청시간</div>
				<div><?=$application['a_created_time']?></div>
			</div>
			
			<div class="d-flex" style="font-size: 15px; margin-bottom: 10px;">
				<div style="width: 120px; font-weight: bold;">상태</div>
				<? if($application['status'] == 'wait')
						echo "<div style='color: green; font-weight: bold;'>대기</div>";
				   else if($application['status'] == 'accepted')
						echo "<div style='color: blue; font-weight: bold;'>승인</div>";
					else echo "<div style='color: red; font-weight: bold;'>거절</div>";
				?>
			</div>
			
			<div class="d-flex" style="font-style: italic;font-size: 13px;">
				<p>**거절되거나 처리된 신청서는 수정 또는 삭제가 불가능합니다**</p>
			</div>
			
		</div>
		
		<form name="group_form" action="apply_status.php?application_id=<?=$application['application_id']?>" method="post" class="fullwidth" onsubmit="apply_prompt()">
            <input type="hidden" name="manager_id" value="<?=$manager['user_id']?>"/>
            <input type="hidden" class="apply_status" name="apply_status" value=""/>
            <input class="check_password" type="hidden" name="check_password" value=""/>
            
            
			<div class="d-flex justify-content-end" style="margin: 20px 0px 0px 10px;">
				<button type = 'submit'  class='btn btn-danger btn-apply btn-deny' style="margin-right: 20px;">거절하기</button>
				<button type = 'submit' class='btn btn-primary btn-apply btn-accept'>승인하기</button>
			</div>
		</form>
	
   </div>
   
   <script>
    $(".delete-button").click(function delete_prompt(){
     	if(confirm("정말 삭제하시겠습니까?")==true){
     		var passinput = prompt("'<?=$application['user_name']?>' 에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
			if(passinput == null) return false;
			else{
				$(".check_password").val(passinput);
				return true;
			}
     	}else return false;
    	
    });
    
   
    $(".btn-apply-modify").click(function(){
	      window.location = $(this).data('href');
    });
    
   
    $(".btn-apply").click(function apply_prompt(){
    
    	if($(this).hasClass('btn-accept')){
    			var passinput = prompt("'<?=$application['group_name']?>'의 매니저 '<?=$manager['user_name']?>'에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
				if(passinput == null) return false;
				else{
					$(".check_password").val(passinput);
					$(".apply_status").val('accept');
					return true;
				}
    	}
    	else if($(this).hasClass('btn-deny')){
    			var passinput = prompt("'<?=$application['group_name']?>'의 매니저 '<?=$manager['user_name']?>'에게만 허용된 권한입니다.\n 패스워드를 입력해주세요"+"");
				if(passinput == null) return false;
				else{
					$(".check_password").val(passinput);
					$(".apply_status").val('deny');
					return true;
    			}
    	}
 
    });
   </script>
<? include("footer.php") ?>
