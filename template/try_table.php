<div><img src="http://img03.taobaocdn.com/imgextra/i3/760842260/T28YdoXw4bXXXXXXXX-760842260.png" /></div>
<div>
  <table class="" style="width:790px;background-color:rgb(216,215,215);font-size:12px;cursor:pointer;border-spacing:0px;border-collapse:collapse;border:1px solid #CCC;color:rgb(85,85,85);line-height:20px;">
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
</div>
