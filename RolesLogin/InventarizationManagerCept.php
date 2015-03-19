<?php
$I = new RLGuy($scenario);
$I->wantTo('inventarization manager login');
$jsCondition   =  GlobalJS::jQueryActive;
$waiting_time  =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::InventarizationManagerTestName, Users::InventarizationManagerTestPassword);
$I->dontsee('Войти');