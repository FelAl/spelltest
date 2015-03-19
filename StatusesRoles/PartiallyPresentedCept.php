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

$I->wantTo('Check parially presented');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::CreateOrder($I,$test_client);
$I->fillField('photo_link',Items::OneItemId);
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time, $jsCondition);
for($i=0; $i < 7; $i++)
{
  $I->click('Добавить товар');
  $I->waitForJs($waiting_time, $jsCondition);
}
$I->waitForJs($waiting_time, $jsCondition);
$I->fillField('photo_link', Items::OneItemId);
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Добавить товар');

####
$I->click('Сохранить');
$I->waitForJs($waiting_time, $jsCondition);
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]','Прибыл');
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$I->waitForJs($waiting_time, $jsCondition);
#Choose statuses
$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginDelieveryManager($I);
$I->amOnPage($currentUrl);
###
$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Вручен');
$I->waitForJs($waiting_time, $jsCondition);
#Problem place ?????
$I->click('Сохранить');
#$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->see('Частично вручен','.columns.six');

