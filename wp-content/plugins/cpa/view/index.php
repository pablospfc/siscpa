<main class="siscpa" ng-app="<?= isset($ngApp) ? $ngApp : 'SiscpaApp'?>" ng-cloak>
    <div class="container-fluid">
        <hr>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <div alert-bootstrap></div>
                <div loading-bootstrap></div>
            </div>
        </div>
        <div class="row">
            <?= $this->getContent(); ?>
        </div>
    </div>
</main>