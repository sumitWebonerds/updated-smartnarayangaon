<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Buses */

$this->title = 'Update Buses: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Buses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="buses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
