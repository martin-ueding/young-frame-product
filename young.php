<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>

require_once('yf.php');

$a_text = $_POST['A'];
$b_text = $_POST['B'];
$n = (int) $_POST['N'];

if (count($a_text) > 20 || count($a_text) > 20) {
    die('Input too much. I do not want to consume all the resources of my server. :-)');
}

$a = explode("\n", trim($a_text));
$b = explode("\n", trim($b_text));


for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
    $a[$a_row_id] = trim($a[$a_row_id]);
    $a[$a_row_id] = preg_replace('/[^ ]/', '#', $a[$a_row_id]);
}

for ($b_row_id = 0; $b_row_id < count($b); $b_row_id++) {
    $b[$b_row_id] = trim($b[$b_row_id]);
    $b[$b_row_id] = preg_replace('/[^ ]/', chr(ord('a')+$b_row_id), $b[$b_row_id]);
}

$results = array();
$messages = array();

$messages[] = '<div class="alert alert-info">Starting tensor multiplication of '.format($a).' and '.format($b).' with \( N = '.$n.' \).</div>';

add_block($a, 0, 0);

$formatted_results = array();
foreach ($results as $result) {
    $formatted_results[] = format($result);
}
#sort($formatted_results);

$template_result = implode(' \( \oplus \) ', $formatted_results);

$normalized_results = array();
foreach ($results as $result) {
    $normalized_result = format(normalize($result));
    if (array_key_exists($normalized_result, $normalized_results)) {
        $normalized_results[$normalized_result]++;
    }
    else {
        $normalized_results[$normalized_result] = 1;
    }
}

$norm_results_format = array();
foreach ($normalized_results as $norm_res => $mult) {
    $norm_results_format[] = $mult . $norm_res;
}
$template_result_norm = implode(' \( \oplus \) ', $norm_results_format);
