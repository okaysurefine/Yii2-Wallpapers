<?php

use yii\helpers\Url;
use yii\models\Article;
?>

<div class="sidebar">
    <aside class="widget">
        <h5 class="name">Популярное</h5>

        <?php foreach ($popular as $article):?> 
            <div class="popular_img">

                <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id ]);?>"><img src="<?= $article->getImage();?>" alt="">
                </a>

                <div class="date">
                    <?= $article->getDate(); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </aside>

    <aside class="widget">
        <?php foreach ($recent as $article):?> 
            <h5 class="name">Новое</h5>
            <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>"><img src="<?= $article->getImage(); ?>" alt="">
                <div class="p-overlay"></div>
            </a>
            <div class="date">
                <?= $article->getDate(); ?>
            </div>
        <?php endforeach; ?>                 
    </aside>
</div>
