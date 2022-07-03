<?php
namespace frontend\actions;
use yii\base\Action;
use yii;
use frontend\models\Blog;
use common\models\Rates;
use common\models\Bet;
use common\models\User;
use common\models\BlogTime;
use common\models\BlogImage;
use common\models\BlogField;
use common\models\BlogAuction;
use common\models\CatServices;
use common\models\PaymentSystem;
use common\models\Payment;
use common\models\BlogCoord;
use \yii\base\DynamicModel;
use common\models\Field;
use yii\web\NotFoundHttpException;
use common\models\Orders;
use yii\helpers\Url;
use common\models\AuctionCat;
class AuctionUpdate extends Action
{
    public function run($id)
    {
		
	   //Аукцион
	   $auk_category = false;
       $model = $this->findModel($id);
	   //---Обновление координаты ---//
	   if(isset($model->coord->text)) {
	   $model->address = $model->coord->text;
	   $model->coordlat = $model->coord->coordlat;
	   $model->coordlon = $model->coord->coordlon;
	   }
	   
//параметры аукциона из базы
	   if(isset($model->auctions)) {

		$model->auk_price_add = $model->auctions['price_add'];


			    $model->auk_price_moment = $model->auctions['price_moment'];
	
			
		 $model->auk_shag = $model->auctions['shag'];	
		 //$time = $model->auctions['date_end'] - $model->auctions['date_add'];
		 $date1 = strtotime(date('Y-m-d H:i:s'));
         $date2 = strtotime($model->auctions['date_end']);
         $diff = ABS($date1 - $date2);
		 $auk_time =  $diff/86400;
		 $auk_time = ceil($auk_time);
		 $model->auk_time =  $auk_time;	
	   }
	   
	   
	    //Проверяем права на редактирование
		if($model->express == '1') {
			return $this->controller->redirect(['expressupdate', 'id' => $model->id]);
		}

		if($model->user_id != Yii::$app->user->id) {	
		  if(!Yii::$app->user->can('updateOwnPost', ['board' => $model]) && !Yii::$app->user->can('updateBoard')) {
			 throw new NotFoundHttpException('The requested page does not exist.'); 
		  }
	   }	
	   
	$model->text = $model->text; 
	   //Копируем изображения во временную папку
	$dir_name = $id;
	if (!Yii::$app->request->post()) {
	$base = BlogImage::find()->where(['blog_id'=>$id])->select(['image'])->asArray()->all();
	$this->findFile($id, $base);
	}
	   //Для проверки изменения категории, а вдруг выбрали платную.
	   $category_pay = $model->category;
	   $region_pay = $model->region;
	   
	   
	   $times = BlogTime::find()->where(['def'=>'1'])->orderBy([ 'sort' => SORT_ASC])->all();	
       $catid = $model->category;
        if (Yii::$app->request->post('Pjax_category')) {
             $catid = Yii::$app->request->post('Pjax_category');
			 
			 
	//------------------------Проверяем, платная ли категория------------------//
		$reg_price = Yii::$app->request->post('Pjax_region');	
        $time_price	= Yii::$app->request->post('Pjax_time');
	    $price_category = $this->findMod($catid, $reg_price);	
        if (isset($price_category)) {
			$price_cat = array();;
			$price_cat['price'] = (int)$price_category * (int)$time_price;
			$price_cat['tyme'] = $time_price;
			$price_cat['sum'] = $price_category ;
		}
		  
		  //Аукцион, открывать ли в этой категории
		$auk_category = $this->findAuk($catid);
	
		  
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
		//->addRule($required, function ($attribute) use ($model2, $price){ foreach($price as $res) {if($attribute == $res)if ($model2->attributes[$attribute] == 0) {$model2->addError($attribute,  'Поле не может быть 0.');}}})
		->addRule($string, 'string',['message'=>'Должны быть введены только строковые значения'])
		->addRule($integer, 'integer',['message'=>'Должны быть введены только целые числа'])
		//Проверка на телефон
        ->addRule($phone, function ($attribute) use ($model2) {$pattern = "#^\+[0-9] {1,2}\s?\([0-9]{3}\)\s?[0-9]+\-[0-9]+\-[0-9]+$#"; if(preg_match($pattern, $model2->attributes[$attribute], $out)){}else{$model2->addError($attribute,  'Не верный формат номера');}})
        //Проверка на Ютуб
	    ->addRule($youtube, function ($attribute) use ($model2) {$res = 'https://www.youtube.com/watch?v=';if (strpos($model2->attributes[$attribute], $res) !== false) {$headers = get_headers($model2->attributes[$attribute]);if (!strpos($headers[0], '200')) { $model2->addError($attribute,  'Видео не существует');}}else{ $model2->addError($attribute,  'Не верный формат ссылки на ролик');}})
        //Проверка ограничения символов
        ->addRule($mod, function ($attribute) use ($model2,$max) {
			if (is_array($model2->attributes[$attribute])) {foreach($model2->attributes[$attribute] as $res) {$strlen = strlen($res); foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}}}}
            if (!is_array($model2->attributes[$attribute])) {$strlen = strlen($model2->attributes[$attribute]);foreach($max as $res) {if ($res['id'] == $attribute) {if ($strlen > $res['max']) {$model2->addError($attribute,  'Привышено допустимое количество символов ('.$res['max'].')');}}} }})
        //Проверка адреса сайта
	    ->addRule($url, function ($attribute) use ($model2) {if(!parse_url($model2->attributes[$attribute], PHP_URL_HOST)) {$model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}})
	    ->addRule($integer_с, function ($attribute) use ($model2) {if (is_array($model2->attributes[$attribute])) { foreach($model2->attributes[$attribute] as $res) {if(!is_numeric($res)){$model2->addError($attribute,  'Вы совершиили ошибку в заполнении данного поля.');}} }if (!is_array($model2->attributes[$attribute])) { if(!is_numeric($model2->attributes[$attribute])){ $model2->addError($attribute,  'Не верный формат URL (Адреса сайта)');}} })
         ;
	 
	
        $array_post = Yii::$app->request->post();
		     
			
             $model2_arr =  $this->findModel2($id);
			
