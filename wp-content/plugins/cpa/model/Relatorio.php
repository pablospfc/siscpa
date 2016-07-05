<?php
/**
 * Created by PhpStorm.
 * User: baldez
 * Date: 27/06/16
 * Time: 09:37
 */

namespace Siscpa\model;
use MocaBonita\includes\Validator;
use MocaBonita\model\ModelMB;
use Exception;

class Relatorio extends ModelMB
{
    private $rules;
    private $structure;

    public function __construct()
    {
        parent::__construct();
    }

    public function getParticipantesByAvaliacao($idQuestionario) {
        try {
            if(!Validator::check(['id_questionario' => $idQuestionario], ['id_questionario' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de questionário foi enviado!");

            $query = "SELECT COUNT(id_usuario) as qtd 
                      FROM cpa_questionario_usuario 
                      WHERE id_questionario = %d ";

            $count = (int)parent::getRow(
                parent::prepare($query, [$idQuestionario])
            )->count;

            return $count;

        }catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível obter o quantitativo de participantes desta avaliação.");
        }
    }
    
    public function getParticipantesByCentro($centro) {
        try {
            if(!Validator::check(['centro' => $centro], ['centro' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de centro foi enviado!");

            $query = "SELECT COUNT(cpa_centro_departamento_usuario.id_usuario) as qtd 
                      FROM cpa_centro_departamento_usuario
                      INNER JOIN cpa_usuario on cpa_usuario.id = cpa_centro_departamento_usuario.id_usuario 
                      INNER JOIN (SELECT DISTINCT id_usuario FROM cpa_questionario_usuario) as tabela2 
                      ON tabela2.id_usuario = cpa_centro_departamento_usuario.id_usuario 
                      WHERE cpa_centro_departamento_usuario.centro = %d";

            $count = (int)parent::getRow(
                parent::prepare($query, [$centro])
            )->count;

            return $count;

        }catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível obter o quantitativo de participantes deste centro.");
        }
    }

    public function getRelatorioGeral() {
        try {
            $query = "SELECT questionario.id as questionario_id,
                             questionario.nome as questionario_nome, 
                             questionario.data_inicio as questionario_data_inicio,
                             questionario.data_fim as questionario_data_fim,
                             tipo_usuario.nome as tipo_usuario_nome,
                             eixo.id as eixo_id,
                             eixo.nome as eixo_nome,
                             dimensao.id as dimensao_id,
                             dimensao.nome as dimensao_nome,
                             dimensao.ordem as dimensao_ordem,
                             topico.id as topico_id,
                             topico.nome as topico_nome,
                             topico.ordem as topico_ordem,
                             pergunta.id as pergunta_id,
                             pergunta.nome as pergunta_nome,
                             pergunta.ordem as pergunta_ordem,
                             opcao.nome as opcao_nome,
                             usuario.matricula as matricula,
                             centro.centro as centro
                      FROM cpa_questionario as questionario 
                      INNER JOIN cpa_tipo_usuario as tipo_usuario on tipo_usuario.id = questionario.id_tipo_usuario
                      INNER JOIN cpa_pergunta as pergunta on questionario.id = pergunta.id_questionario
                      INNER JOIN cpa_dimensao as dimensao on pergunta.id_dimensao = dimensao.id
                      INNER JOIN cpa_eixo as eixo ON eixo.id = dimensao.id_eixo
                      INNER JOIN cpa_resposta as resposta ON resposta.id_pergunta = pergunta.id
                      INNER JOIN cpa_opcao as opcao ON opcao.id = resposta.id_opcao
                      INNER JOIN cpa_usuario as usuario ON usuario.id = resposta.id_usuario
                      INNER JOIN cpa_questionario_usuario as questionario_usuario ON questionario_usuario.id_usuario = usuario.id
                      INNER JOIN cpa_centro_departamento_usuario as centro ON centro.id_usuario = usuario.id
                      LEFT JOIN cpa_topico as topico on pergunta.id_topico = topico.id
                      ORDER BY eixo.id, dimensao_ordem, topico.ordem, pergunta_ordem";

            return $this->getResultsT($query, ARRAY_A);

        }catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível gerar o relatório! Tente novamente.");
        }
    }

    public function getRelatorioByCentro($centro) {
        try {

            if(!Validator::check(['id' => $centro], ['id' => 'string : 10']))
                throw  new SiscpaException("Nenhum ID de centro foi enviado!");

            $query = "SELECT questionario.id as questionario_id,
                             questionario.nome as questionario_nome, 
                             questionario.data_inicio as questionario_data_inicio,
                             questionario.data_fim as questionario_data_fim,
                             tipo_usuario.nome as tipo_usuario_nome,
                             eixo.id as eixo_id,
                             eixo.nome as eixo_nome,
                             dimensao.id as dimensao_id,
                             dimensao.nome as dimensao_nome,
                             dimensao.ordem as dimensao_ordem,
                             topico.id as topico_id,
                             topico.nome as topico_nome,
                             topico.ordem as topico_ordem,
                             pergunta.id as pergunta_id,
                             pergunta.nome as pergunta_nome,
                             pergunta.ordem as pergunta_ordem,
                             opcao.nome as opcao_nome,
                             usuario.matricula as matricula,
                             centro.centro as centro
                      FROM cpa_questionario as questionario 
                      INNER JOIN cpa_tipo_usuario as tipo_usuario on tipo_usuario.id = questionario.id_tipo_usuario
                      INNER JOIN cpa_pergunta as pergunta on questionario.id = pergunta.id_questionario
                      INNER JOIN cpa_dimensao as dimensao on pergunta.id_dimensao = dimensao.id
                      INNER JOIN cpa_eixo as eixo ON eixo.id = dimensao.id_eixo
                      INNER JOIN cpa_resposta as resposta ON resposta.id_pergunta = pergunta.id
                      INNER JOIN cpa_opcao as opcao ON opcao.id = resposta.id_opcao
                      INNER JOIN cpa_usuario as usuario ON usuario.id = resposta.id_usuario
                      INNER JOIN cpa_questionario_usuario as questionario_usuario ON questionario_usuario.id_usuario = usuario.id
                      INNER JOIN cpa_centro_departamento_usuario as centro ON centro.id_usuario = usuario.id
                      LEFT JOIN cpa_topico as topico on pergunta.id_topico = topico.id
                      WHERE centro.centro = '%s'
                      ORDER BY eixo.id, dimensao_ordem, topico.ordem, pergunta_ordem";

            $dados = $this->getResults($this->prepare($query, array($centro)));

            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhum centro foi encontrado com esse ID!");

            return $dados;

        }catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível gerar o relatório! Tente novamente.");
        }
    }

    public function getRelatorioByQuestionario($idQuestionario) {
        try {

            if(!Validator::check(['id' => $idQuestionario], ['id' => 'numeric : 1']))
                throw  new SiscpaException("Nenhum ID de questionário foi enviado!");

            $query = "SELECT questionario.id as questionario_id,
                             questionario.nome as questionario_nome, 
                             questionario.data_inicio as questionario_data_inicio,
                             questionario.data_fim as questionario_data_fim,
                             tipo_usuario.nome as tipo_usuario_nome,
                             eixo.id as eixo_id,
                             eixo.nome as eixo_nome,
                             dimensao.id as dimensao_id,
                             dimensao.nome as dimensao_nome,
                             dimensao.ordem as dimensao_ordem,
                             topico.id as topico_id,
                             topico.nome as topico_nome,
                             topico.ordem as topico_ordem,
                             pergunta.id as pergunta_id,
                             pergunta.nome as pergunta_nome,
                             pergunta.ordem as pergunta_ordem,
                             opcao.nome as opcao_nome
                      FROM cpa_questionario as questionario 
                      INNER JOIN cpa_tipo_usuario as tipo_usuario on tipo_usuario.id = questionario.id_tipo_usuario
                      INNER JOIN cpa_pergunta as pergunta on questionario.id = pergunta.id_questionario
                      INNER JOIN cpa_dimensao as dimensao on pergunta.id_dimensao = dimensao.id
                      INNER JOIN cpa_eixo as eixo ON eixo.id = dimensao.id_eixo
                      INNER JOIN cpa_resposta as resposta ON resposta.id_pergunta = pergunta.id
                      INNER JOIN cpa_opcao as opcao ON opcao.id = resposta.id_opcao
                      LEFT JOIN cpa_topico as topico on pergunta.id_topico = topico.id
                      WHERE questionario.id = %d
                      ORDER BY eixo.id, dimensao_ordem, topico.ordem, pergunta_ordem";

           $dados = $this->getResults($this->prepare($query, array($idQuestionario)));

            if(empty($dados) || !is_array($dados))
                throw new SiscpaException("Nenhum questionário foi encontrado com esse ID!");

            return $dados;

        }catch (Exception $e) {
            Log::getInstance()->setLog($e);
            throw new Exception("Não foi possível gerar o relatório! Tente novamente.");
        }
    }

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





}