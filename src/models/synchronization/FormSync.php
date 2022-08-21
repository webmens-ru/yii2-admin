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

/**
 * Class FormSync
 * @package wm\admin\models\synchronization
 */
class FormSync extends Model
{
    /**
     * @var
     */
    public $entityId;
    /**
     * @var
     */
    public $period;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['entityId', 'period'], 'required'],
            [
                ['entityId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Synchronization::className(),
                'targetAttribute' => ['entityId' => 'id']
            ],
            [['entityId', 'period'], 'integer'],

        ];
    }

}
