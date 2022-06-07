<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Category;
/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		
		
	
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>

    </p>

    <?= DetailView::widget([

        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'url',
            [                                                  // name свойство зависимой модели owner
            'label' => 'Изображение',
            'value' => $model->image,
            'format' => 'image',            
            'contentOptions' => ['class' => 'views_image'],    // настройка HTML атрибутов для тега, соответсвующего value
             // настройка HTML атрибутов для тега, соответсвующего label
        ],
			
           [                                         
            'label' => 'Родительская категория',
            'value'=> function ($model) {return Category::linenav($model->parent);},         
         ],
			
			
            'description:ntext',
            'sort',
        ],
    ]) ?>

</div>
