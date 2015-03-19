<?php
$I = new RLGuy($scenario);
$I->wantTo('admin login');
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
#$I->see('Выйти');
$I->see('Logout');