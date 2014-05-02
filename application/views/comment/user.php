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
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-primary">馬上安裝跳針留言小幫手</a></p>
	
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
<script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>
<script type="text/javascript">
//width 取得DIV寬度會較好
var margin = {top: 20, right: 20, bottom: 30, left: 40},
    width = 1024 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var x = d3.time.scale().range([0, width]);


var y = d3.scale.linear()
    .range([height, 0]);

var color = d3.scale.category10();

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left");

var svg = d3.select(".chart_div").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");




  x.domain(d3.extent(window.datas, function(d) { return new Date(d[0]); })).nice();
  y.domain(d3.extent(window.datas, function(d) { return d[1]; })).nice();


var div = d3.select("body").append("div")   
    .attr("class", "tooltip")               
    .style("opacity", 0);
var formatTime = d3.time.format("%Y-%m-%d %H:%M:%S");
  svg.selectAll(".dot")
      .data(window.datas)
    .enter().append("circle")
      .attr("class", "dot")
      .attr("r", 10)
      .attr("cx", function(d) { return x(new Date(d[0]) ); })
      .attr("cy", function(d) { return y(d[1]); })
      .on("mouseover",function(d){           
          div.transition()        
                .duration(200)      
                .style("opacity", .9);      
          div .html(formatTime(new Date(d[0])) + "<br/>字數:"  + d[1]+"<br/>"+d[2])  
                .style("left", (d3.event.pageX) + "px")     
                .style("top", (d3.event.pageY - 28) + "px");  
      })
      .on("mouseout", function(d) {       
            div.transition()        
                .duration(500)      
                .style("opacity", 0);   
      })
      .style("fill", function(d) { return color(d[1]); });
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .append("text")
      .attr("class", "label")
      .attr("x", width)
      .attr("y", -6)
      .style("text-anchor", "end")
      .text("發文時間");

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("class", "label")
      .attr("transform", "rotate(-90)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("字數")
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