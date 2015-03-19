<?php
$I = new SGuy($scenario);
$needStatus      ='Отложен';
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

$I->wantTo('Check accepted after postponed');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
###############################
$I->waitForJs($waiting_time, $jsCondition);
$before_array = array();
$before_array = TestCommons::ReadGoodsStatuses($I,8);
$I->selectOption('Order[delivery_type]' ,'Самовывоз из пункта Боксберри');
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]','Отложен');
$I->acceptPopup();
#Add
$I->fillField('Order[cancel_status_reason]', 'Some reasons. Test');
$I->click('#save_status_reason');
$I->waitForJs($waiting_time, $jsCondition);
$I->fillField("Order[postponed_date]", CurrentDate::CurrentDate);


TestCommons::SaveOrder($I);
$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginClientManager($I);
$I->amOnPage($currentUrl);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
#$I->wait(30000);
$needStatus = 'Подтвержден';

$I->executeJS(UpdateOrderPage::PlusButton);
############################

$I->waitForJs($waiting_time, $jsCondition);
$before_array = array();
$before_array = TestCommons::ReadGoodsStatuses($I,8);

$size = count($before_array);


$needStatus = "Подтвержден";
$I->selectOption('Order[status]',$needStatus);
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SaveOrder($I);
$after_array = array();
$after_array = TestCommons::ReadGoodsStatuses($I,8);
$size = count($after_array);
TestCommons::specialCompare($I,$before_array,$after_array,'Отложен','Подтвержден');


