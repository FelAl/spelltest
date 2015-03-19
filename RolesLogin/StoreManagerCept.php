<?php
$I = new RLGuy($scenario);
$I->wantTo('store manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::StoreManagerTestName, Users::StoreManagerTestPassword);
$I->dontsee('Войти');

