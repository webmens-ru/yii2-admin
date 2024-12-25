<?php

namespace wm\admin\models\synchronization;

use Yii;
use wm\admin\models\Synchronization;

/**
 * This is the model class for table "admin_synchronization_field".
 *
 * @property int $id
 * @property string $name
 * @property int $synchronizationEntityId
 * @property string $title
 * @property string $noDelete
 *
 * @property Synchronization $synchronizationEntity
 */
class SynchronizationField extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_synchronization_field';
    }

    /**
     * @param bool $insert
     * @param mixed $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $modelClassName = $this->synchronizationEntity->modelClassName;
            $modelClassName::createColumns([$this->name]);
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $modelClassName = $this->synchronizationEntity->modelClassName;
        $modelClassName::deleteColumn($this->name);
        return parent::beforeDelete();
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'synchronizationEntityId', 'title'], 'required'],
            [['synchronizationEntityId', 'noDelete'], 'integer'],
            [['name'], 'string', 'max' => 48],
            [['title'], 'string', 'max' => 128],
            [['name', 'synchronizationEntityId'], 'unique', 'targetAttribute' => ['name', 'synchronizationEntityId']],
            [
                ['synchronizationEntityId'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Synchronization::class,
                'targetAttribute' => ['synchronizationEntityId' => 'id']
            ],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Системное имя',
            'synchronizationEntityId' => 'Идентификатор сущности',
            'title' => 'Название',
            'noDelete' => 'Не для удаления'
        ];
    }

    /**
     * Gets query for [[SynchronizationEntity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSynchronizationEntity()
    {
        return $this->hasOne(Synchronization::class, ['id' => 'synchronizationEntityId']);
    }
}
