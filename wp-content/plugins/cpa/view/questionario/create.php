<div class="wrapper" ng-show="info">
    <div class="container-fluid">
        <div class="card card-signup">

            <form class="form">

                <div class="header header-primary cadastro_questionario text-center">
                    <h4>Informações do Questionário</h4>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">create</i>
                                    </span>
                                    <div class="form-group label-floating questionario-form">
                                        <label class="control-label">Digite o nome do questionário</label>
                                        <input type="text" class="form-control" ng-model="formulario.nome">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">date_range</i>
                                    </span>
                                    <div class="form-group label-floating" ng-if="!isFirefox()">
                                        <label class="control-label">Data de Ínicio</label>
                                        <input class="form-control" 
                                               type="date" 
                                               ng-model="formulario.data_inicio_objeto"
                                               ng-blur="processarObjetoData('data_inicio')"/>
                                    </div>
                                    <div class="form-group label-floating" ng-if="isFirefox()">
                                        <label class="control-label">Data de Ínicio (Ex: 26/06/2016)</label>
                                        <input type="text"
                                               class="form-control"
                                               ng-model="formulario.data_inicio_objeto"
                                               maxlength="10"
                                               ng-keypress="mascaraData('data_inicio_objeto')"
                                               ng-blur="processarObjetoData('data_inicio')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">date_range</i>
                                    </span>
                                    <div class="form-group label-floating" ng-if="!isFirefox()">
                                        <label class="control-label">Data de Fim</label>
                                        <input class="form-control" 
                                               type="date" 
                                               ng-model="formulario.data_fim_objeto"
                                               ng-blur="processarObjetoData('data_fim')"/>
                                    </div>
                                    <div class="form-group label-floating" ng-if="isFirefox()">
                                        <label class="control-label">Data de Fim (Ex: 26/06/2016)</label>
                                        <input type="text"
                                               class="form-control"
                                               ng-model="formulario.data_fim_objeto"
                                               maxlength="10"
                                               ng-keypress="mascaraData('data_fim_objeto')"
                                               ng-blur="processarObjetoData('data_fim')">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group tipo-usuario">
                                    <button
                                        class="btn btn-list btn-raised btn-block btn-default dropdown-toggle"
                                        data-toggle="dropdown">
                                        {{formulario.id_tipo_usuario ? (formulario.tipo_usuario.nome | cuttext : false : 30 : ' ...' ) : 'Tipo de
                                        Usuário'}} <b class="caret"></b>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="tipoUsuario in info.tipo_usuarios">
                                            <a href="" ng-click="selecionarTipoUsuario(tipoUsuario)">
                                                {{tipoUsuario.nome}}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">

                    <p class="text-divider"></p>

                    <div class="panel panel-primary cadastro_questionario">
                        <div class="panel-heading">
                            <h4 class="text-center">Adicionar nova Pergunta</h4>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">create</i>
                                        </span>
                                        <div class="form-group label-floating questionario-form">
                                            <label class="control-label">Digite a pergunta aqui</label>
                                            <input type="text" class="form-control" ng-model="pergunta.nome">
                                            <span class="material-input"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="material-icons">swap_vert</i>
                                        </span>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Ordem (Apenas números)</label>
                                            <input class="form-control" type="text" ng-model="pergunta.ordem"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group tipo-usuario">
                                        <button
                                            class="btn btn-list btn-raised btn-block btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                            {{pergunta.id_dimensao ? (pergunta.dimensao.nome | cuttext : false : 40 : ' ...' ) : 'Dimensão'}} <b class="caret"></b>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li ng-repeat="dimensao in info.dimensoes">
                                                <a href="" ng-click="selecionarDimensao(dimensao)">
                                                    {{dimensao.ordem}} - {{dimensao.nome}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group tipo-usuario">
                                        <button
                                            class="btn btn-list btn-raised btn-block btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                            {{pergunta.topico ? (pergunta.topico.nome | cuttext : false : 30 : ' ...' ) : 'Tópico'}}  <b class="caret"></b>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li ng-repeat="topico in formulario.topicos">
                                                <a href="" ng-click="selecionarTopico(topico)">{{topico.nome}}</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="" ng-click="adicionarTopico()">
                                                    Adicionar Tópico
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <button class="btn btn-raised btn-success btn-sm"
                                                type="button"
                                                ng-disabled="!verificarAdicionarPergunta()"
                                                ng-click="adicionarPergunta()">
                                            Salvar Pergunta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body panel-body-list">
                            <ul class="list-group list-group-panel"
                                ng-repeat="topico in formulario.topicos | orderBy:'ordem'">
                                <li class="list-group-item text-center" ng-if="topico.existe && topico.perguntas.length">
                                    <strong>{{topico.nome}}</strong>
                                </li>
                                <li class="list-group-item"
                                    ng-repeat="pergunta in topico.perguntas | orderBy:['dimensao.ordem', 'ordem']">
                                    <div class="row">
                                        <div class="col-sm-12 col-form">
                                            <p class="p-list">
                                                <strong>{{pergunta.dimensao.ordem}}.{{pergunta.ordem}} - </strong>{{pergunta.nome}}
                                            </p>
                                        </div>
                                        <div class="col-sm-8 col-form">
                                            <p><strong>Dimensão: </strong> {{pergunta.dimensao.nome}}
                                            </p>
                                        </div>
                                        <div class="col-sm-4 col-form">
                                            <div class="pull-right action-buttons">
                                                <button class="btn btn-list btn-raised btn-sm btn-danger"
                                                        type="button" 
                                                        ng-click="removerPergunta(pergunta, topico)">
                                                    Excluir
                                                </button>
                                                <button class="btn btn-list btn-raised btn-sm btn-success"
                                                        type="button"
                                                        ng-click="editarPergunta(pergunta, topico)">
                                                    Editar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="col-sm-12 text-center">
                        <button class="btn btn-raised btn-danger"
                                type="button"
                                ng-click="fecharQuestionario()">
                            <i class="material-icons">clear</i> Fechar
                        </button>
                        <button class="btn btn-raised btn-success" 
                                type="button"
                                ng-disabled="!verificarAdicionarQuestionario()"
                                ng-click="adicionarQuestionario()">
                            <i class="material-icons">send</i> Salvar
                        </button>
                    </div>

                </div>

                <p class="text-divider"></p>

            </form>
        </div>
    </div>

</div>