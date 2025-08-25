<?php

namespace wm\admin\models;

use wm\yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "admin_users".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $b24_user_id
 * @property string $name
 * @property string $last_name
 * @property string $access_token
 * @property string $date_expired
 * @property string $auth_key
 * @property string $b24AccessParams
 */

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_users';
    }
//    public function tableName() {
//        return 'users';
//    }

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['b24_user_id'], 'integer'],
            [['name', 'last_name', 'access_token', 'auth_key'], 'string', 'max' => 255],
            [['date_expired'], 'safe'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],
        ];
    }

    /**
     * @param $id
     * @return User|\yii\web\IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }


    /**
     * @param $token
     * @param $type
     * @return User|\yii\web\IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public static function findByBitrixId($id)
    {
        return self::findOne(['b24_user_id' => $id]);
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }


    /**
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return $this->password === $password;
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @return void
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * @return void
     * @throws \yii\base\Exception
     */
    public function generateAccessToken()
    {
        $timestamp = time() + 3600 * (11);
        //echo $timestamp;
        $datetimeFormat = 'Y-m-d H:i:s';
        $date = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $date->setTimestamp($timestamp);
        $this->date_expired = $date->format($datetimeFormat);

        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->auth_key = $this->access_token;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getAccessToken()
    {
        if (!$this->access_token || $this->date_expired < date('Y-m-d h:i:s')) {
            $this->generateAccessToken();
        }

        return $this->access_token;
    }

    /**
     * @param int $length
     * @return false|string
     */
    public static function generatePassword($length = 10)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, $length);
    }
//    public static function b24Login($b24Id) {
////        if ($this->validate()) {
////            if ($this->rememberMe) {
//                $user = static::findByBitrixId($b24Id);
//                $user->generateAccessToken();
//                $user->save();
////            }
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
////        }
//        return false;
//    }


    /**
     * @param int $userId
     * @return mixed
     */
    public static function getPortalName($userId)
    {
        $user = self::findOne(['id' => $userId]);
        if (!$user) {
            return null;
        }
        $b24AccessParams = Json::decode($user->b24AccessParams);

        return $b24AccessParams['domain'];
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public static function getMemberId($userId)
    {
        $user = self::findOne(['id' => $userId]);
        if (!$user) {
            return null;
        }
        $b24AccessParams = Json::decode($user->b24AccessParams);

        return $b24AccessParams['member_id'];
    }

    /**
     * @param $userId
     * @return int|null
     */
    public static function getB24UserId($userId){
        $user = self::findOne(['id' => $userId]);
        if (!$user) {
            return null;
        }
        return $user->b24_user_id;
    }
}
