<?php

use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;

$expression = new Expression('YEAR(NOW())');
$year = (new Query)->select($expression)->scalar();

?>

<footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow">
    <div class="footer-copyright">
        <div class="container">
            <div class="float-left">
                <p class="m-0">Powered By Teleaon AI Sdn Bhd
                    <a href="https://teleaon.ai/" target="_blank">
                        <img src=" <?= Yii::getAlias('@web') . '/theme/assets/images/yaco.png' ?>"
                             class="footer-img"/>
                        <a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!--<script>
    $(document).ready(function() {
        // Attach a click event handler to the link
        $('#blf-link').on('click', function(e) {
            e.preventDefault(); // Prevent default link behavior (navigation)
            var url = "<?php /*= Url::to(['/blf/extension-blf/blf']) */?>"; // Get the URL from data attribute
            // Make an AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html', // Expect HTML response
                success: function(response) {
                    // Update the DOM with the retrieved content
                    $('.extension-container').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        });
    });
</script>-->
