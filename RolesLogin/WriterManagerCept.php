<?php
$I = new RLGuy($scenario);
$I->wantTo('writer manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::WriterTestName, Users::WriterTestPassword);
$I->dontsee('Войти');

