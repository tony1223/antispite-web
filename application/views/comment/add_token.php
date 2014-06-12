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
</style>
<div class="container">
	
	<form action="<?=site_url("comment/add_token")?>" method="POST" >
		<table class="table">
			<tr>
				<td>Token</td>
				<td>
					<?php foreach($tokens as $token) {?> 
						[<?=h($token["_id"])?>],
					<?php }?>
				</td>
			</tr>
			<tr>
				<td><input type="text" name="token" /></td>
				<td><input type="submit" value="送出" /></td>
			</tr>
		</table>
	</form>

</div>

<?php function js_section(){?>
<?php }?>

<?php include(__DIR__."/../_site_footer.php")?>