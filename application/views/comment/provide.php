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
	
	<table class="table table-bordered table-comment">
		<tr class="comment-row">
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
		</tr>
		<tr>
			<td rowspan="2" width="150px">
				<?php if($comment["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td>
				<p>
					<?php 
						$user_url = comment_user_link($comment);
					?>
					<?php if($user_url != null){?>
					<a target="_blank" href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a>&nbsp;
					<?php }else{?>
						<?=h($comment["name"]) ?>&nbsp;
					<?php }?>
					<br />
					留言網頁：
					<a class="news-title" target="_blank"  href="<?=h($comment["url"]) ?>">
						<?php if(isset($comment["url_title"]) && $comment["url_title"] !="no-title"){ ?>
							<?=h($comment["url_title"]) ?>
						<?php }else{ ?>
							<?=h($comment["url"]) ?>
						<?php }?>
					</a>
				</p>
			</td>
			<td><?=_display_date_with_fulldate_ms($comment["time"]) ?></td>
		</tr>
		<tr>
			<td colspan="3" style="padding-left:20px;">
				留言內容：
				<p style="min-height:50px;padding-top:10px;">
					<?=nl2br(h($comment["content"]))?>
				</p>
				<?php if(isset($comment["reply"])){?>
					<div style="padding-left:30px;">
						<hr />
						<p style="color:red;">目前的回應資料：  </p>
						<p><?=nl2br(h($comment["reply"]["content"])) ?></p>
						<?php if(!empty($comment["reply"]["url"])){?>
							<p>相關連結:<a target="_blank" href="<?=h($comment["reply"]["url"])?>"><?=h($comment["reply"]["url"])?></a></p>
						<?php }?>
						<hr />
					</div>
					<?php }else{ ?>
				<?php }?>				
			</td>
		</tr>
		<tr>
			<td>提供參考資料</td>
			<td colspan="2">
				本機制的設立主要促進良性討論與資訊揭露而不是作為吵架平台，故請遵循以下規則：<br />
				<ul>
					<li> 純粹情緒性回應不受理 </li>
					<li> 以明確引用錯誤、理論錯誤為主 </li>
					<li> 盡量以連結取代長篇文字（最多140字） </li>
					<li> 受文對象是使用外掛或瀏覽留言的"閱讀者"，不是"留言者"。 </li>
					<li style="color:red;"> 不保證一定會處理/不一定能夠馬上處理  </li>
				</ul>
				<form method="POST" action="<?=site_url("comment/reply")?>" >
					<h2>提供參考資料</h2>
					<br />
					<?php if(isset($comment["reply"])){?>	 
						<textarea name="info" style="width:100%;height:100px;" maxlength="140"><?=nl2br(h($comment["reply"]["content"])) ?></textarea> <br />
						相關參考網址：<input type="text" name="url" value="<?=h($comment["reply"]["url"])?>" style="width:80%;" /> <br />
					<?php }else { ?>
						<textarea name="info" style="width:100%;height:100px;" maxlength="140"></textarea> <br />
						相關參考網址：<input type="text" name="url" style="width:80%;" /> <br />
					<?php }?>
					<br />
					<input type="hidden" name="id" value="<?=h($comment["_id"])?>" />
					<input type="submit" value="送出" class="btn btn-primary" />
				</form>
			</td>
		</tr>
	</table>
	
	
	
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary">馬上安裝跳針留言小幫手</a></p>
	
</div>

<?php function js_section(){ ?>

<?php }?>
<?php include(__DIR__."/../_site_footer.php")?>