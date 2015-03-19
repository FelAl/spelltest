<?php
$I = new DisGuy($scenario);
$I->wantTo('clear client balance');
Testcommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::ClearDiscountOfClient($I, Client::client_id);
$I->see('0',ViewClientPage::Client_balance);