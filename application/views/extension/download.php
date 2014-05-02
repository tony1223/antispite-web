<?php include(__DIR__."/../_site_header.php")?>
<style>
	* {
	 	font-size:16px;
	}
	
	.container{
		line-height:160%;
	}
	.container ul{
		line-height:200%;
	}
</style>
<div class="container">
	
	<h2>跳針留言小幫手說明 <div style="float:right;" class="fb-like" data-href="http://antispite.tonyq.org/" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div></h2>
	<ul>
		<li>目前支援  <a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-default">Chrome </a> &nbsp;&nbsp; 
			<a href="https://addons.mozilla.org/zh-TW/firefox/addon/%E8%B7%B3%E9%87%9D%E7%95%99%E8%A8%80%E5%B0%8F%E5%B9%AB%E6%89%8B/" target="_blank" class="btn  btn-default">Firefox </a>
		</li>
		<li>目前只支援 FB 留言板、Yahoo 留言板偵測（未來預計會支援更多類型留言） </li>
		<li>安裝之後到任一 FB 留言板就會進行偵測。
			（示範用範例 <a href="http://www.appledaily.com.tw/realtimenews/article/new/20140416/379882/" target="_blank">http://www.appledaily.com.tw/realtimenews/article/new/20140416/379882/</a>）</li>
		<li>回報跳針後會需要一點時間處理才會標示為跳針留言，回報不保證一定會標示為跳針。</li>
	</ul>
	
	<p><a href="https://chrome.google.com/webstore/detail/pppcoehiccnccehmfpmanaekjkcijmpj/" target="_blank" class="btn btn-default install_extension">安裝跳針留言小幫手(Chrome) </a> &nbsp;&nbsp; <a href="https://addons.mozilla.org/zh-TW/firefox/addon/%E8%B7%B3%E9%87%9D%E7%95%99%E8%A8%80%E5%B0%8F%E5%B9%AB%E6%89%8B/" target="_blank" class="btn  btn-default install_extension">安裝跳針留言小幫手(Firefox) </a></p>
	
	<p>安裝後瀏覽留言時，遇到跳針留言會幫忙標示為跳針留言並增加回報跳針按鈕，如下圖：</p>
	<p><img src="<?=base_url("img/example.png")?>" /></p>
	
	<p>如果有使用者提供資料，則會顯示於畫面上，如下圖：(2014/5/1 新增功能)</p>
	<p><img src="<?=base_url("img/example2.jpg")?>" /></p>
	
</div>

<?php include(__DIR__."/../_site_footer.php")?>