<?php
$I = new SGuy($scenario);
$I->wantTo('check that after order status is "sent", all goods status is sent too');
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
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
$needStatus="Отправлен";
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SetGoodsStatuses($I,8,'Подтвержден');
TestCommons::SaveOrder($I);
$I->selectOption('Order[status]',$needStatus);
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
TestCommons::SaveOrder($I);
$my_array = array();
$my_array = TestCommons::ReadGoodsStatuses($I,8);
for ($i = 0; $i < 8; ++$i)
{
  if ($my_array[$i] != $needStatus)
  {
    $I->see('Problems with sent status');
  }
}

