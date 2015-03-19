<?php
$I = new OrGuy($scenario);
$I->wantTo('Check all statuses of popular order life cycle');

if (!function_exists('StatusChangeAndCheck'))
{
	 function StatusChangeAndCheck($I,$new_Status)
	{
		$jsCondition  =  GlobalJS::jQueryActive;
		$waiting_time =  GlobalJS::WaitingTime;
		$I->click('Изменить заказ');
		$I->waitForJs($waiting_time,$jsCondition);
		$I->selectOption('Order[status]',$new_Status);
		$I->acceptPopup();
		$I->click('Сохранить');
		$I->wait(30000);
		$I->waitForJs($waiting_time,$jsCondition);
		#$I->wait(15000);
		$I->click('Вернуться к заказу');
		#$I->acceptPopup();
		$I->see($new_Status, '//*[@id="content"]/div[2]/div/div[1]');
		#$I->acceptPopup();
	}
}

#Information about client
$Man_last_name   ='Петренко';
$Man_first_name  ='Гертруд';
$Man_middle_name ='Альбертович';
$Man_phone       ='2902199990';
$Man_email       ='newclient@glient.ru';
$Man_region      ='Москва';
$Man_city        ='Химки';
$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';

$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);

$jsCondition  =  GlobalJS::jQueryActive;
$waiting_time =  GlobalJS::WaitingTime;

Testcommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
Testcommons::CreateOrderAdr($I, $test_client);
$I->click('Вернуться к заказу');
$I->waitForJs($waiting_time,$jsCondition);
$I->see('Создан','//*[@id="content"]/div[2]/div/div[1]');

#StatusChangeAndCheck($I,  'Подтвержден');
StatusChangeAndCheck($I,  'Товар в офисе');
StatusChangeAndCheck($I,	'Запакован');
StatusChangeAndCheck($I,	'Отправлен');
StatusChangeAndCheck($I,	'Прибыл');
StatusChangeAndCheck($I,	'Вручен');
StatusChangeAndCheck($I,	'Оплачен');
