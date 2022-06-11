<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Пользователь', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
           // 'auth_key',
           //'password_hash',
           // 'password_reset_token',
            'email:email',
            'Status',
			'balance',
			  ['attribute'=>'created_at','format'=>'raw', 'value'=>
	           function($data) {
				  return date('d.m.Y h:i:s', $data->created_at);
			   }
			],
				  ['attribute'=>'updated_at','format'=>'raw', 'value'=>
	           function($data) {
				  return date('d.m.Y h:i:s', $data->updated_at);
			   }
			],
            //'verification_token',
        ],
    ]) ?>

</div>