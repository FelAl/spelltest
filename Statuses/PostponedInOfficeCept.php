<?php
$I = new SGuy($scenario);
$I->wantTo('check that after order status is "sent", all goods status is sent too');
$needStatus      ='Прибыл';
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Воронежская область';
$Man_city        ='Воронеж';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);

$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;

$I->wantTo('Check parially paid if one or more goods are in paid status');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
#$I->wait(25000);
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');

$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
$I->waitForJs($waiting_time, $jsCondition);
#$I->wait(1000);
TestCommons::SetGoodsStatuses($I,8,'Зарезервирован');
#$I->wait(2000);
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SaveOrder($I);
#$I->wait(50000);
$I->selectoption('Order[status]','Отложен');
$I->acceptpopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->fillField('Order[cancel_status_reason]', 'Some reasons. Test');
$I->click('#save_status_reason');
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SaveOrder($I);
$I->selectoption('Order[status]','Подтвержден');
$I->acceptpopup();
TestCommons::SaveOrder($I);
$I->click('Вернуться к заказу');
$I->see('Товар в офисе', '//span');
