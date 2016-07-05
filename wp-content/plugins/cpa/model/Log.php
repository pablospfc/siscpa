<?php

namespace Siscpa\model;

use MocaBonita\model\ModelMB;

/**
 * Class Log
 * @package Siscpa\model
 */
class Log extends ModelMB
{
    private static $instance;


    /**
     * @return Log
     */
    public static function getInstance()
    {
        if (is_null(self::$instance))
            self::$instance = new Log();

        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
        $this->table = 'cpa_log';
    }

    public function setLog(\Exception $e)
    {
        try {
            $this->insert($this->table, [
                'descricao' => $e->getMessage()
            ], [
                'descricao' => '%s'
            ]);
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }

}