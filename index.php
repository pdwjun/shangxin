<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php

session_start();

$url = "";
if (!empty($_POST) && array_key_exists("url", $_POST)) {
  $url = $_POST["url"];
}
$type = 0;
if (!empty($_POST) && array_key_exists("type", $_POST)) {
  $type = $_POST["type"];
}
if (empty($type)) {
  $type = 0;
}

$result = "请选择模块";
$success = 0;
if (!empty($url)) {

  $oldUrl = $_SESSION['url'];
//  if (empty($oldUrl) || $oldUrl != $url) {
    include "moonbasa.class.php";
    $moonbasa = new Moonbasa(array());
    try {
      $retMap = $moonbasa->parseUrl($url);
      //var_dump($retMap);
      $success = $retMap["success"];
      if ($success == 1) { 
        $_SESSION['url'] = $url;
        $_SESSION['product'] = $retMap;
      }
    } catch(Exception $ex) {
      $result = "error: " . $ex->getMessage();
    }
//  } else {
//    $retMap = $_SESSION['product'];
//    $success = $retMap["success"];
//  }
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>moonbasa</title>
<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
<!-- 
<script type="text/javascript" src="assets/js/jquery.zclip.js"></script>
 -->
<script type="text/javascript">
	function copy_clip() {
		var txt = $('textarea#code').val();
		//alert(txt);
        if (window.clipboardData) {
                window.clipboardData.clearData();
                window.clipboardData.setData("Text", txt);
        } else if (navigator.userAgent.indexOf("Opera") != -1) {
                window.location = txt;
        } else if (window.netscape) {
                try {
                        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                } catch (e) {
                        alert("您的firefox安全限制限制您进行剪贴板操作，请在新窗口的地址栏里输入'about:config'然后找到'signed.applets.codebase_principal_support'设置为true'");
                        return false;
                }
                var clip = Components.classes["@mozilla.org/widget/clipboard;1"].createInstance(Components.interfaces.nsIClipboard);
                if (!clip)
                        return;
                var trans = Components.classes["@mozilla.org/widget/transferable;1"].createInstance(Components.interfaces.nsITransferable);
                if (!trans)
                        return;
                trans.addDataFlavor('text/unicode');
                var str = new Object();
                var len = new Object();
                var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
                var copytext = txt;
                str.data = copytext;
                trans.setTransferData("text/unicode", str, copytext.length * 2);
                var clipid = Components.interfaces.nsIClipboard;
                if (!clip)
                        return false;
                clip.setData(trans, null, clipid.kGlobalClipboard);
        }
	}
</script>
</head>
<body
	style="width: 1400px; margin: 0 auto; text-align: center; font-size: 12px;">
<table width="1400" border="0"
		style="float: left; border-spacing: 0px; border-collapse: collapse; border: 1px solid #CCC;">
  <tr height="100%"
			style="text-align: center; background-position: initial; background-repeat: initial;">
    <td width="100%" height="0" bgcolor="#EEEEEE"
				style="padding-top: 0.3em; padding-bottom: 0.3em; border-style: solid; border-width: 1.0px; border-color: #d8d7d7; height: 14px;"><form
					id="form1" name="form1" method="post" action="">
        <input type="text" name="url" id="url"
						style="width: 900px; height: 14px;" value="<?php echo $url; ?>" />
        <select
						name="type" id="type" style="width: 180px; ">
                        
          <option value="0"
							<?php if ($type==0) echo 'selected="selected"' ?>>***转换商品代码***</option>
          <option value="1"
							<?php if ($type==1) echo 'selected="selected"' ?>>商家说明</option>
          <option value="4"
							<?php if ($type==4) echo 'selected="selected"' ?>>尺码表</option>
          <option value="5"
							<?php if ($type==5) echo 'selected="selected"' ?>>试穿报告</option>
          <option value="3"
							<?php if ($type==3) echo 'selected="selected"' ?>>产品图</option>
          <option value="2"
							<?php if ($type==2) echo 'selected="selected"' ?>>模特效果图</option>
          <option value="6"
							<?php if ($type==6) echo 'selected="selected"' ?>>全部790（天猫）</option>
          <option value="7"
							<?php if ($type==7) echo 'selected="selected"' ?>>全部780（折800）</option>
          <option value="8"
							<?php if ($type==8) echo 'selected="selected"' ?>>全部750（分销平台/淘宝/卷皮）</option>
          <option value="9"
							<?php if ($type==9) echo 'selected="selected"' ?>>全部688（聚划算）</option>
        
          <option value="10"
							<?php if ($type==10) echo 'selected="selected"' ?>>***尺码对照表790***</option>
          <option value="11"
							<?php if ($type==11) echo 'selected="selected"' ?>>女装</option>
          <option value="12"
							<?php if ($type==12) echo 'selected="selected"' ?>>男装</option>
          <option value="13"
							<?php if ($type==13) echo 'selected="selected"' ?>>文胸</option>
          <option value="14"
							<?php if ($type==14) echo 'selected="selected"' ?>>女裤</option>
          <option value="15"
							<?php if ($type==15) echo 'selected="selected"' ?>>男裤</option>
          <option value="16"
							<?php if ($type==16) echo 'selected="selected"' ?>>童装</option>
          <option value="17"
							<?php if ($type==17) echo 'selected="selected"' ?>>戒指</option>
          <option value="18"
							<?php if ($type==10) echo 'selected="selected"' ?>>***尺码对照表750***</option>
          <option value="19"
							<?php if ($type==11) echo 'selected="selected"' ?>>女装</option>
          <option value="20"
							<?php if ($type==12) echo 'selected="selected"' ?>>男装</option>
          <option value="21"
							<?php if ($type==13) echo 'selected="selected"' ?>>文胸</option>
          <option value="22"
							<?php if ($type==14) echo 'selected="selected"' ?>>女裤</option>
          <option value="23"
							<?php if ($type==15) echo 'selected="selected"' ?>>男裤</option>
          <option value="24"
							<?php if ($type==16) echo 'selected="selected"' ?>>童装</option>
          <option value="25"
							<?php if ($type==17) echo 'selected="selected"' ?>>戒指</option>
        </select>
        <input type="submit" name="button" id="button" value="转换" /></form>
      </td>
  </tr>
  <tr
			style="height: 620px; text-align: center; background-position: initial; background-repeat: initial;">
    <td height="0" bgcolor="#EEEFFF"
				style="padding-top: 0.3em; padding-bottom: 0.3em; border-style: solid; border-width: 1.0px; border-color: #d8d7d7;"><?php if ($success==1) { ?>
      <textarea style="width: 100%; height: 620px; text-align: left;"
						id="code">
<?php 
if ($type == 1) {
  include 'template/product.php'; 
} else if ($type == 2) {
  include 'template/product_img.php';
} else if ($type == 3) {
  include 'template/detail_img.php';
} else if ($type == 4) {
  include 'template/size_table.php';
} else if ($type == 5) {
  include 'template/try_table.php';
} else if ($type == 6) {
  include 'template/all790.php';
} else if ($type == 7) {
  include 'template/all780.php';
} else if ($type == 8) {
  include 'template/all750.php';
} else if ($type == 9) {
  include 'template/juhuasuan688.php';
} else if ($type == 11) {
  include 'template/nvzhuang.php';
} else if ($type == 12) {
  include 'template/nanzhuang.php';
} else if ($type == 13) {
  include 'template/wenxiong.php';
} else if ($type == 14) {
  include 'template/nvku.php';
} else if ($type == 15) {
  include 'template/nanku.php';
} else if ($type == 16) {
  include 'template/tongzhuang.php';
} else if ($type == 17) {
  include 'template/jiezhi.php';
} else if ($type == 18) {
  include 'template/nvzhuang750.php';
} else if ($type == 19) {
  include 'template/nanzhuang750.php';
} else if ($type == 20) {
  include 'template/wenxiong750.php';
} else if ($type == 21) {
  include 'template/nvku750.php';
} else if ($type == 22) {
  include 'template/nanku750.php';
} else if ($type == 23) {
  include 'template/tongzhuang750.php';
} else if ($type == 24) {
  include 'template/jiezhi750.php';
} else {
  echo "请选择模块";
} 
?>
    </textarea>
      <?php 
} else { 
  echo $result;
} 

?></td>
  </tr>
  <tr height="100%"
			style="text-align: center; background-position: initial; background-repeat: initial;">
    <td height="0" bgcolor="#EEEEEE"
				style="padding-top: 0.3em; padding-bottom: 0.3em; border-style: solid; border-width: 1.0px; border-color: #d8d7d7;"><font
				style="float: right; margin-right: 20px;">
      <?php if($type!=0) {?>
      <a href="preview.php?type=<?php echo $type; ?>" target="_blank">预览</a>
      <?php } ?>
      <a id="copy-dynamic" href="javascript:void(0);" onclick="copy_clip()">复制代码</a></font></td>
  </tr>
</table>
<script>
$(document).ready(function(){

	/*
    $("a#copy-dynamic").zclip({
    	path:'<%=request.getContextPath()%>/assets/js/ZeroClipboard.swf',
        copy:function(){alert("hehe");return $('input#dynamic').val();},
    	beforeCopy:function(){
    	},
    	afterCopy:function(){
    		alert("已拷贝值剪贴板");
    	}
    });
    console.log($("a#copy"));
    */
});
</script>
</body>
</html>
