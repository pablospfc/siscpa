<?php

namespace Siscpa\model;

use MocaBonita\includes\Validator;
use MocaBonita\model\ModelMB;
use Exception;

class Dimensao extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_dimensao';

        $this->rules = [
            'id_eixo' => 'numeric :  1',
            'nome'    => 'string  : 10',
            'ordem'   => 'string  : 10',
            'numero'  => 'numeric :  8'
        ];

        $this->structure = null;
    }

    public function create(array $data)
    {
        try {
            //Validar campos para criar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $lastId = $this->insert($this->table, $dados, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "A dimensão foi cadastrada com sucesso!",
                'id'      => $lastId,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao cadastrar essa dimensão! Tente novamente.");
        }
    }

    public function update(array $data, $id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de dimensão foi enviado!");

            //Validar campos para atualizar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->replace($this->table, $dados, $where, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "A dimensão foi atualizada com sucesso!",
                'id'      => $id,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao atualizar essa dimensão! Tente novamente.");
        }
    }

    public function delete($id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID da dimensão foi enviado!");

            $status = $this->remove($this->table, $where);

            return [
                'status'  => $status,
                'message' => $status ? "A dimensão foi excluída com sucesso!" : "Nenhuma dimensão foi encontrada!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao excluir essa dimensão! Tente novamente.");
        }
    }

    private function validarDados($data){
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if(!$dados){
            $mensagemErro = "Dimensao: ";

            //Processar todas as mensagens de erro de uma unica vez
            foreach (Validator::getMessages() as $message)
                $mensagemErro .= implode("\n", $message) . "\n";

            throw new SiscpaException($mensagemErro);
        }

        return $dados;
    }

    public function getList()
    {
        try {
            $query = "SELECT 
                             cpa_dimensao.id as id,
                             cpa_dimensao.nome as nome,
                             cpa_dimensao.ordem as ordem,
                             cpa_dimensao.numero as numero,
                             cpa_dimensao.id_eixo as id_eixo,
                             cpa_eixo.nome as eixo
                      FROM cpa_dimensao
                      INNER JOIN cpa_eixo on cpa_eixo.id = cpa_dimensao.id_eixo
                         ";
            
            $dados = $this->getResultsT($query, ARRAY_A);
            
            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhuma dimensão foi encontrada!");
            
            return $dados;
            
        } catch (SiscpaException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao listar dimensões! Tente novamente.");
        }
    }
    
    public function getById($id) {
        try {
            if(!Validator::check(['id' => $id], ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de dimensão foi enviado!");

            $query = "SELECT 
                             cpa_dimensao.id as id,
                             cpa_dimensao.nome as nome,
                             cpa_dimensao.ordem as ordem,
                             cpa_dimensao.numero as numero,
                             cpa_dimensao.id_eixo as id_eixo,
                             cpa_eixo.nome as eixo
                      FROM cpa_dimensao
                      INNER JOIN cpa_eixo on cpa_eixo.id = cpa_dimensao.id_eixo
                      WHERE cpa_dimensao.id = %d";

            $dados = $this->getResults($this->prepare($query, array($id)));

            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhuma dimensão foi encontrado com esse ID!");
            
        }catch (SiscpaException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar essa dimensão! Tente novamente.");
        }
    }
    

}