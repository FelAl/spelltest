<?php
class TaskCommons
{ 
	public static function FCReceiverAdd($I,$needStatus)
  {
		$Man_last_name   ='Петренко';
		$Man_first_name  ='Гертруд';
		$Man_middle_name ='Альбертович';
		$Man_phone       ='9202199999';
		$Man_email       ='newclient@glient.ru';
		$Man_region      ='Москва';
		$Man_city        ='Химки';
		$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
		$test_client     = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
	  $jsCondition  =  GlobalJS::jQueryActive;
	  $waiting_time =  GlobalJS::WaitingTime;
		$I->wantTo('perform actions and see result');
		TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
		TestCommons::CreateOrderParamCourier($I, $test_client); #Create order without receiver

		####Current Status
		$I->selectOption('Order[status]',$needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#Changes for task create
		$I->click('Указать получателя');
		$I->fillField('Order[recipient_last_name]','Иванова');
		$I->fillField('Order[recipient_first_name]','Алина');
		$I->fillField('Order[recipient_phone]','9201142393');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('Перепечатать накладную на заказ №'.$OrderId);
		$I->see('Исполнить','//tr');
   }

  public static function  FCReceiverNumberChange($I,$needStatus)
  {
		$Man_last_name   ='Петренко';
		$Man_first_name  ='Гертруд';
		$Man_middle_name ='Альбертович';
		$Man_phone       ='9202199999';
		$Man_email       ='newclient@glient.ru';
		$Man_region      ='Москва';
		$Man_city        ='Химки';
		$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
		$test_client     = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
		$I->wantTo('perform actions and see result');
		TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
		TestCommons::CreateOrderParamCourier($I, $test_client); #Create order without receiver
		$I->click('Указать получателя');
		$I->fillField('Order[recipient_last_name]','Иванова');
		$I->fillField('Order[recipient_first_name]','Алина');
		$I->fillField('Order[recipient_phone]','9201142393');

		####Current Status
		$I->selectOption('Order[status]',$needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		#Changes for task create
		$I->fillField('Order[recipient_phone]','9201142395');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('Перепечатать накладную на заказ №'.$OrderId);
		$I->see('Исполнить','//tr');
  }

  public static function FCCustomerNumberChanged($I, $needStatus)
  {
		$Man_last_name   ='Петренко';
		$Man_first_name  ='Гертруд';
		$Man_middle_name ='Альбертович';
		$Man_phone       ='9202199999';
		$Man_email       ='newclient@glient.ru';
		$Man_region      ='Москва';
		$Man_city        ='Химки';
		$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
		$test_client     = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);
	  $jsCondition  =  GlobalJS::jQueryActive;
	  $waiting_time =  GlobalJS::WaitingTime;
		$I->wantTo('perform actions and see result');
		TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
		$I->click('Заказы');
		$I->waitForJs($waiting_time,$jsCondition);
		TestCommons::CreateOrderParamCourier($I, $test_client); #Create order without receiver
		####Current Status
		TestCommons::SaveOrder($I);
		$I->executeJS(UpdateOrderPage::ClickOnTheFirstPositionInOrder);
		$I->waitForJs($waiting_time,$jsCondition);
		$I->click('Запакован');
		$I->waitForJs($waiting_time,$jsCondition);
		TestCommons::SaveOrder($I);
		$I->selectOption('Order[status]', $needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		TestCommons::SaveOrderId($I);
		#Changes for task create
    #$I->wait(50000);
		$I->fillField('Client[phone_number]','9201142211');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		#$I->acceptPopup();
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('Перепечатать накладную на заказ №'.$OrderId);
		$I->see('Исполнить','//tr');
		#----Delete Order
		$I->click('Удалить заказ');
		$I->acceptPopup();
  }

  public static function FCustomerNumberChanged($I,$needStatus)
  {
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

		$I->wantTo('perform actions and see result');
		TestCommons::CreateOrderParam($I, $test_client); #Create order without receiver
		####Current Status
    if ($needStatus == 'Отправлен')
    {
      $I->selectOption('Order[delivery_type]','Самовывоз из пункта Боксберри');
      $I->waitForJs($waiting_time,$jsCondition);
      $I->click('Сохранить');
    }
		$I->selectOption('Order[status]',$needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#Changes for task create
		$I->fillField('Client_phone_number', '9204443322');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Сохранить');
		#
		$I->waitForJs($waiting_time, $jsCondition);
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('В заказе №'.$OrderId. ' изменился номер телефона');
		$I->see('Исполнить','//tr');
  }

  public static function FReceiverAdd($I, $needStatus)
  {
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

		$I->wantTo('perform actions and see result');
		TestCommons::CreateOrderParam($I, $test_client); #Create order without receiver
		####Current Status
		$I->selectOption('Order[status]', $needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		#Changes for task create
		$I->click('Указать получателя');
		$I->fillField('Order[recipient_last_name]','Иванова');
		$I->fillField('Order[recipient_first_name]','Алина');
		$I->fillField('Order[recipient_phone]','9201142393');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		if ($needStatus == 'Отправлен')
	  {
      $I->selectOption('Order[delivery_type]','Самовывоз из пункта Боксберри');
      $I->waitForJs($waiting_time,$jsCondition);
      $I->click('Сохранить');
	  }
    #
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('В заказе №'.$OrderId. ' изменился номер телефона');
		$I->see('Исполнить','//tr');
  }
  
  public static function FReceiverNumberChange($I, $needStatus)
  {
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

		$I->wantTo('perform actions and see result');
		TestCommons::CreateOrderParam($I, $test_client); #Create order without receiver
		$I->click('Указать получателя');
		$I->fillField('Order[recipient_last_name]','Иванова');
		$I->fillField('Order[recipient_first_name]','Алина');
		$I->fillField('Order[recipient_phone]','9201142393');
		if ($needStatus == 'Отправлен')
    {
      $I->selectOption('Order[delivery_type]','Самовывоз из пункта Боксберри');
      $I->waitForJs($waiting_time,$jsCondition);
      $I->click('Сохранить');
    }
		####Current Status
		$I->selectOption('Order[status]',$needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		#Changes for task create
		$I->fillField('Order[recipient_phone]','9201142395');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('В заказе №'.$OrderId. ' изменился номер телефона');
		$I->see('Исполнить','//tr'); 
  }


  public static function FAdressChange($I, $needStatus)
  {
	  $Man_last_name   ='Петренко';
		$Man_first_name  ='Гертруд';
		$Man_middle_name ='Альбертович';
		$Man_phone       ='9202199999';
		$Man_email       ='newclient@glient.ru';
		$Man_region      ='Москва';
		$Man_city        ='Химки';
		$Man_adress      ='Российская федерация, город Москва, район Химки, улица Тимирязева, дом 34, квартира 35';
		$test_client = new TestClient($Man_last_name, $Man_first_name, $Man_middle_name, $Man_phone, $Man_email, $Man_region, $Man_city, $Man_adress);

		$jsCondition  =  GlobalJS::jQueryActive;
		$waiting_time =  GlobalJS::WaitingTime;

		$I->wantTo('perform actions and see result');
		TestCommons::LoginCRM($I, Users::AdminKseniaName, Users::AdminKseniaPassword);
		TestCommons::CreateOrderParamCourier($I, $test_client); #Create order without receiver

		####Current Status
		$I->selectOption('Order[status]', $needStatus);
		$I->acceptPopup();
		$I->waitForJs($waiting_time,$jsCondition);
		#Changes for task create

		$I->fillField('Client[phone_number]','9201142211');
		$I->click('Сохранить');
		$I->waitForJs($waiting_time, $jsCondition);
		#
		$OrderId = TestCommons::SaveOrderId($I);
		$I->see($OrderId);
		$I->click('Вернуться к заказу');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->click('Задания');
		$I->waitForJs($waiting_time, $jsCondition);
		$I->see('Перепечатать накладную на заказ №'.$OrderId);
		$I->see('Исполнить','//tr');
  }

}
?>
