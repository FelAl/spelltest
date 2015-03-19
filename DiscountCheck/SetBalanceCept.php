<?php
$I = new DisGuy($scenario);
$I->wantTo('perform actions and see result');
Testcommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::ClearDiscountOfClient($I, Client::client_id);
TestCommons::SetDiscountOfClient($I, Client::client_id, Client::balance);
$I->see(Client::balance, ViewClientPage::Client_balance);