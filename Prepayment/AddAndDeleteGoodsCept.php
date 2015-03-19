<?php
$I = new PPGuy($scenario);
$I->wantTo('check that prepayment return and delete when we manipulate with goods');
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

$discountInRubles       = 0;
$discountInBalance      = 5000;
$prepaymentGrabFromPage = 0;
$prepayment             = 3000;
$client_id              = 51786 ;
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
#Discount in balance add, Select zero in balanc
TestCommons::ClearDiscountOfClient($I, $client_id);
TestCommons::SetDiscountOfClient($I, $client_id, $discountInBalance);

#Buy new good with discount balance
$I->amOnPage('/');
$I->click('Заказы');
$I->waitForJs($waiting_time ,$jsCondition);
$I->click('Новый заказ');
$I->acceptPopup();
$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
$I->click('Поиск клиента');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Создать заказ');
$I->waitForJs($waiting_time ,$jsCondition);
#Add goods
$I->fillField('photo_link','3');
$I->executeJS("$('input#photo_link').trigger(jQuery.Event('keypress', {keyCode: 13}));");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');


$I->waitForJs($waiting_time,$jsCondition);
#Check discount
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
#Debug
$I->click('OK');
$I->fillField('Client[first_name]','ТестИмя');
TestCommons::FillDate($I);
#$I->wait(35000);
$I->click('//*[@id="payment_types"]/option[4]');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('//*[@id="payment_status"]/option[2]');
$I->waitForJs($waiting_time,$jsCondition);
$I->fillField('//*[@id="prepaymentDialog"]/div/div/div[2]/input', $prepayment);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Установить размер предоплаты');
$I->waitForJs($waiting_time,$jsCondition);
TestCommons::SaveOrder($I);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->wait(50000);

#$I->see( "1250", "#yw2 .even td:first");
#Save current url ??!?
$orderUrl =  $I->grabFromCurrentUrl('/(index.*)/'); # regex Ok
$I->amOnPage('index.php?r=client/view&id=51786');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить');
$I->waitForJs($waiting_time,$jsCondition);
$val = $I->grabValueFrom('Client[credit]');
If ($val != (string) 1750)
{
  $I->see('Problems with minus prepayment1');
}
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time,$jsCondition);
#Add goods second time
$I->fillField('photo_link','3');
$I->executeJS("$('input#photo_link').trigger(jQuery.Event('keypress', {keyCode: 13}));");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');
$I->waitForJs($waiting_time,$jsCondition);
#Check discount
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
##See balance again
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->see( "1250", '//*[@id="yw2"]/tbody/tr[2]');
$I->see( "1250", '//*[@id="yw2"]/tbody/tr[4]');
#Save current url ??!?
$orderUrl =  $I->grabFromCurrentUrl('/(index.*)/'); # regex Ok
$I->amOnPage('index.php?r=client/view&id=51786');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить');
$I->waitForJs($waiting_time,$jsCondition);
$val = $I->grabValueFrom('Client[credit]');
If ($val != (string) 500)
{
  $I->see('Problems with minus prepayment');
}
##Delete one good
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить заказ');
$I->waitForJs($waiting_time,$jsCondition);
$I->executeJS('$(".explode_positions.no_position_purchase_comment.button.tiny.radius").click();');
$I->waitForJs($waiting_time,$jsCondition);
$I->executeJS('$(".tiny.radius.button.updateOPstatus.orderPayed").click();');
$I->waitForJs($waiting_time,$jsCondition);
try 
{
  #$I->click('//*[@id="addPosDialog"]/div/button[2]');
  $I->click('Отказ');
  $I->waitForJs($waiting_time,$jsCondition);
} 
catch(Exception $e)
{

}
$I->acceptPopup();
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
##
$I->waitForJs($waiting_time,$jsCondition);
$I->amOnPage('index.php?r=client/view&id=51786');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Изменить');
$I->waitForJs($waiting_time,$jsCondition);
$val = $I->grabValueFrom('Client[credit]');
If ($val != (string) 1750)
{
  $I->see('Problems with return prepayment');
}













