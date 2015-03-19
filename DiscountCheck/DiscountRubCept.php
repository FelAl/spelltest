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
$I->click('Создать заказ');
$I->waitForJs($waiting_time ,$jsCondition);
#Add goods
$I->fillField('photo_link','5');
$I->executeJS("$('input#photo_link').trigger(jQuery.Event('keypress', {keyCode: 13}));");
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить товар');
$I->waitForJs($waiting_time,$jsCondition);
#Check discount
$I->executeJS('$("div.button.radius.small.edit_simple_discount").trigger("click");');
$I->waitForJs($waiting_time,$jsCondition);
$I->fillField('//*[@id="simpleDiscountDialog"]/div/div[1]/div[1]/input',$discountInRubles);
$I->fillField('//*[@id="simpleDiscountDialog"]/div/div[1]/div[2]/textarea','Новый комментарий к скидке');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Добавить скидку');
$I->waitForJs($waiting_time,$jsCondition);
$I->see( $discountInRubles, '//*[@id="simple-discount-grid"]/table/tbody/tr/td[1]');
$I->see('Новый комментарий к скидке','//*[@id="simple-discount-grid"]/table/tbody/tr/td[2]');
$I->click('Закрыть окно');
$I->waitForJs($waiting_time,$jsCondition);
$I->click('Сохранить');
$I->waitForJs($waiting_time,$jsCondition);
$valDiscount = $I->grabValueFrom('Order[simple_discount]');
if ($valDiscount !=(string) $discountInRubles):
{
  $I->see("Can't see new discount");
}
endif;
