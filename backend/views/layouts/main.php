<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\models\Clients;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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

<!-- Preloader -->
<!--<div class="preloader-it">-->
<!--    <div class="la-anim-1"></div>-->
<!--</div>-->
<!-- /Preloader -->

<div class="wrapper theme-4-active pimary-color-red">

    <div class="wrap-loader-global">
        <span class="loader-global"></span>
    </div>

    <?= $this->render('/layouts/blocks/top-menu')?>
    <?= $this->render('/layouts/blocks/left-sidebar', [
        'clients' => Clients::find()->where(['status' => Clients::STATUS_ACTIVE])->all(),
    ])?>
    <?= $this->render('/layouts/blocks/right-sidebar')?>

    <!-- Main Content -->
    <div class="page-wrapper">

        <div class="container-fluid pt-25">
            <?= $content ?>
        </div>

        <!-- Footer -->
        <?= $this->render('/layouts/blocks/footer')?>
        <!-- /Footer -->

    </div>
    <!-- /Main Content -->

</div>
<!-- /#wrapper -->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
