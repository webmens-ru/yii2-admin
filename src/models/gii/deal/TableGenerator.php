<?php

namespace wm\admin\models\gii\deal;

use Bitrix24\B24Object;
use wm\admin\models\gii\ColumnSchema;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Schema;
use  wm\admin\models\gii\BaseB24TableGenerator;

class TableGenerator extends BaseB24TableGenerator
{
    protected $primaryKeyColumnName = 'ID';

    protected function getFields()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $request = $obB24->client->call('crm.deal.fields');
        return ArrayHelper::getValue($request, 'result');
    }
}
