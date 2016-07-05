<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 31/05/16
 * Time: 10:36
 */

namespace Siscpa\model;
use MocaBonita\includes\Validator;
use Mocabonita\model\ModelMB;
use \Exception;

class Pergunta extends ModelMB
{
    private $rules;
    private $structure;
    
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_pergunta';

        Validator::addRules('id_topico', function ($data){
            if((is_numeric($data) && $data > 0) || (is_string($data) && $data == "no_topic")){
                return true;
            } else
                throw new Exception("O id_topico é invalido!");
        });

        $this->rules     = [
            'id_dimensao'     => 'numeric :  1',
            'id_topico'       => 'id_topico :  1',
            'id_questionario' => 'numeric :  1',
            'nome'            => 'string  : 10',
            'ordem'           => 'numeric :  1'
        ];
        
        $this->structure = null;
    }

    public function create(array $data)
    {
        try {
            //Validar campos para criar questionário
            $dados = $this->validarDados($data);

            //Exceção de id_topic pois pode ser null
            if($dados['id_topico'] == "no_topic")
                unset($dados['id_topico']);

            //Inserir os dados no banco de dados e receber o ID gerado
            $lastId = $this->insert($this->table, $dados, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "A pergunta foi cadastrado com sucesso!",
                'id'      => $lastId,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao cadastrar essa pergunta! Tente novamente.");
        }
    }

    public function getList() {
        try {
            $query = "SELECT cpa_pergunta.id as id,
                             cpa_pergunta.nome as nome,
                             cpa_pergunta.ordem as ordem,
                             cpa_pergunta.id_topico as id_topico,
                             cpa_pergunta.id_dimensao as id_dimensao,
                             cpa_pergunta.id_questionario as id_questionario,
                             cpa_topico.nome as topico,
                             cpa_dimensao.nome as dimensao,
                             cpa_questionario.nome as questionario
                      FROM cpa_pergunta
                      LEFT JOIN cpa_topico on cpa_topico.id = cpa_pergunta.id_topico
                      INNER JOIN cpa_dimensao on cpa_dimensao.id = cpa_pergunta.id_dimensao
                      INNER JOIN cpa_questionario on cpa_questionario.id = cpa_pergunta.id_questionario
                         ";
            return $this->getResultsT($query, ARRAY_A);

        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar essa pergunta! Tente novamente.");
        }
    }

    public function update(array $data, $id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID da pergunta foi enviado!");

            //Validar campos para atualizar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->replace($this->table, $dados, $where, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "A pergunta foi atualizada com sucesso!",
                'id'      => $id,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao atualizar essa pergunta! Tente novamente.");
        }
    }

    public function delete($id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de pergunta foi enviado!");

            $status = $this->remove($this->table, $where);
            
            (new Topico())->deleteTopicoSemPergunta();

            return [
                'status'  => $status,
                'message' => $status ? "A pergunta foi excluída com sucesso!" : "Nenhuma pergunta foi encontrada!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao excluir essa pergunta! Tente novamente.");
        }
    }

    private function validarDados($data){
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if(!$dados){
            $mensagemErro = "Pergunta: ";

            //Processar todas as mensagens de erro de uma unica vez
            foreach (Validator::getMessages() as $message)
                $mensagemErro .= implode("\n", $message) . "\n";

            throw new SiscpaException($mensagemErro);
        }

        return $dados;
    }

}