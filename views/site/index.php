<?php 


use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\models\Article;

$this->title = 'My Yii Application';
?>
<div class="container-fluid marg">
    <div class="col-md-10">
        <?php foreach($articles as $row):?>
            <div class="row categories">
                <?php foreach($row as $item):?>
                    <div class="col-md-4">
                        <div class="img">
                            <a href="<?= Url::toRoute(['site/category', 'id'=>$item['category']->id]);?>">
                                <div class="name"><h3><?= $item['category']->title; ?></h3></div>
                            </a>
                            
                            <a href="<?= Url::toRoute(['site/category', 'id'=>$item['category']->id]);?>">
                                <img src="<?= $item['article']->getImage() ?>" alt="">
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>   
            </div>
		<br>
        <?php endforeach; ?>
    </div>

    <div class="col-md-2">
       <?= $this->render('/partials/sidebar', [
        'popular' => $popular,
        'recent' => $recent,
    ]);?>
    </div>

</div>

