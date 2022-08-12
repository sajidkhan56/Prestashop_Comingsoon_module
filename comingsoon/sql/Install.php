<?php
 

$sqls = array();
$sqls[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'comingsoon_products` (
    `id_comingsoon_products`  int(11) AUTO_INCREMENT,
    `id_products` int,
    `id_product_array`   int,
    `available_date`  date,
    PRIMARY KEY (id_comingsoon_products)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sqls[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'comingsoon_products_shop` (
        `id_comingsoon_products` int(10) NOT NULL,
        `id_shop` int(10) NOT NULL,
        PRIMARY KEY (`id_comingsoon_products`, `id_shop`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sqls as $sql) {
    if (!Db::getInstance()->execute($sql)) {
        return false;
    }
}
