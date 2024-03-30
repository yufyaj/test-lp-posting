<?php

function addNum(int $num1, int $num2): int
{
    return $num1 + $num2;
}

$num1 = 1;
$num2 = 2;

$result = addNum($num1, $num2);
print_r($result);