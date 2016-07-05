<div class="panel panel-primary">
    <div class="panel-heading">
        <span class="text-uppercase">Questionarios Cadastrados</span>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center-vertical text-center"><i class="material-icons">settings</i></th>
            <th class="text-center-vertical text-center">#</th>
            <th class="text-center-vertical text-center">Nome do Questionário</th>
            <th class="text-center-vertical text-center">Data de Início</th>
            <th class="text-center-vertical text-center">Data de Fim</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="questionario in questionarios">
            <td class="text-center-vertical text-center">
                <div class="dropdown">
                    <button class="btn btn-raised btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                        Ações <b class="caret"></b>
                    </button>
                    <ul class="dropdown-menu pull-left">
                        <li><a href="#">Editar</a></li>
                        <li>
                            <a href="" ng-click="deleteQuestionario(questionario)">
                                Excluir
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a ng-href="./admin.php?page=cpa_questionario#/previsualizar/{{questionario.id}}">
                                Pré-Visualizar
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
            <th scope="row" class="text-center-vertical text-center">
                {{questionario.id}}
            </th>
            <td class="text-center-vertical">
                {{questionario.nome}}
            </td>
            <td class="text-center-vertical text-center">
                {{questionario.data_inicio != '0000-00-00 00:00:00' ? (questionario.data_inicio | dateToISO | date:'dd/MM/yyyy') : 'Sem data'}}
            </td>
            <td class="text-center-vertical text-center">
                {{questionario.data_fim != '0000-00-00 00:00:00' ? (questionario.data_fim | dateToISO | date:'dd/MM/yyyy') : 'Sem data'}}
            </td>
        </tr>
        <tr ng-if="questionarios.length == 0">
            <td colspan="5" class="text-center">
                Nenhum questionário foi carregado!
            </td>
        </tr>
        </tbody>
    </table>
</div>