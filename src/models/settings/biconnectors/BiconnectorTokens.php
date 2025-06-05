<?php

namespace wm\admin\models\settings\biconnectors;

use wm\ui\Date;
use yii\base\Security;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_biconnector_tokens".
 *
 * @property int $id
 * @property string $token
 * @property string $created_at
 */
class BiconnectorTokens extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%admin_biconnector_tokens}}';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['token'], 'string', 'length' => 32],
            [['token'], 'unique'],
        ];
    }

    /**
     * Проверяет валидность токена.
     *
     * @param string $token Токен для проверки
     * @return bool true, если токен валиден, иначе false
     */
    public static function isValidToken($token)
    {
        return self::find()->where(['token' => $token])->exists();
    }

    /**
     * Генерирует новый токен и сохраняет его в базу данных.
     *
     * @return string Новый токен
     */
    public static function generateToken()
    {
        $newToken = self::generateTokenString();

        $model = new self();
        $model->token = $newToken;
        $model->created_at = date('Y-m-d H:i:s');
        $model->save();

        return $newToken;
    }

    /**
     * @return void
     */
    public function refreshToken()
    {
        $newToken = self::generateTokenString();
        $this->token = $newToken;
        $this->created_at = date('Y-m-d H:i:s');
        $this->save();
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateTokenString()
    {
        $security = new Security();
        $token = $security->generateRandomString(32);
        return $token;
    }
}
