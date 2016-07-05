<?php

namespace Siscpa\controller;

use MocaBonita\controller\Controller;
use Siscpa\model\Questionario;

class QuestionarioController extends Controller
{
    private $questionario; //Reservada para a model

    public function __construct()
    {
        parent::__construct();
        $this->questionario =  new Questionario();
    }
    
    //Views

    public function indexAction()
    {
        $this->view->setPage('questionario');
        $this->view->setVars(['ngApp' => 'QuestionarioApp']);
    }
    
    //Views carregadas pelo angularJs
    
    public function listViewAction(){
        $this->view->setView('blank', 'questionario', 'list');
    }

    public function previewViewAction(){
        $this->view->setView('blank', 'questionario', 'preview');
    }

    public function createViewAction(){
        $this->view->setView('blank', 'questionario', 'create');
    }
    
    public function updateViewAction(){
        $this->view->setView('blank', 'questionario', 'update');        
    }
    
    public function adicionarTopicoViewAction(){
        $this->view->setView('modal', 'questionario', 'adicionar_topico');
    }

    //Dados para alimentação
    
    public function readAction(){
        return $this->questionario->getById($this->getRequestParams('id'));
    }

    public function getInfoAction(){
        return $this->questionario->dadosParaQuestionario();
    }
    
    public function getListAction(){
        return $this->questionario->getList();
    }
    
    //Alteração dos dados
    
    public function createAction()
    {
        return $this->questionario->adicionarQuestionario($this->getRequestData());
    }

    public function updateAction()
    {
        //return $this->questionario->update($this->getRequestData());
    }

    public function deleteAction()
    {
        return $this->questionario->delete($this->getRequestData('id'));
    }

}