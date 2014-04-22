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
				<p style="min-height:50px;padding-top:10px;">
					<?=nl2br(h($comment["content"]))?>
				</p>
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
<?php }?>
<?php include(__DIR__."/../_site_footer.php")?>