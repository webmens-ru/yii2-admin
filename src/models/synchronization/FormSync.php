<?php

namespace wm\admin\models\synchronization;

use Bitrix24\B24Object;
use wm\admin\jobs\DealSynchronizationDeltaJob;
use wm\admin\jobs\DealSynchronizationFullListJob;
use wm\admin\models\settings\Agents;
use wm\admin\models\settings\events\Events;
use wm\admin\models\Synchronization;
use wm\b24tools\b24Tools;
use Yii;
use yii\base\Model;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class FormSync
 * @package wm\admin\models\synchronization
 */
class FormSync extends Model
{
    public $entityId;

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['entityId'], 'required'],
            [
                ['entityId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Synchronization::class,
                'targetAttribute' => ['entityId' => 'id']
            ],
            [['entityId'], 'integer'],

        ];
    }

    public function initAgentTimeSettings()
    {
        if (!$this->entityId) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $synchronizationModel = Synchronization::find()->where(['id' => $this->entityId])->one();
        if (!$synchronizationModel) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $agent = Agents::find()
            ->where(
                [
                    'class' => substr($synchronizationModel->modelClassName, 1),
                    'method' => 'synchronization'
                ]
            )
            ->one();
        $modelAgentTimeSettings = new Agents();
        $modelAgentTimeSettings->scenario = Agents::SCENARIO_ONLY_TIME_SETTINGS;
        if ($agent) {
            $modelAgentTimeSettings->load(ArrayHelper::toArray($agent), '');
        } else {
            $modelAgentTimeSettings->minuteTypeId = 1;
            $modelAgentTimeSettings->hourTypeId = 1;
            $modelAgentTimeSettings->dayTypeId = 1;
            $modelAgentTimeSettings->monthTypeId = 1;
            $modelAgentTimeSettings->finishTypeId = 1;
        }
        return $modelAgentTimeSettings;
    }
}
