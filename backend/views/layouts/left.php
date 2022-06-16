<?php
use mdm\admin\components\Helper;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Добро пожаловать</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
		
		<? $menuItems = [
                      ['label' => 'Главное меню', 'options' => ['class' => 'header']],
					  
					  [
                        'label' => 'Поддержка',
                        'icon' => 'life-ring',
                        'url' => '#',
                        'items' => [
						['label' => 'Споры','icon' => 'chevron-right','url' => ['/dispute/index'],],
						['label' => 'Техподдержка','icon' => 'chevron-right','url' => ['/supportsubject/index']],
                        ],
                    ],
					  
					  [
                        'label' => 'Каталог',
                        'icon' => 'folder-open',
                        'url' => '#',
                        'items' => [
						['label' => 'Объявления','icon' => 'chevron-right','url' => ['/blog/index'],],
                        ['label' => 'Экспресс объявления','icon' => 'chevron-right','url' => ['/blog/express'],],
						['label' => 'Категории','icon' => 'chevron-right','url' => ['/category/index']],
						['label' => 'Регионы','icon' => 'chevron-right','url' => ['/region/index']],
						['label' => 'Поля фильтра','icon' => 'chevron-right','url' => ['/field/index'],],
						['label' => 'Срок жизни объявления','icon' => 'chevron-right','url' => ['/blogtime/index'],],
						
                        ],
						
                    ],
					 [
                        'label' => 'Комментарии',
                        'icon' => 'comments',
                        'url' => '#',
                        'items' => [
						['label' => 'К объявлениям','icon' => 'chevron-right','url' => ['/blogcomment'],],
						['label' => 'К магазинам','icon' => 'chevron-right','url' => ['/shopcomment'],],
						['label' => 'К статьям','icon' => 'chevron-right','url' => ['/articlecomment'],],
                        ],
						
                    ],
					[
                        'label' => 'Статьи',
                        'icon' => 'book',
                        'url' => '#',
                        'items' => [
						['label' => 'Статьи','icon' => 'chevron-right','url' => ['/article'],],
						['label' => 'Категории','icon' => 'chevron-right','url' => ['/articlecat'],],
                        ],
						
                    ],
					
					
					
					[
                        'label' => 'Аукцион',
                        'icon' => 'gavel',
                        'url' => '#',
                        'items' => [
						['label' => 'Категории аукциона','icon' => 'chevron-right','url' => ['/auctioncat/index'],],
						['label' => 'Все аукционы','icon' => 'chevron-right','url' => ['/blogauction/index'],],
						['label' => 'Все аставки', 'icon' => 'chevron-right','url' => ['/bet/index'],],
                        ],
						
                    ],
					
					['label' => 'Магазины','icon' => 'shopping-cart','url' => ['/shop'],],
					['label' => 'Пассажиры','icon' => 'car','url' => ['/passanger'],],
		               ['label' => 'Монетизация',
                        'icon' => 'credit-card',
                        'url' => '#',
                        'items' => [
						['label' => 'Платежные системы','icon' => 'chevron-right','url' => ['/paymentsystem/index'],],
						['label' => 'Платежная история','icon' => 'chevron-right','url' => ['/payment/index'],],
						['label' => 'Валюта','icon' => 'chevron-right','url' => ['/rates/index'],],
						['label' => 'Заказы пользователей','icon' => 'chevron-right','url' => ['/blogservices/index'],],
						['label' => 'Платные категории','icon' => 'chevron-right','url' => ['/catservices/index'],],
						['label' => 'Платные услуги','icon' => 'chevron-right','url' => ['/blogpayment/index'],],
						
                                   ],
						],
						
						['label' => 'Пользователи',
                        'icon' => 'user-circle-o',
                        'url' => '#',
                        'items' => [
						['label' => 'Пользователи', 'icon' => 'chevron-right', 'url' => ['/user/index']],
                      //['label' => 'Меню', 'icon' => 'chevron-right', 'url' => ['/rbac/menu/index']],
						['label' => 'Назначение', 'icon' => 'chevron-right', 'url' => ['/rbac/assignment/index']],
						['label' => 'Роли', 'icon' => 'chevron-right', 'url' => ['/rbac/role/index']],
					    ['label' => 'Разрешения', 'icon' => 'chevron-right', 'url' => ['/rbac/permission/index']],
						['label' => 'Маршруты', 'icon' => 'chevron-right', 'url' => ['/rbac/route/index']],
						['label' => 'Правила', 'icon' => 'chevron-right', 'url' => ['/rbac/rule']],
                                   ],
						],	
					    ['label' => 'Настройки',
                        'icon' => 'sliders',
                        'url' => '#',
                        'items' => [
						['label' => 'Основные настройки','icon' => 'chevron-right','url' => ['/settings/index'],],
						['label' => 'Блоки','icon' => 'chevron-right','url' => ['/block/index'],],
						['label' => 'СЕО модуль','icon' => 'chevron-right','url' => ['/seomodule/index'],],
						['label' => 'Email сообщения','icon' => 'chevron-right','url' => ['/mail/index'],],
						['label' => 'Статичные страницы','icon' => 'chevron-right','url' => ['/staticpage'],],
						['label' => 'Таймеры','icon' => 'clock-o','url' => ['/timer'],],
						['label' => 'Таймер для аукционов','icon' => 'clock-o','url' => ['/timerauction'],],
                                   ],
						],	
						['label' => 'Менеджер изображений','icon' => 'picture-o','url' => ['/settings/imagemanager'],],
						
						['label' => 'Для разработчиков',
                        'icon' => 'wrench',
                        'url' => '#',
                        'items' => [
						['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                        ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                                   ],
						],	
                   
                 
                ];?>
				
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => Helper::filter($menuItems), 
				
            ]
        );?>

    </section>

</aside>
