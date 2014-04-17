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
	
	<form action="<?=site_url("user/logining")?>" method="POST">
		<table class="table table-bordered">
			<tr>
				<td>帳號</td>
				<td><input type="text" name="account" /></td>
			</tr>
			<tr>
				<td>密碼</td>
				<td><input type="password" name="password" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" value="登入" class="btn" />
				</td>
			</tr>
		</table>
	</form>
</div>

<?php include(__DIR__."/../_site_footer.php")?>