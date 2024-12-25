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
    /**
     * @var mixed
     */
    public $file;

    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'file', 'checkExtensionByMimeType' => false, 'extensions' => ['zip']],
        ];
    }

    /**
     * @return mixed[]
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Файл с роботом',
        ];
    }

    /**
     * @return void
     * @throws \Throwable
     */
    public function import()
    {
        $zip = new \ZipArchive();
        $res = $zip->open($this->file->tempName);
        if ($res === true) {
            for ($i = 0; $i < $zip->count(); $i++) {
                if(is_array($zip->statIndex($i)) && $zipStatIndexIName = ArrayHelper::getValue($zip->statIndex($i), 'name')){
                    if (preg_match('/Action.php$/', $zipStatIndexIName)) {
                        $filePatch = '../controllers/handlers/robots/';
                        $zip->extractTo($filePatch, $zipStatIndexIName);
                    } elseif (is_array($zip->statIndex($i)) && $zipStatIndexIName == 'robot.json') {
                        if($zip->getFromIndex($i) && json_decode($zip->getFromIndex($i))){
                            Robots::import(ArrayHelper::toArray(json_decode($zip->getFromIndex($i))));
                        }

                    }
                }

            }
            $zip->close();
        } else {
            Yii::error('ошибка с кодом:' . $res);
        }
    }

    /**
     * @param mixed $file
     * @return void
     */
    private function addRobotToDb($file)
    {
    }

    /**
     * @param mixed $file
     * @return void
     */
    private function addRobotActionFile($file)
    {
    }
}
