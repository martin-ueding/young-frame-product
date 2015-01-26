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

$template_result = implode(' + ', $formatted_results);
