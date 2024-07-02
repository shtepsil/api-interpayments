<?php

use yii\helpers\Url;

?>
<!-- Left Sidebar Menu -->
<div class="fixed-sidebar-left">
    <ul class="nav navbar-nav side-nav nicescroll-bar">
        <li class="navigation-header">
            <span>Меню</span>
            <i class="zmdi zmdi-more"></i>
        </li>
        <li>
            <a href="<?= Url::to(['clients/index'])?>"><div class="pull-left"><i class="zmdi zmdi-flag mr-20"></i><span class="right-nav-text">Клиенты</span></div><div class="pull-right"><span class="label label-warning"><?= count($clients)?></span></div><div class="clearfix"></div></a>
        </li>
        <li><hr class="light-grey-hr mb-10"/></li>

</div>
<!-- /Left Sidebar Menu -->
