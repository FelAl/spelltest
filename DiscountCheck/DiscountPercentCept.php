<?php
$I = new DisGuy($scenario);
$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;

#Information about client
$Man_last_name   ='Курпатов';
$Man_first_name  ='Дмитрий';
$Man_middle_name ='Олеговчи';
$Man_phone       ='9202199222';
$Man_email       ='curpatov@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Одинцово';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';

$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);

$discountInRubles  = 500;
$discountInBalance = 2000;

$I->wantTo('Check discount');
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
#Discount in balance add
$I->click('Заказы');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Новый заказ');
$I->acceptPopup();
#Buy new good with discount balance
$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
$I->click('Поиск клиента');
$I->waitForJs($waiting_time,$jsCondition);
$jsConditionVisibleElement = '$("#create_order").is(":visible")';
$I->waitForJs($waiting_time,$jsConditionVisibleElement);
$I->click('Создать заказ');
$I->waitForJs($waiting_time ,$jsCondition);
#Add goods
$I->fillField('photo_link','3');
$I->executeJS("$('input#photo_link').trigger(jQuery.Event('keypress', {keyCode: 13}));");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
$SumWithoutDiscounts  =  $I->grabValueFrom('Order[total_cost]');
$I->click('OK');
$I->waitForJs($waiting_time,$jsCondition);
TestCommons::SetMoscow($I);
TestCommons::NearMetro($I);
TestCommons::FillDate($I);
TestCommons::SaveOrder($I);
$SumWithoutDiscountsPlusDelivery  =  $I->grabValueFrom('Order[total_cost]');
$I->click('Вернуться к заказу');
#$I->See("$SumWithoutDiscounts");
$I->click('Изменить заказ');
#Check discount 5
$I->executeJS('$("#5_p").click()');
$I->waitForJs($waiting_time,$jsCondition);
TestCommons::SaveOrder($I);
$Sum = (string) $SumWithoutDiscounts;
$SumWithp =(((int)$SumWithoutDiscountsPlusDelivery->__toString()) - ((int) $SumWithoutDiscounts->__toString())*0.05);
$SumWithDiscGrab = $I->grabValueFrom('Order[total_cost]');
if ((string) $SumWithDiscGrab !=(string) $SumWithp)
{
  $I->see('Problem with 5 percent');
}
$I->click('Вернуться к заказу');
#$I->See("$SumWithDiscGrab");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time,$jsCondition);
#Check discount 7
$I->executeJS('$("#7_p").click()');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
$Sum = (string) $SumWithoutDiscounts;
$SumWithp =(((int)$SumWithoutDiscountsPlusDelivery->__toString()) - ((int) $SumWithoutDiscounts->__toString())*0.07);
$SumWithDiscGrab = $I->grabValueFrom('Order[total_cost]');
if ($SumWithDiscGrab !=(string) $SumWithp)
{
  $I->see('Problem with 7 percent');
}
$I->click('Вернуться к заказу');
$I->See("$SumWithDiscGrab");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time,$jsCondition);
#Check discount 10
$I->executeJS('$("#10_p").click()');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
$Sum = (string) $SumWithoutDiscounts;
$SumWithp =(((int)$SumWithoutDiscountsPlusDelivery->__toString()) - ((int) $SumWithoutDiscounts->__toString())*0.1);
$SumWithDiscGrab = $I->grabValueFrom('Order[total_cost]');
if ($SumWithDiscGrab !=(string) $SumWithp)
{
  $I->see('Problem with 10 percent');
}
$I->click('Вернуться к заказу');
$I->See("$SumWithDiscGrab");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time,$jsCondition);




























