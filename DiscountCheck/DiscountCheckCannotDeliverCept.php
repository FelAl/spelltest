<?php
$I = new DisGuy($scenario);
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;
#Information about client
$Man_last_name   ='Гусев';
$Man_first_name  ='Герман';
$Man_middle_name ='Олеговчи';
$Man_phone       ='2403323565';
$Man_email       ='curpatov@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Одинцово';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';


$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);

$discountInRubles     = 500;
$discountInBalance    = 2000;
$discountGrabFromPage = 0;

$client_in_test_id = 56983;

$I->wantTo('Check client balance when order is deleted');
Testcommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
TestCommons::ClearDiscountOfClient($I, $client_in_test_id);
TestCommons::AddToClientBalance($I, $client_in_test_id,  $discountInBalance);
#Buy new good with discount balance
$I->click('Заказ');
$I->click('Новый заказ');
$I->acceptPopup();
$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
$I->click('Поиск клиента');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Создать заказ');
$I->waitForJs($waiting_time ,$jsCondition);
#####Prepare order
$I->fillField('photo_link', Items::OneItemId);
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');
$I->waitForJs($waiting_time,$jsCondition);
$order_id = TestCommons::SaveOrderId($I);
TestCommons::SetMoscow($I);
TestCommons::NearMetro($I);
TestCommons::FillDate($I);
TestCommons::SaveOrder($I);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
$buf = TestCommons::TakeDiscountFromOrder($I, $order_id)->__toString();
$I->see($buf);
$value_i_see_in_reminder = $discountInBalance - intval($buf);
$I->see($buf);
$I->click('Изменить');
$I->waitForJs($waiting_time,$jsCondition);



$discountGrabFromPage = $I->grabValueFrom('Order[balance_discount]');
if ($discountGrabFromPage != (string) $buf):
{
  $I->see("Can't see new discount balance");
}
endif;
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Вернуться к заказу');
$I->see( $buf, "#yw2 tr:nth-child(3) td");
#Save current url ??!?
$orderUrl =  $I->grabFromCurrentUrl('/(index.*)/'); # regex Ok
$I->amOnPage("index.php?r=client/view&id=".$client_in_test_id);
$I->waitForJs($waiting_time,$jsCondition);
#$I->click('Изменить');
#$I->waitForJs($waiting_time,$jsCondition);
$val = TestCommons::ReadDiscountOfClient($I, $client_in_test_id);
If ($val != (string) ($value_i_see_in_reminder))
{
  $I->see('Problems with minus balance');
}

#Order page
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->selectOption('Order_status','Не смогли доставить');
#$I->selectOption('Order_status','Отказ');
$I->acceptPopup();
$I->fillField('Order[cancel_status_reason]', 'Some reasons. Test');
$I->click('#save_status_reason');
$I->waitForJs($waiting_time, $jsCondition);
#$I->fillField("Order[postponed_date]", CurrentDate::CurrentDate);
#$I->wait(35000);
#TestCommons::SaveOrder($I);;
$I->waitForJs($waiting_time,$jsCondition);

#Client page
$I->amOnPage('index.php?r=client/view&id='.$client_in_test_id);
$I->waitForJs($waiting_time,$jsCondition);
$val = TestCommons::ReadDiscountOfClient($I, $client_in_test_id);
If ($val != (string) $discountInBalance)
{
  $I->see('Problems with return balance');
}

