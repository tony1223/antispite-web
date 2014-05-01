<?php include(__DIR__."/../_site_header.php")?>
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
	
	<h2>近期跳針留言清單（<?=count($comments)>=100 ?"最近一百筆" :count($comments) ?>）<div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<p><a href="<?=site_url("extension/download")?>" target="_blank" >想要即時看到跳針資料嗎？ 馬上安裝跳針留言小幫手</a></p>
	<table class="table table-bordered">
		<tr class="comment-row">
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr class="comment-row">
			<td rowspan="2" width="100px">
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
					（<a target="_blank"  href="<?=site_url("comment/user/?key=".rawurlencode($comment["userkey"])) ?>">瀏覽更多 <?=h($comment["name"]) ?> 的跳針留言</a>）
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
				<div style="min-height:50px;padding-top:10px;">
					<div class="comment-content">
						<?=nl2br(h($comment["content"]))?>
					</div>
					<?php 
					if(isset($comment["reply"])){?>
					<div style="padding-left:30px;">
						<hr />
						<p style="color:red;">小幫手網友想說：  </p>
						<p><?=nl2br(h($comment["reply"]["content"])) ?></p>
						<?php if(!empty($comment["reply"]["url"])){?>
							<p>相關連結: 
								<a href="<?=h($comment["reply"]["url"])?>">
								<?php if(isset($comment["reply"]["url_title"])){?>
									<?=h($comment["reply"]["url_title"])?>
								<?php }else{ ?>
									<?=h($comment["reply"]["url"])?>
								<?php }?>
								</a>
							</p>
						<?php }?>
						<hr />
						<a  target="_blank" href="<?=site_url("comment/provide/?key=".rawurlencode($comment["_id"]))?>" >修改補充或回應資料</a>
						<?php if(is_login()){ ?>
							<a  target="_blank" href="<?=site_url("comment/removeReply/?key=".rawurlencode($comment["_id"]))?>" >移除補充</a>
						<?php }?>
					</div>
					<?php }else{ ?>
					<a  target="_blank" href="<?=site_url("comment/provide/?key=".rawurlencode($comment["_id"]))?>">補充或回應資料</a>
					<?php }?>
										
				</div>
			</td>
		</tr>			
		<?php }?>
	</table>
	<p><a href="<?=site_url("extension/download")?>" target="_blank" >想要即時看到跳針資料嗎？ 馬上安裝跳針留言小幫手</a></p>
</div>

<?php include(__DIR__."/../_site_footer.php")?>