<?php

namespace Siscpa\model;
use MocaBonita\includes\Validator;
use MocaBonita\model\ModelMB;
use Exception;

class Topico extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_topico';

        $this->rules = [
            'nome'  => 'string  : 10 : 100',
            'ordem' => 'numeric :  1',
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
                'message' => "O tópico foi cadastrado com sucesso!",
                'id'      => $lastId,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao cadastrar esse topico! Tente novamente.");
        }
    }

    public function update(array $data, $id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de eixo foi enviado!");

            //Validar campos para atualizar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->replace($this->table, $dados, $where, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "O topico foi atualizado com sucesso!",
                'id'      => $id,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao atualizar esse topico! Tente novamente.");
        }
    }

    public function delete($id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID do eixo foi enviado!");

            $status = $this->remove($this->table, $where);

            return [
                'status'  => $status,
                'message' => $status ? "O questionário foi excluído com sucesso!" : "Nenhum questionário foi encontrado!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao excluir esse topico! Tente novamente.");
        }
    }

    public function deleteTopicoSemPergunta()
    {
        try {
            
            $status = $this->query(
                "DELETE FROM cpa_topico
                    WHERE id IN (
                      SELECT * FROM (
                        SELECT
                          cpa_topico.id
                        FROM cpa_topico
                          LEFT JOIN cpa_pergunta ON cpa_topico.id = id_topico
                        GROUP BY cpa_topico.id, id_topico
                        HAVING id_topico IS NULL AND count(*) = 1
                      ) AS p
                    )"
            );

            return [
                'status'  => $status,
                'message' => $status ? "Os tópicos sem perguntas foram excluídos!" : "Nenhum tópico sem pergunta foi encontrado!",
            ];

        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao excluir os topicos! Tente novamente.");
        }
    }

    private function validarDados($data){
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if(!$dados){
            $mensagemErro = "Topico: ";

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
            return $this->getAll(['id', 'nome', 'numero'], [], ['nome']);
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar esse eixo! Tente novamente.");
        }
    }

    public function getById($id) {
        try {
            if(!Validator::check(['id' => $id], ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de eixo foi enviado!");

            $query = "SELECT * FROM {$this->table} WHERE id = %d";

            $dados = $this->getResults($this->prepare($query, array($id)));

            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhum eixo foi encontrado com esse ID!");

        }catch (SiscpaException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar esse eixo! Tente novamente.");
        }
    }
}