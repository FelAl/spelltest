<?php
$I = new DisGuy($scenario);
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;
$client_in_test_id =  56983;
$status            =  'Потерян, не компенсирован';
TestCommons::ReturnToBalanceDiscountLost($I, $client_in_test_id, $status);
