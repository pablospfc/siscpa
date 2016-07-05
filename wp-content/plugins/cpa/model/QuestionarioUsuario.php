<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 23/06/16
 * Time: 13:07
 */

namespace Siscpa\model;
use MocaBonita\includes\Validator;
use Exception;
use MocaBonita\model\ModelMB;

class QuestionarioUsuario extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_questionario_usuario';

        $this->rules = [
            'id_questionario' => 'numeric :  1',
            'id_usuario'      => 'numeric :  1',
            'observacao'      => 'string  : 10',
        ];

        $this->structure = null;
    }

    public function create(array $data)
    {
        try {
            //Validar campos para criar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->insert($this->table, $dados, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "Cadastrado com sucesso!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível associar usuário ao questionário! Tente novamente.");
        }
    }

    private function validarDados($data){
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if(!$dados){
            $mensagemErro = "Questionário: ";

            //Processar todas as mensagens de erro de uma unica vez
            foreach (Validator::getMessages() as $message)
                $mensagemErro .= implode("\n", $message) . "\n";

            throw new SiscpaException($mensagemErro);
        }

        return $dados;
    }

}