<?php
session_start();

$type = 0;
if (!empty($_GET) && array_key_exists("type", $_GET)) {
  $type = $_GET["type"];
}
if (empty($type)) {
  $type = 0;
}

$retMap = $_SESSION['product'];

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
}
?>
