<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" ng-click="dismissModal()">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Adicionar novo Tópico</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-8">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons">create</i>
                </span>
                <div class="form-group label-floating questionario-form">
                    <label class="control-label">Digite o nome do tópico</label>
                    <input type="text" class="form-control" ng-model="topico.nome">
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="material-icons">swap_vert</i>
                </span>
                <div class="form-group label-floating questionario-form">
                    <label class="control-label">Ordem</label>
                    <input type="text" class="form-control" ng-model="topico.ordem">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" ng-click="dismissModal()">Cancelar</button>
    <button class="btn btn-primary" data-dismiss="modal" ng-click="adicionarTopico()">Adicionar</button>
</div>