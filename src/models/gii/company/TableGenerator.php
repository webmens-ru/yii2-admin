<?php

namespace wm\admin\models\gii\company;

use Bitrix24\B24Object;
use wm\admin\models\gii\BaseB24TableGenerator;
use wm\b24tools\b24Tools;
use yii\helpers\ArrayHelper;

class TableGenerator extends BaseB24TableGenerator
{
    protected $primaryKeyColumnName = 'ID';

    protected function getFields()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $request = $obB24->client->call('crm.company.fields');
        return ArrayHelper::getValue($request, 'result');

    }


}
