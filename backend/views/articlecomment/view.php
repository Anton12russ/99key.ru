<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ShopComment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Комментарии объявлений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'blog_id',
                ['attribute'=>'user_email','format'=>'raw','value'=> 
                 function($data) {
					 if($data['author']['email']) {
                         return '<span class="author_email">'.$data['author']['email'].'</span>';
					 }else{
						 return $data['user_email'];
					 }
                 },
				],
            'date',
            'text:ntext',
            'user_name',
            'user_email:email',
            'Status',
        ],
    ]) ?>

</div>
