<?php

namespace wm\admin\models;

use Bitrix24\B24Object;
use Bitrix24\Bitrix24Entity;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "admin_crm_user".
 *
 * @property string $DATE_REGISTER
 * @property string $IS_ONLINE
 * @property string $LAST_NAME
 * @property string $NAME
 * @property string $PERSONAL_BIRTHDAY
 * @property string $PERSONAL_CITY
 * @property string $PERSONAL_COUNTRY
 * @property string $PERSONAL_GENDER
 * @property string $PERSONAL_PHOTO
 * @property string $PERSONAL_PROFESSION
 * @property string $PERSONAL_STATE
 * @property string $SECOND_NAME
 * @property string $TIME_ZONE
 * @property string $TIME_ZONE_OFFSET
 * @property string $TITLE
 * @property string $UF_EMPLOYMENT_DATE
 * @property string $UF_INTERESTS
 * @property string $UF_PHONE_INNER
 * @property string $UF_SKILLS
 * @property string $UF_TIMEMAN
 * @property string $USER_TYPE
 * @property string $WORK_CITY
 * @property string $WORK_COUNTRY
 * @property string $WORK_POSITION
 * @property string $WORK_STATE
 * @property string $XML_ID
 * @property boolean $ACTIVE
 * @property array $UF_DEPARTMENT
 * @property array $LAST_ACTIVITY_DATE
 * @property array TIMESTAMP_X
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
            [['ID'], 'integer'],
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
            for ($i = 1; $i < $countCalls; $i++) {
                $obB24->client->addBatchCall(
                    'user.get',
                    array_merge([], ['start' => $obB24->client::MAX_BATCH_CALLS * $i]),
                    function ($result) use (&$users) {
                        $users = array_merge($users, ArrayHelper::getValue($result, 'result'));
                    }
                );
            }
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
