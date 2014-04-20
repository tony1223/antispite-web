<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8">
	<title><?php
		if(isset($pageTitle)){
			if(empty($selector) || $selector != "home"){
				echo $pageTitle." - 反跳針服務中心" ;
			}else{
				echo $pageTitle; 
			}
		} else{
			echo "反跳針服務中心" ; 
		}
	?></title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	<meta name="google-site-verification" content="AkD7jR9M0OkMfRj9N4EgYUOlcrscDtf9kgfsKcFDvbI" />
	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(function_exists("css_section")){
    	css_section();
     }?>
</head>
<body>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="<?=site_url("/")?>">反跳針留言 </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if($selector == "intro"){ ?> class="active" <?php } ?>><a href="<?=site_url("/")?>">說明</a></li>
        <li <?php if($selector == "download"){ ?> class="active" <?php } ?>><a href="<?=site_url("extension/download")?>">跳針留言小幫手</a></li>
        <li <?php if($selector == "comments"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/")?>">瀏覽跳針留言</a></li>
        <li <?php if($selector == "rank"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/")?>">跳針排行榜</a></li>
        <?php if(is_login()){?>
        <li <?php if($selector == "confirm"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/confirm")?>">確認跳針留言</a></li>
        <?php }?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

