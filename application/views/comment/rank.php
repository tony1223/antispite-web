<?php include(__DIR__."/../_site_header.php")?>
<style>
	* {
	 	font-size:16px;
	}
	
	.content{
		line-height:160%;
	}
</style>
<div class="container">
	
	<h2>跳針排行榜<div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<table class="table table-bordered">
		<tr>
			<td>類型</td>
			<td>留言者</td>
			<td>跳針數量</td>
			<td>留言清單連結</td>
			<td>最後紀錄時間</td>
		</tr>
		<?php foreach($users as $user){?>
		<tr>
			<td>
				<?php if($user["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td><a target="_blank" href="<?=h(comment_user_link($user,"user"))?>"><?=h($user["name"]) ?></a></td>
			<td><?=h($user["count"])?> </td>
			<td><a target="_blank"  href="<?=site_url("comment/user/?key=".rawurlencode($user["user"])) ?>">跳針留言清單</a></td>
			<td><?=_display_date_with_fulldate_ms($user["last_update"]) ?></td>
		</tr>
		<?php }?>
	</table>
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary">馬上安裝跳針留言小幫手</a></p>
</div>

<?php include(__DIR__."/../_site_footer.php")?>