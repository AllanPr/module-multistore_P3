<?php

class AdminMultistoreShopCmdController extends ModuleAdminController {


    public function __construct()
    {
        // compatibilité "graphique" 1.6.X
        $this->bootstrap = true;

        // On indique la table sur laquelle on se base
        $this->table = 'employee';
        // On indique le nom de la classe associée
        $this->className = "MultistoreEmployee";
        // On indique sur la table est multilingue
        $this->lang = false;

        // On récupère tous les champs qu'on souhaite afficher dans le tableau
        $this->fields_list = [
            'id_employee' => [
                'title' => $this->l('#'),
                'type' => 'int'
            ],
            'lastname' => [
                'title' => $this->l('lastname'),
                'type' => 'text'
            ],
            'firstname' => [
                'title' => $this->l('firstname'),
                'type' => 'text'
            ],          
        ];
        parent::__construct();  
    }

    /*
    * Method Translation Override For PS 1.7
    */
    public function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if (method_exists('Context', 'getTranslator')) {
            $this->translator = Context::getContext()->getTranslator();
            $translated = $this->translator->trans($string);
   
            if ($translated !== $string) {
                return $translated;
            }
        }
        if ($class === null || $class == 'AdminTab') {
            $class = Tools::substr(get_class($this), 0, -10);
        } elseif (Tools::strtolower(Tools::substr($class, -10)) == 'controller') {
            $class = Tools::substr($class, 0, -10);
        }
        return Translate::getAdminTranslation($string, $class, $addslashes, $htmlentities);
    }
}