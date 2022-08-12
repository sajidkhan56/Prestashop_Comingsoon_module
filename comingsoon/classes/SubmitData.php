<?php
 

class SubmitData extends ObjectModel
{

    //tables columns
    public $id_products;
    public $id_product_array;
    public $available_date;

    public static $definition = [
        'table' => 'comingsoon_products',
        'primary' => 'id_comingsoon_products',
        'multilang' => false,
        'multishop' => true,
        'fields' => [

            'id_products' => ['type' => self::TYPE_INT],
            'id_product_array' => ['type' => self::TYPE_INT],
            'available_date' => ['type' => self::TYPE_DATE],
        ],
    ];

    public function __construct($id = null, $id_lang = null)
    {
        Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
        parent::__construct($id, $id_lang);
    }

    //to check if record already exist in database with the same product_id then update the record
    public static function forDuplication($id_product, $id_product_array)
    {

        $sql = new DbQuery();
        $sql->select('id_comingsoon_products');
        $sql->from('comingsoon_products');
        $sql->where('id_products = ' . (int) pSQL($id_product) . ' AND id_product_array =' . (int) pSQL($id_product_array));

        return (int) Db::getInstance()->getValue($sql);
    }
    //return all records present in database
    public static function productInformation($id_shop)
    {
        $sql = new DbQuery();
        $sql->select('id_products');
        $sql->select('id_product_array');
        $sql->select('available_date');
        $sql->from('comingsoon_products', 'cs');
        $sql->leftJoin('comingsoon_products_shop', 'sp', 'cs.id_comingsoon_products = sp.id_comingsoon_products');
        $sql->where('sp.id_shop = '.$id_shop);

        return Db::getInstance()->executeS($sql);
    }

     public static function updateRecord($id_product)
     {
         $query = "UPDATE `"._DB_PREFIX_."product_shop` SET available_for_order=0 where id_product=".$id_product;
         return Db::getInstance()->Execute($query);
     }
}
