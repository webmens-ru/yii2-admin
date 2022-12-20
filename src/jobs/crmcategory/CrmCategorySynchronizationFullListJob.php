<?php

namespace wm\admin\jobs\crmcategory;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;


class CrmCategorySynchronizationFullListJob extends BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;

    const DEAL_ENTITY_ID = 2;
    const DEAL_ENTITY_TITLE = 'Сделка';

    public function execute($queue)
    {
        $component = new b24Tools();
        \Yii::$app->params['logPath'] = 'log/';
        $b24App = $component->connectFromAdmin();
        $b24Obj = new B24Object($b24App);
        $requestEntityTypeIds = $b24Obj->client->call(
            'crm.type.list',
            []
        );
        $entityTypies = ArrayHelper::getValue($requestEntityTypeIds, 'result.types');
        $entityTypiesList = ArrayHelper::map($entityTypies, 'entityTypeId', 'title');
        $entityTypiesList[self::DEAL_ENTITY_ID] = self::DEAL_ENTITY_TITLE;
        $entityTypeIds = ArrayHelper::getColumn($entityTypies, 'entityTypeId');
        $entityTypeIds[] = self::DEAL_ENTITY_ID;
        $this->modelClass::deleteAll();
        $res = [];
        foreach ($entityTypeIds as $entityTypeId) {
            $b24Obj->client->addBatchCall('crm.category.list',
                ['entityTypeId' => $entityTypeId],
                function ($result) use (&$res, $entityTypeId) {
                    $res[$entityTypeId] = ArrayHelper::getValue($result, 'result.categories');
                }
            );
        }
        $b24Obj->client->processBatchCalls();
        foreach ($res as $entityTypeCategories) {
            foreach ($entityTypeCategories as $oneEntity){
                $oneEntity['entityTypeName'] = $entityTypiesList[$oneEntity['entityTypeId']];
                $model = Yii::createObject($this->modelClass);
                $model->loadData($oneEntity);
            }
        }
    }
}