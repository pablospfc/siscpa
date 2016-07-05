<?php
/*
    Plugin Name: Siscpa
    Plugin URI: https://github.com/Jhorzyto/moca-bonita
    Description: Um plugin wordpress construido usando o Moça Bonita.
    Version: 1.0
    Author: Moça Bonita
    Author URI: https://github.com/Jhorzyto/moca-bonita
    License: GPLv2
*/

//Verificar se o plugin esta sendo acessado através do wordpress
if(!defined('ABSPATH'))
    exit('O acesso direto não é permitido para esse plugin!\n');

//Inicializar o plugin após o wordpress definir
add_action('plugins_loaded', function(){
    try{

        //Caminho do composer autoload
        $_autoload = plugin_dir_path(__FILE__) . "vendor/autoload.php";

        //Verificar se existe o autoload
        if(!file_exists($_autoload))
            throw new Exception('O composer autoload não foi instalado no plugin!');

        //Incluir o autoload
        require_once $_autoload;

        //Instanciar o Moca Bonita
        $_mocaBonita = new \MocaBonita\MocaBonita(true);

        //Incluir menu e submenu ao wordpress
        $_mocaBonita->addMenuItem('CPA Plugin', 'read', 'siscpa', 'dashicons-list-view', 4, true);

        $_mocaBonita->addSubMenuItem('Questionarios', 'read', 'cpa_questionario', 'siscpa');
        $_mocaBonita->addSubMenuItem('Respostas'    , 'read', 'cpa_resposta', 'siscpa');
        $_mocaBonita->addSubMenuItem('Relatórios'   , 'read', 'cpa_relatorio'   , 'siscpa');
        $_mocaBonita->addSubMenuItem('Login'        , 'read', 'cpa_login');

        //Inserir CSS ao wordpress
        $_mocaBonita->insertCSS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'bootstrap/dist/css/bootstrap.min.css');
        $_mocaBonita->insertCSS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'roboto-fontface/css/roboto/roboto-fontface.css');
        $_mocaBonita->insertCSS('plugin', \MocaBonita\includes\Path::PLGCSS . 'assets/material-icon/material-icons.css');
        $_mocaBonita->insertCSS('cpa_questionario', \MocaBonita\includes\Path::PLGCSS . 'app/style.css');
        $_mocaBonita->insertCSS('cpa_questionario', \MocaBonita\includes\Path::PLGCSS . 'assets/theme.min.css');

        //Inserir JS ao wordpress
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'jquery/dist/jquery.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'bootstrap/dist/js/bootstrap.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'angular/angular.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'angular-route/angular-route.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'angular-animate/angular-animate.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'angular-touch/angular-touch.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGBOWER . 'barbara-js/barbarajs.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGJS . 'assets/ui-bootstrap-tpls-1.3.3.min.js', true);
        $_mocaBonita->insertJS('plugin', \MocaBonita\includes\Path::PLGJS . 'app/siscpa.js', true);
        $_mocaBonita->insertJS('cpa_questionario', \MocaBonita\includes\Path::PLGJS . 'assets/theme.min.js', true);
        $_mocaBonita->insertJS('cpa_questionario', \MocaBonita\includes\Path::PLGBOWER . 'angular-modal-service/dst/angular-modal-service.min.js', true);
        $_mocaBonita->insertJS('cpa_questionario', \MocaBonita\includes\Path::PLGBOWER . 'ng-device-detector/ng-device-detector.min.js', true);
        $_mocaBonita->insertJS('cpa_questionario', \MocaBonita\includes\Path::PLGBOWER . 're-tree/re-tree.min.js', true);
        $_mocaBonita->insertJS('cpa_questionario', \MocaBonita\includes\Path::PLGJS . 'app/questionario_admin.js', true);

        //Adicionar serviços ao wordpress
        $_mocaBonita->insertService('siscpa', 'Siscpa\service\SiscpaService', ['menuPrincipal']);

        //Adicionar os actionsPost ao wordpress
        $_mocaBonita->generateActionPosts('cpa_questionario', 'listView');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'createView');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'updateView');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'previewView');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'adicionarTopicoView');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'read'   , false, 'GET'   , 'ajax');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'getList', true , 'GET'   , 'ajax');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'create' , true , 'POST'  , 'ajax');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'getInfo', true , 'GET'   , 'ajax');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'update' , true , 'PUT'   , 'ajax');
        $_mocaBonita->generateActionPosts('cpa_questionario', 'delete' , true , 'DELETE', 'ajax');
        $_mocaBonita->generateActionPosts('cpa_resposta'    , 'create' , false, 'POST'  , 'ajax');

        //Adicionar os 'Todos' ao wordpress
        $_mocaBonita->insertTODO('cpa_questionario', 'Siscpa\controller\QuestionarioController');
        $_mocaBonita->insertTODO('cpa_relatorio'   , 'Siscpa\controller\RelatorioController'   );
        $_mocaBonita->insertTODO('cpa_login'       , 'Siscpa\controller\LoginController'       );
        $_mocaBonita->insertTODO('cpa_resposta'    , 'Siscpa\controller\RespostaController'    );

        //Lançar o plugin para o wordpress
        $_mocaBonita->launcher();

    } catch (\Exception $e){
        //Exibir exceção
        echo $e->getMessage();
    }
});
