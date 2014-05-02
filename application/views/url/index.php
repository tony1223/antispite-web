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
	
	<h2>跳針留言相關新聞列表 <div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	
	<a class="btn btn-default" href="<?=site_url("url/hot")?>">熱門跳針留言網址</a>
	<table class="table table-bordered table-url">
		<tr class="comment-row">
			<td>#</td>
			<td>標題</td>
			<td>跳針指數</td>
			<td>紀錄時間</td>
		</tr>
		<?php foreach($urls as $ind => $url){?>
		
			<?php if(isset($url["resolved_date"])){?>
			<tr>
				<td><?=h($ind +1 )?></td>
				<td>
					<?=h($url["title"])?>
					<br />
					<a target="_blank" href="<?=h($url["_id"])?>"><?=h($url["_id"])?></a>
				</td>
				<td><?=h($url["count"])?></td>
				<td>
					<?=_display_date_with_fulldate_ms($url["createDate"])?>
				</td>
			</tr>			
			<?php }else{ ?>
			<tr>
				<td><?=h($ind +1 )?></td>
				<td>
					<a target="_blank" href="<?=h($url["_id"])?>"><?=h($url["_id"])?></a>
				</td>
				<td><?=h($url["count"])?></td>
				<td>
					<?=_display_date_with_fulldate_ms($url["createDate"])?>
				</td>
			</tr>				
			<?php }?>
		<?php }?>
	</table>
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary install_extension">馬上安裝跳針留言小幫手</a></p>
	
</div>


<?php function js_section(){ ?>
 
<?php }?>
<?php include(__DIR__."/../_site_footer.php")?>