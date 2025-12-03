<?php
/* @var $data */
/* @var $action_value */

if ($data != '') {
    $htm = '';
    foreach ($data as $key => $value) {
        $selected = '';
        if ($value['id'] == $action_value) {
            $selected = 'selected';
        }

        $htm .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
    }
    echo $htm;
}
?>