<?php
$I = new SGuy($scenario);
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';

$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;

$I->wantTo('???????? !');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
#Delivery manager
$I->click('Заказы');
$I->waitForJs($waiting_time, $jsCondition);
$I->amOnPage('/index.php?r=order/update&id=198311');
#$I->wait(50000);
$I->waitForJs($waiting_time, $jsCondition);

$before_array = array();
$before_array = TestCommons::ReadGoodsStatuses($I,8);

TestCommons::SetGoodsStatuses($I,8,'Новый');

$after_array = array();
$after_array = TestCommons::ReadGoodsStatuses($I,8);

for ($i = 0; $i < 8; ++$i)
{
$I->cansee($after_array[$i]);
}
