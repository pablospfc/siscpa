<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 23/06/16
 * Time: 11:06
 */

namespace Siscpa\model;

use MocaBonita\includes\Validator;
use MocaBonita\model\ModelMB;
use Exception;

class Resposta extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_resposta';

        $this->rules = [
            'id_opcao' => 'numeric : 1',
            'id_usuario' => 'numeric : 1',
            'id_pergunta' => 'numeric : 1',
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
                'message' => "O questionário foi respondido com sucesso!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao responder o questionário! Tente novamente.");
        }
    }

    public function update(array $data, $idPergunta, $idUsuario) {
        try {
            //Estrutura do id do update
            $where = ['id_pergunta' => $idPergunta, 'id_usuario' => $idUsuario];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id_pergunta' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de pergunta foi enviado!");

            if(!Validator::check($where, ['id_usuario' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de usuário foi enviado!");

            //Validar campos para atualizar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->replace($this->table, $dados, $where, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "As respostas do questionário foram atualizadas com sucesso!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao atualizar esse questionário! Tente novamente.");
        }
    }

    public function adicionarResposta(array $post)
    {
        try {
            //Iniciar Transação
            $this->beginTransaction();

            foreach ($post['respostas'] as $resposta) {
                $this->create($resposta);
            }

            (new QuestionarioUsuario())->create($post);

            //Finalizar Transação
            $this->commit();

            return [
                'message' => "O questionário foi respondido com sucesso!",
            ];

        } catch (\Exception $e) {
            //Desfazer transação
            Log::getInstance()->setLog($e);
            $this->rollBack();
            throw $e;
        }
    }

    public function atualizarResposta(array $post)
    {
        try {
            foreach ($post['respostas'] as $resposta) {
                $this->create($resposta);
            }

            return [
                'message' => "As respostas do questionário foram atualizadas com sucesso!",
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function validarDados($data)
    {
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if (!$dados) {
            $mensagemErro = "Questionario: ";

            //Processar todas as mensagens de erro de uma unica vez
            foreach (Validator::getMessages() as $message)
                $mensagemErro .= implode("\n", $message) . "\n";

            throw new SiscpaException($mensagemErro);
        }

        return $dados;
    }

    public function getRespostas($idUsuario, $idQuestionario)
    {
        if(!Validator::check(['id_usuario' => $idUsuario], ['id_usuario' => 'numeric : 1']))
            throw  new SiscpaException("Nenhum ID de usuário não foi enviado enviado!");

        if(!Validator::check(['id_questionario' => $idQuestionario], ['id_questionario' => 'numeric : 1']))
            throw  new SiscpaException("Nenhum ID de questionário não foi enviado!");

        try {
            $query = "SELECT res.id_opcao AS id_opcao,
                         res.id_usuario AS id_usuario,
                         res.id_pergunta AS id_pergunta,
                         opc.nome AS opcao,
                         opc.peso AS peso,
                         usu.matricula AS matricula,
                         que.id AS id_questionario,
                         que.nome AS questionario,
                         per.nome AS pergunta
                  FROM cpa_resposta res
                  INNER JOIN cpa_opcao opc ON opc.id = res.id_opcao
                  INNER JOIN cpa_pergunta per ON per.id = res.id_pergunta
                  INNER JOIN cpa_usuario usu ON usu.id = res.id_usuario
                  INNER JOIN cpa_questionario_usuario qus ON qus.id_usuario = usu.id
                  INNER JOIN cpa_questionario que ON que.id = qus.id_questionario
                  WHERE que.id = %d AND usu.id = %d";

           return $this->getResults($this->prepare($query, array($idUsuario, $idQuestionario )));
            
        }catch (SiscpaException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao listar as respostas do seu questionário! Tente novamente.");
        }
    }

}