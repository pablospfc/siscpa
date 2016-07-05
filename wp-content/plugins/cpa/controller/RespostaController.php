<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 23/06/16
 * Time: 11:05
 */

namespace Siscpa\controller;


use MocaBonita\controller\Controller;
use Siscpa\model\Resposta;

class RespostaController extends Controller
{
    private $resposta; //Reservada para a model

    public function __construct()
    {
        parent::__construct();
        $this->resposta =  new Resposta();
    }

    public function indexAction()
    {
        $this->view->setPage('resposta');
    }

    public function createAction()
    {
        return $this->resposta->adicionarResposta($this->getRequestData());
    }



}