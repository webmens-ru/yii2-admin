<?php

namespace wm\admin\models\settings\biconnectors;

use Yii;

/**
 * This is the model class for table "admin_biconnector_tables".
 *
 * @property string $name
 * @property string $title
 */
class BiconnectorTables extends \yii\db\ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'admin_biconnector_tables';
    }


    /**
     * @return mixed[]
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }


    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'title' => 'Title',
        ];
    }
}
