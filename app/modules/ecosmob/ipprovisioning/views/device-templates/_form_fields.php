<?php

use yii\helpers\Html;

/* @var $fields */

foreach ($fields as $field): ?>

    <div class="col s6">
        <div class="input-field">
            <?= Html::label($field->parameter_label ?: $field->parameter_name, $field->parameter_name) ?>
            <?php
            $inputOptions = ['id' => $field->parameter_name, 'class' => 'form-control'];
            if ($field->input_type === 'boolean') {
                $checked = $field->parameter_value === '1';
                echo Html::checkbox($field->id, $checked, [
                    'value' => '1',
                    'uncheck' => '0',
                    'id' => $field->parameter_name
                ]);
            } else {
                echo Html::input('text', $field->id, $field->parameter_value, $inputOptions);
            }
            ?>
        </div>
    </div>

<?php endforeach; ?>