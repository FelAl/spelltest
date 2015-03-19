<?php
$I = new DisGuy($scenario);
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;
#Information about client
$client_in_test_id =  56983;
$status            =  'Отказ';

TestCommons::ReturnToBalanceDiscount($I, $client_in_test_id, $status);

