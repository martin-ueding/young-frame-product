<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>
# Licensed under The GNU Public License Version 2 (or later)

function add_block($a, $b_row_id, $b_col_id) {
    global $messages;
    global $b;
    global $results;

    $messages[] = '<div class="alert alert-info">Starting add_block($a=…, $b_row_id='.$b_row_id.', $b_col_id='.$b_col_id.') with:'.format($a).'</div>';

    if (!is_legal($a)) {
        return;
    }

    # If we are completely through B, i.e. appended all the blocks to A, we are
    # done. If we made it to here, a legal diagram is created.
    if ($b_row_id == count($b)) {
        $results[] = $a;
        $messages[] = '<div class="alert alert-success">This is a valid result.</div>';
        return;
    }

    # Compute the next index of B.
    $b_next_col_id = $b_col_id + 1;
    $b_next_row_id = $b_row_id;
    if ($b_next_col_id == strlen($b[$b_row_id])) {
        $messages[] = '<div class="alert alert-info">Next element form B is taken from the next row because the current row only has '.strlen($b[$b_row_id]).' element and we currently work with element '.$b_col_id.' (zero indexed).</div>';
        $b_next_col_id = 0;
        $b_next_row_id++;
    }

    # Try to append at every row of A.
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        # Create another copy to pass to the next function.
        $a3 = $a;

        # Append the current element of 
        $a3[$a_row_id] .= $b[$b_row_id][$b_col_id];


        add_block($a3, $b_next_row_id, $b_next_col_id);
    }

    # Try to add a new row to the end of A.
    $a3 = $a;
    $a3[] = $b[$b_row_id][$b_col_id];
    add_block($a3, $b_next_row_id, $b_next_col_id);
}

function is_legal(&$a) {
    global $messages;
    global $n;

    # If a colum has more than N entries, it is not legal.
    if (count($a) > $n) {
        $messages[] = '<div class="alert alert-warning">'.format($a).' is not legal because a column has more than N entries.</div>';
        return false;
    }

    # If a row of A is as long as the previous row, this is not legal.
    for ($a_row_id = 1; $a_row_id < count($a); $a_row_id++) {
        if (strlen($a[$a_row_id-1]) < strlen($a[$a_row_id])) {
            $messages[] = '<div class="alert alert-warning">'.format($a).' is not legal because a row is longer than the previous one.</div>';
            return false;
        }
    }

    # If letters appear twice in the same column, it is not legal.
    for ($a_col_id = 0; $a_col_id < strlen($a[0]); $a_col_id++) {
        $letters = array();
        for ($a_row_id = 0; $a_row_id < count($a) && strlen($a[$a_row_id]) > $a_col_id; $a_row_id++) {
            $letter = $a[$a_row_id][$a_col_id];
            if ($letter == '#') {
                continue;
            }
            if (in_array($letter, $letters)) {
                $messages[] = '<div class="alert alert-warning">'.format($a).' is not legal because a letter appears twice in a column.</div>';
                return false;
            }
            else {
                $letters[] = $letters;
            }
        }
    }

    # Check ← ↓ traversal.
    $counts = array();
    for ($i = 0; $i < 26; $i++) {
        $counts[$i] = 0;
    }
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        for ($a_col_id = strlen($a[$a_row_id]) - 1; $a_col_id >= 0; $a_col_id--) {
            $letter = $a[$a_row_id][$a_col_id];
            $ord = ord($letter) - ord('a');
            $counts[$ord]++;

            if (count($counts) > 1) {
                for ($count_id = 1; $count_id < count($counts); $count_id++) {
                    if ($counts[$count_id - 1] < $counts[$count_id]) {
                        $messages[] = '<div class="alert alert-warning">'.format($a).' is not legal because the traversal criterion is not fulfilled.</div>';
                        return false;
                    }
                }
            }
        }
    }

    return true;
}

function format($a) {
    return '<pre class="yf">' . implode('<br />', $a) . '</pre>';
}

function normalize($a) {
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        $a[$a_row_id] = preg_replace('/[a-z]/', '#', $a[$a_row_id]);
    }

    return $a;
}

function dimension($a) {
    return product($a) / hook_number($a);
}

function product($a) {
    global $n;

    $product = 1;
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        for ($a_col_id = 0; $a_col_id < strlen($a[$a_row_id]); $a_col_id++) {
            $box_value = $n + $a_col_id - $a_row_id;
            $product *= $box_value;
        }
    }

    return $product;
}

function hook_number($a) {
    $hook_number = 1;
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        for ($a_col_id = 0; $a_col_id < strlen($a[$a_row_id]); $a_col_id++) {
            # Number of elements in the current row that are behind the current
            # one, current one included.
            $summand2 = (strlen($a[$a_row_id]) - $a_col_id);

            # Number of elements below the current one, current one excluded.
            $summand1 = 0;
            for ($sub_row = $a_row_id + 1; $sub_row < count($a); $sub_row++) {
                if (strlen($a[$sub_row]) > $a_col_id) {
                    $summand1++;
                }
            }
            $factor = $summand1 + $summand2;
            echo "$factor ";
            $hook_number *= $factor;
        }
        echo "</br>";
    }
    echo "</br>";
    echo "</br>";

    return $hook_number;
}
