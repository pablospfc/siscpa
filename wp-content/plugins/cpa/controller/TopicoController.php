<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 22/06/16
 * Time: 16:58
 */

namespace Siscpa\controller;

use Siscpa\model\Topico;
use MocaBonita\controller\Controller;

class TopicoController extends Controller
{
    private $topico; //Reservada para a model

    public function __construct()
    {
        parent::__construct();
        $this->topico =  new Topico();
    }

    public function indexAction()
    {
        $this->view->setPage('topico');
    }

    public function readAction(){
        return $this->topico->getById($this->getRequestParams('id'));
    }

    public function createAction()
    {
        return $this->topico->create($this->getRequestData());
    }

    public function updateAction()
    {
        return $this->topico->update($this->getRequestData());
    }

    public function deleteAction()
    {
        return $this->topico->delete($this->getRequestData('id'));
    }

}