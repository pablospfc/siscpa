<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/06/16
 * Time: 13:00
 */

namespace Siscpa\model\saws;

class Centro extends SAWS
{
    public function __construct()
    {
        parent::__construct();
        $this->method = "sig_centros";
    }

    public function getCenters(){
        try{
            $params = [
                'action' => 'getCenters',
            ];
            $response = $this->sawsRequest( $this->method, $params);

            if (is_null($response))
                throw new \Exception("Nenhuma conta foi encontrada, verifique os dados e tente novamente!", 2);

            elseif (!is_array($response))
                throw new \Exception("Ocorreu um erro de comunicação com o servidor da UEMA.", 1);

            return $response['sucess'];
        } catch(\Exception $e){
            return HTTPMethod::getError(HTTPMethod::REQUEST_UNAVAIABLE, "Ocorreu um problema interno ao listar centros! " . $e->getMessage());
        }
    }

    public function getCenterById($centerId){
        try{

            $params = [
                'action'    => 'getCenters',
                'id'        => $centerId
            ];

            $response = $this->sawsRequest( $this->method, $params);

            if (is_null($response))
                throw new \Exception("Nenhuma conta foi encontrada, verifique os dados e tente novamente!", 2);

            elseif (!is_array($response))
                throw new \Exception("Ocorreu um erro de comunicação com o servidor da UEMA.", 1);

            return $response['sucess'];

        } catch(\Exception $e){
            return HTTPMethod::getError(HTTPMethod::REQUEST_UNAVAIABLE, "Ocorreu um problema interno ao buscar o centro especificado! " . $e->getMessage());
        }
    }
}