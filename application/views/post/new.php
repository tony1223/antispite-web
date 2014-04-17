<?php include(__DIR__."/../_site_header.php")?>

<div class="container">
	<form role="form" action="<?=site_url("post/create")?>" method="POST" class="col-md-6">
	  <div class="form-group">
	    <label for="poster">Poster</label>
	    <input type="text" class="form-control input-lg" id="poster" name="poster" placeholder="Enter your name">
	  </div>
	  <div class="form-group">
	    <label for="content">Content</label>
	    <textarea class="form-control input-lg" name="content" placeholder="Enter your message here"></textarea>
	  </div>
	  <button type="submit" class="btn btn-default">Submit</button>
	</form>
</div>

<?php include(__DIR__."/../_site_footer.php")?>