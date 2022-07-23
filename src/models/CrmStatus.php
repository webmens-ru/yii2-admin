<?php

namespace wm\admin\models;

use Bitrix24\B24Object;
use wm\b24tools\b24Tools;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "windows_settings".
 *
 * @property string $ID
 * @property string $CATEGORY_ID
 * @property string $COLOR
 * @property string $ENTITY_ID
 * @property string $NAME
 * @property string $NAME_INIT
 * @property string $SEMANTICS
 * @property string $SORT
 * @property string $STATUS_ID
 * @property string $SYSTEM
 */
class CrmStatus extends \wm\yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_crm_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'string', 'max' => 32],
            [['ID'], 'unique'],
            [[
                'CATEGORY_ID',
                'COLOR',
                'ENTITY_ID',
                'NAME',
                'NAME_INIT',
                'SEMANTICS',
                'SORT',
                'STATUS_ID',
                'SYSTEM'
            ], 'string', 'max' => 255]
        ];
    }

    public static function synchronization()
    {
        self::deleteAll();

        $errors = [];

        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $statuses = ArrayHelper::getValue($obB24->client->call('crm.status.list', []), 'result');
        foreach ($statuses as $status) {
            $statusObj = new CrmStatus();
            if (!($statusObj->load($status, '') && $statusObj->save())) {
                Yii::error([
                    $status,
                    $statusObj->errors
                ], 'statusObjErrors');

                $errors[] = $statusObj->errors;
            }
        }
        if ($errors) {
            return [
               'success' => false,
               'errors' => $errors
            ];
        } else {
            return [
                'success' => true
            ];
        }
    }
}
