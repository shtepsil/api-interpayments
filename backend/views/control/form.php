<?php
/**
 * @var models\Structure $item
 * @var
 */

use common\components\Debugger as d;
use shadow\widgets\AdminForm;

?>
<?if(ADMIN_FORM_DEBUG_RES){echo d::res();}?>
<section id="content">
    <?= AdminForm::widget(['item' => $item]) ?>
</section>
