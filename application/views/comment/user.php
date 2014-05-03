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
	<div id="chart_div" style="width: 100%; height: 500px;">
		<div id="placeholder" class="demo-placeholder" style="float:left; width:650px;height:800px;"></div>
		<div id="overview" class="demo-placeholder" style="float:right;width:260px; height:225px;"></div>
		<div id="info">
		    time:<p id="item_time"></p>
		    time:<p id="item_count"></p>
		    time:<p id="item_content"></p>
		</div>
	</div>
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
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/"  target="_blank" class="btn btn-primary install_extension">馬上安裝跳針留言小幫手</a></p>
	
</div>


<?php 
$data = array();
foreach($comments as $comment){
 	$data[]=array($comment["time"],mb_strlen($comment["content"]),$comment["content"]);
} 			
?>
<script>
	window.datas = <?=json_encode($data)?>;

</script>
<?php function js_section(){ ?>
    <script type="text/javascript" src="http://www.flotcharts.org/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="http://www.flotcharts.org/flot/jquery.flot.time.js"></script>
    <script type="text/javascript" src="http://www.flotcharts.org/flot/jquery.flot.selection.js"></script>
    <script type="text/javascript">
 	$(function() {

		// setup plot

		function getData(x1, x2) {
            var data = window.datas;
			var d = [];
            datalength = data.length-1;
			for (var i = 0; i <= datalength; ++i) {
               //console.log(data[i]);
				if(data[i][0]>=x1 && data[i][0]<=x2)
				    d.push(data[i]);
			}

			return [
				{ label: "filter data", data: d }
			];
		}

		var options = {
            grid:{
            hoverable:true
            },
			legend: {
				show: false
			},
			series: {
				points: {
					show: true,
                    radius:5
				}
			},

            xaxis: { mode: "time" },
			selection: {
				mode: "xy"
			}
		};

		var startData = getData(0, 3 * Math.PI);

		var plot = $.plot("#placeholder", [window.datas], options);

		// Create the overview plot

		var overview = $.plot("#overview", [window.datas], {
			legend: {
				show: false
			},
			series: {
                points: {
					show: true
				},
				shadowSize: 0
			},
			xaxis: {
				 mode: "time" 
			},

			grid: {
				color: "#999"
			},
			selection: {
				mode: "xy"
			}
		});

		// now connect the two

		$("#placeholder").bind("plotselected", function (event, ranges) {

			// clamp the zooming to prevent eternal zoom
			if (ranges.xaxis.to - ranges.xaxis.from < 0.00001) {
				ranges.xaxis.to = ranges.xaxis.from + 0.00001;
			}
			if (ranges.yaxis.to - ranges.yaxis.from < 0.00001) {
				ranges.yaxis.to = ranges.yaxis.from + 0.00001;
			}

			// do the zooming

			plot = $.plot("#placeholder", getData(ranges.xaxis.from, ranges.xaxis.to),
				$.extend(true, {}, options, {
					xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to },
					yaxis: { min: ranges.yaxis.from, max: ranges.yaxis.to }
				})
			);

			// don't fire event on the overview to prevent eternal loop

			overview.setSelection(ranges, true);
		});

		$("#overview").bind("plotselected", function (event, ranges) {
			plot.setSelection(ranges);
		});
        $("#placeholder").bind("plothover", function (event, pos, item) {
            if(item)
            {
			row = item.series.data[item.dataIndex];
                $("#item_time").text(new Date(row[0]));
                $("#item_count").text(row[1]);
                $("#item_content").text(row[2]);
            }
		});


	});
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