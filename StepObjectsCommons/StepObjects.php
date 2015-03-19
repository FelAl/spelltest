<?php
class TestCommons
{
  public static function LoginCRM($I, $username, $pass)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->amOnPage('/');
    $I->see('Логин');
    $I->fillField('Логин',$username);
    $I->fillField('Пароль',$pass );
    $I->click('Войти');
    $I->waitForJs($waiting_time,$jsCondition);
    #$I->see('Выйти');
  }

  public static function ReturnToBalanceDiscount($I, $client_id, $status)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $Man_last_name   ='Познер';
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
    $client_in_test_id = $client_id;
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
    $I->selectOption('Order_status', $status);
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
  }

  public static function ReturnToBalanceDiscountLost($I, $client_id, $status)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $Man_last_name   ='Познер';
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
    $client_in_test_id = $client_id;
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
    $I->selectOption('Order_status', $status);
    #$I->selectOption('Order_status','Отказ');
    $I->acceptPopup();
    #####$I->fillField('Order[cancel_status_reason]', 'Some reasons. Test');
    #####$I->click('#save_status_reason');
    TestCommons::SaveOrder($I);


    #Client page
    $I->amOnPage('index.php?r=client/view&id='.$client_in_test_id);
    $I->waitForJs($waiting_time,$jsCondition);
    $val = TestCommons::ReadDiscountOfClient($I, $client_in_test_id);
    $I->wait(5000);
    If ($val != (string) $discountInBalance)
    {
      $I->see('Problems with return balance');
    }
  }














  public static function FillDate($I)
  {
    $I->fillField('Order_deliveryDate',         OrderParameters::Date);
    $I->fillField('Order_delivery_time_start',  OrderParameters::TimeFrom);
    $I->fillField('Order_delivery_time_finish', OrderParameters::TimeTo);
    $I->executeJS("$('.ui-state-default.ui-state-active').click();");
  }

  public static function ClearDiscountOfClient($I, $client_id)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->amOnPage('index.php?r=client/view&id='.$client_id);
    $I->waitForJs($waiting_time ,$jsCondition);
    $current_balance = $I->grabTextFrom(ViewClientPage::Client_balance);
    $I->see($current_balance);
    TestCommons::SetDiscountOfClient($I,$client_id, "-".$current_balance);
  }

  public static function SetDiscountOfClient ($I, $client_id, $balance_sum )
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->amOnPage('index.php?r=client/view&id='.$client_id);
    $I->waitForJs($waiting_time ,$jsCondition);
    $I->click('Начислить баланс');
    $I->waitForJs($waiting_time ,$jsCondition);
    $I->fillField('ClientBalanceHistory[amount]',$balance_sum);
    $I->fillField('ClientBalanceHistory[comment]', 'ком');
    #$I->wait(5000);
    $I->click('.balance-up-form button');
    $I->waitForJs($waiting_time ,$jsCondition);
  }

  public function ReadDiscountOfClient($I, $client_id)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;

    return $I->grabTextFrom(ViewClientPage::Client_balance);

  }



  public static function SetReceiver($I)
  { 
    $I->click('Указать получателя');
    $I->fillField('Order[recipient_last_name]','Иванова');
    $I->fillField('Order[recipient_first_name]','Алина');
    $I->fillField('Order[recipient_phone]','9201142393');
  }



  public static function SetMoscow($I)
  {  
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->selectOption('Order[region_id]', 'Москва');
    $I->waitForJs($waiting_time,$jsCondition);
    $I->selectOption('Order[city_id]', 'Люберцы');
    $I->waitForJs($waiting_time,$jsCondition);
  }

  public static function NearMetro($I)
  {
    $I->checkOption('У метро');
    $I->selectOption('Order_subway_station_id', 'Авиамоторная');
  }

  public function TakePriceFromOrder($I, $order_id)
  {
    $I->amOnPage('/index.php?r=order/view&id='.$order_id);
    $val = $I->grabTextFrom('.order_view_top_total');
    return $val;
  }


    public function TakeDiscountFromOrder($I, $order_id)
  {
    $I->amOnPage('/index.php?r=order/view&id='.$order_id);
    #$I->wait(50000);
    $val = $I->grabTextFrom("#yw2 tr:nth-child(3) td");
    return $val;
  }




  public static function FillDetailsOfTheOrder($I, $test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->fillField('Client_last_name', $test_client->Model_client_last_name);
    $I->fillField('Client_first_name',$test_client->Model_client_first_name);
    $I->fillField('Client_middle_name',$test_client->Model_client_middle_name);
    $I->fillField('Client_email',$test_client->Model_client_email);
    $I->selectOption('Order[region_id]', $test_client->Model_client_region);
    $I->waitForJs($waiting_time,$jsCondition);
    $I->selectOption('Order[city_id]',$test_client->Model_client_city);
    $I->waitForJs($waiting_time,$jsCondition);
  }

  public static function CreateEmptyOrder($I , $phone_number)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;  
    $I->click('Заказы');
    $I->waitForJs($waiting_time,$jsCondition);
    $I->click('Новый заказ');
    $I->acceptPopup();
    #Find client by phone number
    $I->fillField('//*[@id="phone"]', $phone_number);
    $I->click('Поиск клиента');
    $I->waitForJs($waiting_time, $jsCondition);
    $jsConditionVisibleElement = '$("#create_order").is(":visible")';
    $I->waitForJs($waiting_time,$jsConditionVisibleElement);
    $I->click('Создать заказ');
    $I->waitForJs($waiting_time, $jsCondition);
    $I->fillField('Client_first_name', "имя");
    $I->selectOption('Order[region_id]', 'Москва');
    $I->waitForJs($waiting_time, $jsCondition);
    #$I->wait(25000);
    TestCommons::NearMetro($I);
    TestCommons::FillDate($I);
    $I->click('Сохранить');
    $I->waitForJs($waiting_time, $jsCondition);
  }

  public static function SendSMS($I, $text)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime; 
    $I->click('Вернуться к заказу');
    $I->waitForJs($waiting_time, $jsCondition);
    ## Send SMS
    $I->click('SMS');
    $I->waitForJs($waiting_time, $jsCondition);
    $I->click('Отправить SMS');
    $I->waitForJs($waiting_time, $jsCondition);
    $I->click('//*[@id="sms-form"]/select/option[2]');
    #$I->executeJS('$(".sms_template_select").val("orderCantCall");');
    $I->waitForJs($waiting_time, $jsCondition);
    $I->click('Создать');
    $I->waitForJs($waiting_time, $jsCondition);
    #$I->wait(25000);
    

  }

  public static function CreateOrder($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
## for function
    $I->click('Заказы');
    $I->waitForJs($waiting_time, $jsCondition);

    $I->click('Новый заказ');
  	$I->acceptPopup();
  	#$I->see('Выйти');
  	#Find client by phone number
  	$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
  	$I->click('Поиск клиента');
  	$I->waitForJs($waiting_time, $jsCondition);
  	$jsConditionVisibleElement = '$("#create_order").is(":visible")';
  	$I->waitForJs($waiting_time,$jsConditionVisibleElement);
  	$I->click('Создать заказ');
  	$I->waitForJs($waiting_time, $jsCondition);
  	#Set order details
    TestCommons::FillDetailsOfTheOrder($I, $test_client);
  	$I->selectOption('Order[delivery_type]','Курьер');
  	$I->waitForJs($waiting_time,$jsCondition);
    TestCommons::SetReceiver($I);
  	$I->fillField('Order_comments',$test_client->Model_client_adress);
    TestCommons::NearMetro($I);
    $I->click('Сохранить');
    $I->waitForJs($waiting_time,$jsCondition);
  	$I->fillField('photo_link',  Items::ThreeItemId);
  	$I->executeJS( UpdateOrderPage::PressEnter );
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->click('Добавить товар');
  	$I->waitForJs($waiting_time,$jsCondition);
  	#Set date
    TestCommons::FillDate($I);
  	$I->click('Сохранить');
  }

  public static function CreateOrderSD($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    ## for function
    $I->click('Заказы');
    $I->waitForJs($waiting_time, $jsCondition);


    $I->click('Новый заказ');
  	$I->acceptPopup();
  	#$I->see('Выйти');
  	#Find client by phone number
  	$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
  	$I->click('Поиск клиента');
  	$I->waitForJs($waiting_time, $jsCondition);
  	$jsConditionVisibleElement = '$("#create_order").is(":visible")';
  	$I->waitForJs($waiting_time,$jsConditionVisibleElement);
  	$I->click('Создать заказ');
  	$I->waitForJs($waiting_time, $jsCondition);
  	#Set order details
    TestCommons::FillDetailsOfTheOrder($I, $test_client);
  	#$I->selectOption('Order[status]','Создан');
    #$I->acceptPopup();
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->selectOption('Order[delivery_type]','СДЭК');
  	$I->waitForJs($waiting_time,$jsCondition);
    TestCommons::SetReceiver($I);
    $I->fillField('Order_comments',$test_client->Model_client_adress);


  	$I->fillField('photo_link',Items::TwoItemId);
  	$I->executeJS(UpdateOrderPage::PressEnter);
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->click('Добавить товар');
  	$I->waitForJs($waiting_time,$jsCondition);
  	#Set date
    $I->fillField('//*[@id="Order_deliveryAddress_street"]','Одинцово');
    $I->fillField('//*[@id="Order_deliveryAddress_house"]', '64');
    $I->fillField('//*[@id="Order_deliveryAddress_flat"]','92');
    ###
    $I->click('Сохранить');
  }

    public static function CreateOrderSDSpec($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    ## for function
    $I->click('Заказы');
    $I->waitForJs($waiting_time, $jsCondition);


    $I->click('Новый заказ');
    $I->acceptPopup();
    #$I->see('Выйти');
    #Find client by phone number
    $I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
    $I->click('Поиск клиента');
    $I->waitForJs($waiting_time, $jsCondition);
    $jsConditionVisibleElement = '$("#create_order").is(":visible")';
    $I->waitForJs($waiting_time,$jsConditionVisibleElement);
    $I->click('Создать заказ');
    $I->waitForJs($waiting_time, $jsCondition);
    #Set order details
    TestCommons::FillDetailsOfTheOrder($I, $test_client);
    $I->waitForJs($waiting_time, $jsCondition);
    #$I->selectOption('Order[status]','Создан');
    #$I->acceptPopup();
    $I->waitForJs($waiting_time,$jsCondition);
    $I->selectOption('Order[delivery_type]','Самовывоз из пункта Боксберри');
    $I->waitForJs($waiting_time,$jsCondition);



    TestCommons::SetReceiver($I);
    #$I->fillField('Order_comments',$test_client->Model_client_adress);
    $I->fillField('photo_link',Items::TwoItemId);
    $I->executeJS(UpdateOrderPage::PressEnter);
    $I->waitForJs($waiting_time,$jsCondition);
    $I->click('Добавить товар');
    $I->waitForJs($waiting_time,$jsCondition);
    #Set date
    #$I->fillField('//*[@id="Order_deliveryAddress_street"]','Одинцово');
    #$I->fillField('//*[@id="Order_deliveryAddress_house"]', '64');
    #$I->fillField('//*[@id="Order_deliveryAddress_flat"]','92');
    ###
    $I->click('Сохранить');
  }











  public static function CreateOrderAdr($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->click('Новый заказ');
  	$I->acceptPopup();
  	$I->see('Выйти');
  	#Find client by phone number
  	$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
  	$I->click('Поиск клиента');
  	$I->waitForJs($waiting_time, $jsCondition);
  	$jsConditionVisibleElement = '$("#create_order").is(":visible")';
  	$I->waitForJs($waiting_time,$jsConditionVisibleElement);
  	$I->click('Создать заказ');
  	$I->waitForJs($waiting_time, $jsCondition);
  	#Set order details

    TestCommons::FillDetailsOfTheOrder($I, $test_client);
  	$I->selectOption('Order[status]','Создан');
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->selectOption('Order[delivery_type]', 'Самовывоз');
  	$I->waitForJs($waiting_time,$jsCondition);	
    TestCommons::SetReceiver($I);
  	$I->fillField('photo_link', Items::OneItemId);
  	$I->executeJS(UpdateOrderPage::PressEnter);
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->click('Добавить товар');          
  	$I->waitForJs($waiting_time,$jsCondition);
  	#Set date
    TestCommons::FillDate($I);
  	$I->click('Сохранить');
    $I->waitForJs($waiting_time,$jsCondition);
    $I->selectOption('Order[status]','Создан');
    $I->click('Сохранить');
  }

  public function ReadGoodsStatuses($I,$number)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    # Another loop must be here !
    $I->executeJS('$(".explode_positions.no_position_purchase_comment.button.tiny.radius").click();');
    $I->executeJS('$("body").append($("<input type=\"text\" id=\"myInput\"/>"));');
    $oneArray = array();
    $buf      = '';
    for ($i = 0; $i < $number; ++$i)
    {
      $I->executeJS('$("#myInput").val($($(".updateOPstatus")['.$i.']).html( ) );');
      $I->waitForJs($waiting_time, $jsCondition);
      $buf = $I->grabValueFrom('//*[@id="myInput"]');
      array_push($oneArray, $buf);
      $I->waitForJs($waiting_time, $jsCondition);
    }
    return $oneArray;
  }

  public function SetGoodsStatuses($I,$number,$NeedStatus)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    for($i=0 ; $i < $number ; ++$i)
    {
      $I->executeJS(UpdateOrderPage::PlusButton);
      $I->waitForJs($waiting_time, $jsCondition);
      $I->executeJS( '$(".updateOPstatus")['.$i.'].click();');
      $I->waitForJs($waiting_time, $jsCondition);
      $I->click($NeedStatus);
      $I->waitForJs($waiting_time, $jsCondition);
    }  
  }

  public function EightOrder($I,$test_client)
  {     
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    TestCommons::CreateOrderSDSpec($I,$test_client);
    $I->fillField('photo_link', Items::OneItemId);
    $I->executeJS(UpdateOrderPage::PressEnter);
    $I->waitForJs($waiting_time, $jsCondition);
    for ($i=1; $i <= 6; ++$i )
      {
        $I->click('Добавить товар');
    	  $I->waitForJs($waiting_time, $jsCondition);
      }
    $I->waitForJs($waiting_time, $jsCondition);
    $I->fillField('photo_link',Items::OneItemId);
    $I->executeJS(UpdateOrderPage::PressEnter);
    $I->waitForJs($waiting_time, $jsCondition);
    $I->click('Добавить товар');
    ###for function
    $I->waitForJs($waiting_time,  $jsCondition);
    TestCommons::SaveOrder($I);
  }

    public function specialCompare($I,$arrayOne,$arrayTwo,$Before_Status,$After_Status)
    {
      $size1 = count($arrayOne);
      $size2 = count($arrayTwo);
      if ($size1 != $size2 or $size1 == 0 )
      {
        $I->see('Problem with arrays size');
      }
      for ($i = 0; $i < $size1; ++$i)
      {
        if($arrayOne[$i] == $Before_Status and $arrayTwo[$i] != $After_Status)
        {
          $I->canSee($arrayTwo[$i]);
          $I->see('Some statuses not changed');
        }
      }
    }
  
  public function LoginClientManager($I)
  { 
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->waitForJs($waiting_time, $jsCondition);
    TestCommons::LoginCRM($I, Users::ClientManagerTestName, Users::ClientManagerTestPassword);
    $I->waitForJs($waiting_time, $jsCondition);
  }

  public function LoginDelieveryManager($I)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->waitForJs($waiting_time, $jsCondition);
    TestCommons::LoginCRM($I, Users::DeliveryManagerTestName, Users::DeliveryManagerTestPassword);
    $I->waitForJs($waiting_time, $jsCondition);
  }

  public function LogOut($I)
  { 
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->waitForJs($waiting_time, $jsCondition);
    $I->amOnPage('/');
    #$I->click('Выйти');
    #Temprorary !!!!!!!!!
    $I->click('Logout');
    #$I->wait(35000);
    $I->waitForJs($waiting_time, $jsCondition);
  }

  public function SaveCurrentOrderUrl($I)
  {
    $orderUrl =  $I->grabFromCurrentUrl('/(index.*)/'); # regex Ok
    return $orderUrl;
  }

  public function SaveOrderId($I)
  {
    $orderId =  $I->grabFromCurrentUrl("/(\d.*)/"); # regex Ok
    return $orderId;
  }

 public static function CreateOrderParam($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->amOnPage('/');
    $I->click('Новый заказ');
    $I->acceptPopup();
    #$I->see('Выйти');
    #Find client by phone number
    $I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
    $I->click('Поиск клиента');
    $I->waitForJs($waiting_time, $jsCondition);
    $jsConditionVisibleElement = '$("#create_order").is(":visible")';
    $I->waitForJs($waiting_time,$jsConditionVisibleElement);
    $I->click('Создать заказ');
    $I->waitForJs($waiting_time, $jsCondition);
    #Set order details
    TestCommons::FillDetailsOfTheOrder($I, $test_client);
    $I->selectOption('Order[status]','Создан');
    $I->acceptPopup();
    $I->waitForJs($waiting_time,$jsCondition);        
    $I->selectOption('Order[delivery_type]','СДЭК');
    $I->waitForJs($waiting_time,$jsCondition);
    $I->fillField('Order_comments', $test_client->Model_client_adress);
    $I->fillField('photo_link',Items::TwoItemId);
    $I->executeJS(UpdateOrderPage::PressEnter);
    $I->waitForJs($waiting_time,$jsCondition);
    $I->click('Добавить товар');
    $I->waitForJs($waiting_time,$jsCondition);
    TestCommons::SaveOrderId($I);
    #Set date
    $I->fillField('//*[@id="Order_deliveryAddress_street"]','Одинцово');
    $I->fillField('//*[@id="Order_deliveryAddress_house"]', '64');
    $I->fillField('//*[@id="Order_deliveryAddress_flat"]','92');
    $I->waitForJs($waiting_time,$jsCondition);
    $I->selectOption('Order[status]','Создан');
    #$I->acceptPopup();
    TestCommons::SaveOrderId($I);
  }


 public static function CreateOrderParamCourier($I,$test_client)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->click('Новый заказ');
  	$I->acceptPopup();
  	#$I->see('Выйти');
  	#Find client by phone number
  	$I->fillField('//*[@id="phone"]', $test_client->Model_client_phone);
  	$I->click('Поиск клиента');
  	$I->waitForJs($waiting_time, $jsCondition);
  	$jsConditionVisibleElement = '$("#create_order").is(":visible")';
  	$I->waitForJs($waiting_time,$jsConditionVisibleElement);
  	$I->click('Создать заказ');
  	$I->waitForJs($waiting_time, $jsCondition);
  	#Set order details
  	TestCommons::FillDetailsOfTheOrder($I, $test_client);
    TestCommons::SaveOrderId($I);
    $I->waitForJs($waiting_time,$jsCondition);
  	$I->selectOption('Order[status]','Создан');
    #$I->wait(30000);
    $I->acceptPopup();
  	$I->waitForJs($waiting_time,$jsCondition);     
  	$I->selectOption('Order[delivery_type]','Курьер');
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->fillField('Order_comments',$test_client->Model_client_adress);
    TestCommons::NearMetro($I);
  	$I->fillField('photo_link',Items::OneItemId);
  	$I->executeJS(UpdateOrderPage::PressEnter);
  	$I->waitForJs($waiting_time,$jsCondition);
  	$I->click('Добавить товар');
  	$I->waitForJs($waiting_time,$jsCondition);
  	#Set date
    TestCommons::FillDate($I);
    #$I->wait(45000);
  	TestCommons::SaveOrder($I);
    ###
    $I->waitForJs($waiting_time,$jsCondition);
    #$I->selectOption('Order[status]','Создан');
    #$I->acceptPopup();
    #TestCommons::SaveOrder($I);
    $I->selectOption('Order_courier_id','Равхат');
    $I->waitForJs($waiting_time,$jsCondition);
    TestCommons::SaveOrder($I);
  }

  public static function AddToClientBalance($I, $client_id, $sum)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $client_adress = 'index.php?r=client/view&id='.$client_id;
    $I->amOnPage($client_adress);
    $I->click('Начислить баланс');
    $I->fillField('ClientBalanceHistory_amount', $sum);
    $I->fillField('ClientBalanceHistory_comment', 'Была зачислена сумма='.$sum);
    $I->click(ViewClientPage::OverCharging);
    $I->waitForJs($waiting_time, $jsCondition);
  }

  public function ReadClientBalance($I, $client_id)
  { 
    $client_adress = 'index.php?r=client/view&id='.$client_id;
    $I->amOnPage($client_adress);
    $sum  = $I->grabTextFrom(ViewClientPage::CSSClientBalance);
    return $sum;
  }


  public static function DeleteOrder($I, $order_id)
  {



  }


  #TestCommons::SaveOrder($I)
  public static function SaveOrder($I)
  {
    $jsCondition  =  GlobalJS::jQueryActive;
    $waiting_time =  GlobalJS::WaitingTime;
    $I->executeJs(UpdateOrderPage::CloseUiWidgetOverlay);
    $I->click('Сохранить');
    $I->waitforjs($waiting_time, $jsCondition);
  }

}

class TestClient 
{
  public $Model_client_last_name;
  public $Model_client_first_name;
  public $Model_client_middle_name;
  public $Model_client_phone;
  public $Model_client_email;
  public $Model_client_region;
  public $Model_client_city;
  public $Model_client_adress;

  public function __construct( $Model_client_last_name, $Model_client_first_name, $Model_client_middle_name, $Model_client_phone, $Model_client_email, $Model_client_region, $Model_client_city, $Model_client_adress )
  {
    $this->Model_client_last_name   = $Model_client_last_name;
    $this->Model_client_first_name  = $Model_client_first_name;
    $this->Model_client_middle_name = $Model_client_middle_name;
    $this->Model_client_phone       = $Model_client_phone;
    $this->Model_client_email       = $Model_client_email;
    $this->Model_client_region      = $Model_client_region;
    $this->Model_client_city        = $Model_client_city;
    $this->Model_client_adress      = $Model_client_adress;
  } 
}

