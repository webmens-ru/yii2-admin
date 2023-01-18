<?php

namespace wm\admin\models\gii;

use yii\base\BaseObject;
use yii\db\Schema;

class ColumnSchema extends BaseObject
{
    public static function getDbType($params)
    {
        if ($params['isMultiple']) {
            return Schema::TYPE_STRING;
        } else {
            switch ($params['type']) {
                case 'string':
                    return Schema::TYPE_STRING;
                case 'integer':
                    return Schema::TYPE_INTEGER;
                case 'datetime':
                    return Schema::TYPE_DATETIME;
                case 'user':
                    return Schema::TYPE_INTEGER;
                case 'boolean':
                    return Schema::TYPE_BOOLEAN;
                case 'crm_company':
                    return Schema::TYPE_INTEGER;
                case 'crm_contact':
                    return Schema::TYPE_INTEGER;
                case 'crm_quote':
                    return Schema::TYPE_INTEGER;
                case 'crm_lead':
                    return Schema::TYPE_INTEGER;
                case 'enumeration':
                    return Schema::TYPE_INTEGER;
                case 'crm_category':
                    return Schema::TYPE_STRING . '(32)';
                case 'crm_status':
                    return Schema::TYPE_STRING . '(32)';
                case 'date':
                    return Schema::TYPE_DATE;
                case 'money':
                    return Schema::TYPE_STRING . '(32)';
                case 'crm_currency':
                    return Schema::TYPE_STRING . '(32)';
                case 'iblock_element':
                    return Schema::TYPE_INTEGER;
                case 'char':
                    return Schema::TYPE_BOOLEAN;
                case 'double':
                    return Schema::TYPE_DOUBLE;
                case 'location':
                    return Schema::TYPE_STRING;
                case 'url':
                    return Schema::TYPE_STRING;
                case 'file':
                    return Schema::TYPE_STRING;
                case 'crm':
                    return Schema::TYPE_STRING . '(32)';
                case 'address':
                    return Schema::TYPE_STRING;
                case 'resourcebooking':
                    return Schema::TYPE_STRING;
                case 'employee':
                    return Schema::TYPE_STRING . '(32)';

                default:
                    return Schema::TYPE_STRING;
            }
        }
    }
}
