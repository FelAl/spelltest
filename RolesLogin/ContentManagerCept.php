<?php
$I = new RLGuy($scenario);
$I->wantTo('content manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::ContentManagerTestName, Users::ContentManagerTestPassword);
$I->dontsee('Войти');

