<?php

namespace wm\admin\models\settings\documentgenerator;

use Yii;
use Bitrix24\B24Object;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "admin_dg_templates".
 *
 * @property string $name
 * @property string $file_path
 * @property int $numerator_id
 * @property string $region_id
 * @property string $code
 * @property string $active
 * @property string $with_stamps
 * @property int $sort
 * @property int|null $template_id
 */
class Templates extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_dg_templates';
    }

    /**
     * @var mixed
     */
    public $file;

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($file = UploadedFile::getInstance($this, 'file')) {
            $dir = 'uploads/';
            $this->file_path = $dir . $this->code . '-' . $file->baseName . '.' . $file->extension;
            $file->saveAs($this->file_path);
        }
        return parent::beforeSave($insert);
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'numerator_id', 'region_id', 'code', 'active', 'with_stamps', 'sort'], 'required'],
            [['numerator_id', 'sort', 'template_id'], 'integer'],
            [['name', 'file_path'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 32],
            [['region_id'], 'string', 'max' => 2],
            [['active', 'with_stamps'], 'string', 'max' => 1],
            [['code'], 'unique'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'docx'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название шаблона',
            'file_path' => 'Файл',
            'numerator_id' => 'Идентификатор нумератора',
            'region_id' => 'Страна',
            'code' => 'Символьный код шаблона',
            'active' => 'Флаг активности',
            'with_stamps' => 'Ставить печати и подписи',
            'sort' => 'Индекс сортировки',
            'template_id' => 'Идентификатор шаблона на портале'
        ];
    }

    /**
     * @param mixed $b24App
     * @return mixed[]
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
     */
    public static function getRegionsList($b24App)
    {
        $obB24 = new B24Object($b24App);
        $res = $obB24->client->call('documentgenerator.region.list')['result'];
        $regions = [];
        foreach ($res['regions'] as $key => $value) {
            $regions[$key] = $value['title'];
        }
        return $regions;
    }

    /**
     * @param mixed $b24App
     * @return mixed[]
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
     */
    public static function getNumeratorsList($b24App)
    {
        $obB24 = new B24Object($b24App);
        $res = $obB24->client->call('documentgenerator.numerator.list')['result'];
        $numerators = ArrayHelper::map($res['numerators'], 'id', 'name');
        return $numerators;
    }

    /**
     * @return mixed[]
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
        $obB24 = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24->
                client->
                call(
                    'documentgenerator.template.add',
                    [
                            'fields' => [
                                'name' => $this->name,
                                'file' => base64_encode(file_get_contents($this->file_path)),
                                'code' => $this->code,
                                'numeratorId' => $this->numerator_id,
                                'region' => $this->region_id,
                                'users' => '',
                                'active' => $this->active,
                                'withStamps' => $this->with_stamps,
                                'sort' => $this->sort
                            ]
                        ]
                );
        $this->template_id = ArrayHelper::getValue($b24, 'result.template.id');
        $this->save();
        return $b24;
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
    public function fieldsBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24->
                client->
                call(
                    'documentgenerator.template.getfields',
                    [
                            'id' => $this->template_id,
                            'providerClassName' => '\\Bitrix\\DocumentGenerator\\DataProvider\\Rest',
                            'value' => 1,
                        ]
                );
        return $b24['result'];
    }

    /**
     * @return mixed[]
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
    public function updateBitrix24()
    {
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();
        $obB24 = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24->
                client->
                call(
                    'documentgenerator.template.update',
                    [
                            'id' => $this->template_id,
                            'fields' => [
                                'name' => $this->name,
                                'file' => base64_encode(file_get_contents($this->file_path)),
                                'code' => $this->code,
                                'numeratorId' => $this->numerator_id,
                                'region' => $this->region_id,
                                'users' => '',
                                'active' => $this->active,
                                'withStamps' => $this->with_stamps,
                                'sort' => $this->sort
                            ]
                        ]
                );
        return $b24;
    }

    /**
     * @return mixed[]
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
        $obB24 = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24->client->call('documentgenerator.template.list', []);
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
        $component = new \wm\b24tools\b24Tools();
        $b24App = $component->connectFromAdmin();

        $obB24 = new \Bitrix24\B24Object($b24App);
        $b24 = $obB24->client->call('documentgenerator.template.delete', ['id' => $this->template_id]);
        $this->template_id = null;
        $this->save();
    }
}
