<?php
$I = new SGuy($scenario);
$needStatus      ='Идет обратно';
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

$I->wantTo('Check All come back');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
#Prepare for Role and Test
$I->selectOption('Order[delivery_type]' ,'Самовывоз из пункта Боксберри');
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]','Отправлен');
$I->acceptPopup();
#$I->wait(30000);
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginDelieveryManager($I);
$I->amOnPage($currentUrl);
#$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]',$needStatus);
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
#$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
##Check results
#$I->wait(30000);
$I->waitForJs($waiting_time, $jsCondition);
#$I->selectOption('Order[status]',$needStatus);
#$I->fillField("Order[cancel_status_reason]",'cause');
#$I->click('//*[@id="save_status_reason"]');
#$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
#$I->acceptPopup();

$I->waitForJs($waiting_time, $jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
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
