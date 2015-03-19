<?php
$I = new TGuy($scenario);
TestCommons::LoginCRM($I,'ksenia','111');
$arr = array('Запакован','Запакован, проверен', 'Отправлен', 'Прибыл');
$size_arr = count($arr);
for ($i = 0; $i < $size_arr; ++$i)
{
  try
  {
    TaskCommons::FCustomerNumberChanged($I, $arr[$i]);
  }
  catch (Exception $e)
  {
    $I->lookForwardTo(('[Bug detected in'.$arr[$i].']') );
    echo "[Bug detected in ",$arr[$i],"]" , $e->getMessage();
  }
}
