<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_cat".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property int $parent
 * @property int $sort
 */
class ArticleCat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_cat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'text'], 'required'],
			[['parent'], 'default', 'value'=> 0],
			[['sort'], 'default', 'value'=> 1000000],
            [['text', 'url'], 'string'],
            [['parent', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 300],
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
            'name' => 'Название',
            'text' => 'Описание',
            'parent' => 'Родительская категория',
			'sort' => 'Сортировка',
        ];
    }
	
	
		//Начало функции последовательности категорий
static function linenav($cats_id, $first = true) {
$cats_id = ArticleCat::findOne($cats_id);
 static $array = array();
    if($cats_id['parent'] != 0 && $cats_id['parent'] != "")
        {
     ArticleCat::linenav($cats_id['parent'], false);
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
