<?php

namespace Siscpa\model;
use MocaBonita\model\ModelMB;

class Opcao extends ModelMB
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_opcao';
    }
}