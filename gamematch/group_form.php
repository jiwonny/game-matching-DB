<?
include "header.php";
include "config.php";    //데이터베이스 연결 설정파일
include "util.php";      //유틸 함수

$conn = dbconnect($host, $dbid, $dbpass, $dbname);
$mode = "등록";
$action = "group_insert.php";

if (array_key_exists("game_id", $_GET)) {
    $game_id = $_GET["game_id"];
    $query =  "select * from games where game_id = $game_id";
    $res = mysqli_query($conn, $query);
    $from_game = mysqli_fetch_array($res);
    if(!$from_game) {
        msg("게임이 존재하지 않습니다.");
    }
	
}

if (array_key_exists("group_id", $_GET)) {
    $group_id = $_GET["group_id"];
    $query =  "select * from groups natural join belongs natural join users where group_id = $group_id and role='manager'";
    $res = mysqli_query($conn, $query);
    $groups = mysqli_fetch_array($res);
    if(!$groups) {
        msg("그룹이 존재하지 않습니다.");
    }
    
    $mode = "수정";
    $action = "group_modify.php";
}

//for option of games
$games = array();
$query = "select * from games";
$res = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($res)) {
    $games[$row['game_id']] = $row['game_name'];
}
//
?>
    <div class="container">
        <form name="group_form" action="<?=$action?>" method="post" class="fullwidth">
            <input type="hidden" name="group_id" value="<?=$groups['group_id']?>"/>
            <h3><?=$from_game['game_name']?> 게임 그룹 정보 <?=$mode?></h3>
            <?if($mode === '수정'){
              	echo "<div style='font-style: italic; font-size: 15px;'>그룹의 매니저만 수정 가능합니다. 비밀번호를 입력하고 수정하세요</div>";
                echo "<br>";
              }
            ?>
            
            <div class="d-flex">
            	<p>
            	<?if($mode === '등록'){
		        		echo '<label for="user_name">User name 입력</label>';
		                echo "<input type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name' value='{$groups['user_name']}'/>";
            		}	
            	  else{
            	  	    echo '<label for="user_name">User name 입력</label>';
		                echo "<input readonly type='text' placeholder='사용자 닉네임 입력' id='user_name' name='user_name' value='{$groups['user_name']}'/>";
            	  }
				?>
				</p>
			</div>
			
            <p>
                <label for="password">비밀번호 입력</label>
                <input type="password"  id="password" name="password" value=""/>
            </p>
			
			<p>
				<label for="game_id">게임 선택</label>
                <select name="game_id" id="game_id">
                    <option value="-1">선택해 주십시오.</option>
                    <?
                        foreach($games as $id => $name) {
                            if($id == $groups['game_id'] || $id == $from_game['game_id']){
                                echo "<option value='{$id}' selected>{$name}</option>";
                            } else {
                                echo "<option value='{$id}'>{$name}</option>";
                            }
                        }
                    ?>
                </select>
			</p>
		
			<p>
                <label for="group_name">그룹 이름</label>
                <input type="text"  placeholder = "게임 그룹 이름 작성" id="group_name" name="group_name" value="<?=$groups['group_name']?>" />
            </p>
            
            <p>
                <label for="gr_introduction">그룹 소개</label>
                <input type="text"  placeholder = "게임 그룹 소개" id="gr_introduction" name="gr_introduction" value="<?=$groups['gr_introduction']?>" />
            </p>
            
            
            <p align="center"><button class="button primary large" onclick="javascript:return validate();"><?=$mode?></button></p>

            <script>
                function validate() {
                    if($("#game_id").val() == "-1") {
                        alert ("해당 게임을 선택하세요"); return false;
                    }
                    else if($("#group_name").val() == "") {
                        alert ("그룹 이름을 입력해 주십시오"); return false;
                    }
                    else if($("#gr_introduction").val() == ""){
                        alert ("그룹소개를 입력해 주십시오"); return false;
                    }
                    else if($("#user_name").val() == ""){
                        alert ("사용자 닉네임을 입력해 주십시오"); return false;
                    }
                   
                    return true;
                }
            </script>
        </form>
    </div>
<? include("footer.php") ?>
