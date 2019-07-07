<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$mode = "등록";
$action = "user_insert.php";

if (array_key_exists("user_id", $_GET)) {
    $user_id = $_GET["user_id"];
    $query =  "select * from users where user_id = $user_id";
    $res = mysqli_query($conn, $query);
    $users = mysqli_fetch_array($res);
    if(!$users) {
        msg("사용자가 존재하지 않습니다.");
    }

	//password 복호화
	$check_password = $_POST['check_password'];
	$password_query = "select AES_DECRYPT(UNHEX(password), SHA2('password', 512)) as password FROM users where user_id = $user_id";
	$password_res = mysqli_query($conn, $password_query);
	$password_origin = mysqli_fetch_array($password_res);
	
	if($password_origin['password'] != $check_password){
		msg("사용자 닉네임과 비밀번호가 일치하지 않습니다.");
		echo "<meta http-equiv='refresh' content='0; url=user_index.php'>";
	}

    $mode = "수정";
    $action = "user_modify.php";
}


$query = "select user_name from users";
$res = mysqli_query($conn, $query);

?>
    <div class="container">
        <form name="user_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="product_id" value="<?=$users['user_id']?>"/>
            <h3>사용자 정보 <?=$mode?></h3>
            
       
        	<? if($mode === "등록"){
        		echo " <div class='d-flex'>";
            	echo "<label for='user_name'>User name 입력</label>";
            	echo "<input type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name'/>";
            	echo "</div>";
        	  }
        	  else{
        	  	echo "<div style='font-style: italic; font-size: 14px;'>**닉네임은 수정 불가능합니다.**</div>";
        	  	echo " <div class='d-flex'>";
        	  	echo "<label for='user_name'>User name 입력</label>";
            	echo "<input readonly type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name' value='{$users['user_name']}'/>";
        		echo "</div>";
        	  }
        	 ?>
				<!--<button id = "usercheck" type="button" class="btn btn-outline-secondary">-->
				<!--	중복확인-->
				<!--</button>-->
				<!--<p id="usercheck_notice"></p>-->
		
			<br>
            <p>
                <label for="password">비밀번호 입력</label>
                <input type="password"  id="password" name="password" value=""/>
            </p>
			
			<p>
                <label for="password">비밀번호 확인</label>
                <input type="password"  id="password_confirm" name="password_confirm" value="<?=$password_confirm?>" />
            </p>
            
			<p>
                <label for="password">한줄 소개 작성</label>
                <input type="text"  placeholder = "간단한 소개 작성" id="introduction" name="introduction" value="<?=$users['introduction']?>" />
            </p>
            
            
            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

        
        	<script>
                function validate() {
                    if($("#user_name").val() == "") {
                        alert ("사용자 닉네임을 입력하세요"); return false;
                    }
                    else if($("#password").val() == "") {
                        alert ("비밀번호를 입력해 주십시오"); return false;
                    }
                    else if($("#password_confirm").val() == ""){
                        alert ("비밀번호 확인란을 작성해 주십시오"); return false;
                    }
                    else if($("#introduction").val() == "") {
                        alert ("자기소개란을 작성해 주십시오"); return false;
                    }
                   
                    return true;
                }
            </script>
        </form>
    </div>
<? include("footer.php") ?>

<script>
	// $("#usercheck").click(function(){
	// 	$name = $("#user_name").val();
	// 	console.log("ajax 전" + $name);
		
	// 	$.ajax({
	// 	    type: 'POST',
	// 	    dataType: 'json',
	// 	    url: './ajax.php',
	// 	    data: {val: $name},
	// 	    success: function(data) {
	// 	    	data = JSON.parse(data);
	// 	    	 console.log(data);
	// 	    },error: function (request, status, error) {
 //       		console.log(request.responseText+ "\nerror:"+ error);
 //   		}
	// 	});
		
	// })
</script>
