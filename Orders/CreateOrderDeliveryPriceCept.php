<?php
$I  = new OrGuy($scenario);
#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199993';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
$waiting_time = 10000;
$jsCondition = GlobalJS::jQueryActive;
$I->wantTo('see changes of delivery price');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
$I->see('Выйти');
$I->click('Новый заказ');
$I->acceptPopup();
$I->see('Выйти');
$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
$I->click('Поиск клиента');
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Создать заказ');
$I->fillField('Client_last_name',     $test_client->Model_client_last_name);
$I->fillField('Client_first_name',    $test_client->Model_client_first_name);
$I->fillField('Client_middle_name',   $test_client->Model_client_middle_name);
$I->fillField('Client_email',         $test_client->Model_client_email);
$I->selectOption('Order[region_id]',  $test_client->Model_client_region);

$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[city_id]',$test_client->Model_client_city);
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[status]','Подтвержден');
$I->acceptPopup();
$I->click('OK');
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[delivery_type]','Курьер');
$I->waitForJs($waiting_time,$jsCondition);
$I->fillField('Order_comments',$test_client->Model_client_adress);
$I->checkOption('У метро');
#Change city and region, and see what happens with delivery cost
$I->seeInField('//*[@id="Order_delivery_cost"]', '300');
$I->selectOption('Order[city_id]', 'Троицк');
$I->waitForJs($waiting_time,$jsCondition);
$I->seeInField('//*[@id="Order_delivery_cost"]', '400');
$I->selectOption('Order[region_id]','Казань');
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[city_id]', 'Другой');
$I->waitForJs($waiting_time,$jsCondition);
$I->seeInField('//*[@id="Order_delivery_cost"]', '250');
$I->click('Сохранить');