		        foreach ($model2['attributes'] as $key =>$result) {
					if (!isset($model2_arr[$key]['value'])) {$model2_arr[$key]['value'] = '';}
		           $model2[$key] = $model2_arr[$key]['value'];

				   if (strpos($key, '_address') !== false){
					   $key_addr = str_replace('_address', '', $key);
					   if(isset($model2_arr[$key_addr]['dop'])) {
				         $model2[$key] = $model2_arr[$key_addr]['dop'];
					   }
					   $key_addr = '';
				   }
			          //Преобразуем цену из дефолтной в указанную пользователем
			          foreach($price as $res) {	 
					      if ($key == $res) {
							  if ($model2[$res]) {
					            $model2[$key] = ($model2[$res] / Yii::$app->caches->rates()[$model2_arr[$key]['dop']]['value']);
								$model2[$key.'_rates'] = $model2_arr[$key]['dop'];
							  }
			              }
					  }
		        }
	   	
		 
		 
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
		
		


			
	
	
		//------------------------Проверяем, платная ли категория------------------//

      if ($model->category != $category_pay || $model->region != $region_pay || $model->active == 0) {
          $price_category = $this->findMod($model->category, $model->region);
          if ($price_category) {
			 
		     	$price_datedel = $model->date_del;
			    $model->active = 0;
	      }else{
              $model->active = 1;  
		}
      }


		
		//передаем эту переменную в шаблон, чтобы вывести сообщение с успешным размещением
		$save['status'] = $model->status_id;
		
