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
	
	<h2><?=$comments[0]["name"] ?> 近期跳針留言清單（<?=count($comments)>=100 ?"最近一百筆" :count($comments) ?>）<div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary">馬上安裝跳針留言小幫手</a></p>
	<table class="table table-bordered">
		<tr>
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr>
			<td>
				<?php if($comment["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td><a target="_blank"  href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a></td>
			<td><?=_display_date_with_fulldate_ms($comment["time"]) ?></td>
		</tr>
		<tr>
			<td colspan="4" style="padding-left:40px;">
				<a target="_blank"  href="<?=h($comment["url"]) ?>"><?=h($comment["url"]) ?></a>
				<hr />
				<?=nl2br(h($comment["content"]))?>
			</td>
		</tr>			
		<?php }?>
	</table>
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary">馬上安裝跳針留言小幫手</a></p>
	
</div>

<?php include(__DIR__."/../_site_footer.php")?>