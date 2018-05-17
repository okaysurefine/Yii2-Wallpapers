<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\SiteAsset;

SiteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wllps</title>
    <?= Html::csrfMetaTags() ?>  
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container-fluid">
    <div class="row header">
        <div class="col-md-1">
        </div>
        <a href="<?= Url::toRoute(['/'])?>">
        <div class="col-md-1 logo">
        </div></a>
        <div class="col-md-3"></div>
        <div class="col-md-4"></div>
        <div class="padding-left col-md-3">
        <div class="btsize">
            <?php if(Yii::$app->user->isGuest):?>
                <a href="<?= Url::toRoute(['auth/signup']);?>"><button type="button" class="btn btn-secondary">Регистрация</button></a>
                <a href="<?= Url::toRoute(['auth/login']);?>"><button type="button" class="btn btn-secondary">Авторизация</button></a>
        
                <?php else: ?>
                    <a href="<?= Url::toRoute(['/admin/article/create']);?>"><button type="submit" class="btn btn-secondary">Добавить изображение</button></a>
                    <a href="<?= Url::toRoute(['/auth/logout']);?>"><button type="submit" class="btn btn-secondary">Выйти</button></a>
                <?php endif;?>

        </div>
    
    </div>
    </div>
    <?= $content; ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
