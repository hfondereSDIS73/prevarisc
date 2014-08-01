<?php

class SearchController extends Zend_Controller_Action
{
    public function init()
    {
      $this->view->nav_side_items = array(
        array("text" => "Etablissements", "icon" => "home", "link" => $this->view->url(array(), 'search_ets', true)),
        array("text" => "Dossiers", "icon" => "folder-open", "link" => $this->view->url(array(), 'search_doss', true)),
        array("text" => "Courriers", "icon" => "envelope", "link" => $this->view->url(array(), 'search_courriers', true))
      );
    }

    public function etablissementAction()
    {
        $service_search = new Service_Search;

        $service_genre = new Service_Genre;
        $service_statut = new Service_Statut;
        $service_avis = new Service_Avis;
        $service_categorie = new Service_Categorie;
        $service_type = new Service_Type;
        $service_famille = new Service_Famille;
        $service_classe = new Service_Classe;

        $this->view->DB_genre = $service_genre->getAll();
        $this->view->DB_statut = $service_statut->getAll();
        $this->view->DB_avis = $service_avis->getAll();
        $this->view->DB_categorie = $service_categorie->getAll();
        $this->view->DB_type = $service_type->getAll();
        $this->view->DB_famille = $service_famille->getAll();
        $this->view->DB_classe = $service_classe->getAll();

        if ($this->_request->isGet() && count($this->_request->getQuery()) > 0) {
            try {
                $parameters = $this->_request->getQuery();
                $label = array_key_exists('label', $parameters) && $parameters['label'] != '' && (string) $parameters['label'][0] != '#' ? $parameters['label'] : null;
                $identifiant = array_key_exists('label', $parameters) && $parameters['label'] != '' && (string) $parameters['label'][0] == '#'? substr($parameters['label'], 1) : null;
                $genres = array_key_exists('genres', $parameters) ? $parameters['genres'] : null;
                $categories = array_key_exists('categories', $parameters) ? $parameters['categories'] : null;
                $classes = array_key_exists('classes', $parameters) ? $parameters['classes'] : null;
                $familles = array_key_exists('familles', $parameters) ? $parameters['familles'] : null;
                $types = array_key_exists('types', $parameters) ? $parameters['types'] : null;
                $avis_favorable = array_key_exists('avis', $parameters) && count($parameters['avis']) == 1 ? $parameters['avis'][0] == 'true' : null;
                $statuts = array_key_exists('statuts', $parameters) ? $parameters['statuts'] : null;
                $local_sommeil = array_key_exists('presences_local_sommeil', $parameters) && count($parameters['presences_local_sommeil']) == 1 ? $parameters['presences_local_sommeil'][0] == 'true' : null;
                $city = array_key_exists('city', $parameters) && $parameters['city'] != '' ? $parameters['city'] : null;
                $street = array_key_exists('id_street', $parameters) && $parameters['id_street'] != '' ? $parameters['id_street'] : null;

                $search = $service_search->etablissements($label, $identifiant, $genres, $categories, $classes, $familles, $types, $avis_favorable, $statuts, $local_sommeil, null, null, null, $city, $street, 50, $parameters['page']);

                $paginator = new Zend_Paginator(new SDIS62_Paginator_Adapter_Array($search['results'], $search['search_metadata']['count']));
                $paginator->setItemCountPerPage(50)->setCurrentPageNumber($parameters['page'])->setDefaultScrollingStyle('Elastic');

                $this->view->results = $paginator;
            } catch (Exception $e) {
                $this->_helper->flashMessenger(array('context' => 'danger','title' => 'Problème de recherche','message' => 'La recherche n\'a pas été effectué correctement. Veuillez rééssayez. (' . $e->getMessage() . ')'));
            }
        }
    }

    public function dossierAction()
    {
        $service_search = new Service_Search;

        $DB_type = new Model_DbTable_DossierType();
        $this->view->DB_type = $DB_type->fetchAll()->toArray();

        if ($this->_request->isGet() && count($this->_request->getQuery()) > 0) {
            try {
                $parameters = $this->_request->getQuery();
                $num_doc_urba = array_key_exists('objet', $parameters) && $parameters['objet'] != '' && (string) $parameters['objet'][0] == '#'? substr($parameters['objet'], 1) : null;
                $objet = array_key_exists('objet', $parameters) && $parameters['objet'] != ''  && (string) $parameters['objet'][0] != '#'? $parameters['objet'] : null;
                $types = array_key_exists('types', $parameters) ? $parameters['types'] : null;

                $search = $service_search->dossiers($types, $objet, $num_doc_urba, null, null, null, 50, $parameters['page']);

                $paginator = new Zend_Paginator(new SDIS62_Paginator_Adapter_Array($search['results'], $search['search_metadata']['count']));
                $paginator->setItemCountPerPage(50)->setCurrentPageNumber($parameters['page'])->setDefaultScrollingStyle('Elastic');

                $this->view->results = $paginator;
            } catch (Exception $e) {
                $this->_helper->flashMessenger(array('context' => 'danger','title' => 'Problème de recherche','message' => 'La recherche n\'a pas été effectué correctement. Veuillez rééssayez. (' . $e->getMessage() . ')'));
            }
        }
    }

    public function courriersAction()
    {
        $service_search = new Service_Search;

        $DB_type = new Model_DbTable_DossierType();
        $this->view->DB_type = $DB_type->fetchAll()->toArray();

        if ($this->_request->isGet() && count($this->_request->getQuery()) > 0) {
            try {
                $parameters = $this->_request->getQuery();
                $num_doc_urba = array_key_exists('num_doc_urba', $parameters) ? $parameters['num_doc_urba'] : null;
                $objet = array_key_exists('objet', $parameters) && $parameters['objet'] != '' ? $parameters['objet'] : null;

                $search = $service_search->dossiers(5, $objet, $num_doc_urba, null, null, null, 50, $parameters['page']);

                $paginator = new Zend_Paginator(new SDIS62_Paginator_Adapter_Array($search['results'], $search['search_metadata']['count']));
                $paginator->setItemCountPerPage(50)->setCurrentPageNumber($parameters['page'])->setDefaultScrollingStyle('Elastic');

                $this->view->results = $paginator;
            } catch (Exception $e) {
                $this->_helper->flashMessenger(array('context' => 'danger','title' => 'Problème de recherche','message' => 'La recherche n\'a pas été effectué correctement. Veuillez rééssayez. (' . $e->getMessage() . ')'));
            }
        }
    }

    public function displayAjaxSearchAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $html = "<ul class='search-list'>";
        $html .= Zend_Layout::getMvcInstance()->getView()->partialLoop('components/search_item_' . $this->_request->items . '.phtml', (array) $this->_request->data );
        $html .= "</ul>";

        echo $html;
    }
}
