<div><img src="http://img04.taobaocdn.com/imgextra/i4/760842260/T2c0MZXcFbXXXXXXXX-760842260.png" /></div>
<div style="width:790px;color:rgb(85,85,85);">
<div style="width:590px;float:left;">
<table width="590" border="0" style="border-spacing:0px;border-collapse:collapse;border:1px solid #CCC; font-size:12px;">
  <tr>
    <td align="center" style="background-color:#eee;width:80px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">货号</td>
    <td style="width:510px;background-color:#eee;padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['code']; ?></td>
  </tr>
  <tr>
    <td align="center" style="border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">尺码</td>
    <td style="padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['sizes'];?></td>
  </tr>
  <tr>
    <td align="center" style="background-color:#eee;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">颜色</td>
    <td style="background-color:#eee;padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['colors']; ?></td>
  </tr>
  <tr>
    <td align="center" style="border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">参考价</td>
    <td style="padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['price']; ?></td>
  </tr>
  <?php $propIndex = 0; ?>
  <?php foreach($retMap['props'] as $prop) { ?>
  <tr>
    <td align="center" style="<?php if ($propIndex % 2 == 0) echo 'background-color:#eee;';?>border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $prop[0]; ?></td>
    <td style="<?php if ($propIndex % 2 == 0) echo 'background-color:#eee;'; ?>padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $prop[1]; ?></td>
    <?php $propIndex++; ?>
  </tr>
  <?php } ?>
  <tr>
    <td align="center" style="<?php if($propIndex % 2 == 0) echo 'background-color:#eee;'; ?>border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">选款师推荐</td>
    <td style="<?php if($propIndex % 2 == 0) echo 'background-color:#eee;'; ?>padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['desc']; ?></td>
  </tr>
</table>
</div>
<div style="width:200px;float:left">
<img src="<?php echo $retMap['img']; ?>" style="width:200px; height:270px;"/></div></div>