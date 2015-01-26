<?php
# Copyright © 2015 Martin Ueding <dev@martin-ueding.de>

$a_text = $_POST['A'];
$b_text = $_POST['B'];

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

function add_block($a2, $b_row_id, $b_col_id, $a_row_id, $a_col_id) {
    # If this row of A is as long as the previous row, one must not append to
    # it.
    if ($a_row_id > 0 && count($a[$a_row_id-1]) <= count($a[$a_row_id])) {
        return;
    }

    # If we are completely through B, i.e. appended all the blocks to A, we are
    # done. If we made it to here, a legal diagram is created.
    if ($b_row_id == count($b) - 1 && $b_col_id == count($b[$b_row_id])) {
    }
}
