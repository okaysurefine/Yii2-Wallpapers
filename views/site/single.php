<!--main content start-->
<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
    <div class="container-fluid marg">
            <div class="col-md-10">                       
                <div class="wallpaper">
                    <img src="<?= $article->getImage() ;?>" width="100%" alt="">
                </div>

                    <div class="marg">
                        <?php foreach ($tags as $tag):?>
                            <a href="<?= Url::toRoute(['site/tag', 'id'=>$tag->id]);?>" class="btn btn-default"><?=$tag->title?></a>
                            <?php endforeach; ?>
                        <div class="text-right"><?= $article->getDate() ?></div>
                    </div>

                        <div>
                            <!-- <span>By <?= $article->author['name'] ?></span> -->
                        </div>

                        <div class="text-right">
                        <h4><a class="text-right" href="<?= Url::toRoute(['site/category', 'id'=>$article->category->id]) ?>"><?= $article->category->title ?></a></h4>
                        </div>
                </div>
            
                <div class="col-md-2">
                 <?= $this->render('/partials/sidebar', [
                    'popular' => $popular,
                    'recent' => $recent,
                    'categories'=>$categories,
                    'tags'=>$tags
                ]);?>
                </div>    
        </div>
    </div>
<!-- end main content-->