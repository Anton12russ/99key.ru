<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use frontend\models\BlogExpress;
use common\models\Rates;
use common\models\User;
use common\models\Field;
use common\models\BlogTime;
use common\models\BlogImage;
use common\models\BlogField;
use common\models\BlogCoord;
use common\models\BlogAuction;
use common\models\AuctionCat;

use common\models\CatServices;
use common\models\PaymentSystem;
use \yii\base\DynamicModel;
class ExpressAdd extends Action
{
    public function run()
    {
		
		//Аукцион
	$auk_category = false;
	   $registr_success = false;
	   $times = BlogTime::find()->where(['def'=>'1'])->orderBy([ 'sort' => SORT_ASC])->all();	

	   
	
	   $dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
       $model = new BlogExpress();
       $catid = $model->category;
        if (Yii::$app->request->post('Pjax_category')) {
             $catid = Yii::$app->request->post('Pjax_category');
			 
			 
 //------------------------Проверяем, платная ли категория------------------//
		$reg_price = Yii::$app->request->post('Pjax_region');	
        $time_price	= Yii::$app->request->post('Pjax_time');
	    $price_category = $this->findMod($catid, $reg_price);	
		
		//Аукцион, открывать ли в этой категории
		$auk_category = $this->findAuk($catid);

        if (isset($price_category)) {
			$price_cat = array();
			$price_cat['price'] = (int)$price_category * (int)$time_price;
			$price_cat['tyme'] = $time_price;
			$price_cat['sum'] = $price_category ;
		}
		  
        }
		if(@Yii::$app->request->post()['Blog']['category']) {
               $catid =  Yii::$app->request->post()['Blog']['category'];	
		}
   

	
		
		if (isset($cordin)) {
        $mod = array_merge($mod, $cordin);	
        $string = array_merge($string, $cordin);			
        }		
		

		

        $array_post = Yii::$app->request->post();
		  
      
		   
		$model->url = Yii::$app->userFunctions->transliteration(Yii::$app->request->post('Blog')['title']);
		

       

		
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

		if ($model->dir_name) {$dir_name = $model->dir_name;}

		//Модерация объявления
		if(Yii::$app->caches->setting()['moder']) {
			$model->status_id = 1;	
		}else{
			$model->status_id = 0;
		}
		if($registr_success === true) {
			$model->status_id = 2;
		}
		
		//------------------------Проверяем, платная ли категория------------------//
	    $price_category = $this->findMod($model->category, $model->region);
		if ($price_category) {
			$price_datedel = $model->date_del;
			 $model->active = 0;
		}	else{
             $model->active = 1; 
		}	

		
		//передаем эту переменную в шаблон, чтобы вывести сообщение с успешным размещением
		$save['status'] = $model->status_id;
		
		//Не забыть передать в шаблон уведоиление об оплате
		$model->user_id = $user_id;
		
		
		
		
        $model->date_add =  date('Y-m-d H:i:s');
	
		$model->date_del =  date('Y-m-d H:i:s', strtotime(' + 30 day'));

		$model->user_id = 1;
		//Сохраняем статичные поля, проверка внутри скрыта, чтобы не проверять повторно пользователя
       
		$model->save(false);
		
//цена
if(!$model->price) {
	$model->price = '0';
}
	  $filprice = new BlogField();
	  $filprice->message = $model->id;
	  $filprice->field  = '481';
	  $filprice->value  = $model->price;
	  $filprice->dop  = '1';
	  $filprice->save();

//Телефон
$filphone = new BlogField();
$filphone->message = $model->id;
$filphone->field  = '475';
$filphone->value  = $model->phone;
$filphone->dop  = '';
$filphone->save();	
				//------------------Обновление ---------------Добавляем координаты-------------------//
		
		           $coord = new BlogCoord();
                   $coord->blog_id = $model->id;
                   $coord->coordlat  = $model->coordlat;
                   $coord->coordlon  = $model->coordlon;
				   $coord->text  = $model->address;
                   $coord->save();
		
		$save['id'] = $model->id;
		//передаем количество дней
		$save['date_del'] = $time_d;
		
		
		if ($price_category) {
			$price_cat = array();
			$sum_pay = (int)$price_category * (int)$price_datedel;
			$price_cat['sum'] = $sum_pay;
			$price_cat['date'] = $price_datedel;
			$price_cat['id_payment'] = Yii::$app->userFunctions->addPayment($sum_pay, $model->id, $user_id);
		}
		
		




	  //Обрабатываем массив с данными и группируем валюту и адрес в поле dop
	  $post_save = array();


		            $dir_maxi = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
		            $dir_mini = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/mini/';
		            $dir_original = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/original/';
				    $dir_copy_maxi = Yii::getAlias('@img').'/board/maxi/';
		            $dir_copy_mini = Yii::getAlias('@img').'/board/mini/';
		            $dir_copy_original = Yii::getAlias('@img').'/board/original/';
					
					
			//Функция перемещения изображений из временной папки в папку с фото
	    $list = Yii::$app->userFunctions->filesDir($dir_name);
		if ($list) 
		{
			//BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $model->id]);
		   foreach($list as $key => $file) {
			$timew = microtime();
			$timew = str_replace(' ','',$timew);
			$timew = str_replace('.','',$timew);
			$timew = str_replace(',','',$timew);
            rename($dir_maxi.$file, $dir_copy_maxi.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$timew.'.jpg');
			rename($dir_mini.$file, $dir_copy_mini.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$timew.'.jpg');
			rename($dir_original.$file, $dir_copy_original.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$timew.'.jpg');
			
			//Сохраняем название файла в базу данных
			      $blogimage = new BlogImage();
				   $blogimage->id = '';
                   $blogimage->blog_id = $model->id;
                   $blogimage->image  = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$timew.'.jpg';
                   $blogimage->save();
		   }			
	    }	
		
		Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');


         
		
		  //-------------------------Отправка оповещение админу-----------------------------//
			  if(Yii::$app->caches->setting()['email_board']) {
				  $url = Url::to(['blog/one', 'region'=>$model->regions['url'], 'category'=>$model->categorys['url'], 'url'=>$model->url, 'id'=>$model->id]);
			      if(!isset(Yii::$app->user->identity->email)) {
					  $email_user = $user->email;
				  }else{
					  $email_user = Yii::$app->user->identity->email;
				  }
				  Yii::$app->functionMail->board_admin($url, $email_user, Yii::$app->caches->setting()['email_board']);
			  }  
	   }else{
		   $auk_category = $this->findAuk($catid);
		   //Для разработчика
		   	$print = $model->errors;
	   }
	
      
		  //Создаем правильный массив чтобы в представлении скормить его в хелпер
		  if (count($times) > 1) {
		     foreach($times as $res) {
			    $time[$res['days']] = $res['text'];
		     }
		  }
		
