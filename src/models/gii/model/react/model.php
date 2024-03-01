<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/** @var yii\web\View $this */
/** @var wm\admin\models\gii\model\Generator $generator */
/** @var string $tableName full table name */
/** @var string $className class name */
/** @var string $queryClassName query class name */
/** @var yii\db\TableSchema $tableSchema */
/** @var array $properties list of properties (property => [type, name. comment]) */
/** @var string[] $labels list of attribute labels (name => label) */
/** @var string[] $rules list of validation rules */
/** @var array $relations list of relations (name => relation declaration) */
/** @var array $gridFields list of grid fields */
/** @var array $formFields list of form fields */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
<?php if ($generator->crud): ?>
use yii\helpers\Url;
<?php endif; ?>

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    /*
    public const RENDER_MODE_GRID = 'grid';
    public const RENDER_MODE_FORM = 'form';

    public $renderMode = self::RENDER_MODE_GRID;
    */

    /**
    * @return string
    */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
    * @return mixed[]
    */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
    * @return mixed[]
    */
<?php if ($generator->crud): ?>
    public function fields()
    {
        switch ($this->renderMode) {
            case self::RENDER_MODE_GRID:
                return [
                    'actions' => function () {
                        return ['update', 'view', 'delete'];
                    },
        <?php foreach ($gridFields as $gridField): ?>
            <?= $gridField.",\n" ?>
        <?php endforeach; ?>
                ];
            case self::RENDER_MODE_FORM:
                return [
        <?php foreach ($labels as $name => $label): ?>
            <?= "'$name',\n" ?>
        <?php endforeach; ?>
                ];
            default:
                return parent::fields();
        }
    }
<?php else: ?>
    public function fields()
    {
        return [
    <?php foreach ($gridFields as $gridField): ?>
        <?= $gridField.",\n" ?>
    <?php endforeach; ?>
    ];
    }
<?php endif; ?>

<?php if ($generator->crud): ?>
    /**
    * @return mixed[]
    */
    public static function getGridOptions()
    {
        return [
            "key" => "id",
            "actionColumnName" => "actions",
            "actions" => [
                [
                    "id" => "update",
                    "title" => 'Изменить',
                    'type' => 'openApplication',
                    "params" => [
                        'path' => 'mainForm',
                        'bx24_width' => 700,
                        'updateOnCloseSlider' => true,
                        "params" => [
                            'entity' => '<?= $generator->crudController ?>',
                            'mode' => 'edit',
                            'action' => 'update',
                            'canToggleMode' => true
                        ]
                    ]
                ],
                [
                "id" => "view",
                "title" => 'Просмотреть',
                'type' => 'openApplication',
                "params" => [
                    'path' => 'mainForm',
                    'bx24_width' => 700,
                    'updateOnCloseSlider' => true,
                    "params" => [
                        'entity' => '<?= $generator->crudController ?>',
                        'action' => 'update',
                        'mode' => 'view',
                        'canToggleMode' => true
                        ]
                    ]
                ],
                [
                "id" => "delete",
                "title" => 'Удалить',
                "type" => "trigger",
                "handler" => Url::toRoute('/<?= $generator->crudController ?>/delete', 'https'),
                "params" => [
                    'updateOnCloseSlider' => true,
                    "popup" => [
                        "title" => "Удаление",
                        "width" => 400,
                        "height" => 450,
                        "body" => [
                            "text" => 'Вы действительно хотите удалить данный элемент'
                        ],
                        "buttons" => [
                            "success" => "Удалить",
                            "cancel" => "Отмена"
                        ]
                    ]
                ]
            ],
            ]
        ];
    }

    /**
    * @return mixed[]
    */
    public static function getButtonAdd()
    {
        return [
            'title' => 'Добавить',
            'params' => [
                'type' => 'openApplication',
                'path' => 'mainForm',
                'entity' => '<?= $generator->crudController ?>',
                'mode' => 'edit',
                'action' => 'create',
                'bx24_width' => 700,
                'updateOnCloseSlider' => true,
                'canToggleMode' => false
            ],
        ];
    }

    /**
    * @return mixed[]
    */
    public static function getFormFields()
    {
        return [
    <?php foreach ($formFields as $formField): ?>
        <?= $formField.",\n" ?>
    <?php endforeach; ?>
        ];
    }
<?php endif; ?>



<?php foreach ($relations as $name => $relation): ?>

    /**
     * Gets query for [[<?= $name ?>]].
     *
     * @return <?= $relationsClassHints[$name] . "\n" ?>
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
}
