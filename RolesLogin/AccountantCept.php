<?php
$I = new RLGuy($scenario);
$I->wantTo('accountant login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::AccountantTestName, Users::AccountantTestPassword);
$I->dontsee('Войти');