<?php
namespace frontend\modules\shop\action;
use yii\base\Action;
use yii;
use frontend\models\Shop;
use yii\web\NotFoundHttpException;
use frontend\models\ShopComment;
use common\models\Article;
use common\models\Payment;
use common\models\CarBuyer;
use common\models\CarBuyer1;
use common\models\CarNote;
use common\models\Car;
use common\models\User;
use frontend\models\Blog;

class Cart extends Action
{
    public function run($url)
    {
	
	 $rates = Yii::$app->caches->rates();
        foreach($rates as $resrat) {
			if($resrat['def'] == 1) {
		      $rate_def = $resrat;
			}
		}
	
    	$arr = $this->findModel($url);

	    $shop = $arr['model'];
		$meta = $arr['meta'];
		$vote = $arr['vote'];
		$count_art = $arr['count_art'];
		$rates = $arr['rates'];		
		$notepad = $arr['notepad'];	
		$count_com = $arr['count_com'];
		

	$cars = unserialize(Yii::$app->request->cookies['car']);
	$car_note = unserialize(Yii::$app->request->cookies['arr_note']);
	if($cars) {

	

        foreach($cars as $key => $car) {
		   $car_id[] = $key;
	    }
	
    $blogs = Blog::find()->andWhere(['id'=>$car_id,'user_id'=>$shop->user_id,'status_id' => 1, 'active' => 1])->orderBy(['id' => SORT_DESC])->limit(20)->all();
    
	foreach(array_reverse(Yii::$app->caches->field()) as $res) {
	   if ($res['type'] == 'p')  {
		 $price = $res['id'];
	   }
	}
	
	$rates = Yii::$app->caches->rates();
	}else{
		$blogs = '';
	}
	$this->controller->layout = 'shop';
	
	
	
         $save = false;

        //Отправляем post
		if(!isset($_GET['address'])) {
        $car = new CarBuyer();
		}else{
		$car = new CarBuyer1();
		}
		
		
        if ($car->load(Yii::$app->request->post()) && $car->validate()) {
		   $buyer_mail = $car;
			
	       $blog_id = '';

		   foreach($blogs as $resul) {
			   
				   foreach($resul->blogField as $res) {

					 if ($res['field'] == $price) { 
					   if($res['dop']) {
					      $current = $rates[$res['dop']]['value'];
					   }else{
						   $current = 1;
					   }
				          $price_val = $res['value'];
				     }
				   }
				  //Проверяем, если покупка была со скидкой

                 if(str_replace(' ','',$cars[$resul->id]['price']) != $price_val) {
					 if(str_replace(' ','',$cars[$resul->id]['price']) == $resul->discount*$current) {
					     $price_val = $resul->discount;
					  }else{
						  exit('Ошибка, сообщите администрации');
					  }
				 }


				  $blog_id .= $resul->id.'|'.$cars[$resul->id]['count'].'|'.$price_val.',';
		          $sum[] = $price_val*$cars[$resul->id]['count'];
				  
				  //Вынесли из условия ниже, из-за отправки на почту всех данных
				  $product = Blog::findOne($resul->id);
			
				  if(isset($resul->count)) {
					  $count_pro = $resul->count-$cars[$resul->id]['count'];
					  //$product = Blog::findOne($resul->id);
					  $blogarr[] = array('id' => $resul->id, 'count' => $count_pro);
					  /*$product->count = $count_pro;
                      $product->update(false);*/
				  }
				  //передаем данные товара в сообщении e-mail 
				  $product_val[] = array('array'=> $product, 'price' => $price_val, 'count' => $cars[$resul->id]['count']);
		   }
		   
		   $price_one = array_sum($sum);
		   if(!Yii::$app->user->id || Yii::$app->user->identity->balance < $price_one || (Yii::$app->user->identity->balance >= $price_one && $car->private_payment == '1')) {
		      $pay = 0;
		   }else{
			  $pay = 1;
		   }
		   
		   
		//Запрещае проводить сделку не автоиизированному юзеру
		   if(!isset(Yii::$app->user->id)) {
			   list($x1,$x2) = explode('.',strrev($_SERVER['HTTP_HOST']));
                    $xdomain = strrev($x1.'.'.$x2);
                    $patch_url = PROTOCOL.$xdomain;
			   $errpay = '<div class=" col-md-12 alert alert-danger">Для завершения покупки Вы должны авторизироваться на сайте "'.Yii::$app->caches->setting()['site_name'].'" <a data-pjax="0" target="_blank" href="'.$patch_url.'/user">'.$xdomain.'</a></div>';
		       $save = false;
			   return $this->controller->render('cart', compact('errpay','shop','save', 'car', 'cars', 'car_note', 'car_id',  'price', 'comment', 'vote', 'rates', 'count_art', 'blogs', 'blogarr'));
		   }
		   //Возвращаем ошибку, если не хватает средств при включеном "Гарант-Сервисе"
		   if((isset(Yii::$app->user->id) && Yii::$app->user->identity->balance < $price_one && $car->private_payment == '0') || (!Yii::$app->user->id && $car->private_payment == '0')) {
			   if(isset(Yii::$app->user->id) && Yii::$app->user->identity->balance < $price_one && $car->private_payment == '0') { 
			       $errpay = '<div class=" col-md-12 alert alert-danger">Внимание! У Вас не хватает средств для завершении покупки, пополните пожалуйста свой баланс и продолжите покупку.</div>';
			   }
			   
			    if(!Yii::$app->user->id && $car->private_payment == '0') {
					list($x1,$x2) = explode('.',strrev($_SERVER['HTTP_HOST']));
                    $xdomain = strrev($x1.'.'.$x2);
                    $patch_url = PROTOCOL.$xdomain;
					
					$errpay = '<div class=" col-md-12 alert alert-danger">Для завершения покупки Вы должны авторизироваться на сайте "'.Yii::$app->caches->setting()['site_name'].'" <a data-pjax="0" target="_blank" href="'.$patch_url.'/user">'.$xdomain.'</a></div>';
				}
				$save = false;
			 
			   return $this->controller->render('cart', compact('errpay','shop','save', 'car', 'cars', 'car_note', 'car_id',  'price', 'comment', 'vote', 'rates', 'count_art', 'blogs', 'blogarr'));
		   }
		   
		   
		   
		   //перенос обновления количества товара
		   if(isset($blogarr)) {
		     foreach($blogarr as $res) {
			    $product = Blog::findOne($res['id']);
			    $product->count = $res['count'];
                $product->update(false);
		     }
		   }
		   
		   //Если есть платная доставка
			if($shop->field->pay_delivery) {
				$delivery = $shop->field->pay_delivery;
				$blog_id .= '&'.$shop->field->pay_delivery;
			}else{
				$delivery = 0;
			}
          $cart = new Car();
		  $cart->data_add = date('Y-m-d H:i:s');
		  $cart->data_end = date('Y-m-d H:i:s', strtotime(' + 30 day'));
		  
		  $cart->status = 0;
		  $cart->id_product = $blog_id;
		  $cart->pay = $pay;
		  $cart->price = $price_one+$delivery;
		  $cart->user_id = $shop->user_id;
		  $cart->shop_id = $shop->id;
		  $cart->buyer = Yii::$app->user->id;	
		  $cart->save();
		  
		  //Добавляем примечания
		  foreach ($cars as $key => $car_nots) {
			  if(isset($car_note[$key])) {
			    $carnote = new CarNote();
			    $carnote->id_car = $cart->id;
			    $carnote->id_product = $key;
			    $carnote->note = $car_note[$key];
			    $carnote->save();
			  }
		  }
		 
	
		  
		  
		  
		  $car->car_id = $cart->id;
		  $car->save();
		  $user2 = $this->findUser($shop->user_id); 
		  if($pay == 1) {
			 $user = $this->findUser(Yii::$app->user->id); 
			 if($user->balance < $price_one) {return 'Ошибка';}
			 $user->balance =  $user->balance-$price_one-$delivery;
			 $user->balance_temp = $user->balance_temp+$price_one+$delivery;
			 $user->update(false);
			 
			 
			 $user2 = $this->findUser($shop->user_id); 
			 $user2->balance_temp = $user2->balance_temp+$price_one+$delivery;
			 $user2->update(false);
    
			 
			 foreach($rates as $rat) {
				 if($rat['def'] == 1) {$rate = $rat;}
			 }
			 
		  $pays = new Payment();
		  $pricess = $price_one+$delivery;
          $pays->price = -$pricess;
		  $pays->currency  = $rate['code'];
		  $pays->user_id  = Yii::$app->user->id;
		  $pays->system  = 'Личный счет';
		  $pays->blog_id  = '';
		  $pays->services  = 'car';
		  $pays->status  = '1';
		  $pays->time  = date('Y-m-d H:i:s');
		  $pays->save(false); 
			
		  }
		  
		  
		  Yii::$app->response->cookies->remove('car');
          Yii::$app->response->cookies->remove('arr_note');
		  //Отправка уведомления продавцу
		  
	      $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		  $price_sum = $price_one+$delivery;
		  //Сообщение продавцу
		  Yii::$app->functionMail->Seller($user2->username, $shop->name, $product_val, $buyer_mail, $price_sum, $pay, $patch_url, $shop->field->pay_delivery, $shop->field->delivery, $user2->email, $car_note);
		  
		  //Сообщение покупателю
		  Yii::$app->functionMail->Bayer($shop->name, $product_val, $buyer_mail, $price_sum, $pay, $patch_url, $shop->field->pay_delivery, $shop->field->delivery, $car_note);
		  
		  $save = true;
        }



	
	
	
	
	
	
	return $this->controller->render('cart', compact('shop','save', 'car', 'cars', 'car_note', 'car_id',  'price', 'comment', 'vote', 'rates', 'count_art', 'blogs'));
		
	}
	

	
	protected function findModel($url)
    {
        if (($shop = Shop::find()->with('field')->andWhere(['domen'=>$url, 'status' => 1, 'active' => 1])->one()) !== null) {
			
			
	//Получаем мета теги
	$arr['meta'] = Yii::$app->userFunctions->metaOne($shop->category, $shop->region, $shop->name, 'shop');


     //Достаем количество комментариев
	$query = ShopComment::find()->andWhere(['blog_id' => $shop->id, 'status' => 1])->orderBy(['id' => SORT_DESC]);  
	$arr['count_com'] = $query->count();

	   //Манипуляции с графиком
	     $shop->grafik = array();
	     $grafik_post = explode(' | ',$shop->field->grafik);
		  foreach($grafik_post as $key => $res) {
			  if ($res == 'False') {
			       $shop->grafik[$key]['vih'] = 1;
			  }else{
				   $obed = explode(' && ', $res); //Обед

				    if(isset($obed[1]) && $obed[1] != 'obed_none') {
                        $obed_arr =  explode(' - ',$obed[1]);
						$shop->grafik[$key]['obed'] = $obed_arr[0].' - '.$obed_arr[1];
					}
					$days = explode(' && ', $res);
					$days = explode(' - ', $days[0]); 
					if(isset($days[1])) {
				       $shop->grafik[$key]['time'] = $days[0].' - '.$days[1];
				   }
			  }
		  }

 //Проверяем, голосовал ли юзер  
	if(@unserialize(Yii::$app->request->cookies['votes']->value)) {
    if(in_array($id, unserialize(Yii::$app->request->cookies['votes']))) {	
	         $vote = true;
	      }else{
		    $vote = true;
	      }
     }elseif(isset(Yii::$app->request->cookies['votes']) && Yii::$app->request->cookies['votes']->value == $shop->id){
       $vote = true;
     }else{
	   $vote = false;
     }
	$arr['vote'] = $vote;
	$arr['rates'] = Yii::$app->caches->rates();
	$arr['notepad'] = Yii::$app->userFunctions->notepadArr();

    $query_art = Article::find()->andWhere(['user_id' => $shop->user_id, 'status' => 1]);
	$arr['count_art'] = $query_art->count();
	$arr['model'] =	$shop;
            return $arr;
          
        }

        throw new NotFoundHttpException('Такого магазина не существует');
    }
	
	
	
	
	protected function findUser($id)
    {
		if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такого юзера нет.');
	}
}