<?php

namespace Siscpa\model\saws;

abstract class WsSiguema
{

    private $wsUrl;
    private $appNome;
    private $appToken;

    public function __construct()
    {
        $this->wsUrl = 'http://wssiguema.teste';
        $this->appNome = 'siguema';
        $this->appToken = '9cad5b4181fa8326500f2d6473fa9a36';
    }

    protected function wsRequest(       $method,
                                 array  $params,
                                        $messageError = null,
                                        $routeName = 'wservice')
    {
        $url = "{$this->wsUrl}/{$routeName}/{$method}/?" . http_build_query($params);
        $response = wp_remote_get($url, [
            'headers' => ['appNome' => $this->appNome, 'appToken' => $this->appToken],
            'timeout' => 15,
        ]);
        $messageError = is_null($messageError) ? "Ocorreu um erro ao consultar o servidor." : $messageError;

        $isJson = function($string) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        };

        if(!is_array($response))
            throw new \Exception($messageError);

        elseif('null' == $response['body'])
            return null;

        elseif(!$isJson($response['body']))
            return $response['body'];

        $returnData = json_decode($response['body'], true);

        if (isset($returnData[0]['error']))
            throw new \Exception($returnData[0]['error']);

        return $returnData;
    }
}