<?php

namespace wm\admin\models;

use Yii;
use yii\helpers\ArrayHelper;
use wm\admin\models\synchronization\SynchronizationField;

/**
 * This is the model class for table "admin_synchronization".
 *
 * @property int $id
 * @property string $title
 * @property int $active
 * @property string $modelClassName
 *
 * @property string $table
 *
 */
class Synchronization extends \yii\db\ActiveRecord
{
    /**
     *
     */
    public const TIME_DIFF = 5;
    /**
     * @var int
     */
    public $inB24 = 0;
    /**
     * @var int
     */
    public $inDb = 0;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_synchronization';
    }

    /**
     * @return void
     */
    public function getCount()
    {
        $this->inB24 = $this->modelClassName::getCountB24();
        $this->inDb = $this->modelClassName::getCountDb();
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['title', 'active'], 'required'],
            [['active'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Ид',
            'title' => 'Название',
            'active' => 'Активность',
            'table' => 'Название таблицы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSyncFields()
    {
        return $this->hasMany(SynchronizationField::class, ['synchronizationEntityId' => 'id']);
    }

    /**
     * @return mixed
     */
    public function getB24Fieldslist()
    {
        return $this->modelClassName::getB24FieldsList();
    }

    /**
     * @return mixed
     */
    public function getB24Fields()
    {
        return $this->modelClassName::getB24Fields();
    }

    /**
     * @param string $method
     * @param string $dateTimeStart
     * @return mixed
     */
    public function addJobFull($method, $dateTimeStart = null)
    {
        return $this->modelClassName::addJobFull($method, $dateTimeStart);
    }

    /**
     * @param mixed $modelAgentTimeSettings
     * @return void
     */
    public function activate($modelAgentTimeSettings = null)
    {
        if ($this->active) {
            $this->modelClassName::stopSynchronization();
        } else {
            $this->modelClassName::startSynchronization($modelAgentTimeSettings);
        }
        $this->active = $this->active ? 0 : 1;
        $this->save();
    }

    /**
     * @return bool
     */
    public function isTable()
    {
        $tableName = $this->modelClassName::tableName();
        return (bool) Yii::$app->db->getTableSchema($tableName);
    }

    /**
     * @return string
     */
    public function getTable(){
        return $this->modelClassName::tableName();
    }

    /**
     * @return void
     */
    public function createTable()
    {
        $this->modelClassName::createTable($this->id);
    }

    /**
     * @return void
     */
    public function deleteUnusedFields()
    {
        $this->modelClassName::deleteUnusedFields($this->id);
    }
}
