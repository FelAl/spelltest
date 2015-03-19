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

$I->wantTo('Check parially paid if one or more goods are in paid status');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::CreateOrder($I,$test_client);
$I->fillField('photo_link', Items::TwoItemId);
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time, $jsCondition);
for ($i=1; $i <= 6; ++$i )
{
  $I->click('Добавить товар');
  $I->waitForJs($waiting_time, $jsCondition);
}
$I->waitForJs($waiting_time, $jsCondition);
$I->fillField('photo_link', Items::OneItemId);
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time,  $jsCondition);
$I->click('Добавить товар');
$I->waitForJs($waiting_time, $jsCondition);
$I->waitForJs($waiting_time,  $jsCondition);
TestCommons::SaveOrder($I);
#Set statuses
$NeedStatus = 'Вручен';
$I->executeJS( UpdateOrderPage::PlusButton );
$I->waitForJs($waiting_time, $jsCondition);
$val = $I->grabTextFrom('//*[@id="orderposition-grid"]/table');
$I->see($val);
for($i=0; $i < 8; $i++)
{
  $I->executeJS(UpdateOrderPage::PlusButton);
  $I->waitForJs($waiting_time, $jsCondition);
  $I->executeJS( '$(".updateOPstatus")['.$i.'].click();');
  $I->waitForJs($waiting_time, $jsCondition);
  $I->click($NeedStatus);
  $I->waitForJs($waiting_time, $jsCondition);
}
$I->executeJS(UpdateOrderPage::CloseUiWidgetOverlay);
$I->click('//*[@id="order-form-create"]/div[2]/div/span/input[1]');
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time, $jsCondition);
$I->see($NeedStatus,'.columns.six');
