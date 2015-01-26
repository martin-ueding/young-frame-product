<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>

require_once('yf.php');

$a_text = "##\n#";
$b_text = "##\n#";

if (!empty($_POST['A'])) {
    $a_text = $_POST['A'];
}
if (!empty($_POST['B'])) {
    $b_text = $_POST['B'];
}
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

if (!empty($_POST['A']) && !empty($_POST['B'])) {
    $messages[] = '<div class="alert alert-info">Starting tensor multiplication of '.format($a).' and '.format($b).' with \( N = '.$n.' \).</div>';

    $input_dim = dimension($a) * dimension($b);


    add_block($a, 0, 0);

    $formatted_results = array();
    foreach ($results as $result) {
        $formatted_results[] = format($result);
    }
    #sort($formatted_results);

    $template_result = implode(' \( \oplus \) ', $formatted_results);

    $norm_assoc = array();
    foreach ($results as $result) {
        $norm_res = normalize($result);
        $norm_res_str = implode("\n", $norm_res);
        if (array_key_exists($norm_res_str, $norm_assoc)) {
            $norm_assoc[$norm_res_str]++;
        }
        else {
            $norm_assoc[$norm_res_str] = 1;
        }
    }

    $norm_res_fmt = array();
    foreach ($norm_assoc as $norm_res_str => $mult) {
        $norm_res = explode("\n", $norm_res_str);
        $norm_res_fmt[] = $mult . format($norm_res);
    }
    $template_result_norm = implode(' \( \oplus \) ', $norm_res_fmt);

    $dim_array = array();
    $total_dim = 0;
    foreach ($norm_assoc as $norm_res_str => $mult) {
        $norm_res = explode("\n", $norm_res_str);
        $p = product($norm_res);
        $h = hook_number($norm_res);
        $d = $p / $h;
        $dim_array[] = '\('.$mult.'\Gamma_{'.$p.'/'.$h.'='.$d.'} \)';
        $total_dim += $d * $mult;
    }
    $template_dim_format = implode(' \( \oplus \) ', $dim_array);

}
