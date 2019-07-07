<?php include ("header.php"); ?>
	<style>
		.banner{
			position: relative;
		}
		
		.banner img{
			-webkit-filter: grayscale(85%); /* Safari 6.0 - 9.0 */
			filter: grayscale(85%);
		}
		
		.banner-text{
			position: absolute;
			top: 20%;
		}
		
		.search-box{
			position: absolute;
			top: 70%;
		}
		
		
		.box1{
			width: 400px;
			height: 50px;
			background-color: white;
			border-top-left-radius: 15px;
			border-bottom-left-radius: 15px;
			border: 2px solid #424242;
			padding-left: 1rem;
			padding-right: 1rem;;
			margin: 0px;
		}
		
		.box2{
			width: 80px;
			height: 50px;
			border-top-right-radius: 15px;
			border-bottom-right-radius: 15px;
			background-color: #424242;
			color: white;
			-webkit-appearance: none;
		    border-style: none;
		    cursor: pointer;
			
			margin: 0px;
		}
		
	</style>
	<form action="search_view.php" method="post">
    <div class="banner container d-flex justify-content-center">
    	<img src="banner.jpg" style="width: 1200px; height: 460px;">
	    
	    <div class="banner-text container">
	    	<p class="d-flex justify-content-center" style="font-weight: bold; font-size: 60px; text-align: center; color: white; text-shadow: 6px 2px 2px gray;">GAME MATCHING<p>
	    	<p class="d-flex justify-content-center" style="font-weight: bold; font-size: 20px;">그룹에 참여해 함께 게임할 친구를 찾아보세요<p>
	    </div>
	    
	    
	    <div class="search-box container d-flex justify-content-center">
	    		<input class="box1" type="text" name="search_keyword" placeholder="게임/그룹/사용자 검색">
	    		<button class="box2" type="submit">검색</button>
	    </div>
    </div>
    </form>
    
    <div class="d-flex justify-content-center" style="margin-top: 30px; text-align: center;">
    	사용자 등록 후 함께 게임할 친구들을 찾아보세요.<br>게임 그룹을 만들어 함께 게임할 친구를 모집하거나 이미 있는 그룹에 참여해보세요.
    </div>
<?php include ("footer.php"); ?>