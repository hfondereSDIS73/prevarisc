<?php

class Service_Contact extends Service_Abstract
{
    /**
     * Récupération de l'ensemble des types de fonctions
     *
     * @return array
     */
    public function getFonctions()
    {
        $DB_contactfonction = new Model_DbTable_Fonction;

        return $DB_contactfonction->fetchAll()->toArray();
    }
}
