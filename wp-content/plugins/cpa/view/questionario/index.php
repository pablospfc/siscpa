<div class="col-sm-12">
    <div class="nav-align-center">
        <ul class="nav nav-pills nav-pills-primary" ng-controller="navMenuController">
            <li ng-class="isTabActive('QuestionarioListaController')">
                <a href="./admin.php?page=cpa_questionario#/lista" data-toggle="tab" data-target="#main" id="lista-tab">
                    <i class="material-icons">view_list</i>
                    Listar Questionários
                </a>
            </li>
            <li ng-class="isTabActive('QuestionarioCriarController')">
                <a href="./admin.php?page=cpa_questionario#/criar" data-toggle="tab" data-target="#main" id="criar-tab">
                    <i class="material-icons">add</i>
                    Cadastrar Questionários
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="col-sm-10 col-sm-offset-1"><hr></div>

<div class="col-sm-12">
    <div ng-view></div>
</div>