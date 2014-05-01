<?php include(__DIR__."/../_site_header.php") ?>
<style>
	* {
	 	font-size:16px;
	}
	
	.content{
		line-height:160%;
	}
	
	.news-title{
		color: gray;
		text-decoration: underline;
	}
	.comment-row td{
	 	border-top:3px double black !important;
	}
</style>

<div class="container">
	
	<h2>您的參考意見已送出</h2>
	<p>感謝您提供的資訊</p>

	接下來你可以
	<ul>
		<li><a href="<?=site_url("/comment/rank")?>">到跳針留言榜</a></li>
		<li><a href="<?=site_url("/comment/user/?key=".$comment["userkey"])?>">到 <?=$comment["name"]?> 的跳針清單</a></li>
	</ul>
	
</div>



<?php if(is_login()){?>
<?php }?>
<?php include(__DIR__."/../_site_footer.php")?>