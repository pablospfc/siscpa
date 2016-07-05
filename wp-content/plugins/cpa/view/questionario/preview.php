<div class="card card-signup" ng-if="questionario">

    <form class="form" action="">

        <div class="header header-primary text-center">
            <h4 class="text-uppercase">{{questionario.questionario_nome}}</h4>
        </div>

        <div class="container-fluid">

            <p class="text-divider text-justify desc">
                É o processo avaliativo que faz a diferença, por ser este processo o instrumento que serve
                para orientar as instituições na redefinição constante de seus objetivos, metas e
                prioridades acadêmico/científicas e sociais. A Comissão Própria de Avaliação - CPA/UEMA
                convida você a participar da autoavaliação e deste modo contribuir para o planejamento
                futuro da sua universidade. Há um espaço no final do questionário para sugestões e críticas.
            </p>

            <p class="text-divider"></p>

            <div class="panel panel-primary"
                 ng-repeat="dimensao in questionario.dimensoes">
                <div class="panel-heading">
                    <span class="text-uppercase">
                        {{dimensao.dimensao_ordem}} - {{dimensao.dimensao_nome}}
                    </span>
                </div>
                <div class="panel-body panel-body-list">
                    <ul class="list-group list-group-panel">
                        <li class="list-group-item"
                            ng-repeat="pergunta in dimensao.perguntas">
                            <div class="row"
                                 ng-if="pergunta.tipo == 'pergunta'">
                                <div class="col-sm-12 col-form">
                                    <p class="p-list">
                                        <strong>{{dimensao.dimensao_ordem}}.{{pergunta.pergunta_ordem}} - </strong>{{pergunta.pergunta_nome}}
                                    </p>
                                </div>
                                <div class="col-sm-12 col-form">
                                    <div class="radio questionario_opcoes text-center">
                                        <label ng-repeat="opcao in pergunta.opcoes">
                                            <input type="radio" name="optionsRadios[{{pergunta.pergunta_id}}]" material-kit>
                                            {{opcao.nome}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--Quando for tópico-->
                            <strong ng-if="pergunta.tipo == 'topico'">{{pergunta.topico_nome}}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <hr>

            <div class="col-sm-12">
                <strong>Observações:</strong>
                <br>
                <ol>
                    <li>Caso haja dúvidas a respeito do que foi perguntado, ou tenha identificado
                        alguma questão que não lhe parece pertinente, especifique o número da questão,
                        bem como a natureza do problema encontrado. Sugira acréscimo ou supressões a
                        este instrumento de avaliação. </li>
                    <li>Dê sugestões para a melhoria do funcionamento da Instituição. A sua opinião é
                        extremamente importante, pois este instrumento poderá ser reformulado para
                        futuras avaliações.</li>
                </ol>
                <textarea class="form-control"
                          placeholder="Escreva sua observação aqui"
                          ng-disabled="true"
                          rows="5"></textarea>
            </div>

            <div class="col-sm-12 text-center">
                <button class="btn btn-raised btn-danger" ng-disabled="true">
                    <i class="material-icons">clear</i> Cancelar Avaliação
                </button>
                <button class="btn btn-raised btn-success" ng-disabled="true">
                    <i class="material-icons">send</i> Realizar Avaliação
                </button>
            </div>

        </div>

        <p class="text-divider"></p>

    </form>
</div>