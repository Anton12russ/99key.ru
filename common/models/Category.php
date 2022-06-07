<?php
namespace common\models;
use yii\helpers\Url;
use Yii;
use common\models\CounterCat;
use common\models\CounterCatshop;
/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $image
 * @property string $relative
 * @property int $parent
 * @property string $description
 * @property int $sort
 * @property int $smallImage
 * @property int $filter
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['parent', 'sort'], 'integer'],
			[['parent'], 'default', 'value'=> 0],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 200],
			[['relative'], 'string', 'max' => 200],
			[['relative'], 'default', 'value'=> ''],
            [['url'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 500],
			[['url'], 'unique'],
        ];
    }


	
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название категории',
            'url' => 'ЧПУ',
            'image' => 'Изображение',
            'parent' => 'Родительская категория',
            'description' => 'Описание',
            'sort' => 'Сортировка',
			'filter' => 'Поля фильтра',
			'SmallImage' => 'Изображение',
		
        ];
    }

public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
          if ($this->parent) {
              $this->relative = '| '.Yii::$app->userFunctions->catParentArray(Yii::$app->caches->category(), $this->parent).' |';
		  }
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }


    //Связь cо счетчиком
	public function getCounter() {
		//
		$regcookies = Yii::$app->request->cookies['region'];
		if ($regcookies) {
			$params = ['region'=>$regcookies];
		}
    if (!isset($params)) {$params = "";}
    return $this->hasOne(CounterCat::className(),['cat'=>'id'])->andWhere($params);
	}
	
	
	
	
	
	   //Связь cо счетчиком
	public function getCountershop() {
		//
		$regcookies = Yii::$app->request->cookies['region'];
		if ($regcookies) {
			$params = ['region'=>$regcookies];
		}
    if (!isset($params)) {$params = "";}
    return $this->hasOne(CounterCatshop::className(),['cat'=>'id'])->andWhere($params);
	}
	//Связь со счетчиком
/*	public function getCounterCat() {
		$region = Yii::$app->request->cookies['region']->value;
		
		if ($region) {
			$params = ['region' => $region];
		}else{
			$params = ['region' => 0];
		}
    return $this->hasMany(CounterCat::className(), ['cat'=>'id'])->andWhere($params);
	}
	*/
	
public function getSmallImage() {
		if($this->image) {
	    $path = $this->image;
	    }else{
		$path = '/uploads/images/no-photo.png';
	    }
	return $path;
	}
	
	
	//Начало функции последовательности категорий
static function linenav($cats_id, $first = true) {
$cats_id = Category::findOne($cats_id);
 static $array = array();
    if($cats_id['parent'] != 0 && $cats_id['parent'] != "")
        {
     Category::linenav($cats_id['parent'], false);
		}else{
		$array = array();
		}
   $array[] = array('name' => $cats_id['name'], 'id' => $cats_id['id']);
    foreach($array as $k=>$v)
        {
			if(!isset($return)) {$return = '';}
        $return .= $v['name'];
        if($k != (count($array)-1)){$return .= ' > ';}
        }
    return  $return;

    }
	
	
	

	
}
