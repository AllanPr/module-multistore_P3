<?php

class MultistoreEmployee extends ObjectModel{

        public function __construct($id = null, $id_lang = null) {
            parent::__construct($id, $id_lang);
        }

        public static function getMultistoreEmployees() {

            $query = new DbQuery();
            $query->select('*');
            $query->from('employee');

            // On exécute la requête
            $result = Db::getInstance()->executeS($query);

            return $result;

            dump($result);die;
        }

}