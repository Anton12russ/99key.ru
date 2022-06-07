<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
$online = Yii::$app->adminFunctions->Onlinetop();
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></span><span class="logo-lg">Главное Меню</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">


   
                <li class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Магазины">
                    <a href="<?=Url::to(['shop/index'])?>">
                       <i class="fa fa-cart-plus" aria-hidden="true"></i>
                        <span class="label label-success"><?=Yii::$app->adminFunctions->Shop();?></span>
                    </a>
                </li>
				
				<li class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Объявления">
                    <a href="<?=Url::to(['blog/index'])?>">
                       <i class="fa fa-bullhorn" aria-hidden="true"></i>
                        <span class="label label-success"><?=Yii::$app->adminFunctions->Board();?></span>
                    </a>
                </li>
                <li class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Статьи">
                    <a href="<?=Url::to(['article/index'])?>">
                       <i class="fa fa-id-card" aria-hidden="true"></i>
                        <span class="label label-success"><?=Yii::$app->adminFunctions->Article();?></span>
                    </a>
                </li>
                <li class="dropdown notifications-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Продажи">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-shopping-basket"></i>
                        <span class="label label-warning"><?=Yii::$app->adminFunctions->CartopToday();?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><strong>Статистика продаж</strong></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        Сегодня (<?=Yii::$app->adminFunctions->CartopToday();?>) продаж
                                    </a>
                                </li>
								<li>
                                    <a href="#">
                                        Вчера (<?=Yii::$app->adminFunctions->CartopYesterday();?>) продаж
                                    </a>
                                </li>
								<li>
                                    <a href="#">
                                        За месяц (<?=Yii::$app->adminFunctions->CartopMonth();?>) продаж
                                    </a>
                                </li>
								<li>
                                    <a href="#">
                                        За год (<?=Yii::$app->adminFunctions->CartopYear();?>) продаж
                                    </a>
                                </li>
                     
                            </ul>
                        </li>
                        <li class="footer"><?=Html::a('Все продажи',['/car'])?></li>
                    </ul>
                </li>
          

				
				
				
				
				
				  <li class="dropdown notifications-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Онлайн пользователи">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-user-circle" aria-hidden="true"></i>
                       <span class="label label-success"><?=count($online);?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><strong>Пользователи онлайн</strong></li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
							<? foreach($online as $res) { ?>
                                <li>
                                    <a href="#">
                                        <?print_r($res->user['email']);?>
                                    </a>
                                </li>
							<? } ?>	
                            </ul>
                        </li>
                        <li class="footer"><?=Html::a('Все пользователи',['/user'])?></li>
                    </ul>
                </li>
                <li class="dropdown tasks-menu" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Споры">
                    <a href="<?=Url::to(['dispute/index'])?>">
                        <i class="fa fa-balance-scale" aria-hidden="true"></i>
                        <span class="label label-danger"><?=Yii::$app->adminFunctions->Disputetop();?></span>
                    </a>
                </li>
				<li class="dropdown tasks-menu">
                    <a href="<?=Url::to(['supportsubject/index'])?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Техподдержка">
                        <i class="fa fa-life-ring" aria-hidden="true"></i>
                        <span class="label label-danger"><?=Yii::$app->adminFunctions->Support();?></span>
                    </a>
                </li>
						
				<div class="pull-right">
                  <?= Html::a(
                        '<i class="fa fa-times" aria-hidden="true"></i> Выход',
                        ['/site/logout'],
                        ['data-method' => 'post', 'class' => 'signup']
                  ) ?>
               </div>
            </ul>
        </div>
    </nav>
</header>
