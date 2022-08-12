<?php
 
if (!defined('_PS_VERSION_')) {
    exit;
}
include_once _PS_ROOT_DIR_ . '/modules/comingsoon/classes/SubmitData.php';

class ComingSoon extends Module
{

    public function __construct()
    {
        $this->name = 'comingsoon';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Sajid Khan';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Comming Soon Products');
        $this->description = $this->l('This module will be use for to inform about coming soon products avalibility on product page ');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        include_once $this->local_path . 'sql/Install.php';
        return (parent::install()
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('actionObjectProductUpdateAfter')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('header')
            && $this->initTokenSecure()
        );
    }

    public function uninstall()
    {
        Configuration::deleteByName('FME_COMINGSOON_SHOW_COUNTDOWN');
        Configuration::deleteByName('FME_COMINGSOON_AUTO_EMAIL');
        Configuration::deleteByName('FME_COMINGSOON_TEXT');
        include_once $this->local_path . 'sql/Uninstall.php';
        return (parent::uninstall());
    }

    public function initTokenSecure()
    {
        $now = date('Y-m-d H:i:s');
        $time_string = strtotime($now);
        $time_string = md5(_COOKIE_KEY_ . $time_string);
        Configuration::updateValue('FME_CARTENABLE_TOKEN', $time_string);
        return true;
    }

    public function getContent()
    {
        if (Tools::isSubmit('submit' . $this->name)) {
            $fme_comingsoon_text = [];
            $default_language_id = Configuration::get('PS_LANG_DEFAULT');
            $languages = Language::getLanguages(false);

            foreach ($languages as $lang) {
                $fme_comingsoon_text[$lang['id_lang']] = Tools::getValue('fme_comingsoon_text_' . $lang['id_lang']);
            }
            if (empty($fme_comingsoon_text[$default_language_id])) {
                $output = $this->context->controller->errors[] = $this->l('Comingsoon text required');
                return $output . $this->displayForm();
            } else {
                Configuration::updateValue('FME_COMINGSOON_SHOW_COUNTDOWN', (int) Tools::getValue('fme_comingsoon_show_countdown_1'));
                Configuration::updateValue('FME_COMINGSOON_AUTO_EMAIL', (int) Tools::getValue('fme_comingsoon_auto_email_1'));
                Configuration::updateValue('FME_COMINGSOON_BACKGROUND_COLOR_FOR_TIMER', Tools::getValue('background_color_for_timer'));
                 

                Configuration::updateValue('FME_COMINGSOON_TEXT', $fme_comingsoon_text, true);

                $output = $this->displayConfirmation($this->l('Settings updated'));
                return $output . $this->displayForm();
            }
        }
        return $this->displayForm();
    }

    public function displayForm()
    {
        $form = [
            'form' => [
                'legend' => [
                    'icon' => 'icon-cogs',
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'checkbox',
                        'label' => $this->l('Show Countdown'),
                        'name' => 'fme_comingsoon_show_countdown',
                        'values' => [
                            'query' => [
                                [
                                    'check_id' => '1',
                                    'name' => $this->l('Show the arrival time countdown'),
                                    'val' => '1',
                                ],
                            ],
                            'id' => 'check_id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'checkbox',
                        'label' => $this->l('Auto Email'),
                        'name' => 'fme_comingsoon_auto_email',
                        'values' => [
                            'query' => [
                                [
                                    'check_id' => '1',
                                    'name' => $this->l('Send email automatically when the product arrival time is over'),
                                    'val' => '1',

                                ],

                            ],
                            'id' => 'check_id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Background Color For Timer'),
                        'name' => 'background_color_for_timer'   
                    ],
                    [
                        'type' => 'textarea',
                        'lang' => true,
                        'autoload_rte' => true,
                        'label' => $this->l('Coming Soon Text'),
                        'name' => 'fme_comingsoon_text',
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],

            ],
        ];

        $helper = new HelperForm();
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->identifier = $this->identifier;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
        '&configure=' . $this->name .
        '&tab_module=' . $this->tab .
        '&module_name=' . $this->name;
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->submit_action = 'submit' . $this->name;
        $helper->show_cancel_button = true;
        return $helper->generateForm([$form]);
    }

    public function getConfigFieldsValues()
    {
        $return = [];
        $return['fme_comingsoon_show_countdown_1'] = (int) Configuration::get('FME_COMINGSOON_SHOW_COUNTDOWN');
        $return['fme_comingsoon_auto_email_1'] = (int) Configuration::get('FME_COMINGSOON_AUTO_EMAIL');
        $return['background_color_for_timer'] =  Configuration::get('FME_COMINGSOON_BACKGROUND_COLOR_FOR_TIMER');
         
        

        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $return['fme_comingsoon_text'][$lang['id_lang']] = Configuration::get('FME_COMINGSOON_TEXT', $lang['id_lang']);
        }

        return $return;
    }

    public function hookHeader()
    {   
        $this->context->controller->addJS($this->_path . 'views/js/Frontfile.js');
        $this->context->controller->addCSS($this->_path . 'views/css/front/File.css');
        $id = Tools::getValue('id_product');
        $id_product_array = Tools::getValue('id_product_attribute');
        $comingsoon_available_date = SubmitData::productInformation((int)$this->context->shop->id);
        foreach ($comingsoon_available_date as $value) {
            if ($id == $value['id_products'] && $id_product_array == $value['id_product_array']) {
                
                Media::addJsDef(array(
                    'coomingsoon_date' => $value['available_date'],
                    'id_product' => $id,
                    'FME_CARTENABLE_TOKEN' => Configuration::get('FME_CARTENABLE_TOKEN'),
                    'myurl' => Context::getContext()->link->getModuleLink('comingsoon', 'cartenable', array('ajax' => true))));
            }
        }
    }
    //Loading text from database to frontoffice
    public function hookDisplayProductAdditionalInfo($params)
    {
        $lang = $this->context->language->id;
        $id = Tools::getValue('id_product');
        $id_product_array =  $params['product']['id_product_attribute'];
        $allcomingsoonproducts = SubmitData::productInformation((int)$this->context->shop->id);
        $this->context->smarty->assign('fme_comingsoon_text', Configuration::get('FME_COMINGSOON_TEXT', $lang));
        $this->context->smarty->assign('background_color_for_timer', Configuration::get('FME_COMINGSOON_BACKGROUND_COLOR_FOR_TIMER'));
        foreach ($allcomingsoonproducts as $value) {
            if ($id == $value['id_products'] && $id_product_array == $value['id_product_array']) {
                return $this->display(__FILE__, 'views/templates/hook/DisplayInfo.tpl');
            }
        }
        
    }

    //Loading tpl file in front office
    public function hookDisplayAdminProductsExtra($params)
    {

        $product = new Product($params['id_product']);

        if ($product->hasAttributes()) {
            $combinations = $product->getAttributesResume($this->context->language->id);
            $this->context->smarty->assign('id_product', $product->id);
            $this->context->smarty->assign('combinationdata', $combinations);
            return $this->display(__FILE__, 'views/templates/admin/MyTabvariation.tpl');
        } else {
            $this->context->smarty->assign('id_product', $product->id);
            return $this->display(__FILE__, 'views/templates/admin/MyTabsimple.tpl');
        }
    }

    //Storing data in database
    public function hookActionObjectProductUpdateAfter($params)
    {
        $id_product_array = Tools::getValue('id_product_array');
        $id_product = Tools::getValue('id_product');
        $available_date = Tools::getValue('availabledate');
        SubmitData::updateRecord($id_product);
        foreach ($id_product_array as $value) {
            if (empty($available_date[$value])) {
                $this->context->controller->errors['hooks_availabledate'];
                if ($this->context->controller->errors) {
                    http_response_code(400);
                    die(json_encode($this->context->controller->errors));
                }
            } else {
                $id = SubmitData::forDuplication($id_product, $value);
                $object = new SubmitData($id);
                $object->id_products = $id_product;
                $object->id_product_array = $value;
                $object->available_date = $available_date[$value];
                $object->save();
            }
        }
         
    }

    public function hookDisplayBackOfficeHeader($params)
    {

        if ($this->context->controller->controller_name == 'AdminProducts') {
            if (Tools::version_compare(_PS_VERSION_, '1.7.6.9', '<=') == true) {
                $this->context->controller->addJS(Media::getJqueryPath(), true);
            }

            $this->context->controller->addJS($this->_path . 'views/js/File.js');
            $this->context->controller->addCSS($this->_path . 'views/css/admin/adminfile.css');
        }
    }
}
