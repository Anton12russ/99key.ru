<?php
 
namespace common\components;
use yii\helpers\Url;
use yii\base\Component;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii;
use common\models\Mail;
use common\models\User;
use common\models\MailToken;
class FunctionMail extends Component { 	

//-----------------------Доработка--------------------------------//



    //Пополнение баланса
    public function balance_admin($sum, $user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(8);
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user_email, $mail->text);
		 $text = str_replace('{sum}', $sum, $text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}

    //Новое объявление
    public function board_admin($url, $user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(9);
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user_email, $mail->text);
		 $text = str_replace('{link}', $patch_url.$url, $text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}

    //Новый магазин
    public function shop_admin($url, $user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(10);
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user_email, $mail->text);
		 $text = str_replace('{link}', $patch_url.$url, $text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}


    //Новая статья
    public function article_admin($url, $user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(11);
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user_email, $mail->text);
		 $text = str_replace('{link}', $patch_url.$url, $text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}

    //Новый запрос в поддержку
    public function support_admin($user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(12);
	     $text = str_replace('{author}', $user_email, $mail->text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}




    //Оповещение о спорах
    public function dispute_admin($user_email, $admin_email) 
	{	
		 $mail = Mail::findOne(13);
	     $text = str_replace('{author}', $user_email, $mail->text);
         $email_to = $admin_email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}


    //Оповещение пользователя о новом сообщении
    public function chat_email($user, $author_name) 
	{	
		 $mail = Mail::findOne(14);
	     $text = str_replace('{author}', $user->username, $mail->text);
		 $text = str_replace('{user}', $author_name, $text); 
         $email_to = $user->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}






    //Оповещение пользователя о новом комментарии к объявлению
    public function comment_board($user, $link) 
	{	
		 $mail = Mail::findOne(15);
	     $text = str_replace('{author}', $user->username, $mail->text);
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $href = '<a href="'.$patch_url.$link.'" target="_blank">"'.$patch_url.$link.'"</a>';
		 
		 
		 $text = str_replace('{link}', $href, $text); 
         $email_to = $user->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}


    //Оповещение пользователя о новом комментарии к статье
    public function comment_article($user, $link) 
	{	
		 $mail = Mail::findOne(16);
	     $text = str_replace('{author}', $user->username, $mail->text);
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $href = '<a href="'.$patch_url.$link.'" target="_blank">"'.$patch_url.$link.'"</a>';
		 
		 
		 $text = str_replace('{link}', $href, $text); 
         $email_to = $user->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}


    //Оповещение пользователя о новом комментарии к статье
    public function comment_shop($user, $link) 
	{	
		 $mail = Mail::findOne(17);
	     $text = str_replace('{author}', $user->username, $mail->text);
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $href = '<a href="'.$patch_url.$link.'" target="_blank">"'.$patch_url.$link.'"</a>';
		 
		 
		 $text = str_replace('{link}', $href, $text); 
         $email_to = $user->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}

	
	
	
	
	
	
	
	
	
	
    //Оповещение продавца о предзаказе
    public function order_pred($author, $link, $colvo, $name, $email, $phone, $primechanie) 
	{	
		 $mail = Mail::findOne(18);
		 if(!$name) {
			 $name = 'Не указано';
		 }
		  if(!$phone) {
			 $phone = 'Не указан';
		  }
		  
		 if(!$primechanie) {
			 $primechanie = 'Не указано';
		  }
		 
		 
	     $text = str_replace('{author}', $author->username, $mail->text);

		 $text = str_replace('{colvo}',$colvo, $text);
		 $text = str_replace('{name}',$name, $text);
		 $text = str_replace('{email}',$email, $text);
		 $text = str_replace('{phone}',$phone, $text);
		 $text = str_replace('{text}',$primechanie, $text);
		 
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $href = '<a href="'.$patch_url.$link.'" target="_blank">"'.$patch_url.$link.'"</a>';
		 $text = str_replace('{link}',$href, $text);
         $email_to = $author->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}






    //Оповещение покупателя о поступления товара на склад
    public function order_send($order, $tovar, $colvo, $link, $poddomen) 
	{	
		 $mail = Mail::findOne(19);
		 if(!$order->name) {
			 $order->name = 'Покупатель';
		 }
	     $text = str_replace('{order_name}', $order->name, $mail->text);

		 $text = str_replace('{colvo}',$colvo, $text);
		 $text = str_replace('{tovar}',$tovar, $text);


		 $patch_url = PROTOCOL.$poddomen.'.'.$_SERVER['HTTP_HOST'];
		 $href = '<a href="'.$patch_url.$link.'" target="_blank">"'.$tovar.'"</a>';
		 $text = str_replace('{link}',$href, $text);
         $email_to = $order->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}










	
	
	
	
	
	
	
    //Запись Токена и отправка e-mail (Объявление)
    public function board($blog_id, $user, $link, $title) 
	{	
		 $mail = Mail::findOne(1);
	     
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user->username, $mail->text);
	     $link = $patch_url.$link;
	     $href = '<a href="'.$link.'" target="_blank">"'.$title.'"</a>';
	     $text = str_replace('{title_link}', $href, $text);
	  //Записываем токен
	     $token = new MailToken();
	     $token->token = Yii::$app->security->generateRandomString();
	     $token->blog_id = $blog_id;
	     $token->user_id = $user->id;
	     $token->data = date('Y-m-d H:i:s');
	     $token->save();

		 $url = Url::to(['/token/board', 'token_id'=>$token->id, 'token'=>$token->token, 'general' => 'true']);
		 $token_url = '<a target="_blank"  href="'.$patch_url.$url.'">'.$patch_url.$url.'</a>';
		 $text = str_replace('{link_update}', $token_url, $text);
         $email_to = $user->email;
         $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	
    //Shop
    public function shop($shop_id, $user, $link, $title) 
	{	
		 $mail = Mail::findOne(2);
	     
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user->username, $mail->text);
	     $link = $patch_url.$link;
	     $href = '<a href="'.$link.'" target="_blank">"'.$title.'"</a>';
	     $text = str_replace('{title_link}', $href, $text);
         $user_link = '<a href="'.$patch_url.'/user'.'" target="_blank"> В личный кабинет </a>';
		 $text = str_replace('{user_link}', $user_link, $text);
         $email_to = $user->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}	
	
