<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\Pagination;
use app\models\Article;
use app\models\Category;
use app\models\Tag;
use app\models\CommentForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $tags = Tag::getAll();
        $isGuest = Yii::$app->user->isGuest;    

	$data = [];
	$i = 0;
	foreach ($categories as $category) {
		$a = $category->getArticles();
		if ($a->count()) {
			$data[$i]['article'] = $a->all()[0];
			$data[$i++]['category'] = $category;
		}
	}

        foreach ($data as $index=>$item) 
            $articles[$index / 3][] = $item;
        
        return $this->render('index', [
            'articles' => $articles,
            'popular' => $popular,
            'recent' => $recent,
            'categories' => $categories,
            'isGuest' => $isGuest,
            'tags' => $tags
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $tags = $article->getTags()->all();//Tag::getAll();
        $comments = $article->comments;
        $commentForm = new CommentForm();

        $article->viewedCounter();

        return $this->render('single',[
            'article' => $article,
            'popular' => $popular,
            'recent' => $recent,
            'categories'=>$categories,
            'tags' => $tags,
            'comments' => $comments,
            'commentForm' => $commentForm
        ]);
    }

    public function actionCategory($id)
    {
        
        $data = Category::getArticlesByCategory($id);
        $tags = Tag::getAll();
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();


	$category = Category::findOne($id);

	$articles = [];
        foreach ($data['articles'] as $index=>$item)  {
		if ($index == 0) continue;
            $articles[($index - 1) / 3][] = $item;
	}

        return $this->render('category',[
            'articles'=>$articles,
	    'category'=>$category,
            'pagination'=>$data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories'=>$categories,
            'tags'=>$tags
        ]);
    }

public function actionTag($id)
    {
        
        $data = Tag::getArticlesByTag($id);
        $popular = Article::getPopular();
        $recent = Article::getRecent();
        $categories = Category::getAll();
        $tags = Tag::getAll();
        $tag = Tag::findOne($id);

	$articles = [];
        foreach ($data['articles'] as $index=>$item)  {
		if ($index == 0) continue;
            $articles[($index - 1) / 3][] = $item;
	}


        return $this->render('tag',[
            'articles'=>$articles,
            'pagination'=>$data['pagination'],
            'popular' => $popular,
            'recent' => $recent,
            'categories'=>$categories,
            'tags' => $tags,
            'tag' => $tag
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
