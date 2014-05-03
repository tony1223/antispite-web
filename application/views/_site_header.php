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

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">展開選單</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="<?=site_url("/")?>">反跳針留言 </a>
</div>
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav ">
        <li <?php if($selector == "intro"){ ?> class="active" <?php } ?>><a href="<?=site_url("/")?>">說明</a></li>
        <li <?php if($selector == "download"){ ?> class="active" <?php } ?>><a href="<?=site_url("extension/download")?>">跳針留言小幫手</a></li>
        <li <?php if($selector == "comments"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/")?>">瀏覽跳針留言</a></li>
        <li <?php if($selector == "rank"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/rank")?>">跳針排行榜</a></li>
        <li <?php if($selector == "url"){ ?> class="active" <?php } ?>><a href="<?=site_url("url")?>">跳針網址</a></li>
        <li <?php if($selector == "api"){ ?> class="active" <?php } ?> target="_blank"><a href="<?=base_url("doc/#!/comment")?>">跳針資料 API </a></li>
        
        <?php if(is_login()){?>
        <li <?php if($selector == "confirm"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/confirm")?>">確認跳針留言</a></li>
        <li <?php if($selector == "confirm_reply"){ ?> class="active" <?php } ?>><a href="<?=site_url("comment/reply_confirm")?>">確認跳針留言回應</a></li>
        <?php }?>
    </ul>   
</div>
</div>