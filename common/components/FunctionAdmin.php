<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use common\models\Car;
use common\models\Online;
use common\models\Dispute;
use common\models\Shop;
use common\models\Blog;
use common\models\Article;
use common\models\SupportSubject;
use yii\web\NotFoundHttpException;
use yii;

class FunctionAdmin extends Component { 	
    //Количество продаж сегодня
    public function CartopToday() 
	{	
	 $car_count = Car::find()->andFilterWhere(['like', 'data_add', date('Y-m-d')])->orderBy(['id' => SORT_DESC])->count();
     return $car_count;
	}
	//Количество продаж Вчера
    public function CartopYesterday() 
	{	
	 $car_count = Car::find()->andFilterWhere(['like', 'data_add', date('Y-m-d H:i:s', strtotime(' - 1 day'))])->orderBy(['id' => SORT_DESC])->count();
     return $car_count;
	}
	
	//Количество продаж За месяц
    public function CartopMonth() 
	{	
	 $car_count = Car::find()->andFilterWhere(['>=', 'data_add',date('Y-m-d H:i:s', strtotime(' - 30 day'))])->orderBy(['id' => SORT_DESC])->count();
     return $car_count;
	}
	
	//Количество продаж За месяц
    public function CartopYear() 
	{	
	 $car_count = Car::find()->andFilterWhere(['>=', 'data_add',date('Y-m-d H:i:s', strtotime(' - 365 day'))])->count();
     return $car_count;
	}
	
	
	//Количество Не закрытых жалоб
    public function Disputetop() 
	{	
	 $count = Dispute::find()->andWhere(['!=','status', '2'])->count();
     return $count;
	}
	//Количество Не закрытых жалоб
    public function Support() 
	{	
	 $count = SupportSubject::find()->andWhere(['flag_admin' => 1])->count();
     return $count;
	}
	
	//Количество пользователей онлайн
    public function Onlinetop() 
	{	
	   Online::deleteAll(['<','date', date('Y-m-d H:i:s', strtotime('-3 minutes'))]);
	   $count = Online::find()->all();
       return $count;
	}
	
	
	
    public function Shop() 
	{	
	 $count = Shop::find()->andWhere(['status' => '0'])->count();
     return $count;
	}
	
    public function Article() 
	{	
	 $count = Article::find()->andWhere(['status' => '0'])->count();
     return $count;
	}	
	public function Board() 
	{	
	 $count = Blog::find()->andWhere(['status_id' => '0'])->andWhere(['!=', 'express','1'])->count();
     return $count;
	}

	public function Boardexpress() 
	{	
	 $count = Blog::find()->andWhere(['status_id' => '0'])->andWhere(['express' => '1'])->count();
     return $count;
	}
}