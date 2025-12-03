<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $assignUrl array */
/* @var $removeUrl array */
/* @var $opts string */

$this->registerJs("var _opts = {$opts};", View::POS_BEGIN);
?>
<div class="row col s12">
    <div class="col s5">
        <input class="input-field search" data-target="available"
               placeholder="<?php echo Yii::t('yii2mod.rbac', 'Search for available'); ?>">
        <br/>
        <select multiple size="20" class="form-control list" data-target="available"></select>
        <br/>
    </div>
    <div class="col s2">
        <div class="move-buttons">
            <br><br>
            <?php echo Html::a('&gt;&gt;', $assignUrl, [
                'class' => 'btn btn-success btn-assign',
                'data-target' => 'available',
                'title' => Yii::t('yii2mod.rbac', 'Assign'),
            ]); ?>
            <br/><br/>
            <?php echo Html::a('&lt;&lt;', $removeUrl, [
                'class' => 'btn btn-danger btn-assign',
                'data-target' => 'assigned',
                'title' => Yii::t('yii2mod.rbac', 'Remove'),
            ]); ?>
        </div>
    </div>
    <div class="col s5">
        <input class="form-control search" data-target="assigned"
               placeholder="<?php echo Yii::t('yii2mod.rbac', 'Search for assigned'); ?>">
        <select multiple size="20" class="form-control list" data-target="assigned"></select>
        <br/>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('select').formSelect('destroy');
        $('select').show();
        $('select').css('height', '400px');
    })
</script>