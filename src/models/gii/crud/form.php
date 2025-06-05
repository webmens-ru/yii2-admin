<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var wm\admin\models\gii\crud\Generator $generator */

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'baseControllerClass');
