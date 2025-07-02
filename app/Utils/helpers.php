<?php

function test(){
    return 'test';
}

function price_format($amount){
    return number_format((float)$amount,2);
}

function calculateModulo($dividend=0,$divisor=0){
    return round($dividend-(round($dividend / $divisor))*$divisor);
}