		//Не забыть передать в шаблон уведоиление об оплате
		$user_id = Yii::$app->user->id;
		$save['user'] = false;
		

	
		if($model->auction == 3 && $model->status_id == 2  && $model->auk_price_add ) {

             $day_auction = $model->auk_time;
		}else{
        if($model->auction == 1 && $model->auk_price_add && $model->auk_time && $model->status_id != 2) { 
		  if($model->auctions->date_end) {
			    $model->date_del = $model->auctions->date_end;
		   }
		}elseif($model->auction == 1 && $model->auk_price_add && $model->auk_price_moment && $model->auk_time && $model->status_id == 2) {	
	         $day_auction = $model->auk_time;
		}else{
			
			
		     $model->date_del = date('Y-m-d H:i:s', strtotime(' + '.$time_d.' day'));
			
		}
		}
		
		
		//Если есть аукцион, меняем количество дней
		    if($model->auction > 0 && ($model->status_id == 1  || $model->status_id == 0) && $model->auk_price_add) {

			         $day = $model->auk_time;
			 /*Вот тут сомнения берут, если что удалить!!!!!!*/
				      $auction = BlogAuction::find()->where(['blog_id' => $model->id])->One();
                      $auction->date_add = date('Y-m-d H:i:s');
					  $auction->date_end = date('Y-m-d H:i:s', strtotime("+".$day." days"));
					  $auction->update(false);
					  
					  $model->date_del = date('Y-m-d H:i:s', strtotime("+".$day." days"));
		}

	
		//////////////////////////////////////////////////////////////////////////
	    if($model->auction > 0 && $model->auction != 1) {
			$day = round((strtotime ($model->date_del)-strtotime ($model->date_add))/(60*60*24));
				$model->date_add = date('Y-m-d H:i:s');
				$model->date_del = date('Y-m-d H:i:s', strtotime("+".$day." days")); 
				  /*Вот тут сомнения берут, если что удалить!!!!!!*/
				      $auction = BlogAuction::find()->where(['blog_id' => $model->id])->One();
                      $auction->date_add = date('Y-m-d H:i:s');
					  $auction->date_end = date('Y-m-d H:i:s', strtotime("+".$day." days"));
					  $auction->update(false);
					  
				
				$time_d = $day;
		}
		
		
		
		//Сохраняем статичные поля, проверка внутри скрыта, чтобы не проверять повторно пользователя
		$model->text = $model->text; 
		
		
		
		
	
		
		if($model->auction != 0) {
			if($model->status_id == 2) {
					
	              if($model->reserv_user_id > 0  && $model->auction != 3) {
		                 Yii::$app->functionMail->reservdellot($model);
				   }	
                $model->reserv_user_id = '';
				$model->auction = 1;
				if(isset($day_auction)) {
				   $day = $day_auction;
				}else{
				  // $day = round((strtotime ($model->date_del)-strtotime ($model->date_add))/(60*60*24));
				  $day = round((strtotime ($model->auctions->date_end)-strtotime ($model->auctions->date_add))/(60*60*24));
				}
			
				$model->date_add = date('Y-m-d H:i:s');
				$model->date_del = date('Y-m-d H:i:s', strtotime("+".$day." days"));
				
				/*Вот тут сомнения берут, если что удалить!!!!!!*/
				      $auction = BlogAuction::find()->where(['blog_id' => $model->id])->One();
                      $auction->date_add = date('Y-m-d H:i:s');
					  $auction->date_end = date('Y-m-d H:i:s', strtotime("+".$day." days"));
					//  $auction->update(false);
					  
				$time_d = $day;
				
				Bet::deleteAll(['blog_id'=> $model->id]);
				
				
			}
		}
		
		
		
		
		
		if (Yii::$app->user->can('updateBoard')) {
			$model->status_id = $model->status_id_false;
			$model->active = $model->active;
			if(isset($model->balance_minus) && $model->balance_minus > 0) {
			   $this->findUserminus($model->balance_minus, $model->user_id, $model->id);
			}
		}else{
			
			$model->status_id = Yii::$app->caches->setting()['moder'];	
		}
		
		
		
//---------------------- Счетчик, проверяем нужно ли считать---------------------------//

