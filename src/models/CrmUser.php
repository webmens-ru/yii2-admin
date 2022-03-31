<?php

namespace wm\admin\models;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
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
class CrmUser extends \wm\yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_crm_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'integer', 'max' => 11],
            [['ID'], 'unique'],
            [[
                'DATE_REGISTER',
                'IS_ONLINE',
                'LAST_NAME',
                'NAME',
                'PERSONAL_BIRTHDAY',
                'PERSONAL_CITY',
                'PERSONAL_COUNTRY',
                'PERSONAL_GENDER',
                'PERSONAL_PHOTO',
                'PERSONAL_PROFESSION',
                'PERSONAL_STATE',
                'SECOND_NAME',
                'TIME_ZONE',
                'TIME_ZONE_OFFSET',
                'TITLE',
                'UF_EMPLOYMENT_DATE',
                'UF_INTERESTS',
                'UF_PHONE_INNER',
                'UF_SKILLS',
                'UF_TIMEMAN',
                'USER_TYPE',
                'WORK_CITY',
                'WORK_COUNTRY',
                'WORK_POSITION',
                'WORK_STATE',
                'XML_ID'
            ], 'string', 'max' => 255],
            [['ACTIVE'], 'boolean'],
            [[
                'UF_DEPARTMENT',
                'LAST_ACTIVITY_DATE',
                'TIMESTAMP_X'
            ],
                'each', 'rule' => ['integer']],
        ];
    }

    public static function synchronization()
    {
        self::deleteAll();

        $errors = [];

        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new B24Object($b24App);

        $request = $obB24->client->call('user.get', []);
        $countCalls = (int)ceil($request['total'] / $obB24->client::MAX_BATCH_CALLS);
        $users = ArrayHelper::getValue($request, 'result');
        if (count($users) != $request['total']) {
            for ($i = 1; $i < $countCalls; $i++)
                $obB24->client->addBatchCall('user.get',
                    array_merge([], ['start' => $obB24->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use (&$users) {
                        $users = array_merge($users, ArrayHelper::getValue($result, 'result'));
                    }
                );
            $obB24->client->processBatchCalls();
        }
        //$users = ArrayHelper::getValue($obB24->client->call('user.get', []), 'result');
        foreach ($users as $user) {
            $userObj = new CrmUser();
            if (!($userObj->load($user, '') && $userObj->save())) {
                Yii::error([
                    $user,
                    $userObj->errors
                ], '$userObjErrors');

                $errors[] = $userObj->errors;
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
