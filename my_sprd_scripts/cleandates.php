<?php
require 'www/100kwatt.ru/myscripts/vendor/autoload.php';
require "config.php";
$cleandates = $db->query("UPDATE `100kwatt_shop`.`cscart_products` SET `csyml_sales_notes` = '0' WHERE `cscart_products`.`yml2_sales_notes` != '0';");
?>