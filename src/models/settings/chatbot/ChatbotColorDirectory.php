<?php

namespace wm\admin\models\settings\chatbot;

use Yii;

/**
 * This is the model class for table "admin_chatbot_color_directory".
 *
 * @property string $name Имя
 * @property string|null $title Описание
 *
 * @property AdminChatbot[] $adminChatbots
 */
class ChatbotColorDirectory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_chatbot_color_directory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
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
        return $this->hasMany(Chatbot::class, ['p_color_name' => 'name']);
    }
}
