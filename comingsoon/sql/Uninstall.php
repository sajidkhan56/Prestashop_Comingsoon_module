 <?php
 

 $sqls = array();
 $sqls[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'comingsoon_products`';
 $sqls[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'comingsoon_products_shop`';

 foreach ($sqls as $sql) {
     if (!Db::getInstance()->execute($sql)) {
         return false;
     }
 }
