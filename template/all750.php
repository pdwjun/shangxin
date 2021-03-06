<!-- 商品信息开始 -->
<div class="dm_module" data-id="2411329" data-title="商品信息" id="ids-module-2411329">
<div><img src="http://img02.taobaocdn.com/imgextra/i2/760842260/T2ieDDXjhbXXXXXXXX-760842260.png" /></div>
<div style="width:750px;color:rgb(85,85,85);">
<div style="width:550px;float:left;">
<table width="550" border="0" style="border-spacing:0px;border-collapse:collapse;border:1px solid #CCC; font-size:12px;">
  <tr>
    <td align="center" style="background-color:#eee;width:80px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;">货号</td>
    <td style="width:470px;background-color:#eee;padding-left:6px;border-style:solid;border-width:1.0px;border-color:#d8d7d7;line-height:18px;"><?php echo $retMap['code']; ?></td>
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
<img src="<?php echo $retMap['img']; ?>" style="width:200px; height:270px;"/></div></div></div>
<!-- 商品信息结束 -->
<!-- 尺码表开始 -->
<div class="dm_module" data-id="2411341" data-title="尺码表" id="ids-module-2411341">
<div><img src="http://img02.taobaocdn.com/imgextra/i2/760842260/T2wFHTXhNbXXXXXXXX-760842260.png" /></div>
<div><table class="" style="width:750px;background-color:rgb(216,215,215);font-size:12px;cursor:pointer;border-spacing:0px;border-collapse:collapse;border:1px solid #CCC;color:rgb(85,85,85);line-height:20px;">
  <tbody>
    <tr style="background-color:#eee;text-align:center;">
      <c:set var="colomnIndex" value="${0}"/>
      <c:set var="columnWidth" value="${sizeWidth1}"/>
        <c:forEach items="${sizeHeader}"  var="sizeHeaderItem"  >
      <?php 
  $colomnIndex = 0;
  $columnWidth = $retMap['sizeWidth1'];
?>
      <?php foreach($retMap['sizeHeader'] as $sizeHeaderItem) { ?>
      <td style="background-color:rgb(221,221,221);line-height:20px;" width="<?php echo $columnWidth; ?>"> <?php echo $sizeHeaderItem; ?></td>
      <?php 
  $colomnIndex++;
  $columnWidth = $retMap['sizeWidth2'];
  }
?>
    </tr>
    <?php
$rowIndex = 0;
foreach($retMap['sizeTable'] as $sizeRow) {
?>
    <tr style="background-color:<?php if ($rowIndex % 2 == 0) { echo '#fff';} else { echo  '#eee';} ?>;text-align:center;">
      <?php
$colomnIndex = 0;
$columnWidth= $retMap['sizeWidth1'];
foreach($sizeRow as $sizeItem) {
?>
      <td style="border:1px solid #CCC;line-height:20px;" width="<?php echo $columnWidth; ?>%"><?php echo $sizeItem; ?></td>
      <?php 
  $colomnIndex++;
  $columnWidth = $retMap['sizeWidth2'];
  }
$rowIndex++;
?>
    </tr>
    <?php } ?>
  </tbody>
</table></div></div>
<!-- 尺码表结束 -->
<!-- 试穿报告开始 -->
<div class="dm_module" data-id="2411343" data-title="试穿报告" id="ids-module-2411343">
<div><img src="http://img04.taobaocdn.com/imgextra/i4/760842260/T2d31tXDJaXXXXXXXX-760842260.png" /></div>
<div>
  <table class="" style="width:750px;background-color:rgb(216,215,215);font-size:12px;cursor:pointer;border-spacing:0px;border-collapse:collapse;border:1px solid #CCC;color:rgb(85,85,85);line-height:20px;">
    <tbody>
      <tr style="background-color:rgb(255,255,255);text-align:center;">
        <?php 
$colomnIndex = 0;
$columnWidth = $retMap['tryWidth1'];
foreach($retMap['tryHeader'] as $tryHeaderItem) {
?>
        <td style="background-color:rgb(221,221,221);line-height:20px;" width="<?php echo $columnWidth; ?>%"> <?php echo $tryHeaderItem; ?></td>
        <?php
  $colomnIndex++;
  $columnWidth = $retMap['tryWidth2'];
}
?>
      </tr>
      <?php 
$rowIndex = 0;
foreach($retMap['tryTable'] as $tryRow) {
?>
      <tr style="background-color:<?php if ($rowIndex % 2 == 0) { echo  'rgb(241,241,241)'; } else { echo  'rgb(255,255,255)';} ?>;text-align:center;">
        <?php
$colomnIndex = 0;
$columnWidth = $retMap['tryWidth1'];
foreach($tryRow as $tryItem) {  
?>
        <td style="border:1px solid #CCC;line-height:20px;" width="<?php echo $columnWidth; ?>%"><?php echo $tryItem; ?></td>
        <?php 
  $colomnIndex++;
  $columnWidth = $retMap['tryWidth2'];
  }
$rowIndex++;
?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div></div>
<!-- 试穿报告结束 -->
<!-- 产品图开始 -->
<div class="dm_module" data-id="2411348" data-title="产品图" id="ids-module-2411348">
<div><img src="http://img03.taobaocdn.com/imgextra/i3/760842260/T2sUqUXAlaXXXXXXXX-760842260.png" /></div>
<div style="width:750px;text-align:center;">
<?php foreach($retMap['detailImages'] as $img) { ?>
<img width="750" src="<?php echo $img[0]; ?>" alt="" style="vertical-align:top;" />
<?php } ?>
</div>
</div>
<!-- 产品图结束 -->
<!-- 模特展示图开始 -->
<div class="dm_module" data-id="2411345" data-title="模特效果图" id="ids-module-2411345">
<div><img src="http://img02.taobaocdn.com/imgextra/i2/760842260/T2ieDDXjhbXXXXXXXX-760842260.png" /></div>
<div style="width: 760.0px;">

<?php foreach($retMap['productImages'] as $img) { ?>
<div style="width:370px; margin-bottom:10px; margin-right:10px; float:left;">
<img style="width:370px;" alt="alt" src="<?php echo $img; ?>" />
</div>
<?php } ?>
</div>
</div>
<!-- 模特展示图结束 -->