    $model_old = $this->findModel($id);

		

		
     $model->update(false);

		
			 
			 		
					//------Обновление координаты----------------//
			$coord = $this->findCoord($model->id);
			if($coord) {
			  if($model->coordlat) {
               $coord->blog_id = $model->id;
               $coord->coordlat  = $model->coordlat;
               $coord->coordlon  = $model->coordlon;
			   $coord->text  = $model->address;
               $coord->update();
			  }
			}else{
			  if($model->coordlat) {
			   $coord = new BlogCoord();
               $coord->blog_id = $model->id;
               $coord->coordlat  = $model->coordlat;
               $coord->coordlon  = $model->coordlon;
			   $coord->text  = $model->address;
               $coord->save();
			  }
			}
		
		
		$save['id'] = $model->id;
		//передаем количество дней
		$save['date_del'] = $time_d;
		
		
		if (isset($price_category) && $price_category != '') {
			$price_cat = array();
			$sum_pay = (int)$price_category * (int)$price_datedel;
			//$price_cat['user_id'] = $user_id;
			$price_cat['sum'] = $sum_pay;
			$price_cat['date'] = $price_datedel;
			$price_cat['id_payment'] = Yii::$app->userFunctions->addPayment($sum_pay, $model->id, $user_id);
		}
		
		
        BlogField::deleteAll('message = :message', [':message' => $model->id]);
	


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
			$auction_del = BlogAuction::find()->Where(['user_id' => $user_id, 'blog_id' => $model->id])->One();
			if($auction_del) {
			   $auction_del->delete();
			}

