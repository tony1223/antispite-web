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
	<a class="btn btn-default" href="<?=site_url("comment/confirm/-1")?>">確認全部留言</a>
	<a class="btn btn-default" href="<?=site_url("comment/confirm/1")?>">確認跳針留言</a>
	
	<hr />
	<?=$stats[0]?> 待審,<?=$stats[1]?> 跳針,<?=$stats[2]?> 沒問題.
	<hr />
	<table class="table table-bordered table-confirm">
		<tr>
			<td>key</td>
			<td>回報時間</td>			
			<td>類型</td>
			<td>留言者</td>
			<td>留言時間</td>
			<td>回報數</td>
		</tr>
		<?php foreach($comments as $comment){?>
		<tr>
			<td><?=h($comment["_id"]) ?></td>
			<td><?=_display_date_with_fulldate_ms($comment["createDate"]) ?></td>
			<td>
				<?php if($comment["type"] == "FBComment"){ ?>
				FB 留言
				<?php }?>
			</td>	
			<td>
				<a href="<?=h(comment_user_link($comment))?>"><?=h($comment["name"]) ?></a> (<a target="_blank"  href="<?=site_url("comment/user/?key=".rawurlencode($comment["userkey"])) ?>">瀏覽 <?=h($comment["name"]) ?> 的跳針留言</a>)
			</td>
			<td><?=_display_date_with_fulldate_ms($comment["time"]) ?></td>
			<td><?=count($comment["reporters"]) ?></td>
		</tr>
		<tr>
			<td colspan="6" style="padding-left:40px;">
				<a href="<?=h($comment["url"]) ?>"><?=h($comment["url"]) ?></a>
				<hr />
				<?=nl2br(h($comment["content"]))?>
				<hr />
				目前狀態：
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 2) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/2")?>">OK</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 1) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/1")?>">跳針</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 0) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/0")?>">待審查</a>
				<a class="btn btn-confirm btn-default<?php if($comment["status"] == 3) {?> btn-primary <?php }?>" href="<?=site_url("comment/mark/".h($comment["_id"])."/3")?>">很棒的留言</a>
			</td>
		</tr>			
		<?php }?>
	</table>

</div>

<?php function js_section(){?>
<script>
	$(function(){
		$(".table-confirm").on("click",".btn-confirm",function(){
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

<?php include(__DIR__."/../_site_footer.php")?>