<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 22/06/16
 * Time: 17:20
 */

namespace Siscpa\controller;


use MocaBonita\controller\Controller;
use Siscpa\model\Pergunta;

class PerguntaController extends Controller
{
    private $pergunta; //Reservada para a model

    public function __construct()
    {
        parent::__construct();
        $this->pergunta =  new Pergunta();
    }

    public function indexAction()
    {
        $this->view->setPage('pergunta');
    }

    public function readAction(){
        return $this->pergunta->getById($this->getRequestParams('id'));
    }

    public function createAction()
    {
        return $this->pergunta->create($this->getRequestData());
    }

    public function updateAction()
    {
        return $this->pergunta->update($this->getRequestData());
    }

    public function deleteAction()
    {
        return $this->pergunta->delete($this->getRequestData('id'));
    }

}