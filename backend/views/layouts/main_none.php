<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */




    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }



   
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>



        <?php $this->head() ?>

    </head>


<?=$content?>

    <?php $this->endBody() ?>

    </html>
    <?php $this->endPage() ?>

