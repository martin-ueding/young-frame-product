<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>

$a_text = $_POST['A'];
$b_text = $_POST['B'];
$n = (int) $_POST['N'];

if (count($a_text) > 20 || count($a_text) > 20) {
    die('Input too much. I do not want to consume all the resources of my server. :-)');
}

$a = explode("\n", trim($a_text));
$b = explode("\n", trim($b_text));

for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
    $a[$a_row_id] = preg_replace('/[^ ]/', '#', $a[$a_row_id]);
}

for ($b_row_id = 0; $b_row_id < count($b); $b_row_id++) {
    $b[$b_row_id] = preg_replace('/[^ ]/', chr(ord('a')+$b_row_id), $b[$b_row_id]);
}

print_r($a);
print_r($b);

$results = array();

# Iterate through the rows of B.
for ($b_row_id = 0; $b_row_id < count($b); $b_row_id++) {
    # Iterate through the columns of B.
    for ($b_col_id = 0; $b_col_id < count($b[$b_row_id]); $b_col_id++) {
        # Iterate through the rows of A.
        for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {

            # Append the element of B to the end of the current row of A2.
            $a2[$a_row_id] .= $b[$b_row_id][$b_col_id];

            # Test whether A2 is a legal diagram.
        }
    }
}

function add_block($a2, $b_row_id, $b_col_id) {
    if (!is_legal($a2)) {
        return;
    }

    # If we are completely through B, i.e. appended all the blocks to A, we are
    # done. If we made it to here, a legal diagram is created.
    if ($b_row_id == count($b)) {
        $results[] = $a2;
    }

    # Compute the next index of B.
    $b_next_col_id = $b_col_id + 1;
    $b_next_row_id = $b_row_id;
    if ($b_next_col_id == count($b[$b_row_id])) {
        $b_next_col_id = 0;
        $b_next_row_id++;
    }

    # Try to append at every row of A.
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        # Create another copy to pass to the next function.
        $a3 = $a2;

        # Append the current element of 
        $a3[$a_row_id] .= $b[$b_row_id][$b_col_id];


        add_block($a3, $b_next_row_id, $b_next_col_id);
    }

    # Try to add a new row to the end of A.
    $a3 = $a2;
    $a3[] = array($b[$b_row_id][$b_col_id]);
    add_block($a3, $b_next_row_id, $b_next_col_id);
}

function is_legal(&$a) {
    # If a colum has more than N entries, it is not legal.
    if (count($a) > $n) {
        return false;
    }

    # If a row of A is as long as the previous row, this is not legal.
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        if ($a_row_id > 0 && count($a[$a_row_id-1]) <= count($a[$a_row_id])) {
            return false;
        }
    }

    # If letters appear twice in the same column, it is not legal.
    for ($a_col_id = 0; $a_col_id < count($a[0]); $a_col_id++) {
        $letters = array();
        for ($a_row_id = 0; $a_row_id < count($a) && count($a[$a_row_id]) > $a_col_id; $a_row_id++) {
            $letter = $a[$a_row_id][$a_col_id];
            if ($letter > $letters) { # TODO
                return false;
            }
            else {
                $letters[] = $letters;
            }
        }
    }

    # Check ← ↓ traversal.
    $counts = array();
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        for ($a_col_id = count($a[$a_row_id]) - 1; $a_col_id >= 0; $a_col_id--) {
            $letter = $a[$a_row_id][$a_col_id];
            $ord = ord($letter) - ord('a');
            $counts[$ord]++;

            if (count($counts) > 1) {
                for ($count_id = 1; $count_id < count($counts); $count_id++) {
                    if ($counts[$count_id - 1] < $counts[$count_id]) {
                        return false;
                    }
                }
            }
        }
    }

    return true;
}

add_block($a, 0, 0);

$formatted_results = array();
foreach ($results as $result) {
    $formatted_results[] = '<pre>' . implode('<br />', $result) . '</pre>';
}
#sort($formatted_results);

$template_result = implode(' + ', $formatted_results);
