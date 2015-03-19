<?php
$I = new SGuy($scenario);
$needStatus      ='Запакован';
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

$I->wantTo('Check all packed');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
##Create order for test
TestCommons::EightOrder($I,$test_client);
#$I->wait(25000);
#Manipulation with good statuses
$I->waitForJs($waiting_time, $jsCondition);
#$before_array = array();
#$before_array = TestCommons::ReadGoodsStatuses($I,8);
#$I->wait(25000);

#$I->executeJS('$(".explode_positions.no_position_purchase_comment.button.tiny.radius").click();');
#$I->waitForJs($waiting_time, $jsCondition);
#TestCommons::SetGoodsStatuses($I,8,'Подтвержден');
$I->click('Сохранить');

$I->waitForJs($waiting_time, $jsCondition);

#$I->wait(15000);
$I->selectOption('Order[status]',$needStatus);
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$I->waitForJs($waiting_time, $jsCondition);



#Prepare for Role and Test
$I->selectOption('Order[delivery_type]' ,'Самовывоз из пункта Боксберри');
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]','Товар в офисе');
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$currentUrl = TestCommons::SaveCurrentOrderUrl($I);
TestCommons::LogOut($I);
TestCommons::LoginDelieveryManager($I);
$I->amOnPage($currentUrl);
$I->waitForJs($waiting_time, $jsCondition);
$I->selectOption('Order[status]',$needStatus);
$I->acceptPopup();
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Сохранить');
$I->waitForJs($waiting_time, $jsCondition);
#$I->click('Изменить заказ');
$I->waitForJs($waiting_time, $jsCondition);
$I->executeJS(UpdateOrderPage::PlusButton);
$I->waitForJs($waiting_time, $jsCondition);

#Check Results
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

#$I->wait(25000);

