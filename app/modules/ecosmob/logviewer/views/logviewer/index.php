<?php

use app\modules\ecosmob\logviewer\LogViewerModule;

$this->title = LogViewerModule::t('lv', 'log_viewer');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$row_style["0"] = "row_style0";
$row_style["1"] = "row_style1";


if (!isset($_POST['line_number']) || $_POST['line_number'] == '') {
    $_POST['line_number'] = 0;
}

if (!isset($_POST['sort']) || $_POST['sort'] == '') {
    $_POST['sort'] = "asc";
}

if (!isset($_POST['size']) || strlen($_POST['size']) == 0) {
    $_POST['size'] = "1024";
}

if (!isset($_POST['filter'])) {
    $_POST['filter'] = "";
}

if (isset($_GET['a']) && $_GET['a'] == "download") {
    if (isset($_GET['t']) && $_GET['t'] == "logs") {
        $tmp = Yii::$app->params['FREESWITCH_LOG_PATH'];
        $filename = Yii::$app->params['FREESWITCH_LOG_FILENAME'];
    }

    //session_cache_limiter( 'public' );
    $fd = fopen($tmp . $filename, "rb");
    header("Content-Type: binary/octet-stream");
    header("Content-Length: " . filesize($tmp . $filename));
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    fpassthru($fd);
    exit;
}

echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' style='margin-bottom: 15px;'>\n";
echo "	<tr>\n";
echo "		<td align='right' valign='middle' style='background:#fff;padding:15px;box-shadow: 0px 6px 8px #0000000f, -1px -2px 11px #0000000f;border-radius:10px;' nowrap>\n";
echo "			<form action='' method='POST'>\n";
echo "<div class='row'><div class='col s12 m4' style='margin-bottom: 8px;' >" . LogViewerModule::t('lv', 'filter')
    . "<input type='text' name='filter' class='formfld' style='width: 80%; text-align: left; padding: 0 10px;margin-left:5px;' value=\""
    . $_POST['filter'] . "\" onclick='this.select();'> </div>";
echo "			<div class='col s12 m4  mt-1'  style='margin-bottom: 8px;'><input style='margin-right: 5px;cursor:pointer;' type='checkbox' name='line_number' id='line_number' value='1' "
    . (($_POST['line_number'] == 1) ? 'checked' : NULL) . ">" . LogViewerModule::t('lv', 'line_number') . "";
echo "			<input style='margin-right: 5px; margin-left:20px;cursor:pointer;' type='checkbox' name='sort' id='sort' value='desc' " . (($_POST['sort']
        == 'desc')
        ? 'checked' : NULL) . "> " . LogViewerModule::t('lv', 'sort') . "</div>";
echo "<div class='col s12 m4' style='margin-bottom: 8px;' >" . LogViewerModule::t('lv', 'display')
    . " <input type='number' max=9999 class='formfld' style='width: 70px; text-align: center;margin: 0 5px;' name='size' value=\""
    . $_POST['size'] . "\" onclick='this.select();'> " . LogViewerModule::t('lv', 'size');
echo "<input type='hidden' name='_csrf' value='" . Yii::$app->request->getCsrfToken() . "' /> </div>";

echo "	<div class='col s12 text-center mt-3'><input type='submit' class='btn' style='margin-left: 20px;' name='submit' value='" . LogViewerModule::t('lv', 'reload') . "'>";
echo "		<input type='button' class='btn' value='" . LogViewerModule::t('lv', 'download')
    . "' onclick=\"document.location.href='?r=logviewer/logviewer/index&a=download&t=logs';\" />\n";
echo "			</div></div></form>\n";
echo "		</td>\n";
echo "	</tr></tbody></table>\n";
echo "	<table><tbody><tr>\n";
echo "		<td colspan='2' class='log-table' style='padding: 15px;
box-shadow: 0px 6px 8px #0000000f, -1px -2px 11px #0000000f;
border-radius: 10px; color:#fff; text-align:left; background:#212B36;'>";

$MAXEL = 3;

$user_file_size = '0';
$default_color = '#fff';
$default_type = 'normal';
$default_font = 'monospace';
$default_file_size = '512000';
$freeswitchLogFilePath = Yii::$app->params['FREESWITCH_LOG_PATH'];
$freeswitchLogFileName = Yii::$app->params['FREESWITCH_LOG_FILENAME'];
$log_file = $freeswitchLogFilePath . $freeswitchLogFileName;

$array_filter[0]['pattern'] = '[NOTICE]';
$array_filter[0]['color'] = 'cyan';
$array_filter[0]['type'] = 'normal';
$array_filter[0]['font'] = 'monospace';

$array_filter[1]['pattern'] = '[INFO]';
$array_filter[1]['color'] = 'chartreuse';
$array_filter[1]['type'] = 'normal';
$array_filter[1]['font'] = 'monospace';

$array_filter[2]['pattern'] = 'Dialplan:';
$array_filter[2]['color'] = 'burlywood';
$array_filter[2]['type'] = 'normal';
$array_filter[2]['font'] = 'monospace';
$array_filter[2]['pattern2'] = 'Regex (PASS)';
$array_filter[2]['color2'] = 'chartreuse';
$array_filter[2]['pattern3'] = 'Regex (FAIL)';
$array_filter[2]['color3'] = 'red';

