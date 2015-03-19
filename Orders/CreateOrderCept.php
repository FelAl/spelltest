<?php
$I = new OrGuy($scenario);
#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';

$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
$waiting_time = GlobalJS::WaitingTime;
$I->wantTo('Create new order');
###Test start
TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
$I->see('Выйти');
$I->click('Новый заказ');
$I->acceptPopup();
$I->see('Выйти');
#Find client by phone number
$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
$I->click('Поиск клиента');
$jsCondition = GlobalJS::jQueryActive;
$I->waitForJs($waiting_time, $jsCondition);
$I->click('Создать заказ');
#Set order details
$I->fillField('Client_last_name', $test_client->Model_client_last_name);
$I->fillField('Client_first_name',$test_client->Model_client_first_name);
$I->fillField('Client_middle_name',$test_client->Model_client_middle_name);
$I->fillField('Client_email',$test_client->Model_client_email);
$I->selectOption('Order[region_id]', $test_client->Model_client_region);
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[city_id]',$test_client->Model_client_city);
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[status]','Подтвержден');
$I->acceptPopup();
$I->waitForJs($waiting_time,$jsCondition);
$I->selectOption('Order[delivery_type]','Курьер');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('OK');
$I->click('Указать получателя');
$I->fillField('Order[recipient_last_name]','Иванова');
$I->fillField('Order[recipient_first_name]','Алина');
$I->fillField('Order[recipient_phone]','9201142393');
$I->fillField('Order_comments',$test_client->Model_client_adress);
$I->checkOption('У метро');
$I->fillField('photo_link','2');
$I->executeJS(UpdateOrderPage::PressEnter);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');
$I->click('Сохранить');
$I->click('OK');
$I->waitForJs($waiting_time,$jsCondition);
#Set date
$I->fillField('Order_deliveryDate',         OrderParameters::Date);
$I->fillField('Order_delivery_time_start',  OrderParameters::TimeFrom);
$I->fillField('Order_delivery_time_finish', OrderParameters::TimeTo);
#Choose goods stasus and see it
$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('//*[@id="addPosDialog"]/div/button[3]');
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Отложен');
$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('//*[@id="addPosDialog"]/div/button[1]');
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Подтвержден');
$I->executeJS(UpdateOrderPage::CloseUiWidgetOverlay);
$I->click('Сохранить');
$I->waitForJs($waiting_time,$jsCondition);
$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('//*[@id="addPosDialog"]/div/button[3]');
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Отложен');
$I->executeJS(UpdateOrderPage::CloseUiWidgetOverlay);
$I->click('OK');
$I->click('Сохранить');
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Отложен','//td');
$I->click('Удалить');
$I->acceptPopup();
?>
