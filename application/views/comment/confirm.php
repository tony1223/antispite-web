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
		width: 300px;
		height: 800px;
		display: block;
		overflow: auto;
		top: 53px;
		left: 0;
		border: 1px solid red;
		padding: 10px;
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
	
	.control-focus{
		border:2px solid red;
	}
</style>
<div class="container">
	
	<h2>待確認留言清單</h2>
	<a class="btn btn-default" href="<?=site_url("comment/confirm/-1")?>">確認全部留言</a>
	<a class="btn btn-default" href="<?=site_url("comment/confirm/1")?>">確認跳針留言</a>
	
	<hr />
	<?=$stats[0]?> 待審,<?=$stats[1]?> 跳針,<?=$stats[2]?> 沒問題,<?=$stats[3]?> 其他.
	<hr />
	<div class="page-controls">
		<input type="text" class="keyword" value="" />
		<button class="btn btn-default btn-reset">重設條件</button>
		<p class="rules">全部</p>
	</div>
	<hr />
	
	<?php 
	$confirming_users = Array();
	$keywords = Array();
	foreach($tokens as $token){
		$keywords[] = Array("id"=>$token["_id"],"count" => 0 );
	}
	foreach($comments as $comment){
		if(!isset($confirming_users[$comment["userkey"]])){
			$confirming_users[$comment["userkey"]] = Array(
				"count" => 0,
				"keywords" => Array() ,
				"name" => $comment["name"],
				"key" => $comment["userkey"],
				"confirm_count" => $comment["count"]
			);
		}
		$confirming_users[$comment["userkey"]]["count"] ++;
		
		foreach($keywords as &$keyword){
			if(strpos($comment["content"],$keyword["id"]) !== False){
				if(!isset($confirming_users[$comment["userkey"]]["keywords"][$keyword["id"]])){
					$confirming_users[$comment["userkey"]]["keywords"][$keyword["id"]] = 0;
				}
				$confirming_users[$comment["userkey"]]["keywords"][$keyword["id"]]++;
				$keyword["count"] ++;
			}
		};
	} 
	
	function confirming_cmp($a, $b)
	{
		if ($a["count"] == $b["count"]) {
			return 0;
		}
		return ($a["count"] > $b["count"]) ? -1 : 1;
	}	
	usort($confirming_users, "confirming_cmp");
	?>
	<table class="table table-bordered table-confirm table-confirm-user">
		<tr>
			<td class="span1">
				使用者/跳針輔助評估
			</td>
		</tr>
		<?php foreach($confirming_users as $userkey => $user){
		?>
		<tr >
			<td>
				<a class="user-link" href="#<?=h($user["key"]) ?>"><?=h($user["name"])?></a> (<?=h($user["count"])?>
				
					<?php if($user["confirm_count"] >0 ){?>
					/ <span style='color:red;'><?=$user["confirm_count"]?></span>
					<?php }?>
				)
				<br />
				<?php foreach($confirming_users[$userkey]["keywords"] as $keyword => $detail){ ?>
					<span class="keywords" data-keyword="<?=h($keyword)?>" ><?=$keyword?>:<?=$detail?></span>,
				<?php }?>
			</td>
		</tr>
		<?php }?>
		<tr>
			<td colspan="2">
			<b>關鍵字清單</b><br />
			<?php foreach($keywords as $keyword){?>
				<?php if($keyword["count"] > 0 ){?>
				<?=h($keyword["id"])?>:<?=h($keyword["count"])?>,<br />
				<?php }?>
			<?php }?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered table-confirm">
		<tr class="comment-row-head">
			<td>key</td>
			<td>回報時間</td>			
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
			<td>回報數</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr class="comment-row comment-handle" data-url='<?=$comment["url"]?>' data-user='<?=$comment["userkey"]?>'>
			<td><?=h($comment["_id"]) ?></td>
			<td><?=_display_date_with_fulldate_ms($comment["createDate"]) ?></td>
			<td>
				<?=get_comment_type_description($comment["type"])?>
			</td>	
			<td>
				<a target="_blank"  name="<?=h($comment["userkey"])?>" href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a> (<a target="_blank"  href="<?=site_url("comment/user/?key=".rawurlencode($comment["userkey"])) ?>">瀏覽 <?=h($comment["name"]) ?> 的跳針留言</a>) <br />
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
					<a target="_blank" href="<?=site_url("comment/provide/?key=".$comment["_id"])?>" >修改補充或回應資料</a>
				</div>
				<?php }else{ ?>
				<a href="<?=site_url("comment/provide/?key=".$comment["_id"])?>">補充或回應資料</a>
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
				
								
			</td>
		</tr>			
		<?php }?>
	</table>
	<a href="<?=site_url("comment/add_token")?>" target="_blank">增加新 token 。</a>
</div>

<?php function js_section(){?>
<script>
	$(function(){
		$(".page-controls .btn-reset").click(function(){
			$(".comment-handle").show();
			$(".rules").text("全部");
		});

		$(".user-link").click(function(){
			$(".comment-handle").show();
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

		var c_index = 0 ;
		var controls = $(".controls");
		controls.on("mouseenter",function(){
			var ind = controls.index(this);
			c_index = ind;
			var now = controls.eq(c_index);
			$(".control-focus").removeClass("control-focus");
			now.addClass("control-focus");
		});
		$(document).on("keydown",function(e){
			if(e.keyCode == 38 || e.keyCode == 40){
				if(e.keyCode == 38){ //up
					c_index --;
					if(c_index < 0 ){
						c_index = 0;
					}
				}else if(e.keyCode == 40){ //down
					c_index = (c_index + 1 ) % controls.length ;
				}
				var now = controls.eq(c_index);
				$(".control-focus").removeClass("control-focus");
				now.addClass("control-focus");
				$(window).scrollTop(now.offset().top - 200);
			}
			var now = controls.eq(c_index);

			if(e.keyCode == 65){ //a
				now.find(".btn-type-2").click();
			}else if(e.keyCode == 83){ //s
				now.find(".btn-type-1").click();
			}else if(e.keyCode == 68){ //d
				now.find(".btn-type-0").click();
			}else if(e.keyCode == 81){ //q
				now.find(".btn-confirm-all:eq(0)").click();
			}else if(e.keyCode == 87){ //w
				now.find(".btn-confirm-all:eq(1)").click();
			}
			console.log(e.keyCode);
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