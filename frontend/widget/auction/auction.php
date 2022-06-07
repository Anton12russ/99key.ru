<?php

namespace frontend\widget\auction;
use yii\base\Widget;
use common\models\BlogAuction;
use common\models\Rates;
use common\models\Bet;
use common\models\BlogField;
use yii\widgets\ActiveForm;
use Yii;
use yii\helpers\Url;
class Auction extends Widget
{
    public $price;
	public $blog;
    public $blog_id;
    public $auction_act;
    public function run() {
        $blog = $this->blog;
		$auction = BlogAuction::find()->andWhere(['blog_id'=>$this->blog_id])->One();

		$bets = Bet::find()->andWhere(['blog_id'=>$this->blog_id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
		$bets_old = $bets;
		$rates = Rates::findOne($auction->rates);	
		$model = new Bet();
        $save_ok = false;
		$save_del = false; 
			  
			  
		 if ($model->load(Yii::$app->request->post())) {
			  $model->blog_id = $this->blog_id;
			 if ($model->validate()) {
		
		
		   if($model->price > 0) {
	          $model->user_id = Yii::$app->user->id;
			  $model->blog_id = $this->blog_id;
              $model->currency = $auction->rates;
			  $model->date_add = date('Y-m-d H:i:s');
			  $model->save();
			  $field = BlogField::find()->where(['message' => $this->blog_id, 'field' => '481'])->One();
			  $field->value = $model->price;
			  $field->update();
			  
		$save_ok = true;
		$auction = BlogAuction::find()->andWhere(['blog_id'=>$this->blog_id])->One();
		$bets = Bet::find()->andWhere(['blog_id'=>$this->blog_id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
		$rates = Rates::findOne($auction->rates);
		
				//Емэйл сообщение
		if($auction &&  isset($bets_old[0])) {
		  $email_to = $bets_old[0]->author['email'];
		  $subject = 'Ставка перебита';
		  $url = Url::to(['blog/one', 'region'=>$blog->regions['url'], 'category'=>$blog->categorys['url'], 'url'=>$blog->url, 'id'=>$blog->id]);
		  $text = 'Уведомляем Вас, что Ваша ставка на объявление "<a href="https://1tu.ru'.$url.'">'.$blog->title.'"</a> была перебита другим участником аукциона.';
          $link = 'https://1tu.ru'.$url;	 
		  $title = $blog->title;
		  
		 
		  
		  if($bets_old[0]->author['id'] != Yii::$app->user->id) {
			  Yii::$app->functionMail->perebita($bets_old[0]->author['username'], $link, $title, $email_to);
			 $text = 'Уведомляем Вас, что Ваша ставка на объявление "'.$blog->title.'" была перебита другим участником аукциона.';
		     Yii::$app->userFunctions3->Push($bets_old[0]->author['id'], $text, 'https://1tu.ru'.$url);
		  }
        }
		
		
		   }else{
			   $bet = Bet::find()->andWhere(['blog_id'=>$this->blog_id, 'user_id' => Yii::$app->user->id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
               $bet[0]->delete();


              $bet = Bet::find()->andWhere(['blog_id'=>$this->blog_id, 'user_id' => Yii::$app->user->id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
              
              $bet_del = Bet::find()->andWhere(['blog_id'=>$this->blog_id])->orderBy(['id' => SORT_DESC])->One();
              $field = BlogField::find()->where(['message' => $this->blog_id, 'field' => '481'])->One();			 
			 if($bet_del) {
				 if($auction->price_add > $bet_del->price) {
					 $field->value = $auction->price_add; 
				 }else{
			        $field->value = $bet_del->price; 
				 }
              }else{
				$field->value = $auction->price_add; 
			  }
		$field->update(); 
		$auction = BlogAuction::find()->andWhere(['blog_id'=>$this->blog_id])->One();
		$bets = Bet::find()->andWhere(['blog_id'=>$this->blog_id])->limit(1)->orderBy(['id' => SORT_DESC])->All();
		$rates = Rates::findOne($auction->rates);	
        $save_del = true;
		
	

		   }
		 }
		}
		
		
		
		return $this->render('views', [
          'auction' => $auction,
		  'model' => $model,
		  'bets' => $bets,
	      'save_ok' => $save_ok,
		  'save_del' => $save_del,
		  'price' => $this->price,
		  'rates' => $rates, 
		  'blog' => $blog, 
		  'blog_id' => $this->blog_id,
		  'auction_act' => $this->auction_act,
        ]);
	
	}
		
}

?>
