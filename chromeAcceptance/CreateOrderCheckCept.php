cd<?php
$I = new NoGuy($scenario);
#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
$waiting_time =  GlobalJS::WaitingTime;
$I->wantTo('Create new order');
###Test start

TestCommons::LoginCRM($I,'ksenia','ME3ahE8H');
TestCommons::CreateOrder($I, $test_client);

?>