		if (!isset($print)) {$print = '';}
        if ($model->dir_name) {$dir_name = $model->dir_name;}
		if(!isset($price_cat))	 {$price_cat = '';}
		if(!isset($save)) {$save = '';}

	 //Мета теги
	 //Если выбран регион, добавляем к нему склонение
     	if (Yii::$app->request->cookies['region']) {
		   $regionid = (string)Yii::$app->request->cookies['region'];
	       $region_sklo = ' в ' .Yii::$app->caches->regionCase()[$regionid]['name'];
		   $region_key =   ', '.Yii::$app->caches->region()[$regionid]['name'];
		}else{
			$region_sklo = '';
			$region_key = '';
		}
		
	 $meta['title'] = 'Подать объявление '.$region_sklo;
     $meta['h1'] = $meta['title'];
	 $meta['keywords'] = 'подать, объявление'.$region_key;
	 $meta['description'] = 'Добавить объявление'. $region_sklo;
     $meta['breadcrumbs'] = 'Добавить объявление';

	 //Получаем все платежные системы и передаем в шаблон
	    $payment = $this->findPayment();
         return $this->controller->render('add_express', [
            'model' => $model,
			'print' => $print,
			'model_view' => $model_view,
			'dir_name' => $dir_name,
			'save' => $save,
			'meta' => $meta,
			'price_category' => $price_cat,
			'payment' => $payment,
			'registr_success' => $registr_success,
        ]); 
    }
	
	
	
	  protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->functionMail->emailAdmin() => Yii::$app->caches->setting()['email']])
            ->setTo($user->email)
            ->setSubject('Регистрация аккаунта на ' . Yii::$app->caches->setting()['site_name'])
            ->send();
    }
	
	
	
	
	
	//Получаем все платежные системы
	protected function findPayment()
    {
        if (($model = PaymentSystem::find()->where(['status' => 1])->asArray()->all()) !== null) {
            return $model;
        }
    }
	
	
	   protected function findMod($cat, $reg)
    {
		$reg = Yii::$app->userFunctions->catparent($reg, 'reg');
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = CatServices::find()->Where(['cat' => $cat])->asArray()->all();
		foreach ($return as $res) {
           if (in_array($res['reg'], $reg)) {
			   $ret = $res['price'];
		   }
		}		
        if (!isset($ret)) {$ret = '';}			
	    return $ret;
    }
	

	
	protected function findAuk($cat)
    {
		$cat = Yii::$app->userFunctions->catparent($cat, 'cat');
		
		$return = AuctionCat::find()->Where(['cat' => $cat])->asArray()->all();
       if($return) {
		   $return = true;
	   }else{
		   $return = false;
	   }
		
	    return $return;
    }
	
	
	
	
	
	
	    public  function fieldarr($id) {
         $cat = Yii::$app->request->get('cat');
          //Достаем массив категорий, к которым пренадлежит переданная категорий в переменрой $id потом достать все поля этих категорий
		 
             $cat_array = Yii::$app->userFunctions->linens(Yii::$app->caches->category(),$id);
	
             array_unshift($cat_array, "0");
            //Выбираем поля фильтра всех категорий, которым преналдежит $id
            $customers = Field::find()->where(['cat' => $cat_array])->orderBy(['sort' => SORT_ASC])->asArray()->all();

            foreach ($customers as $rows) {
	            if ($rows['type'] == 'p') {
                    $rate = true;
	            }
             }

            return $customers;
      }
}