	//CarBayer
    public function Carbayer($car_id, $name, $email, $status_id) 
	{	
	     $arr_status = array('В ожидании', 'Отправлен', 'Доставлен', 'Отменен', 'Завершен');
	     $status = $arr_status[$status_id];
		 $mail = Mail::findOne(4);
	     
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{bayer}',$name, $mail->text);
		 $text = str_replace('{id_car}',$car_id, $text);
		 $text = str_replace('{status}', $status, $text);
         $email_to = $email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}

 

	//CarShop
    public function Carshop($car_id, $name, $email, $status_id) 
	{	
	     $arr_status = array('В ожидании', 'Отправлен', 'Доставлен', 'Отменен', 'Завершен');
	     $status = $arr_status[$status_id];
		 $mail = Mail::findOne(5);
	     
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}',$name, $mail->text);
		 $text = str_replace('{id_car}',$car_id, $text);
		 $text = str_replace('{status}', $status, $text);
         $email_to = $email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}	
	
	
	
	
	
	
	
	
	
	//Отправка сообщения на почту продавцу товара (оповещение о покупке товара).
	public function Seller($sellername, $shopname, $product, $buyer, $sum, $pay, $domen, $pay_price, $delivery, $emailauthor, $car_note) 
	{	
	if($pay == 1) {
		$pay = 'Оплачено через "Гарант сервис"';
	}else{
		$pay = 'Не оплачено. Ожидает частного оформления заказа';
	}
	   //echo '<pre>';print_r($product); echo '</pre>';
	   $mail = Mail::findOne(6);
       $text = str_replace('{author}',$sellername, $mail->text);
	   $text = str_replace('{shop}',$shopname, $text);


	  $pay_info = '';

	  $pay_info .= '
           <strong>Имя:</strong>  '.$buyer['name'].'<br>
		   <strong>Фамилия:</strong>  '.$buyer['family'].'<br>  
		   <strong>E-mail:</strong>  '.$buyer['email'].'<br>
		   <strong>Телефон:</strong>  '.$buyer['phone'].'<br>
		 
	  ';
	  if(isset($buyer['country']) && $buyer['country']) {
	  $pay_info .= ' 
	       <strong>Страна:</strong>  '.$buyer['country'].'<br>
		   <strong>Регион:</strong>  '.$buyer['region'].'<br>
		   <strong>Город:</strong>  '.$buyer['city'].'<br>
		   <strong>Адрес:</strong>  '.$buyer['address'].'<br>
		   <strong>Индекс:</strong>  '.$buyer['postcode'].'<br>
		   ';
	  }elseif(!isset($buyer['country'])) {
		  $pay_info .= '<strong>Доставка:</strong>  Самовывоз<br> ';
	  }
	   
	   $text = str_replace('{pay}', $pay_info, $text);
	   
	   
	   foreach(Yii::$app->caches->rates() as $rates) {
		   if($rates['def'] == 1) {
			   $rate = $rates['name'];
		   }
	   }
	   

	   $product_info = '<table style=" padding: 5px; border-collapse: collapse; "><tbody>';
	   foreach($product as $tovar) {
	   $url = Url::to(['/boardone', 'id'=> $tovar['array']['id'], 'general' => 'true']);

	   $product_info .= '
	    <tr style="border: 1px solid #ccc;"><td style=" padding: 5px;">
           <strong>ID:</strong>  '.$tovar['array']['id'].'<br>
		   <strong>Название товара:</strong>  '.$tovar['array']['title'].'<br>  
		   <strong>Цена:</strong>   '.$tovar['price'].' '.$rate.'<br>  
		   <strong>Количество:</strong>  '.$tovar['count'].' шт.<br>  
		   <strong>Ссылка на товар:</strong>  <a target="_blank" href="'.$domen.$url.'">Ссылка на товар<a><br>';
		   if(isset($car_note[$tovar['array']['id']])) {$product_info .= '<strong>Примечание:</strong> '.$car_note[$tovar['array']['id']].'<br>';}
		   $product_info .= '<br>
		</tr></td>
	  ';
	   }
	   $product_info .= '</tbody></table>';
	   
	   
	   
	   $text = str_replace('{Product}', $product_info, $text);
	   $text = str_replace('{payment}', $pay, $text);
	   $text = str_replace('{delivery}', $delivery. ' '.$pay_price.' ('.$rate.')', $text);
	   $text = str_replace('{sum}', $sum.' ('.$rate.')', $text);
	   
         $email_to = $emailauthor;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	
	
	
	
	
	//Отправка сообщения на почту покупателю.
	public function Bayer($shopname, $product, $buyer, $sum, $pay, $domen, $pay_price, $delivery, $car_note) 
	{	
	if($pay == 1) {
		$pay = 'Оплачено через "Гарант сервис"';
	}else{
		$pay = 'Не оплачено. Продавец свяжется с вами чтобы уточнить удобный способ оплаты товара.';
	}
	   //echo '<pre>';print_r($product); echo '</pre>';
	   $mail = Mail::findOne(7);
       $text = str_replace('{bayer}',$buyer['name'], $mail->text);
	   $text = str_replace('{shop}',$shopname, $text);

      $pay_info = '';

	  $pay_info .= '
           <strong>Имя:</strong>  '.$buyer['name'].'<br>
		   <strong>Фамилия:</strong>  '.$buyer['family'].'<br>  
		   <strong>E-mail:</strong>  '.$buyer['email'].'<br>
		   <strong>Телефон:</strong>  '.$buyer['phone'].'<br>
		 
	  ';
	  if(isset($buyer['country']) && $buyer['country']) {
	  $pay_info .= ' 
	    <strong>Страна:</strong>  '.$buyer['country'].'<br>
		   <strong>Регион:</strong>  '.$buyer['region'].'<br>
		   <strong>Город:</strong>  '.$buyer['city'].'<br>
		   <strong>Адрес:</strong>  '.$buyer['address'].'<br>
		   <strong>Индекс:</strong>  '.$buyer['postcode'].'<br>
		   ';
	   }elseif(!isset($buyer['country'])) {
		  $pay_info .= '<strong>Доставка:</strong>  Самовывоз<br> ';
	  }
	   $text = str_replace('{pay}', $pay_info, $text);
	   
	   
	   foreach(Yii::$app->caches->rates() as $rates) {
		   if($rates['def'] == 1) {
			   $rate = $rates['name'];
		   }
	   }
	   

	   $product_info = '<table style=" padding: 5px; border-collapse: collapse; "><tbody>';
	   foreach($product as $tovar) {
	   $url = Url::to(['/boardone', 'id'=> $tovar['array']['id'], 'general' => 'true']);

	   $product_info .= '
	    <tr style="border: 1px solid #ccc;"><td style=" padding: 5px;">
           <strong>ID:</strong>  '.$tovar['array']['id'].'<br>
		   <strong>Название товара:</strong>  '.$tovar['array']['title'].'<br>  
		   <strong>Цена:</strong>   '.$tovar['price'].' '.$rate.'<br>  
		   <strong>Количество:</strong>  '.$tovar['count'].' шт.<br>  
		    <strong>Ссылка на товар:</strong>  <a target="_blank" href="'.$domen.$url.'">Ссылка на товар<a><br>';
		   if(isset($car_note[$tovar['array']['id']])) {$product_info .= '<strong>Примечание:</strong> '.$car_note[$tovar['array']['id']].'<br>';}
		   $product_info .= '<br>
		</tr></td>
	  ';
	   }
	   $product_info .= '</tbody></table>';
	   
	   
	   
	   $text = str_replace('{Product}', $product_info, $text);
	   $text = str_replace('{payment}', $pay, $text);
	   $text = str_replace('{delivery}', $delivery. ' '.$pay_price.' ('.$rate.')', $text);
	   $text = str_replace('{sum}', $sum.' ('.$rate.')', $text);
	   
         $email_to = $buyer['email'];
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	
	    //Shop personal
    public function Personal($author_id, $user_name, $user_email, $user_text, $domen) 
	{	
         $user = User::findOne($author_id);
		 $mail = Mail::findOne(3);

	     $text = str_replace('{author}', $user->username, $mail->text);
         $text = str_replace('{shop_domen}', $domen, $text);
		 $text = str_replace('{user_name}', $user_name, $text);
		 $text = str_replace('{user_email}', $user_email, $text);		 
 		 $text = str_replace('{user_text}', $user_text, $text);
         $email_to = $user->email;
		 $subject = $mail->name. ' '. $domen;
        Yii::$app->functionMail->mailEnd($user->email, $subject, $text);
	}	
	
	
	
	//Функция отправки E-mail Администратора
	public function emailAdmin() 
	{
		if(Yii::$app->caches->setting()['email_smtp'] == 1) {
			return preg_replace('/\s+/', '',explode("\n",Yii::$app->caches->setting()['smtp'])[2]);
		}else{
			return Yii::$app->caches->setting()['email'];
		}	
	}
	
	
	
	
	
	
	public function betdelmail ($betmail, $model) {
		
		$mail = Mail::findOne(20);

	     $text = str_replace('{author}', $betmail->author->username, $mail->text);
		 $text = str_replace('{title}', $model->title, $text);

         $email_to = $betmail->author->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
		
	public function betdelmailreserv ($betmail, $model) {
		
		$mail = Mail::findOne(21);

	     $text = str_replace('{author}', $betmail->author->username, $mail->text);
		 $text = str_replace('{title}', $model->title, $text);

         $email_to = $betmail->author->email;
		 $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
		public function reservdel($model) {
		
		$mail = Mail::findOne(22);


		 $text = str_replace('{title}', $model->title, $mail->text);

         $email_to = $model->author->email;
		 $subject = $mail->name;

         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	public function reservdelauthor($model) {
		
		 $mail = Mail::findOne(23);

         $text = str_replace('{author}', $model->reservuser->username, $mail->text);
		 $text = str_replace('{title}', $model->title, $text);

         $email_to = $model->reservuser->email;
		 $subject = $mail->name;

         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	
	public function reservdellot($model) {
		
		 $mail = Mail::findOne(24);


		 $text = str_replace('{title}', $model->title, $mail->text);

         $email_to = $model->reservuser->email;
		 $subject = $mail->name;
		 

         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	public function resevinfo($model, $info, $url) {
		
		 $mail = Mail::findOne(25);

         $text = str_replace('{info}', $info, $mail->text);
		 $text = str_replace('{title}', $model->title, $text);

         $email_to = $model->reservuser->email;
	
		 $subject = $mail->name;
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $text = str_replace('{link}', $patch_url.$url, $text);
		 
		 
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}	
	
	
	
	
	
	
	
	
	
	
		public function lotwinning($email_to, $url, $info, $username, $model) {
		
		 $mail = Mail::findOne(26);

         $text = str_replace('{info}', $info, $mail->text);
		 $text = str_replace('{title}', $model->title, $text);
		 
         $text = str_replace('{username}', $username, $text);
  
	
		 $subject = $mail->name;
		 
		 $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
		 $text = str_replace('{link}', $patch_url.$url, $text);
		 
		 
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	
	
	
	
	
	
	
	public function reservlotauthor($model) {
		
		 $mail = Mail::findOne(27);


		 $text = str_replace('{title}', $model->title, $mail->text);
         $text = str_replace('{username}', $model->reservuser->username, $text);
         $text = str_replace('{useremail}', $model->reservuser->email, $text);
		 $text = str_replace('{link_profil}', '<a href="https://1tu.ru/users?id='.$model->reservuser->id.'">https://1tu.ru/users?id='.$model->reservuser->id.'</a>', $text);
		 $subject = $mail->name;
	     $email_to = $model->author['email'];
		
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}	
	
	
	
	
	

    public function auction($blog_id, $user, $link, $title) 
	{	
		 $mail = Mail::findOne(28);
	     
	     $patch_url = PROTOCOL.$_SERVER['HTTP_HOST'];
	     $text = str_replace('{author}', $user->username, $mail->text);
		 
	     $link = $patch_url.$link;
	     $href = '<a href="'.$link.'" target="_blank">"'.$title.'"</a>';
		 
	     $text = str_replace('{title_link}', $href, $text);
		 
	     $hrefupdate = '<a href="'.$patch_url.'/update?id='.$blog_id.'" target="_blank">Редактировать</a>';
	     $text = str_replace('{link_update}', $hrefupdate, $text);
	  	
	 
         $email_to = $user->email;
         $subject = $mail->name;
         Yii::$app->functionMail->mailEnd($email_to, $subject, $text);
	}
	
	
	 public function perebita($authorname, $link, $title, $email_to) 
	{	
	  $mail = Mail::findOne(29);
	  $text = str_replace('{author}', $authorname, $mail->text);
	  $text = str_replace('{link}', $link, $text);
	  $text = str_replace('{title}', $title, $text);

	  $email_to = $email_to;
      $subject = $mail->name;
      Yii::$app->functionMail->mailEnd($email_to, $subject, $text); 
	}
	
	
	
	//Функция отправки E-mail
	public function mailEnd($email_to, $subject, $text) 
	{
     Yii::$app->mailer->compose()
    ->setFrom('admin@1tu.ru')
    ->setTo($email_to)
    ->setSubject($subject)
    ->setHtmlBody($text)
    ->send();	
	}
	

	//Функция отправки E-mail
	public function mailMail($email_to, $subject, $text) 
	{
  $to = "abc@gmail.com";
  $subject = "Robot - Робот";
  $message = "Hello World!<br /><i>Это письмо отправлено <b>роботом</b>
  и отвечать на него не нужно!</i>";
  $headers = "From: MyRusakov.ru <abc@gmail.com>\r\nContent-type: text/html; charset=windows-1251 \r\n";
  mail ($to, $subject, $message, $headers);

	}
}