$array_filter[3]['pattern'] = '[WARNING]';
$array_filter[3]['color'] = 'fuchsia';
$array_filter[3]['type'] = 'normal';
$array_filter[3]['font'] = 'monospace';

$array_filter[4]['pattern'] = '[ERR]';
$array_filter[4]['color'] = 'red';
$array_filter[4]['type'] = 'bold';
$array_filter[4]['font'] = 'monospace';

$array_filter[5]['pattern'] = '[DEBUG]';
$array_filter[5]['color'] = 'gold';
$array_filter[5]['type'] = 'bold';
$array_filter[5]['font'] = 'monospace';

$array_filter[6]['pattern'] = '[CRIT]';
$array_filter[6]['color'] = 'red';
$array_filter[6]['type'] = 'bold';
$array_filter[6]['font'] = 'monospace';

echo "		<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
echo "			<tr>";
$user_file_size = '32768';
if (isset($_POST['submit'])) {
    if (!is_numeric($_POST['size'])) {
        $user_file_size = 1024 * 1024;
    } else {
        $user_file_size = $_POST['size'] * 1024;
    }
    if (strlen($_REQUEST['filter']) > 0) {
        $uuid_filter = $_REQUEST['filter'];
        echo "		<td style='text-align: left; color: #FFFFFF;'>" . $uuid_filter . "</td>";
    }
}

if (file_exists($log_file)) {
    $file_size = filesize($log_file);
    $file = fopen($log_file, "r") or exit('error-open_file');

    echo "				<td style='text-align: right;'>Displaying " . number_format($user_file_size, 0, '.', ',') . " of "
        . number_format($file_size, 0, '.', ',') . " bytes </td>";
    echo "			</tr>";
    echo "		</table>";
    echo "		<hr size='1' style='color: #fff;'>";

    if ($user_file_size >= '0') {
        if ($user_file_size == '0') {
            $user_file_size = $default_file_size;
        }
        if ($file_size >= $user_file_size) {
            $byte_count = $file_size - $user_file_size;
            fseek($file, $byte_count);
        } else {
            if ($file_size >= $default_file_size) {
                $byte_count = $file_size - $default_file_size;
                fseek($file, $byte_count);
                echo $byte_count . "<br>";
            } else {
                $byte_count = '0';
                fseek($file, 0);
                echo "<br>open_file<br>";
            }
        }
    } else {
        if ($file_size >= $default_file_size) {
            $byte_count = $file_size - $default_file_size;
            fseek($file, $byte_count);
            echo $byte_count . "<br>";
        } else {
            $byte_count = '0';
            fseek($file, 0);
            echo "<br><br>";
        }
    }

    $byte_count = 0;
    $array_output = [];
    while (!feof($file)) {
        $log_line = fgets($file);
        $byte_count++;
        $noprint = FALSE;

        $skip_line = FALSE;
        if (!empty($uuid_filter)) {
            $uuid_match = strpos($log_line, $uuid_filter);
            if ($uuid_match === FALSE) {
                $skip_line = TRUE;
            } else {
                $skip_line = FALSE;
            }
        }

        if ($skip_line === FALSE) {
            foreach ($array_filter as $v1) {
                $pos = strpos($log_line, $v1['pattern']);
                if ($pos !== FALSE) {
                    for ($i = 2; $i <= $MAXEL; $i++) {
                        if (isset($v1["pattern" . $i])) {
                            $log_line = str_replace($v1["pattern" . $i],
                                "<span style='color: " . $v1["color" . $i] . ";'>" . $v1["pattern" . $i] . "</span>",
                                $log_line);
                        }
                    }
                    $array_output[] = "<span style='color: " . $v1['color'] . "; font-family: " . $v1['font'] . ";'>" . $log_line . "</span><br>";
                    $noprint = TRUE;
                }
            }

            if ($noprint !== TRUE) {
                $array_output[] = "<span style='color: " . $default_color . "; font-family: " . $default_font . ";'>" . $log_line . "</span><br>";
            }
        }
    }

    if ($_POST['sort'] == 'desc') {
        $array_output = array_reverse($array_output);
        $adj_index = 0;
    } else {
        $adj_index = 1;
    }

    foreach ($array_output as $index => $line) {
        $line_num = "";
        if ($line != "<span style='color: #fff; font-family: monospace;'></span><br>") {
            if ($_POST['line_number']) {
                $line_num = "<span style='font-family: courier; color: #aaa; font-size: 10px;'>" . ($index + $adj_index)
                    . "&nbsp;&nbsp;&nbsp;</span>";
            }
            echo $line_num . " " . $line;
        }
    }
    fclose($file);
} else {
    echo LogViewerModule::t('lv', 'file_not_found');
    echo "			</tr>";
    echo "		</table>";
}

echo "		</div>";
echo "		</td>";
echo "	</tr>\n";
echo "</table>\n";
