<?php

namespace wm\admin\models\settings\chatbot;

use Yii;

/**
 * This is the model class for table "admin_chatbot_app_js_method_directory".
 *
 * @property string $code
 * @property string $title
 *
 * @property AdminChatbotApp[] $adminChatbotApps
 */
class AppJsMethodDirectory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_chatbot_app_js_method_directory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'title' => 'Описание',
        ];
    }

    /**
     * Gets query for [[AdminChatbotApps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApps()
    {
        return $this->hasMany(App::className(), ['js_method_code' => 'code']);
    }
}
