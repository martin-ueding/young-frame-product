<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>

$a_text = $_POST['A'];
$b_text = $_POST['B'];

if (count($a_text) > 20 || count($a_text) > 20) {
    die('Input too much. I do not want to consume all the resources of my server. :-)');
}

$a = explode("\n", $a_text);
$b = explode("\n", $b_text);

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
    # If a row of A is as long as the previous row, this is not legal.
    for ($a_row_id = 0; $a_row_id < count($a); $a_row_id++) {
        if ($a_row_id > 0 && count($a[$a_row_id-1]) <= count($a[$a_row_id])) {
            return false;
        }
    }
}
