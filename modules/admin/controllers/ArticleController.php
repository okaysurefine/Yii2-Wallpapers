<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use app\models\Article;
use app\models\ArticleSearch;
use app\models\ImageUpload;
use app\models\Tag;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

	if ($model->load(Yii::$app->request->post()))
	{
		$model->file = UploadedFile::getInstance($model, 'file');

		if ($model->file && $model->validate())
		{                
			$filename = time() . '.' . $model->file->extension;
			if ($model->file->saveAs('uploads/' . $filename))
			{
				$model->image = $filename;
			}
		}
		$res = $model->save();
		return $this->redirect(['view', 'id' => $model->id]);
	}

	return $this->render('create', [
		'model' => $model,
	]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
            {
                $model->file = UploadedFile::getInstance($model, 'file');

                if ($model->file && $model->validate())
                { 
                  $model->deleteCurrentImage($model->image);               
                  $filename = time() . '.' . $model->file->extension;
                  if ($model->file->saveAs('uploads/' . $filename))
                  {
                    $model->image = $filename;
                }

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', ['model' => $model]);

        //     $model = new Article();

        //     if ($model->load(Yii::$app->request->post())) {

        //        $model->file = UploadedFile::getInstance($model, 'file');

        //        if ($model->file && $model->validate())
        //        {  
        //           $model->deleteImage();            
        //           $filename = time() . '.' . $model->file->extension;
        //           if ($model->file->saveAs('uploads/' . $filename))
        //           {
        //             $model->image = $filename;
        //         }
        //     }

        // $model->save();
        // return $this->redirect(['view', 'id' => $model->id]);
            
        //         }
        //         return $this->render('update', [
        //         'model' => $model,
        //     ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSetCategory($id)
    {
        $article = $this->findModel($id);
        $selectedCategory = ($article->category) ? $article->category->id : '0';
        $categories = ArrayHelper::map(Category::find()->all(), 'id', 'title');

        if (Yii::$app->request->isPost) {
            
            $category = Yii::$app->request->post('category');
            if($article->saveCategory($category))
            {
            return $this->redirect(['view', 'id'=>$article->id]);
            }
        }
        
        return $this->render('category', [

            'article'=>$article,
            'selectedCategory'=>$selectedCategory,
            'categories'=>$categories
        ]);
    }

    public function actionSetTags($id)
    {
        $article = $this->findModel($id);
        $selectedTags = $article->getSelectedTags();
        $tags = ArrayHelper::map(Tag::find()->all(), 'id', 'title');

        if (Yii::$app->request->isPost) 
        {
            $tags = Yii::$app->request->post('tags');
            $article->saveTags($tags);

            return $this->redirect(['view', 'id'=>$article->id]);
        }

        return $this->render('tags', [
            'selectedTags'=>$selectedTags,
            'tags'=>$tags
        ]);
    }

}