<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use yii\helpers\Url;
use frontend\models\Blog;
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
class AuctionAdd extends Action
{
    public function run()
    {
		
		//Аукцион
	$auk_category = false;
	if (Yii::$app->user->isGuest) {
			  return $this->controller->redirect(['/user']);
		 }
		$registr_success = false;
	   $times = BlogTime::find()->where(['def'=>'1'])->orderBy([ 'sort' => SORT_ASC])->all();	

	   
	
	   $dir_name = Yii::$app->security->generateRandomString(5).'_'.time();
       $model = new Blog();
       $catid = $model->category;
        if (Yii::$app->request->post('Pjax_category')) {
             $catid = Yii::$app->request->post('Pjax_category');
			 
			 
 //------------------------Проверяем, платная ли категория------------------//
		$reg_price = Yii::$app->request->post('Pjax_region');	
		if(!$time_price = $model->auk_time) {
            $time_price	= 1;
	    }
	    $price_category = $this->findMod($catid, $reg_price);	
		
		//Аукцион, открывать ли в этой категории
		$auk_category = $this->findAuk($catid);


        if (isset($price_category)) {
			$price_cat = array();;
			$price_cat['price'] = (int)$price_category * (int)$time_price;
			$price_cat['tyme'] = $time_price;
			$price_cat['sum'] = $price_category ;
		}
		 
		  
        }
		if(@Yii::$app->request->post()['Blog']['category']) {
               $catid =  Yii::$app->request->post()['Blog']['category'];	
		}
   
	foreach ($this->fieldarr($catid) as $field) {
	    $model_view[] =  array('id' => $field['id'], 'name' => $field['name'], 'type' => $field['type'], 'type_string' => $field['type_string'], 'req' => $field['req'], 'values' => $field['values']);
	    $mod[] = 'f_'.$field['id'];
	    $max[] = array('id' => 'f_'.$field['id'],'max' => $field['max']);
		
	    //Массив обязательных к заполнению
            if ($field['req'] == '1') {
	            $required[] = 'f_'.$field['id'];
	        }
	    //Массив проверки только на строковое значение text и textarea
	     if ($field['type'] == 'v' || $field['type'] == 't' || $field['type'] == 'y') {
	          $string[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число
	     if ($field['type_string'] == 'n' || $field['type'] == 'p' || $field['type'] == 'r' || $field['type'] == 's') {
              $integer[] = 'f_'.$field['id'];
	     }
	    //Массив проверки только на целое число для чекбоксов. Потому что, иногда возвращается массивом более одного значения
         if ($field['type'] == 'c') {
		      $integer_с[] = 'f_'.$field['id'];
	     }
	   //Массив проверки телефонного номера
	     if ($field['type_string'] == 't' || $field['type_string'] == 'x') {
              $phone[] = 'f_'.$field['id'];
	     }
	
	   //Массив Проверки ссылки на ютуб
	     if ($field['type'] == 'y') {
              $youtube[] = 'f_'.$field['id'];
	     }
	
	  //Массив Проверки ссылки
	     if ($field['type_string'] == 'u') {
              $url[] = 'f_'.$field['id'];
	     }
	
      //Проверяем есть ли у категории, поле цена
	   if ($field['type'] == 'p') {
            $rate = true;
	        $price[] = 'f_'.$field['id'];
	        $price_r[] = 'f_'.$field['id'].'_rates';
	        $integer[] = 'f_'.$field['id'];
	        $integer[] = 'f_'.$field['id'].'_rates';
	   }
	   
	 //Проверяем есть ли у категории, поле координаты
	   if ($field['type'] == 'j') {
           $cordin[] = 'f_'.$field['id'].'_address';
	   }
    }
        if ($rate)
		{
           $rat = Rates::find()->all();	
              foreach ($rat as $res) {
                   $rates[] = array('id' =>  $res['id'], 'name' => $res['name'], 'value' => $res['value'], 'text' => $res['text']);
              }	
	               $mod = array_merge($price_r,$mod);
         }	
		
		if (isset($cordin)) {
        $mod = array_merge($mod,$cordin);	
        $string = array_merge($string,$cordin);			
        }		
		

		$model2 = new DynamicModel($mod);
		
        $model2 
		->addRule($required, 'required',['message'=>'Поле не может быть пустым'])
		->addRule($string, 'string',['message'=>'Должны быть введены только строковые значения'])
		->addRule($integer, 'integer',['message'=>'Должны быть введены только целые числа'])
		//Проверка на телефон
        ->addRule($phone, function ($attribute) use ($model2) {$pattern = "#^\+[0-9] {1,2}\s?\([0-9]{3}\)\s?[0-9]+\-[0-9]+\-[0-9]+$#"; if(preg_match($pattern, $model2->attributes[$attribute], $out)){}else{$model2->addError($attribute,  'Не верный формат номера');}})
        //Проверка на Ютуб
	    ->addRule($youtube, function ($attribute) use ($model2) {$res = 'https://www.youtube.com/watch?v=';if (strpos($model2->attributes[$attribute], $res) !== false) {$headers = get_headers($model2->attributes[$attribute]);if (!strpos($headers[0], '200')) { $model2->addError($attribute,  'Видео не существует');}}else{ $model2->addError($attribute,  'Неверный формат ссылки на ролик');}})
        //Проверка ограничения символов
        ->addRule($mod, function ($attribute) use ($model2,$max) {
			if (is_array($model2->attributes[$attribute])) {foreach($model2->attributes[$attribute] as $res) {$strlen = strlen($res); foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}}}}
            if (!is_array($model2->attributes[$attribute])) {$strlen = strlen($model2->attributes[$attribute]);foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}} }})
        //Проверка адреса сайта
	    ->addRule($url, function ($attribute) use ($model2) {if(!parse_url($model2->attributes[$attribute], PHP_URL_HOST)) {$model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}})
	    ->addRule($integer_с, function ($attribute) use ($model2) {if (is_array($model2->attributes[$attribute])) { foreach($model2->attributes[$attribute] as $res) {if(!is_numeric($res)){$model2->addError($attribute,  'Вы совершиили ошибку в заполнении данного поля.');}} }if (!is_array($model2->attributes[$attribute])) { if(!is_numeric($model2->attributes[$attribute])){ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}} })
         ;
	 

        $array_post = Yii::$app->request->post();
		  
           if($model2->load($array_post) && $model2->validate() ){
                $model2_result = true;
           }elseif($model2->load($array_post) && !$model2->validate()){
			      $auk_category = $this->findAuk($catid);
		   }
		   
		$model->url = Yii::$app->userFunctions->transliteration(Yii::$app->request->post('Blog')['title']);
		

       

        //Добавляем данные пользователя, если тот зарегистрирован, чтобы обойти валидатор, не более того
        if (!Yii::$app->user->isGuest) {
			$model->password = 1;
			$model->password2 = 1;
			$model->email = Yii::$app->user->identity['email'];
			$model->username = Yii::$app->user->identity['username'];
		}
		
		
		 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			
		if ($model->dir_name) {$dir_name = $model->dir_name;}
        if (isset($model2_result)) {
		if (count($times) > 1) {
		    if (count($times) == 1) {$time_d = $times[0]['days'];}else{$time_d = $model->date_del;}
		}else{
		    $time_d = 30;   
		} 
		
		
		//Регистрируем пользователя
        if (Yii::$app->user->isGuest) {
		//передаем эту переменную в шаблон, чтобы вывести сообщение с успешной регистрацией
		$save['user'] = true;
		
		$user = new User();
        $user->username = $model->username;
        $user->email = $model->email;
        $user->setPassword($model->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->save(); 
		
		
		//Отправлять почту пользователю
		$this->sendEmail($user);
        $registr_success = true;
		$user_id = $user->id;
		
		}else{
			
		$user_id = Yii::$app->user->id;
		$registr_success = false;
		$save['user'] = false;
		}
		
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
		//if($model->auction == 1 && $model->auk_price_add && $model->auk_price_moment && $model->auk_time) {
		if($model->auction == 1) {
			$model->date_del =  date('Y-m-d H:i:s', strtotime(' + '.$model->auk_time.' day'));
			$time_d = $model->auk_time;
		}else{
		    $model->date_del =  date('Y-m-d H:i:s', strtotime(' + '.$time_d.' day'));
		}
		//Сохраняем статичные поля, проверка внутри скрыта, чтобы не проверять повторно пользователя
        $model->save(false);
		

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
			$sum_pay = (int)$price_category * (int)$model->auk_time;
			$price_cat['sum'] = $sum_pay;
			$price_cat['date'] = $model->auk_time;
			$price_cat['id_payment'] = Yii::$app->userFunctions->addPayment($sum_pay, $model->id, $user_id);
		}
		
		




	  //Обрабатываем массив с данными и группируем валюту и адрес в поле dop
	  $post_save = array();

	 foreach($model2['attributes'] as $key => $res) {
		 if(isset($model2['attributes'][$key.'_rates'])) {
	       // $res = ($res * Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]);
		   $res = ((int)$res * (int)Yii::$app->caches->rates()[$model2['attributes'][$key.'_rates']]['value']);
			$dop = $model2['attributes'][$key.'_rates'];
		 }
	      if(isset($model2['attributes'][$key.'_address'])) {
		    $dop = $model2['attributes'][$key.'_address']; 
		 }
		 
		  if (strpos($key, '_rates') === false && strpos($key, '_address') === false) {
		         if ($res !== '') {
		              $post_save[] = array('id' => (int)str_replace('f_','',$key), 'value' => $res, 'dop' => $dop);
		         }
		 }
		  $dop = '';
	 }





		
		/*Аукцион*/
		
		if($model->auction == 1 && $model->auk_price_add ) {
			
			$auction = new BlogAuction();
			$auction->price_add = $model->auk_price_add;
			if($model->auk_price_moment) {
			    $auction->price_moment = $model->auk_price_moment;
			}
			$auction->date_add = date('Y-m-d H:i:s');
			$auction->date_end = date('Y-m-d H:i:s', strtotime(' + '.$model->auk_time.' day'));
			$auction->blog_id = $model->id;
			$auction->user_id = $model->user_id;
			$auction->rates = $model->auk_rates;
			$auction->shag = $model->auk_shag;
			$auction->save();
			
	        $auction_field = true;
		}else{
			$auction_field = false;
		}
		

     foreach($post_save as $res) {
		
	           if (!is_array($res['value'])) {	
			   
			    if($auction_field) {
                  if($res['id'] == '481') {
				      $res['value'] = $auction->price_add;
					  $res['dop'] = $auction->rates;
			      }			
		        }
			 
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($res['value']);
				   $customer->dop  = $res['dop'];
                   $customer->save();
	
               }else{
                   foreach($res['value'] as $result) {
                   $customer = new BlogField();
                   $customer->message = $model->id;
                   $customer->field  = $res['id'];
                   $customer->value  = strval($result);
				   $customer->dop  = $res['dop'];
                   $customer->save();	
	                }
               }
		 
            }
			
	
			
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


          }	
		  
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
		   	/*--$print = $model->errors;-*/
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
		
	 $meta['title'] = 'Добавить аукцион '.$region_sklo;
     $meta['h1'] = $meta['title'];
	 $meta['keywords'] = 'подать, аукцион'.$region_key;
	 $meta['description'] = 'Добавить аукцион'. $region_sklo;
     $meta['breadcrumbs'] = 'Добавить аукцион';

	 //Получаем все платежные системы и передаем в шаблон
	    $payment = $this->findPayment();
         return $this->controller->render('/auction/add', [
            'model' => $model,
			'model2' => $model2,
			'model_view' => $model_view,
			'rates' => $rates,
			'dir_name' => $dir_name,
			'time' => $time,
			'print' => $print,
			'save' => $save,
			'meta' => $meta,
			'price_category' => $price_cat,
			'auk_category' => $auk_category,
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