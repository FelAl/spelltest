<?php
$I = new SGuy($scenario);
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

$I->wantTo('Check accepted in office');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SaveOrder($I);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
$I->wait(25000);
TestCommons::SetGoodsStatuses($I,8,'На дальнем складе');
###################
$I->click('Сохранить');
$I->waitForJs($waiting_time, $jsCondition);

$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginDelieveryManager($I);
$I->amOnPage($currentUrl);
$I->click('Изменить заказ');

###################
 
$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SetGoodsStatuses($I,8,'Зарезервирован');
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
#$I->acceptPopup();
$I->waitforjs($waiting_time, $jsCondition);
$I->click('Вернуться к заказу');
$I->see('Товар в офисе', '//span');








