<?php

namespace wm\admin\models\settings\biconnectors;

use wm\b24tools\b24Tools;
use wm\yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "admin_biconnector".
 *
 * @property int $id
 * @property string $title
 * @property string $logo
 * @property string $description
 * @property string $urlCheck
 * @property string $urlTableList
 * @property string $urlTableDescription
 * @property string $urlData
 * @property int $sort
 * @property int|null $bx24Id
 * @property int $isSystem
 *
 * @property BiconnectorSettings[] $biconnectorSettings
 */
class Biconnector extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_biconnector';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['title', 'logo', 'description', 'urlCheck', 'urlTableList', 'urlTableDescription', 'urlData', 'sort', 'isSystem'], 'required'],
            [['logo'], 'string'],
            [['sort', 'bx24Id', 'isSystem'], 'integer'],
            [['title', 'description', 'urlCheck', 'urlTableList', 'urlTableDescription', 'urlData'], 'string', 'max' => 255],
        ];
    }


    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'logo' => 'Логотип',
            'description' => 'Описание',
            'urlCheck' => 'Url Check',
            'urlTableList' => 'Url Table List',
            'urlTableDescription' => 'Url Table Description',
            'urlData' => 'Url Data',
            'sort' => 'Сортировка',
            'bx24Id' => 'Bx24id',
            'isSystem' => 'Is System',
        ];
    }

    /**
     * Gets query for [[BiconnectorSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBiconnectorSettings()
    {
        return $this->hasMany(BiconnectorSettings::class, ['biconnectorId' => 'id']);
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
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $data = $obB24->client->call(
            'biconnector.connector.add',
            [
                'fields' => [
                    'title' => $this->title,
                    'logo' => $this->logo,
                    'description' => $this->description,
                    'urlCheck' => $this->urlCheck,
                    'urlTableList' => $this->urlTableList,
                    'urlTableDescription' => $this->urlTableDescription,
                    'urlData' => $this->urlData,
                    'settings' => ArrayHelper::toArray($this->biconnectorSettings),
                    'sort' => $this->sort,
                ]
            ]
        );
        $this->bx24Id = ArrayHelper::getValue($data, 'result.id');
        $this->save(false);
        return $data;
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
    public function removeBitrix24()
    {
        $component = new b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $data = $obB24->client->call(
            'biconnector.connector.delete',
            [
                'id' => $this->bx24Id
            ]
        );
        $this->bx24Id = null;
        $this->save(false);
        return $data;
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
    public static function getB24List()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24Im = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24Im->client->call('biconnector.connector.list', []);
        return $b24;
    }
}
