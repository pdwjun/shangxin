<div><img src="http://img01.taobaocdn.com/imgextra/i1/760842260/T21nE9XXFbXXXXXXXX-760842260.png" /></div>
<div><table class="" style="width:790px;background-color:rgb(216,215,215);font-size:12px;cursor:pointer;border-spacing:0px;border-collapse:collapse;border:1px solid #CCC;color:rgb(85,85,85);line-height:20px;">
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
</table></div>