<?php
namespace common\models;
use yii\helpers\Url;
use Yii;
use common\models\RegionCase;
/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 * @property string $regionCase
 * @property string $url
 * @property string $relative
 * @property int $parent
 * @property int $sort
 */
class Region extends \yii\db\ActiveRecord
{
	
	public $regionCase;
	public $coordlon;
	public $coordlat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'regionCase'], 'required'],
            [['parent', 'sort'], 'integer'],
			[['parent'], 'default', 'value'=> 0],
			[['sort'], 'default', 'value'=> 100000],
            [['name'], 'string', 'max' => 200],
            [['url'], 'string', 'max' => 50],
			[['relative', 'coordlon', 'coordlat'], 'string'],
			[['url'], 'unique'],
        ];
    }



    //Связь Со склонением
	public function getCases() {
     return  $this->hasOne(RegionCase::className(),['id_region'=>'id']);
    }


   public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->parent) {
              $this->relative = '| '.Yii::$app->userFunctions->catParentArray(Yii::$app->caches->region(), $this->parent).' |';
			}
            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }
	
//Начало функции последовательности категорий
static function linenav($cats_id, $first = true) {
$cats_id = Region::findOne($cats_id);
 static $array = array();
    if($cats_id['parent'] != 0 && $cats_id['parent'] != "")
        {
     Region::linenav($cats_id['parent'], false);
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
	
	
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название категории',
            'url' => 'ЧПУ',
            'parent' => 'Родительская категория',
            'sort' => 'Сортировка',
			'regionCase' => 'Название региона в родительном падеже',
			'coordlat' => 'Широта (координаты)',
			'coordlon' => 'Долгота (координаты)',
        ];
    }
	


}
