<?php
$I = new RLGuy($scenario);
$I->wantTo('client manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::ClientManagerTestName, Users::ClientManagerTestPassword);
#$I->see('Выйти');





