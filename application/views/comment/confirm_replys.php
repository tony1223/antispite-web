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
	
	.comment-row td,.comment-row-head td{
	 	border-top:3px double black !important;
	}
	
	.table-confirm-user{
		position: fixed;
		background: white;
		width: auto;
		height: 241px;
		display: block;
		overflow: auto;
		top: 53px;
		left: 0;
		border: 1px solid red;
		padding:20px;
	}
	
	.page-controls{
		position: fixed;
		background: white;
		width: 340px;
		display: block;
		overflow: auto;
		top: 50px;
		left: 30%;
		padding:20px;
		border: 1px solid red;
	}
	
	.table-confirm .reply{
		border:1px solid #DDDDDD;
		padding:20px;
	}
	.table-confirm .reply-0{
		border:1px solid #DDDDDD;
	}
	.table-confirm .reply-1{
		border:1px solid red;
	}
	.table-confirm .reply-2{
		border:1px solid gray;
	}
</style>
<div class="container">
	
	<h2>待確認留言資訊清單</h2>

	<table class="table table-bordered table-confirm">
		<tr class="comment-row-head">
			<td>key</td>
			<td>回報時間</td>			
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
			<td>回報數</td>
		</tr>
		<?php foreach($comment_replys as $ind => $comment_reply){
			$comment = $comment_reply["comment"];
			$replys = $comment_reply["replys"];
			?>
		<tr class="comment-row comment-handle" data-url='<?=$comment["url"]?>' data-user='<?=$comment["userkey"]?>'>
			<td><?=h($comment["_id"]) ?></td>
			<td><?=_display_date_with_fulldate_ms($comment["createDate"]) ?></td>
			<td>
				<?php if($comment["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td>
				<a name="<?=h($comment["userkey"])?>" href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a> (<a target="_blank"  href="<?=site_url("comment/user/?key=".rawurlencode($comment["userkey"])) ?>">瀏覽 <?=h($comment["name"]) ?> 的跳針留言</a>) <br />
				<?php if($comment["count"] >0 ){?>
					<span style='color:red;'>目前跳針指數 <?=$comment["count"]?></span>
				<?php }?>
			</td>
			<td><?=_display_date_with_fulldate_ms($comment["time"]) ?></td>
			<td>
				<?=count($comment["reporters"]) ?>
				<?php foreach($comment["reporters"] as $reporter){ ?>
				<?=h($reporter)?>,
				<?php }?>
			</td>
		</tr>
		<tr class="controls comment-handle" data-url='<?=$comment["url"]?>' data-user='<?=$comment["userkey"]?>' data-key="<?=h($comment["userkey"])?>">
			<td colspan="6" style="padding-left:40px;">
				<a target="_blank" class="news-title" href="<?=h($comment["url"]) ?>">
					<?php if(isset($comment["url_title"]) && $comment["url_title"] !="no-title"){ ?>
						<?=h($comment["url_title"]) ?>
					<?php }else{ ?>
						<?=h($comment["url"]) ?>
					<?php }?>				
				</a>
				<button 
					<?php if(isset($comment["url_title"]) && $comment["url_title"] !="no-title"){ ?>
						data-title="<?=h($comment["url_title"]) ?>"
					<?php }else{ ?>
						data-title="<?=h($comment["url"]) ?>"
					<?php }?>				
					data-url='<?=$comment["url"]?>' class="btn btn-default btn-filter-url">Filter</button>
				<hr />
				<div class="comment-content">
					<?=nl2br(h($comment["content"]))?>
				</div>
				<?php if(isset($comment["reply"])){?>
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
						<a href="<?=site_url("comment/provide/?key=".$comment["_id"])?>" >修改補充或回應資料</a>
						<?php if(is_login()){ ?>
							<a  target="_blank" href="<?=site_url("comment/removeReply/?key=".rawurlencode($comment["_id"]))?>" >移除補充</a>
						<?php }?>						
					</div>
					<?php }else{ ?>
						<a href="<?=site_url("comment/provide/?key=".$comment["_id"])?>">補充或回應資料</a>
						<?php if(is_login()){ ?>
							<a  target="_blank" href="<?=site_url("comment/removeReply/?key=".rawurlencode($comment["_id"]))?>" >移除補充</a>
						<?php }?>					
				<?php }?>
				<hr />
				目前狀態：
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 2) {?> btn-primary <?php }?> btn-type-2" href="<?=site_url("comment/mark/".h($comment["_id"])."/2")?>">OK</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 1) {?> btn-primary <?php }?> btn-type-1 " href="<?=site_url("comment/mark/".h($comment["_id"])."/1")?>">跳針</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 0) {?> btn-primary <?php }?> btn-type-0" href="<?=site_url("comment/mark/".h($comment["_id"])."/0")?>">待審查</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?> btn-type-3" href="<?=site_url("comment/mark/".h($comment["_id"])."/3")?>">很棒的留言</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?> btn-type-4" href="<?=site_url("comment/mark/".h($comment["_id"])."/4")?>">廣告</a>
				&nbsp; | &nbsp;
				<a class="btn btn-confirm-all btn-default" data-type="2" href="javascript:void 0;" data-key="<?=h($comment["userkey"]) ?>"><?=h($comment["name"]) ?> OK</a>
				<a class="btn btn-confirm-all btn-default" data-type="1" href="javascript:void 0;" data-key="<?=h($comment["userkey"]) ?>"><?=h($comment["name"]) ?> 跳針</a>
				<a class="btn btn-confirm-all btn-default" data-type="0" href="javascript:void 0;" data-key="<?=h($comment["userkey"]) ?>"><?=h($comment["name"]) ?> 待審查</a>
				<hr />
				<?php foreach($replys as $reply){?>
				<div class="reply reply-<?=$reply["status"]?>" >
					<p class="url"><a href="<?=h($reply["url"])?>"><?=h($reply["url"])?></a></p>
					<p class="內容"><?=nl2br(($reply["content"]))?></p>
					<button data-id="<?=h($reply["_id"])?>" data-status="0" class="btn-approve btn">未處理</button>
					<button data-id="<?=h($reply["_id"])?>" data-status="2" class="btn-approve btn">採用</button>
					<button data-id="<?=h($reply["_id"])?>" data-status="1" class="btn-approve btn">不採用</button>
				</div>
				
				<?php }?>
			</td>
		</tr>			
		<?php }?>
	</table>

