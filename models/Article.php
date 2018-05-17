<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\data\Pagination;


class Article extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'date', 'format' => 'php:Y-m-d'],    
            [['date'], 'default', 'value' => date('Y-m-d')],
            [['viewed', 'user_id', 'status', 'category_id'], 'integer'],
            [['image'], 'file', 'extensions' => 'jpg, jpeg, png, bmp']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'title' => 'Title',
            'date' => 'Date',
            'viewed' => 'Viewed',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
        ];
    }

    public function saveImage($filename)
    {
        $this->image = $filename;
        return $this->save(false);
    }

    public function getImage()
    {
        if ($this->image) {
            
            return '/uploads/' . $this->image;
        }

        return '/no-image.png'; 
    }

    public function deleteImage()
    {
        $this->deleteCurrentImage($this->image); 
    }

    public function deleteCurrentImage($currentImage)
    {
        if(file_exists($this->getFolder() . $currentImage))
            {
                unlink($this->getFolder() . $currentImage);
            }
    }

    public function getFolder()
    {
        return "/var/www/html/project/web/uploads/";
    }

    public function fileExists($currentImage)
    {
        if(!empty($currentImage) && $currentImage != null)
        {
            return file_exists($this->getFolder() . $currentImage);
        }
    }

    public function beforeDelete()
    {
        $this->deleteImage();
        return parent::beforeDelete();
    }

    public function getCategory()
    {
        $categ = $this->hasOne(Category::className(), ['id'=>'category_id']);
        return $categ->one();
    }

    public function saveCategory($category_id)
    {
        $category = Category::findOne($category_id);

        if($category != null)
        {
		$this->category_id = $category_id;
		$this->save();
		return true;
        }
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
        ->viaTable('article_tag', ['article_id' => 'id'] );
    }

    public function getSelectedTags()
    {
       $selectedIds = $this->getTags()->select('id')->asArray()->all();
       return ArrayHelper::getColumn($selectedIds, 'id');

    }

    public function saveTags($tags)
    {
        if(is_array($tags))
        {   
            $this->clearCurrentTags();

            foreach($tags as $tag_id)
            {
                $tag = Tag::findOne($tag_id);
                $this->link('tags', $tag);
            }
        }
    }

    public function clearCurrentTags()
    {
       ArticleTag::deleteAll(['article_id'=>$this->id]);   
    }

     public function getDate()
    {
        return Yii::$app->formatter->asDate($this->date);
    }

     public static function getAll($pageSize = 5)
    {
        $query = Article::find();
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>$pageSize]);
        $articles = $query->offset($pagination->offset)
                          ->limit($pagination->limit)
                          ->all();

        $data['articles'] = $articles;
        $data['pagination'] = $pagination;

        return $data;                
    }

     public function getPopular()
    {
        return Article::find()->orderBy('viewed desc')->limit(1)->all();
    }

    public function getRecent()
    {
        return Article::find()->orderBy('id desc')->limit(1)->all();
    }

    public function saveArticle()
    {
        $this->user_id = Yii::$app->user->id;
        return $this->save();
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['article_id'=>'id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id'=>'user_id']);
    }

    public function viewedCounter()
    {
        $this->viewed += 1;
        return $this->save(false);
    }

}
