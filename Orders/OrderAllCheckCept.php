<?php
$I = new OrGuy($scenario);
$I->wantTo('Check that information about client is saved');
#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ="Москва";
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
$I->see('Выйти');
Testcommons::CreateOrderAdr($I, $test_client);
$I->click('Вернуться к заказу');
$I->see($Man_last_name);
$I->see($Man_first_name);
$I->see($Man_middle_name);
$I->see($Man_phone);
$I->see($Man_email);
$I->see( mb_strtoupper($Man_city, 'utf-8'));

