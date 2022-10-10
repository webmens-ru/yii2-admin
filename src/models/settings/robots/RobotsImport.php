<?php

namespace wm\admin\models\settings\robots;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use wm\b24tools\b24Tools;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Inflector;
use yii\base\Model;

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
 */
class RobotsImport extends \yii\base\Model
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'checkExtensionByMimeType' => false, 'extensions' => ['zip']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Файл с роботом',
        ];
    }

    public function import()
    {
        $zip = new \ZipArchive();
        $res = $zip->open($this->file->tempName);
        if ($res === true) {
            for ($i = 0; $i < $zip->count(); $i++) {
                if (preg_match('/Action.php$/', $zip->statIndex($i)['name'])) {
                    $filePatch = '../controllers/handlers/robots/';
                    $zip->extractTo($filePatch, array($zip->statIndex($i)['name']));
                } elseif ($zip->statIndex($i)['name'] == 'robot.json') {
                    Robots::import(ArrayHelper::toArray(json_decode($zip->getFromIndex($i))));
                }
            }
            $zip->close();
        } else {
            Yii::error('ошибка с кодом:' . $res);
        }
    }

    private function addRobotToDb($file)
    {
    }

    private function addRobotActionFile($file)
    {
    }
}
