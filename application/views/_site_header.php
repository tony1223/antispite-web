<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8">
	<title><?php
		if(isset($pageTitle)){
			if(empty($selector) || $selector != "home"){
				echo $pageTitle." - 反跳針留言 - 留言紀錄與回報程式" ;
			}else{
				echo $pageTitle; 
			}
		} else{
			echo "反跳針留言 - 留言紀錄與回報程式" ; 
		}
	?></title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

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
       <!--  <li><a href="<?=site_url("extension/download")?>">Chrome 外掛安裝</a></li> -->
        <li><a href="<?=site_url("comment/")?>">瀏覽黑名單 (與 API)</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

