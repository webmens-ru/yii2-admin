<?php

namespace wm\admin\models\gii\smartprocess;

use Bitrix24\B24Object;
use wm\admin\models\gii\BaseB24TableGenerator;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

class TableGenerator extends BaseB24TableGenerator
{

    public $spId;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        Yii::warning('rules');
        return [
            [['spId', 'tableName'], 'required'],
            [['tableName'], 'string', 'max' => 32],
            [['spId', 'deleteOldTable'], 'integer'],
            ['tableFields', 'validateFields'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'spId' => 'Идентификатор смарт-процесса',
            'tableName' => 'Имя таблицы',
            'deleteOldTable' => 'Удалить таблицу с таким именем при наличии?',
            'tableFields' => 'Поля'
        ];
    }

    public static function getSmartProcess()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $request = $obB24->client->call('crm.type.list', []);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $types = ArrayHelper::getValue($request, 'result.types');
        if (count($types) != $request['total']) {
            for ($i = 1; $i < $countCalls; $i++)
                $obB24->client->addBatchCall('crm.type.list',
                    array_merge([], ['start' => $obB24->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use (&$types) {
                        $types = array_merge($types, ArrayHelper::getValue($result, 'result.types'));
                    }
                );
            $obB24->client->processBatchCalls();
        }
        return $types;
    }

    public function getFieldsList()
    {
        if ($this->spId) {
            $fields = $this->getFields();
            $result = [];
            foreach ($fields as $fieldName => $field) {
                $result[$fieldName] = $field['title'] . ' - ' . $fieldName . '/' . $field['type'];
            }
            return $result;
        } else {
            return [];
        }
    }

    protected function getFields()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $request = $obB24->client->call('crm.item.fields', ['entityTypeId' => $this->spId]);
        return ArrayHelper::getValue($request, 'result.fields');

    }


}
