<?php
$I = new PPGuy($scenario);
$I->wantTo('check that prepayment required field');
$login    ='ksenia';
$password ='ME3ahE8H';
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

$waiting_time           = 10000;
$discountInRubles       = 0;
$discountInBalance      = 0;
$prepaymentGrabFromPage = 0;
$prepayment             = 3000;

$jsCondition = 'jQuery.active == 0';
TestCommons::LoginCRM($I,$login, $password);
$I->see('Выйти');
#Discount in balance add, Select zero in balanc
$I->amOnPage('index.php?r=client/view&id=51786');
$I->waitForJs($waiting_time ,$jsCondition);
$I->click('Изменить');
$I->waitForJs($waiting_time ,$jsCondition);
$I->fillField('Client[credit]',$discountInBalance);
$I->click('Save');

#Buy new good with discount balance
$I->click('Заказ');
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


#$I->wait(10000);

#Debug
#$I->wait(10000);
#$I->click('payment_types');
$I->click('//*[@id="payment_types"]/option[4]');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('//*[@id="payment_status"]/option[2]');
$I->waitForJs($waiting_time,$jsCondition);
#$I->fillField('//*[@id="prepaymentDialog"]/div/div/div[2]/input', $prepayment);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Установить размер предоплаты');
$I->executeJS('$(".ui-icon.ui-icon-closethick").click();');
#$I->wait(25000);
$I->click('Сохранить');
#$I->wait(10000);

$I->waitForJs($waiting_time,$jsCondition);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->wait(25000);
#$I->dontsee( "1250", '//*[@id="yw2"]/tbody/tr[2]');
$I->dontsee( 'Оплачен');
#Save current url ??!?
