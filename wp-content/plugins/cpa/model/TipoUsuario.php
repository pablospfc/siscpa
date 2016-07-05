<?php

namespace Siscpa\model;
use MocaBonita\model\ModelMB;

class TipoUsuario extends ModelMB
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_tipo_usuario';
    }
}