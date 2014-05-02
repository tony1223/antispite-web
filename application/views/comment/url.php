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
	
	<h2><?=$title?> 相關跳針留言清單（<?=count($comments) ?>）<div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<p>跳針總數：<?=$count?></p>
	<div id="chart_div" style="width: 100%; height: 500px;"></div>
	<hr />
	<table class="table table-bordered table-comment">
		<tr class="comment-row">
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr>
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
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary install_extension">馬上安裝跳針留言小幫手</a></p>
	
</div>


<?php 
$data = array();
foreach($comments as $comment){
 	$data[]=array($comment["time"],mb_strlen($comment["content"]),$comment["content"]);
} 			
?>
<script>
	window.datas = <?=json_encode($data)?>;
	for(var i =0 ; i < window.datas.length;++i){
		window.datas[i][0] = new Date(window.datas[i][0]);
	}
</script>
<?php function js_section(){ ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {'packages':['annotatedtimeline']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('datetime', '時間');
        data.addColumn('number', '字數');
        data.addColumn('string', '內容');
        
        data.addRows(window.datas);

        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('chart_div'));
        chart.draw(data, {displayAnnotations: true});
      }
    </script>

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