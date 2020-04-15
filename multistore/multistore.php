<?php

/**
 * Module de gestion de facture pour plusieurs magasins
 * @author Allan Pradel
 */

require_once(dirname(__FILE__).'/classes/MultistoreEmployee.php');

/** 
 * sécurité de base 
 */
if (!defined('_PS_VERSION_')){
    exit;
}

class Multistore extends Module {
    
    public function __construct()
    {
        // Créer les informations de base du module
        // le nom
        $this->name = 'multistore';
        // Nom d'affichage dans le BO (BackOffice)
        $this->displayName = 'Multi Store';
        // Tabulation - en gros equivalent catégorie de module
        $this->tab = 'shipping';
        // Version du module
        $this->version = '1.0.0';
        // Auteur
        $this->author = 'Allan Pradel';
        // Description du module
        $this->description = $this->l('Helps you edit the bill choosing the store !');
        // Compatibilité de votre module
        $this->ps_versions_compliancy =['min' => '1.6', 'max' => _PS_VERSION_];
        // Confirmation de la suppression
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');
        // Compatibilité 1.6
        $this->bootstrap = true;
        // On fait appel au parent
        parent::__construct();
    }

    // On ajoute des actions à l'installation
    public function install() {

        $carrier = $this->addCarrier();
        $this->addZones($carrier);
        $this->addGroups($carrier);
        $this->addRanges($carrier);
        Configuration::updateValue('WWShop', false);
        
        // On se greffe sur un ou plusieurs hooks
        if (!parent::install()

            // On installe le menu
            || !$this->_installTab(0,'AdminMultiStore', $this->l('Commands'))
            || !$this->_installTab('AdminMultistore', 'AdminMultistoreShopCmd', $this->l('Shop commands'))
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayCarrierExtraContent')

            ) {
            return false;
        }
        return true;
    }

    // On ajoute des actions à la désinstallation
    // Suppression des transporteurs
    // Suppression des menus et sous menus

    public function uninstall()
    {
        
        $carrier = new Carrier((int)Configuration::get('MYSHIPPINGMODULE_CARRIER_ID'));
        if(!$carrier->delete()) {
            return false;
         };         
        
        Configuration::deleteByName('WWShop');
        if (!parent::uninstall()
        || !$this->_uninstallTab('AdminMultistore')
        || !$this->_uninstallTab('AdminMultistoreShopCmd')
        ) {
            return false;
        }
        return true;
    }

    // Création de menu de gestion des langues

    private function _installTab($parent, $class_name, $name)
    {
        $tab = new Tab();
        $tab->id_parent = (int)Tab::getIdFromClassName($parent);
        $tab->class_name = $class_name;
        $tab->module = $this->name;

        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }
        return $tab->save();
    }

    // Suppression menu langues

    private function _uninstallTab($class_name)
    {
        $id_tab = Tab::getIdFromClassName($class_name);
        $tab = new Tab((int)$id_tab);

        return $tab->delete();
    }

    // On utilise le Hook Header pour les fichiers CSS et JS

    public function hookDisplayHeader($params) {
        $this->context->controller->addCSS($this->_path.'views/css/multistore.css');
        $this->context->controller->addJS($this->_path.'views/js/multistore.js');
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        if (Context::getContext()->customer->logged == true) {
            $id_address_delivery = Context::getContext()->cart->id_address_delivery;
            $address = new Address($id_address_delivery);

            return 10;
        }

        return $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return true;
    }

    // On crée la liste de magasins sur le hook

    public function hookDisplayCarrierExtraContent($params)
    {

        $idLang = (int)$this->context->language->id;
        $stores = Store::getStores($idLang);
        

        $this->context->smarty->assign('stores',$stores);

        return $this->display(__FILE__, 'displayCarrierExtraContent.tpl');
    }

    // On crée un nouveau transporteur

    protected function addCarrier()
    {
        $carrier = new Carrier();

        $carrier->name = $this->l('Store pickup');
        $carrier->is_module = true;
        $carrier->is_free = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->name;
        $carrier->shipping_method = 2;

        foreach (Language::getLanguages() as $lang)
            $carrier->delay[$lang['id_lang']] = $this->l('Free store pickup');

        if ($carrier->add() == true) {
            @copy(dirname(__FILE__).'/views/img/carrier_image.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg');
            Configuration::updateValue('MYSHIPPINGMODULE_CARRIER_ID', (int)$carrier->id);
            return $carrier;
        }

        return false;
    }

    // On ajoute des groupes

    protected function addGroups($carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group)
            $groups_ids[] = $group['id_group'];

        $carrier->setGroups($groups_ids);
    }

    // On ajoute les fourchettes de prix/volume pour le transporteur

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
    }

    // On ajoute des zones pour le transporteur

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone)
            $carrier->addZone($zone['id_zone']);
    }
   
    // Fonction principale du module

    public function getContent()
    {
        $idLang = (int)$this->context->language->id;
        $stores = Store::getStores($idLang);

        $employees = Employee::getEmployees($activeOnly = true);

        $this->context->smarty->assign('employees',$employees);
        $this->context->smarty->assign('stores',$stores);

        $this->context->smarty->assign('module_version', $this->version);
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
}