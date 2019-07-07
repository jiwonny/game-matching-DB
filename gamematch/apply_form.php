<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$mode = "신청";
$action = "apply_insert.php";


if (array_key_exists("group_id", $_GET)) {
    $group_id = $_GET["group_id"];
    $query = "select * from groups where group_id = $group_id";
    $res = mysqli_query($conn, $query);
    $groups = mysqli_fetch_array($res);
    if(!$groups) {
        msg("그룹이 존재하지 않습니다.");
    }
}

if (array_key_exists("application_id", $_GET)) {
    $application_id = $_GET["application_id"];
    $query = "select * from applications natural join users where application_id = $application_id";
    $res = mysqli_query($conn, $query);
    $application = mysqli_fetch_array($res);
    if(!$application) {
        msg("그룹 가입 신청서가 존재하지 않습니다.");
    }
    
    $mode = "수정";
    $action = "apply_modify.php";
}

?>
    <div class="container">
        <form name="group_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="group_id" value="<?=$groups['group_id']?>"/>
            <input type="hidden" name="application_id" value="<?=$application['application_id']?>"/>
            
            <h3><b><?=$groups['group_name']?></b> 그룹 가입 <?=$mode?></h3>
            <?if($mode === "수정")
            	echo "<div style='font-style: italic; font-size: 15px;'>가입 신청서를 작성했던 본인만 수정 가능합니다. 비밀번호를 입력하고 수정하세요</div>";
            ?>
            <div class="d-flex">
	           	<p>
            		<?if($mode === '신청'){
			        		echo '<label for="user_name">User name 입력</label>';
			                echo "<input type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name' value='{$application['user_name']}'/>";
	            		}	
	            	  else{
	            	  	    echo '<label for="user_name">User name 입력</label>';
			                echo "<input readonly type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name' value='{$application['user_name']}'/>";
	            	  }
					?>
				</p>

			</div>
			
            <p>
                <label for="password">비밀번호 입력</label>
                <input type="password"  id="password" name="password" value="" />
            </p>
			
			
			<p>
                <label for="description">그룹 가입 사유</label>
                <input type="text"  id="description" name="description" value="<?=$application['description']?>" />
            </p>
		
            
            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                    if($("#description").val() == "") {
                        alert ("그룹 가입 사유를 입력해주세요"); return false;
                    }
                    else if($("#user_name").val() == ""){
                        alert ("사용자 닉네임을 입력해 주십시오"); return false;
                    }
                    else if($("#user_name").val() == "")) {
                        alert ("사용자 닉네임을 입력해 주십시오"); return false;
                    }
                   
                    return true;
                }
            </script>
        </form>
    </div>

<? include("footer.php") ?>
