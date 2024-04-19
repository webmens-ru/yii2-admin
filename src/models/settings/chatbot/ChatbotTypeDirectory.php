<?php

namespace wm\admin\models\settings\chatbot;

use Yii;

/**
 * This is the model class for table "admin_chatbot_type_directory".
 *
 * @property string $name Имя
 * @property string $title Описание
 *
 * @property AdminChatbot[] $adminChatbots
 */
class ChatbotTypeDirectory extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_chatbot_type_directory';
    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['name'], 'string', 'max' => 1],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'title' => 'Описание',
        ];
    }

    /**
     * Gets query for [[AdminChatbots]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatbots()
    {
        return $this->hasMany(Chatbot::class, ['type_id' => 'name']);
    }
}
