<?php

namespace app\models;

use Yii;
use yii\data\Pagination;
/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $title
 *
 * @property ArticleTag[] $articleTags
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])
        ->viaTable('article_tag', ['tag_id' => 'id'] );
    }

    public static function getAll()
    {
        return Tag::find()->all();
    }

    public static function getArticlesByTag($id)
    {
        $query = ArticleTag::find()->where(['tag_id'=>$id]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize'=>99]);

        $articleTags = $query->offset($pagination->offset)
                             ->limit($pagination->limit)
                             ->all();

	$articles = [];
        foreach ($articleTags as $articleTag) {
            $articles[] = $articleTag->getArticle()->one();
        }

        $data['articles'] = $articles;
        $data['pagination'] = $pagination;

        return $data;
    }
}
