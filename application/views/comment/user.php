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
	
	<h2><?=$comments[0]["name"] ?> 近期跳針留言清單（<?=count($comments)>=100 ?"最近一百筆" :count($comments) ?>）<div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<p>跳針指數：<?=$count?></p>
	<hr />
	<p><a href="<?=site_url("/extension/download")?>" target="_blank" class="btn btn-primary">想要閱讀留言時馬上看到跳針指數嗎？安裝跳針小幫手就行了！</a></p>
	<?php if(is_login()){?>
	<hr />
	<a class="btn btn-default"  href="<?=site_url("/comment/user/?key=".h($comments[0]["userkey"])."&status=0")?>" href="">待審(<?=$stats[0]?>)</a>
	<a class="btn btn-default"  href="<?=site_url("/comment/user/?key=".h($comments[0]["userkey"])."&status=1")?>">跳針(<?=$stats[1]?>)</a>
	<a class="btn btn-default"  href="<?=site_url("/comment/user/?key=".h($comments[0]["userkey"])."&status=2")?>">ok(<?=$stats[2]?>)</a>
	<a class="btn btn-default"  href="<?=site_url("/comment/user/?key=".h($comments[0]["userkey"])."&status=-1")?>">其他(<?=$stats[3]?>)</a>
	<Br />
	<a class="btn btn-default" onclick='$(".btn-type-1").click()'>全列跳針</a>
	<a class="btn btn-default" onclick='$(".btn-type-0").click()'>全列待審</a>
	<?php }?>
	<pre>
跳針小幫手聲明：
 
本服務透過台灣獨特的「工人」智慧演算法來標示跳針言論，
「工人」智慧的標示趨向並不代表本網站立場。
 
言論自由時代，您有發表言論的自由，
別人也有對您的言論發表評論的自由。
「工人」智慧的跳針標示僅評論您的發言，而非針對您個人。
	</pre>
	<table class="table table-bordered table-comment">
		<tr class="comment-row">
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr>
			<td rowspan="2" width="100px">
				<?=get_comment_type_description($comment["type"])?>
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
			<td><a href="<?=site_url("comment/user/?key=".urlencode($comment["userkey"])."#".urlencode($comment["_id"]))?>" name="<?=$comment["_id"]?>"><?=_display_date_with_fulldate_ms($comment["time"]) ?></a></td>
		</tr>
		<tr>
			<td colspan="3" style="padding-left:20px;">
				<p style="min-height:50px;padding-top:10px;">
					<?=nl2br(h($comment["content"]))?>
				</p>
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
					<a  target="_blank" href="<?=site_url("comment/provide/?key=".$comment["_id"])?>" >修改補充或回應資料</a>
					<?php if(is_login()){ ?>
						<a  target="_blank" href="<?=site_url("comment/removeReply/?key=".rawurlencode($comment["_id"]))?>" >移除補充</a>
					<?php }?>					
				</div>
				<?php }else{ ?>
				<a href="<?=site_url("comment/provide/?key=".$comment["_id"])?>">補充或回應資料</a>
				<?php }?>
				
				
				<?php if(is_login()){?>
					<br />
					目前狀態：
					<a class="btn btn-confirm btn-default<?php if($comment["status"] == 2) {?> btn-primary <?php }?> btn-type-2" href="<?=site_url("comment/mark/".h($comment["_id"])."/2")?>">OK</a>
					<a class="btn btn-confirm btn-default<?php if($comment["status"] == 1) {?> btn-primary <?php }?> btn-type-1 " href="<?=site_url("comment/mark/".h($comment["_id"])."/1")?>">跳針</a>
					<a class="btn btn-confirm btn-default<?php if($comment["status"] == 0) {?> btn-primary <?php }?> btn-type-0" href="<?=site_url("comment/mark/".h($comment["_id"])."/0")?>">待審查</a>
					<a class="btn btn-confirm btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?> btn-type-3" href="<?=site_url("comment/mark/".h($comment["_id"])."/3")?>">很棒的留言</a>
					<a class="btn btn-confirm btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?> btn-type-4" href="<?=site_url("comment/mark/".h($comment["_id"])."/4")?>">廣告</a>
					&nbsp; | &nbsp;
				<?php }?>
			</td>
		</tr>			
		<?php }?>
	</table>
	<p><a href="<?=site_url("/extension/download")?>" target="_blank" class="btn btn-primary">想要閱讀留言時馬上看到跳針指數嗎？安裝跳針小幫手就行了！</a></p>
	
</div>


<?php function js_section(){ ?>

<?php if(is_login()){?>
	<script>
		$(function(){
			$(".table-comment").on("click",".btn-confirm-all",function(){
				var $btn = $(this);
				var type = $btn.data("type");
				var key = $btn.data("key");
				$(".controls").each(function(){
					var $this= $(this);
					if($this.data("key") == key){
						$this.find(".btn-type-"+type).click();
					}
				});
			});
			$(".table-comment").on("click",".btn-confirm",function(){
				var btn = this;
				$.get(btn.href,function(){
					$(btn).parent().find(".btn-primary").removeClass("btn-primary");
					$(btn).addClass("btn-primary");
				});
				return false;
			});
		});
	</script>
<?php }?>    
<?php }?>
<?php include(__DIR__."/../_site_footer.php")?>