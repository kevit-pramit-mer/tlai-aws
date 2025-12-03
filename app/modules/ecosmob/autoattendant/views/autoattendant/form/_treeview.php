<?php

use app\assets\AppAsset;
use app\modules\ecosmob\autoattendant\assets\AutoAttendantAsset;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use yii\helpers\Url;
use yii\web\View;

/* @var $jsTreeData app\modules\ecosmob\autoattendant\controllers\AutoattendantController */

AutoAttendantAsset::register($this);

?>
<?php

/**
 * @param $valueData
 */
function nestedTreeDisplaydata($valueData)
{
foreach ($valueData as $value)
{
if (!is_array($value)) { ?>
<li data-jstree='{"opened":true,"icon":"fa fa-leaf"}'>
    <?php echo $value; ?>

    <?php
    } else { ?>
        <?php nestedTreeDisplay($value); ?>

        <?php
    }
    }
    }
    /**
     * @param $jsTreeData
     */
    function nestedTreeDisplay($jsTreeData)
    {
    foreach ($jsTreeData as $value)
    {
    if (!is_array($value))
    {
    if (preg_match('/\s/', $value)) {

        $subName = explode('-', $value)[2];
        $subMenu = explode('<b><i>', $subName)[1];

        $subId = explode('</i></b>', $subMenu)[0];

        $subMenuId = AutoAttendantMaster::getIdByName($subId);

    } else {
        $subMenu = explode('<b><i>', $value)[1];
        $subId = explode('</i></b>', $subMenu)[0];
        $subMenuId = AutoAttendantMaster::getIdByName($subId);

    }
    ?>
<li data-jstree='{"opened":true,"icon":"fa fa-tree"}'>
    <a href="<?= Url::to(['/autoattendant/autoattendant/settings', 'id' => $subMenuId]); ?>"><?php echo $value; ?></a>


    <?php } else {
        echo "<ul>";
        nestedTreeDisplaydata($value);
        echo "</ul>";
        ?>

    <?php } ?>
    <?php }

    }

    ?>

    <div id="basicTree">
        <ul>
            <?php nestedTreeDisplay($jsTreeData); ?>
        </ul>
    </div>

    <?php
    $this->registerCssFile(
        "@web/theme/assets/global/plugins/jstree/dist/themes/default/style.css",
        [
            'depends' => AppAsset::className(),
            'position' => View::POS_END,
        ]
    );

    /*$this->registerCssFile(
        '@web/theme/assets/global/plugins/material-design-iconic-font/dist/css/material-design-iconic-font.min.css',
        [
            'depends'  => \app\assets\AppAsset::className(),
            'position' => \yii\web\View::POS_END,
        ]
    );*/

    $this->registerJsFile(
        '@web/theme/assets/global/plugins/jstree/dist/jstree.min.js',
        [
            'depends' => AppAsset::className(),
            'position' => View::POS_END,
        ]
    );

    $this->registerJsFile(
        '@web/theme/assets/global/js/treeview.js',
        [
            'depends' => AppAsset::className(),
            'position' => View::POS_END,
        ]
    );
    ?>

    <script type="application/javascript">
        $("#basicTree").on("changed.jstree", function (e, data) {
            window.location.href = data.node.a_attr.href;
        })

    </script>
