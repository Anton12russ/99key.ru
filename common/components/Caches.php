<?php
 
namespace common\components;

use yii\base\Component;
use yii\helpers\Html;
use common\models\Settings;
use common\models\Category;
use common\models\Region;
use common\models\RegionCase;
use common\models\Field;
use common\models\Rates;
use common\models\Block;
use common\models\CounterCat;
use common\models\ArticleCat;
use common\models\BlogServices;
use common\models\SeoModule;
use common\models\StaticPage;
use common\models\RegionCoord;
use yii;

class Caches extends Component { 	
//Настройки
    public function Setting() 
	{	
	  $model = Yii::$app->cache->get('settings');
      if(!$model)
	  {
              $models = Settings::find()->all();
			  $model = [];
              foreach ($models as $res) {
                   $model[$res['name']] = $res['value'];
              }
              Yii::$app->cache->set('settings', $model, 300000);
              }
        return $model;
    }

//Регионы
	public function Category() 
	{	 
	 $model = Yii::$app->cache->get('category');
      if(!$model)
	  {
              $models = Category::find()->orderBy('sort')->all();
			  $model = [];
              foreach ($models as $row) {
                 $model[$row['id']] = array('id' => $row['id'], 'url' => $row['url'], 'name' => $row['name'], 'parent' => $row['parent'], 'image' => $row['image'], 'sort' => $row['sort'],'text' => $row['description'],'relative' => $row['relative']);
              }
              Yii::$app->cache->set('category', $model, 300000);
              }
        return $model;
    }
	
	
	//Коордигнаты регионов
	public function Coord() 
	{	
	 $model = Yii::$app->cache->get('coord');
      if(!$model)
	  {
              $models = RegionCoord::find()->all();
			  $model = [];
              foreach ($models as $row) {
                  $model[$row['region_id']] = $row;
              }
              Yii::$app->cache->set('coord', $model, 300000);
              }
        return $model;
    }
	
	//Категории с родителями для выборки объявлений
     public function CatRelative() 
	{	
    $model = Yii::$app->cache->get('сatRelative');
      if(!$model)
	  {
            $models = category::find()->Select(['id', 'relative'])->all();
			   $model = [];
              foreach ($models as $row) {
                   $model[$row['id']] = $row['relative'];
              }
              Yii::$app->cache->set('сatRelative', $model, 300000);
              }
	         
        return $model;
    }
	
	
//Регионы
	public function Region() 
	{	 
	 $model = Yii::$app->cache->get('region');
      if(!$model)
	  {
              $models = region::find()->orderBy('sort')->all();
			  $model = [];
              foreach ($models as $row) {
                  $model[$row['id']] = array('id' => $row['id'], 'url' => $row['url'], 'name' => $row['name'], 'parent' => $row['parent'], 'sort' => $row['sort'] );
              }
              Yii::$app->cache->set('region', $model, 300000);
              }
        return $model;
    }
	
		//Регионы с родителями для выборки объявлений
     public function RegRelative() 
	{	
 $model = Yii::$app->cache->get('regRelative');
      if(!$model)
	  {
            $models = region::find()->Select(['id', 'relative'])->all();
			   $model = [];
              foreach ($models as $row) {
                   $model[$row['id']] = $row['relative'];
              }
              Yii::$app->cache->set('regRelative', $model, 3600);
              }
	         
        return $model;
    }
	

	
		//Склонение для регионов
	public function RegionCase() 
	{	
	 $model = Yii::$app->cache->get('regCase');
      if(!$model)
	  {
              $models = regionCase::find()->all();
			  $model = [];
              foreach ($models as $row) {
                  $model[$row['id_region']] = $row;
              }
              Yii::$app->cache->set('regCase', $model, 300000);
              }
        return $model;
    }
	




	//Поля фильтра Field
	public function Field() 
	{	
	 $model = Yii::$app->cache->get('field');
      if(!$model)
	  {
              $models = field::find()->orderBy(['sort' => SORT_DESC])->all();
			  $model = [];
              foreach ($models as $row) {
                  $model[$row['id']] = $row;
              }
              Yii::$app->cache->set('field', $model, 300000);
              }
       
        return $model;
    }
	
	
	
			//Счетчик объявлений в рубриках
	public function Rates() 
	{	
	  $model = Yii::$app->cache->get('rates');
      if(!$model)
	  {
              $models = Rates::find()->all();
			  $model = [];
              foreach ($models as $row) {
                  $model[$row['id']] = array('code' => $row['charcode'], 'name' => $row['name'], 'text' => $row['text'], 'value' => $row['value'], 'def' => $row['def']);
              }
			
              Yii::$app->cache->set('rates', $model, 300000);
              }
        return $model;
    }
	
	
				//Блоки
	public function Block() 
	{	
	  $model = Yii::$app->cache->get('block');
      if(!$model)
	  {
              $models = Block::find()->OrderBy('sort')->all();
			  $model = array();
              foreach ($models as $row) {
                  $model[$row['id']] = array('name' => $row['name'], 'text' => $row['text'], 'position' => $row['position'], 'status' => $row['status'], 'date_del' => $row['date_del'], 'action' => $row['action'], 'category' => $row['category'], 'region' => $row['region'], 'header_ok' => $row['header_ok'], 'registr' => $row['registr']);
              }
			
              Yii::$app->cache->set('block', $model, 300000);
              }
        return $model;
    }
	
	
					//Платные
	public function Services() 
	{	
	  $model = Yii::$app->cache->get('services');
      if(!$model)
	  {
              $model = BlogServices::find()->Where(['>', 'date_end', date('Y-m-d H:i:s')])->asArray()->orderBy('date_add')->all();
              Yii::$app->cache->set('services', $model, 300000);
              }
        return $model;
    }
	
	
	
	
						//Платные
	public function Artcat() 
	{	
	  $model = Yii::$app->cache->get('artcat');
      if(!$model)
	  {
              $models = ArticleCat::find()->OrderBy('sort')->all();
                   $model = array();
              foreach ($models as $row) {
                  $model[$row['id']] = $row;
              }
              Yii::$app->cache->set('artcat', $model, 300000); 
      }

        return $model;
    }
	
	
	
	
	//Сео
	public function Seomodule() 
	{	
	  $model = Yii::$app->cache->get('seomodule');
      if(!$model)
	  {
              $model = SeoModule::find()->asArray()->all();
              Yii::$app->cache->set('seomodule', $model, 300000); 
      }

        return $model;
    }
	
	
		//Сео
	public function Staticpage() 
	{	
	  $model = Yii::$app->cache->get('staticpage');
      if(!$model)
	  {
              $model = StaticPage::find()->select(['url', 'name'])->Where(['menu'=> 1])->asArray()->all();
              Yii::$app->cache->set('staticpage', $model, 300000); 
      }

        return $model;
    }
}