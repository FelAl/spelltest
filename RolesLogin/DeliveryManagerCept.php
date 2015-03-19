<?php
$I = new RLGuy($scenario);
$I->wantTo('delivery manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::DeliveryManagerTestName , Users::DeliveryManagerTestPassword);
#$I->see('Выйти');