			$auction = new BlogAuction();
			$auction->price_add = $model->auk_price_add;
			if($model->auk_price_moment) {
			    $auction->price_moment = $model->auk_price_moment;
			}
			if($model->auctions['date_add']) {
				$date_add = $model->auctions['date_add'];
			}else{
				 $date_add =  date('Y-m-d H:i:s');
			}
			$auction->date_add = $date_add;
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
			
			
		$base = BlogImage::find()->where(['blog_id'=>$model->id])->select(['image'])->asArray()->all();
		foreach($base as $res) {
			 
			          @unlink($dir_copy_maxi.$res['image']);
				      @unlink($dir_copy_mini.$res['image']);
				      @unlink($dir_copy_original.$res['image']);
				
		}
			BlogImage::deleteAll('blog_id = :blog_id', [':blog_id' => $model->id]);
		   foreach($list as $key => $file) {
            @rename($dir_maxi.$file, $dir_copy_maxi.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
			@rename($dir_mini.$file, $dir_copy_mini.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
			@rename($dir_original.$file, $dir_copy_original.$model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg');
			
			//Сохраняем название файла в базу данных
			      $blogimage = new BlogImage();
				   $blogimage->id = '';
                   $blogimage->blog_id = $model->id;
                   $blogimage->image  = $model->id.'_'.Yii::$app->userFunctions->transliteration($model->title).'_'.$key.'.jpg';
                   $blogimage->save();
		   }			
	    }	
		Yii::$app->userFunctions->recursiveRemove(Yii::getAlias('@images_temp').'/board/'.$dir_name.'/');
		
		
		
       //Оповещение о презаказах
	   $this->findOrder($model);
	  // return $this->redirect(['view', 'id' => $model->id]);
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
		
	 $meta['title'] = 'Редактировать объявление'.$region_sklo;
     $meta['h1'] = $meta['title'];
	 $meta['keywords'] = 'редактировать, объявление'.$region_key;
	 $meta['description'] = 'Редактировать объявление'. $region_sklo;
     $meta['breadcrumbs'] = 'Редактировать объявление';


//Получаем данные пользователя и передаем в шаблон
 if(Yii::$app->user->can('updateBoard')) {
	   $user = $this->findUserbalance($model->user_id);
  }else{
	   $user = ''; 
  }
	 //Получаем все платежные системы и передаем в шаблон
	    $payment = $this->findPayment();
         return $this->controller->render('/auction/update', [
		    'user' => $user,
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
			'payment' => $payment,
			'auk_category' => $auk_category
        ]); 
    }
	
	
	
	  protected function sendEmail($user)
    {
       /* return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();*/
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
	
	
	
	
	
	
	

	
	
	protected function findModel($id)
    {
		if (($model = Blog::find()->Where(['id'=> $id])->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
	

	
		protected function findModel2($id)
    {
        if (($models = BlogField::find()->Where('message=:message',['message'=> $id])->all()) !== null) {
            foreach($models as $res) {
		     $model3['f_'.$res['field']] = array('value' => $res['value'], 'dop' =>$res['dop']);
		    }
		return $model3;
        }
     }
	 
	 
	 
	 
	 //Формируем массив с файлами в правильном порядке
 function filesDir($dir_name) {
	$dir = Yii::getAlias('@images_temp').'/board/'.$dir_name.'/maxi/';
	$list = Blog::myscandir($dir);	 
if ($list) {	
   foreach($list as $res) {
     $str=strpos($res, "_");
     $row=substr($res, 0, $str);	
     $array[$row] = $res;
    }
   ksort($array);
    foreach($array as $res) {
       $rows[] = $res;
    }
    }	
	if (!isset($rows)) {$rows = '';}
	return $rows;
  } 
	 
	 
	 
	 
	 //Создание папки для файлов при редактировании объявления и перемищения файлов во временные папки для дальнейшей работы с ними 
 protected function findFile($id, $base)
    {
		$url = $id;
		Yii::$app->userFunctions->delDir(Yii::getAlias('@images_temp').'/board/'.$url);
  if ($base) { 
	            @mkdir(Yii::getAlias('@images_temp').'/board/'.$url, 0755);
				@mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/mini/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/', 0755);
			    @mkdir(Yii::getAlias('@images_temp').'/board/'.$url.'/original/', 0755);
           foreach($base as $key => $res) {
	         @copy(Yii::getAlias('@img').'/board/maxi/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/maxi/'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/mini/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/mini/'.$key.'_'.time().'.jpg');
			 @copy(Yii::getAlias('@img').'/board/original/'.$res['image'], Yii::getAlias('@images_temp').'/board/'.$url.'/original/'.$key.'_'.time().'.jpg');
           }

     }
	}







//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserbalance($id)
    {
	   $model = User::find()->where(['id' => $id])->one();
	   return $model;
	}
	
	
	
	//Выбираем данные пользователя для админа, чтобы было видно какой баланс на счету.
 protected function findUserminus($balance, $user_id, $blog_id)
    {	
          $rates = Rates::find()->where(['def' => 1, 'value' => 1])->asArray()->one();
	      $pay = new Payment();
          $pay->price = -$balance;
		  $pay->currency  = $rates['charcode'];
		  $pay->user_id  = $user_id;
		  $pay->system  = 'Личный счет';
		  $pay->blog_id  = $blog_id;
		  $pay->services  = 'act';
		  $pay->status  = '1';
		  $pay->time  = date('Y-m-d H:i:s');
		  $pay->save(false); 
		  
		 
		  //Вычитаем стоимость с баланса у юзера
	
		  $user = User::findOne($user_id);
		  $user->balance = $user->balance-$balance;
		  $user->save(false); 
    }
	
	
		//------Обновление координаты----------------//

	protected function findCoord($id)
    {
		if (($model = BlogCoord::find()->where(['blog_id' => $id])->one()) !== null) {
            return $model;
        }
    }
	
	
		//Оповещение о предзаказе
 protected function findOrder($blog)
    {
		$link = Url::to(['/boardone', 'id'=>$blog->id]);
		$orders = Orders::find()->where(['board_id' => $blog->id])->andWhere(['<=','colvo', $blog->count])->all();
		foreach($orders as $order) {
			Yii::$app->functionMail->order_send($order, $blog->title, $blog->count, $link, $blog->shop['domen']);
			$arrid[] = $order->id;
		}
		if(isset($arrid)) {
		   //Orders::deleteAll(['id' => $arrid]);
		}
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
}