</div>

<?php function js_section(){?>
<script>
	$(function(){
		$(".page-controls .btn-reset").click(function(){
			$(".comment-handle").show();
			$(".rules").text("全部");
		});
		$(document).on("keydown",function(e){
			if(e.keyCode == 27){
				$(".page-controls .btn-reset").click();
				return true;
			}
			if(e.keyCode == 113){
				$(".page-controls .keyword").focus().select();
				return true;
			}
		});

		$(".keywords").click(function(){
			$(".page-controls .keyword").val($(this).data("keyword"));
			var keyword = $(this).data("keyword") ;
			var users = {}; 
			$(".rules").text("關鍵字:"+keyword);
			$(".comment-handle").hide().each(function(){
				var $this = $(this);
				if($this.find(".comment-content").text().indexOf(keyword)!=-1){
					$this.show();
					users[$this.data("user")] = 1;
				}
			});
			$(".comment-handle").each(function(){
				var $this = $(this);
				if(users[$this.data("user")] != null){
					$this.show();
				}
			});			
		});
		$(".page-controls .keyword").keydown(function(e){
			if(e.keyCode == 13){
				var keyword = this.value ;
				var users = {}; 
				$(".rules").text("關鍵字:"+keyword);
				$(".comment-handle").hide().each(function(){
					var $this = $(this);
					if($this.find(".comment-content").text().indexOf(keyword)!=-1){
						$this.show();
						users[$this.data("user")] = 1;
					}
				});
				$(".comment-handle").each(function(){
					var $this = $(this);
					if(users[$this.data("user")] != null){
						$this.show();
					}
				});
			}
		});
		$(".table-confirm").on("click",".btn-filter-url",function(){
			var url = $(this).data("url");
			var title = $(this).data("title");
			var users = {}; 

			$(".rules").text("與新聞 "+title+" 有關");
			$(".comment-handle").hide().each(function(){
				var $this = $(this);
				if($this.data("url") == url){
					$this.show();
					users[$this.data("user")] = 1;
				}
			});
			$(".comment-handle").each(function(){
				var $this = $(this);
				if(users[$this.data("user")] != null){
					$this.show();
				}
			});
		});

		$(".table-confirm").on("click",".btn-approve",function(){
			var status = $(this).data("status");
			var id = $(this).data("id");

			var btn = this;
			var oldtext = $(btn).text();
			$(btn).text("處理");
			$(btn).addClass("btn-wanring");
			$.post("<?=site_url("comment/mark_reply")?>",
				{
					status:status,
					id:id
				},
				function(){
					$(btn).parent().find(".btn-primary").removeClass("btn-primary");
					$(btn).addClass("btn-primary");
					$(btn).removeClass("btn-wanring");
					$(btn).text(oldtext);
					oldtext = null;
			});
		});
		
		
		$(".table-confirm").on("click",".btn-confirm-all",function(){
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
		$(".table-confirm").on("click",".btn-confirm",function(){
			var btn = this;
			var oldtext = $(btn).text();
			$(btn).text("處理");
			$(btn).addClass("btn-wanring");
			$.get(btn.href,function(){
				$(btn).parent().find(".btn-primary").removeClass("btn-primary");
				$(btn).addClass("btn-primary");
				$(btn).removeClass("btn-wanring");
				$(btn).text(oldtext);
				oldtext = null;
			});
			return false;
		});
	});
</script>
<?php }?>

<?php include(__DIR__."/../_site_footer.php")?>