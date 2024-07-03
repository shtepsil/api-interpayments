<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!--Preloader-->
<div class="preloader-it">
    <div class="la-anim-1"></div>
</div>
<!--/Preloader-->

<div class="wrapper pa-0">
    <header class="sp-header">
        <div class="sp-logo-wrap pull-left">
            <a href="<?= Url::to(['site/index'])?>">
<!--                <img class="brand-img mr-10" src="dist/img/logo.png" alt="brand"/>-->
                <span class="brand-text">Админ панель</span>
            </a>
        </div>
<!--        <div class="form-group mb-0 pull-right">-->
<!--            <span class="inline-block pr-10">Don't have an account?</span>-->
<!--            <a class="inline-block btn btn-info btn-rounded btn-outline" href="signup.html">Sign Up</a>-->
<!--        </div>-->
<!--        <div class="clearfix"></div>-->
    </header>

        <?= $content ?>
</div>
<!-- /#wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
