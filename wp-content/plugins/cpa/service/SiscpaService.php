<?php

namespace Siscpa\service;

use MocaBonita\service\Service;

class SiscpaService extends Service
{
    
    public function menuPrincipalDispatcher(){
        $this->redirect('admin.php', ['page' => 'cpa_questionario']);
    }

}