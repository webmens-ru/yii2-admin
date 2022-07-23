<?php

namespace wm\admin\models\gii;

interface TableGeneratorInterface
{
    public $tableName;
    public $deleteOldTable;
    public $tableFields;
}
