<?php

namespace wm\admin\models\synchronization;

use wm\admin\models\Synchronization;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "admin_synchronization_field".
 *
 * @property string[] $name
 * @property int $synchronizationEntityId
 * @property string $title
 *
 * @property Synchronization $synchronizationEntity
 */
class SynchronizationFieldForm extends Model
{

    /**
     * @var string[]
     */
    public $name;

    /**
     * @var int
     */
    public $synchronizationEntityId;

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'synchronizationEntityId'], 'required'],
            [['synchronizationEntityId'], 'integer'],
            [['name'],'each', 'rule' => ['string', 'max' => 48]],
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
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function addField()
    {
        foreach ($this->name as $name) {
            $model = new SynchronizationField();
            $model->name = $name;
            $model->synchronizationEntityId = $this->synchronizationEntityId;

            $modelSync = Synchronization::find()->where(['id' => $this->synchronizationEntityId])->one();
            if(!$modelSync){
                throw new Exception('ModelSync not found');
            }
            $fields = $modelSync->getB24Fields();
            $field = ArrayHelper::getValue($fields, $name);
            if(ArrayHelper::getValue($field, 'formLabel')){
                $model->title = ArrayHelper::getValue($field, 'formLabel');
            }elseif(ArrayHelper::getValue($field, 'title')){
                $model->title = ArrayHelper::getValue($field, 'title');
            }else{
                $model->title = $name;
            }
            $model->save();
            if ($model->errors) {
                Yii::error($model->errors, 'addField $model->errors');
                $this->addError('name', 'Это поле скорее всего было добавлено ранее');
                return false;
            }
        }
        return true;
    }
}
