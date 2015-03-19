<?php
$I = new RolGuy($scenario);
$I->wantTo('Check that some roles cant move from one status to another');
#Login like admin, create order, create two rules for Client manager and 
#Delivery manager, after this logout...waiting time for check and delete his rules
$jsCondition   = 'jQuery.active == 0';
$waiting_time  = 10000;

TestCommons::LoginCRM($I, "ksenia", "111");
#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Афтандил';
$Man_middle_name ='Альбертович';
$Man_phone       ='9202199999';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
#Create Order
TestCommons::CreateOrder($I,$test_client);
#save order url
$orderUrl =  $I->grabFromCurrentUrl('/(index.*)/');
#Go on rules page
$I->amOnPage('index.php?r=orderStatusTransition/index');
#Create->Wainting money || Deny for client manager
$I->amOnPage('index.php?r=orderStatusTransition/update&id=5');
#$I->executeJS('$("#roles_2").click();');
$I->checkOption('#roles_1');
$I->click('Сохранить');
#Create->Unioun with other   || Deny for delivery manager
$I->amOnPage('index.php?r=orderStatusTransition/update&id=6');
#$I->executeJS('$("#roles_1").click();');
$I->checkOption('#roles_0');
$I->click('Сохранить');
##Go on orders page and see two bannes status
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Ждем предоплату');
$I->see('Объединен с другим');
#$I->see('Медведь');
$I->click('Все заказы');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Выйти');
$I->waitForJs($waiting_time,$jsCondition);

####Client manager
TestCommons::LoginCRM($I, "aryabova", "111");
$I->waitForJs($waiting_time,$jsCondition);
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->wait(25000);
$I->dontSee('Ждем предоплату');
$I->see('Объединен с другим');
$I->click('Все заказы');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Выйти');
$I->waitForJs($waiting_time,$jsCondition);
$I->wantTo('Hi');
###Delivery manager
TestCommons::LoginCRM($I, "alexander", "111");
$I->waitForJs($waiting_time,$jsCondition);
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->See('Ждем предоплату');
$I->dontSee('Объединен с другим');
$I->click('Все заказы');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Выйти');
$I->waitForJs($waiting_time,$jsCondition);
#Admin clear
TestCommons::LoginCRM($I, "ksenia", "111");
#Deny Create->Wainting money || Deny for client manager
$I->amOnPage('index.php?r=orderStatusTransition/update&id=5');
$I->executeJS('$("#roles_1").click();');
$I->dontSeeCheckboxIsChecked('#roles_1');
$I->click('Сохранить');
#Deny Create->Unioun with other   || Deny for delivery manager
$I->amOnPage('index.php?r=orderStatusTransition/update&id=6');
$I->executeJS('$("#roles_0").click();');
$I->dontSeeCheckboxIsChecked('#roles_0');
$I->click('Сохранить');
#Delete order
$I->amOnPage($orderUrl);
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Удалить');
$I->acceptPopup();
