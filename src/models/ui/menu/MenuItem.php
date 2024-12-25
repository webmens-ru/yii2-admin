<?php

namespace wm\admin\models\ui\menu;

use wm\yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "menuitem".
 *
 * @property int $id
 * @property string $title
 * @property int $visible
 * @property int $position
 * @property int $userId
 * @property string $url
 * @property int $menuId
 * @property Menu $menu
 * @property string $params
 * @property string|null $authItem
 */
class MenuItem extends \wm\yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_menu_item';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['title', 'visible', 'order', 'params', 'menuId', 'type'], 'required'],
            [['visible', 'menuId'], 'integer'],
            [['title', 'params', 'type'], 'string', 'max' => 255],
            [
                ['menuId'],
                'exist', 'skipOnError' => true,
                'targetClass' => Menu::class,
                'targetAttribute' => ['menuId' => 'id']
            ],
        ];
    }


    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'title' => 'Надпись ',
            'visible' => 'Скрытость',
            'order' => 'Позиция',
            'params' => 'Url',
            'type' => 'Тип',
            'menuId' => 'Menu ID',
        ];
    }

    /**
     * Gets query for [[Menu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id' => 'menuId']);
    }

    /**
     * @return mixed[]
     */
    public function fields()
    {
        return [
            'id',
            'title',
            'visible' => function () {
                return self::getBooleanValue($this->visible);
            },
            'order',
            'menuId',
            'type',
            'params' => function () {
                $res = json_decode($this->params);
                return $res;
            },
        ];
    }

    /**
     * @return mixed[]
     */
    public function getSchema()
    {
        $attributeLabels = $this->attributeLabels();
        unset($attributeLabels['menuId']);
        $attributeLabels['menu'] = 'меню';
        return $this->convertShema($attributeLabels);
    }


    /**
     * @param int $menuId
     * @param int $userId
     * @return mixed[]
     * @throws \Exception
     */
    public static function getItems($menuId, $userId)
    {
        if (!Menu::find()->where(['id' => $menuId])->one()) {
            Yii::error('$menuId не содержится в Базе данных');
            return [];
        }
        $models = self::find()->where(['menuId' => $menuId])->all();
        $menuItems = [];
        foreach ($models as $model){
            if(ArrayHelper::getValue($model, 'authItem')){
                if(Yii::$app->user->can($model->authItem)){//@phpstan-ignore-line
                    $menuItems[] = $model;
                }
            }else{
                $menuItems[] = $model;
            }
        }

        $res = [];
        foreach ($menuItems as $value) {
            $settings = $value->getMenuItemPersonalSettings()->where(['userId' => $userId])->one();
            if ($settings) {
                $value->order = $settings->order;/* @phpstan-ignore-line */
                $value->visible = $settings->visible;
            }
            $res[] = $value;
        }
        return $res;
    }

//    /**
//     * Gets query for [[MenuItemPersonals]].
//     *
//     * @return \yii\db\ActiveQuery
//     */
    public function getMenuItemPersonalSettings() //@phpstan-ignore-line
    {
        return $this->hasMany(MenuItemPersonalSettings::class, ['itemId' => 'id']);
    }
}
