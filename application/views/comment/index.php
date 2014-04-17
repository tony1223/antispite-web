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
	
	<h2>近期惡意留言清單（最近一百筆）</h2>
	<table class="table table-bordered">
		<tr>
			<td>回報時間</td>			
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
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
		</tr>
		<tr>
			<td colspan="4" style="padding-left:40px;">
				<a href="<?=h($comment["url"]) ?>"><?=h($comment["url"]) ?></a>
				<hr />
				<?=nl2br(h($comment["content"]))?>
			</td>
		</tr>			
		<?php }?>
	</table>

</div>

<?php include(__DIR__."/../_site_footer.php")?>