<?php 


use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\models\Article;

$this->title = 'My Yii Application';
?>
<div class="row">
	<div class="col-md-11 catgname"><h3><?= $category->title; ?></h3></div>
</div>

    <div class="container-fluid marg col-md-10">

	<?php foreach($articles as $row):?>
	    <div class="row categories">
	    <?php foreach($row as $article):?>
		<div class="col-md-4">
		    <div class="img">
			<a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>">
			    <img src="<?= $article->getImage() ?>" alt="">
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


