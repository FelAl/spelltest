<?php
$I = new SGuy($scenario);
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
$needStatus = 'Вручен';

$I->wantTo('Check all presented');
###Test start
TestCommons::LoginCRM($I,Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);

$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
#Prepare for Role and Test
$I->selectOption('Order[delivery_type]' ,'Самовывоз из пункта Боксберри');
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]','Прибыл');
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginDelieveryManager($I);
$I->amOnPage($currentUrl);
$I->waitForJs($waiting_time, $jsCondition);
#$I->wait(25000);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SetGoodsStatuses($I,8,'Вручен');
$I->waitForJs($waiting_time, $jsCondition);
#$I->wait(25000);
$I->click('Сохранить');
#$I->acceptPopup();
$needStatus = 'Вручен';
$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
##Check results
$I->wait(35000);
$after_array = array();
$after_array = TestCommons::ReadGoodsStatuses($I,8);

$size = count($after_array);
for ($i = 0; $i < $size; ++$i )
{
  $I->canSee($after_array[$i]);
  if ($after_array[$i] != $needStatus)
  {
        $I->see('Error, good status not change');
  }
}



