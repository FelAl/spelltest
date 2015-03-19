<?php
$I = new SMSGuy($scenario);
$I->wantTo('perform actions and see result');
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::CreateEmptyOrder($I, '89203331122');
TestCommons::SendSMS($I, 'Test SMS text');
$I->see("SMS отправлено");
