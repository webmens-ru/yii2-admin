<?php
/**
 * This is the template for generating CRUD search class of the specified model.
 */

use yii\helpers\StringHelper;


/** @var yii\web\View $this */
/** @var yii\gii\generators\crud\Generator $generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}
$rules = $generator->generateSearchRules();
$labels = $generator->generateSearchLabels();
$searchAttributes = $generator->getSearchAttributes();
$searchConditions = $generator->generateSearchConditions();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use wm\yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;

/**
 * <?= $searchModelClass ?> represents the model behind the search form of `<?= $generator->modelClass ?>`.
 */
class <?= $searchModelClass ?> extends <?= isset($modelAlias) ? $modelAlias : $modelClass ?>

{
    /**
    * @return mixed[]
    */
    public function rules()
    {
        return [
            [
                array_merge(
                    array_keys($this->attributes),
                    []
                ),
            'safe'
            ]
        ];
    }

    /**
    * @param ActiveQuery $query
    * @param array $requestParams
    * @return mixed
    * @throws \Exception
    */
    public function prepareSearchQuery($query, $requestParams)
    {
        $this->load(ArrayHelper::getValue($requestParams, 'filter'), '');
        if (!$this->validate()) {
            $query->where('0=1');
            return $query;
        }
        foreach ($this->attributes() as $value) {
            foreach ($this->{$value} as $item) {
                $query->andFilterCompare($value, $item['value'], $item['operator']);
            }
        }
    return $query;
    }
}
