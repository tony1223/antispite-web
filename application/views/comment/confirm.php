<?php include(__DIR__."/../_site_header.php")?>
<style>
	* {
	 	font-size:16px;
	}
	
	.content{
		line-height:160%;
	}
</style>
<div class="container">
	
	<h2>待確認留言清單</h2>
	<?php 

	?>
	<table class="table table-bordered">
		<tr>
			<td>回報時間</td>			
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
			<td>回報數</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr>
			<td><?=_display_date_with_fulldate_ms($comment["createDate"]) ?></td>
			<td>
				<?php if($comment["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td><a href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a></td>
			<td><?=_display_date_with_fulldate_ms($comment["time"]) ?></td>
			<td><?=count($comment["reporters"]) ?></td>
		</tr>
		<tr>
			<td colspan="4" style="padding-left:40px;">
				<a href="<?=h($comment["url"]) ?>"><?=h($comment["url"]) ?></a>
				<hr />
				<?=nl2br(h($comment["content"]))?>
				<hr />
				目前狀態：
				<a class="btn btn-default<?php if($comment["status"] == 2) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/2")?>">OK</a>
				<a class="btn btn-default<?php if($comment["status"] == 1) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/1")?>">跳針</a>
				<a class="btn btn-default<?php if($comment["status"] == 0) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/0")?>">待審查</a>
				<a class="btn btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/3")?>">很棒的留言</a>
			</td>
		</tr>			
		<?php }?>
	</table>

</div>

<?php include(__DIR__."/../_site_footer.php")?>