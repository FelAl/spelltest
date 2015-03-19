<?php
$I = new RLGuy($scenario);
$I->wantTo('high content manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::HighContentManagerTestName, Users::HighContentManagerTestPassword);
$I->dontsee('Войти');

