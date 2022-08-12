<?php
 

class ComingSoonCartEnableModuleFrontController extends ModuleFrontController
{

    public function __construct()
    {
        parent::__construct();
    }
    //function to enable addtocart button
    public function displayAjaxdataajax()
    {
        $token = Tools::getValue('token');
        $errors = '';

        if (Configuration::get('FME_CARTENABLE_TOKEN') === $token) {
            $product_id = (int) Tools::getValue('product_id');
            $product = new product($product_id);
            $product->available_for_order = true;
            $product->update();
        } else {
            $errors = 'Action not found 404';
        }
        ob_end_clean();
        header('Content-Type: application/json');
        die(Tools::jsonEncode(array(
            'errors' => $errors,
        )));
    }
}
