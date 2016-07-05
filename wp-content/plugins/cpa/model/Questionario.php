<?php

namespace Siscpa\model;
use MocaBonita\includes\Validator;
use MocaBonita\model\ModelMB;
use \Exception;

class Questionario extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();

        Validator::addRules('data', function ($data, $attr){
            if(strtotime($data)){
                return true;
            } else
                throw new Exception("O atributo '{$attr}' enviada é inválido!");
        });

        $this->table     = 'cpa_questionario';
        $this->rules     = [
            'id_tipo_usuario' => 'numeric : 1',
            'nome'            => 'string  : 5',
            'data_inicio'     => 'data',
            'data_fim'        => 'data',
        ];
        $this->structure = null;
    }

    private function create(array $data)
    {
        try {
            //Validar campos para criar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $lastId = $this->insert($this->table, $dados, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "O questionário foi cadastrado com sucesso!",
                'id'      => $lastId,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao cadastrar esse questionário! Tente novamente.");
        }
    }

    public function getList() {
        try {
            return $this->getAll(['id', 'nome', 'data_inicio', 'data_fim'], [], ['id']);
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar esse questionário! Tente novamente.");
        }
    }

    public function getById($id) {
        try {

            if(!Validator::check(['id' => $id], ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de questionário foi enviado!");

            $query = "SELECT questionario.id as questionario_id,
                             questionario.nome as questionario_nome, 
                             questionario.data_inicio as questionario_data_inicio,
                             questionario.data_fim as questionario_data_fim,
                             tipo_usuario.nome as tipo_usuario_nome,
                             dimensao.id as dimensao_id,
                             dimensao.nome as dimensao_nome,
                             dimensao.ordem as dimensao_ordem,
                             topico.id as topico_id,
                             topico.nome as topico_nome,
                             topico.ordem as topico_ordem,
                             pergunta.id as pergunta_id,
                             pergunta.nome as pergunta_nome,
                             pergunta.ordem as pergunta_ordem
                      FROM cpa_questionario as questionario 
                      INNER JOIN cpa_tipo_usuario as tipo_usuario on tipo_usuario.id = questionario.id_tipo_usuario
                      INNER JOIN cpa_pergunta as pergunta on questionario.id = pergunta.id_questionario
                      INNER JOIN cpa_dimensao as dimensao on pergunta.id_dimensao = dimensao.id
                      LEFT JOIN cpa_topico as topico on pergunta.id_topico = topico.id
                      WHERE questionario.id = %d
                      ORDER BY dimensao_ordem, topico.ordem, pergunta_ordem";

            $dados = $this->getResults($this->prepare($query, array($id)));

            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhum questionário foi encontrado com esse ID!");

            //return $dados;
            return $this->formatQuestionario($dados);

        } catch (SiscpaException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar esse questionário! Tente novamente.");
        }
    }

    public function update(array $data, $id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de questionário foi enviado!");

            //Validar campos para atualizar questionário
            $dados = $this->validarDados($data);

            //Inserir os dados no banco de dados e receber o ID gerado
            $this->replace($this->table, $dados, $where, $this->structure);

            //Retornar a mensagem e o ID gerado
            return [
                'message' => "O questionário foi atualizado com sucesso!",
                'id'      => $id,
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao atualizar esse questionário! Tente novamente.");
        }
    }

    public function delete($id)
    {
        try {
            //Estrutura do id do update
            $where = ['id' => $id];

            //Validar id para atualizar questionário
            if(!Validator::check($where, ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de questionário foi enviado!");

            $status = $this->remove($this->table, $where);

            (new Topico())->deleteTopicoSemPergunta();
                        
            return [
                'status'  => $status,
                'message' => $status ? "O questionário foi excluído com sucesso!" : "Nenhum questionário foi encontrado!",
            ];

        } catch (SiscpaException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao excluir esse questionário! Tente novamente.");
        }
    }

    private function validarDados($data){
        //Validar campos do questionário
        $dados = Validator::check($data, $this->rules, true);

        //Verificar se os dados não foram aceitos para retornar a mensagem de erro
        if(!$dados){
            $mensagemErro = "Questionario: ";

            //Processar todas as mensagens de erro de uma unica vez
            foreach (Validator::getMessages() as $message)
                $mensagemErro .= implode("\n", $message) . "\n";

            throw new SiscpaException($mensagemErro);
        }

        return $dados;
    }
    
//    Metodos exclusivos dessa classe.

    private function formatQuestionario(array $dados){

        $questionario = [
            'questionario_id'          => $dados[0]->questionario_id,
            'questionario_nome'        => $dados[0]->questionario_nome,
            'questionario_data_inicio' => $dados[0]->questionario_data_inicio,
            'questionario_data_fim'    => $dados[0]->questionario_data_fim,
            'tipo_usuario_nome'        => $dados[0]->tipo_usuario_nome,
            'dimensoes'                => [],
        ];

        $dimensoes = [];
        $topicos   = [];
        $opcoes    = (new Opcao())->getAll(['id', 'nome', 'ordem'], [], ['ordem']);

        foreach ($dados as $dado){

            if(!isset($dimensoes[(int) $dado->dimensao_id]))
                $dimensoes[(int) $dado->dimensao_id] = [
                    'dimensao_id'    => $dado->dimensao_id,
                    'dimensao_nome'  => $dado->dimensao_nome,
                    'dimensao_ordem' => $dado->dimensao_ordem,
                    'perguntas'      => [],
                ];

            if(!isset($topicos[(int) $dado->dimensao_id][(int)$dado->topico_id]))
                $topicos[(int) $dado->dimensao_id][(int)$dado->topico_id] = [
                    'topico_id'    => $dado->topico_id,
                    'topico_nome'  => $dado->topico_nome,
                    'perguntas'    => [],
                ];

            $topicos[(int) $dado->dimensao_id][(int)$dado->topico_id]['perguntas'][] = [
                'tipo'           => 'pergunta',
                'pergunta_id'    => $dado->pergunta_id,
                'pergunta_nome'  => $dado->pergunta_nome,
                'pergunta_ordem' => $dado->pergunta_ordem,
                'opcoes'         => $opcoes,
            ];
        }

        foreach ($dimensoes as &$dimensao){
            foreach ($topicos[$dimensao['dimensao_id']] as $topico){

                if(!is_null($topico['topico_id']))
                    $dimensao['perguntas'][] = [
                        'tipo'        => 'topico',
                        'topico_id'   => $topico['topico_id'],
                        'topico_nome' => $topico['topico_nome'],
                    ];

                foreach ($topico['perguntas'] as $pergunta)
                    $dimensao['perguntas'][] = $pergunta;
            }
        }

        $questionario['dimensoes'] = array_values($dimensoes);

        return $questionario;
    }
    
    public function dadosParaQuestionario(){
        try {
            return [
                'tipo_usuarios' => (new TipoUsuario())->getAll([], [], ['nome']),
                'dimensoes'     => (new Dimensao())->getAll(['id', 'nome', 'numero', 'ordem'], [], ['ordem']),
                'opcoes'        => (new Opcao())->getAll(['id', 'nome', 'ordem'], [], ['ordem']),
            ];
        } catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Ocorreu um problema interno ao consultar os dados de questionário! Tente novamente.");
        }
    }

    public function adicionarQuestionario(array $post)
    {
        try {
            //Iniciar Transação
            $this->beginTransaction();

            $model = [
                'topico'   => new Topico(),
                'pergunta' => new Pergunta(),
            ];

            $post['id'] = $this->create($post)['id'];

            if(!isset($post['topicos']) || !is_array($post['topicos']))
                throw new Exception("Nenhum topico foi enviado!");

            $topicos = &$post['topicos'];

            foreach ($topicos as &$topico){

                if(!isset($topico['perguntas']) || !is_array($topico['perguntas']))
                    throw new Exception("Nenhuma pergunta foi enviado!");

                elseif(!empty($topico['perguntas'])){

                    $topico['id'] = $topico['existe'] ? $model['topico']->create($topico)['id'] : "no_topic";

                    foreach ($topico['perguntas'] as &$pergunta){
                        $pergunta['id_questionario'] = $post['id'];
                        $pergunta['id_topico']       = $topico['id'];
                        $pergunta['id']              = $model['pergunta']->create($pergunta)['id'];
                    }
                }
            }

            //Finalizar Transação
            $this->commit();
            
            return [
                'message' => "O questionráio '{$post['nome']}' foi cadastrado com sucesso!",
                'id'      => $post['id'],
            ];

        } catch (\Exception $e) {
            //Desfazer transação
            $this->rollBack();
            throw $e;
        }
    }

}