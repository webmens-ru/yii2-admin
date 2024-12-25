<?php

namespace wm\admin\models\settings\robots;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use wm\b24tools\b24Tools;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Inflector;

//
/**
 * This is the model class for table "admin_robots".
 *
 * @property int $id
 * @property string $code
 * @property string $handler
 * @property int|null $auth_user_id
 * @property string $name
 * @property int|null $use_subscription
 * @property int|null $use_placement
 *
 * @property AdminRobotsProperties[] $adminRobotsProperties
 * @property RobotsProperties[] $propertiesIn
 * @property RobotsProperties[] $propertiesNotIn
 * @property RobotsProperties[] $properties
 */
class Robots extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_robots';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['code', 'handler', 'name'], 'required'],
            [['auth_user_id', 'use_subscription', 'use_placement'], 'integer'],
            [['code', 'handler', 'name'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Идентификатор',
            'handler' => 'URL',
            'auth_user_id' => 'ID пользователя',
            'name' => 'Название',
            'use_subscription' => 'Подписка',
            'use_placement' => 'Доп. настройки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperties()
    {
        return $this->hasMany(RobotsProperties::class, ['robot_code' => 'code']);
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getPropertiesIn()
    {
        return RobotsProperties::find()->where(['is_in' => 1, 'robot_code' => $this->code])->all();
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function getPropertiesNotIn()
    {
        return RobotsProperties::find()->where(['is_in' => 0, 'robot_code' => $this->code])->all();
    }

    /**
     * @return mixed
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalRenamedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function toBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\Bizproc\Robot($b24App);
        $use_subscription = $this->use_subscription > 0 ? true : false;
        $handler = Url::toRoute('/admin/handlers/robot/' . $this->handler, 'https');
        $b24 = $obB24->add(
            $this->code,
            $handler,
            $this->name,
            $this->auth_user_id,
            $this->toB24Properties($this->propertiesIn),
            $this->toB24Properties($this->propertiesNotIn),
            $this->use_placement,
            $use_subscription
        );
        return $b24;
    }

    /**
     * @return void
     * @throws \Bitrix24\Exceptions\Bitrix24ApiException
     * @throws \Bitrix24\Exceptions\Bitrix24EmptyResponseException
     * @throws \Bitrix24\Exceptions\Bitrix24Exception
     * @throws \Bitrix24\Exceptions\Bitrix24IoException
     * @throws \Bitrix24\Exceptions\Bitrix24MethodNotFoundException
     * @throws \Bitrix24\Exceptions\Bitrix24PaymentRequiredException
     * @throws \Bitrix24\Exceptions\Bitrix24PortalDeletedException
     * @throws \Bitrix24\Exceptions\Bitrix24SecurityException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsExpiredException
     * @throws \Bitrix24\Exceptions\Bitrix24TokenIsInvalidException
     * @throws \Bitrix24\Exceptions\Bitrix24WrongClientException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function removeBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\Bizproc\Robot($b24App);
        $b24 = $obB24->delete($this->code);
    }

    /**
     * @return string
     */
    public function export()
    {
        $result = ArrayHelper::toArray($this);
        $properties = $this->properties;
        foreach ($properties as $property) {
            $propertyArr = ArrayHelper::toArray($property);
            $propertyArr['type'] = $property->type->name;
            unset($propertyArr['robot_code']);
            unset($propertyArr['type_id']);
            if ($property->type->is_options) {
                $options = $property->options;
                foreach ($options as $option) {
                    $propertyArr['options'][] = [
                        "value" => $option->value,
                        "name" => $option->name,
                    ];
                }
            }
            $result['properties'][] = $propertyArr;
        }
        $file = '../controllers/handlers/robots/' . Inflector::id2camel($this->handler) . 'Action.php';
        $zip = new \ZipArchive();
        $tempName = mt_rand(100000, 999999) . '.zip';
        $zip->open($tempName, \ZipArchive::CREATE);
        $zip->addFile($file, Inflector::id2camel($this->handler) . 'Action.php');
        $zip->addFromString('robot.json', Json::encode($result));
        $zip->close();
        return $tempName;
    }

    /**
     * @param mixed[] $data
     * @return void
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public static function import($data)
    {
        $robot = new Robots();
        $robot->code = $data['code'];
        $robot->handler = $data['handler'];
        $robot->auth_user_id = $data['auth_user_id'];
        $robot->name = $data['name'];
        $robot->use_subscription = $data['use_subscription'];
        $robot->use_placement = $data['use_placement'];
        $transaction = Robots::getDb()->beginTransaction();
        try {
            $robot->save();
            foreach ($data['properties'] as $property) {
                $robotProperty = new RobotsProperties();
                $robotProperty->robot_code = $robot->code;
                $robotProperty->type_id = RobotsTypes::find()->where(['name' => $property['type']])->one()->id;
                $robotProperty->is_in = $property['is_in'];
                $robotProperty->system_name = $property['system_name'];
                $robotProperty->name = $property['name'];
                $robotProperty->description = $property['description'];
                $robotProperty->required = $property['required'];
                $robotProperty->multiple = $property['multiple'];
                $robotProperty->default = $property['default'];
                $robotProperty->sort = $property['sort'];
                $robotProperty->save();
                if (ArrayHelper::getValue($property, 'options')) {
                    foreach (ArrayHelper::getValue($property, 'options') as $option) {
                        $propertyOption = new RobotsOptions();
                        $propertyOption->robot_code = $robot->code;
                        $propertyOption->property_name = $robotProperty->system_name;
                        $propertyOption->name = $option['name'];
                        $propertyOption->value = $option['value'];
                        $propertyOption->save();
                    }
                }
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $file = '../controllers/' . Inflector::id2camel($this->handler) . 'Action.php';
        if (file_exists($file)) {
            unlink($file);
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    /**
     * @param mixed[] $data
     * @return mixed[]
     */
    private function toB24Properties($data)
    {
        $properties = [];
        foreach ($data as $property) {
            $properties[$property->system_name] = [
                'Name' => $property->name,
                'Type' => explode("_", $property->type->name)[0],
                'Required' => b24Tools::toBool($property->required),
                'Multiple' => b24Tools::toBool($property->multiple),
            ];
            if ($property->description) {
                $properties[$property->system_name]['Description'] = $property->description;
            }
            if (!is_null($property->default) && $property->is_in) {
                $properties[$property->system_name]['Default'] = $property->default;
            }
            if ($property->type->name == 'select_static') {
                $properties[$property->system_name]['Options'] = ArrayHelper::map($property->options, 'value', 'name');
            }
        }
        return $properties;
    }
}
