<?php
$I = new NoGuy($scenario);
$I->wantTo('check create task');
#####Test start
TestCommons::LoginCRM($I,'ksenia', 'ME3ahE8H');
$I->click('Задания');
$I->click('Создать задание');
$waiting_time = 10000;
$jsCondition = 'jQuery.active == 0';
$I->waitForJs($waiting_time, $jsCondition);
$I->fillField('Task[dueTime]','20:00');
$I->fillField('Task_dueDate','04.05.2013');
$I->fillField('html.js body div#page.container div.row div.twelve div#content div.form form#task-form div.row div.twelve div.redactor_box div.redactor_','Текст задания здесь 11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111 1111111111111111111111111111111111111111111111111111111111111111111111111 1111111111111111111111111111111111111111111111111111111111111111111 111111111111');
$I->selectOption('Task[role]','Администратор');
$I->click('Сохранить');
$I->see('Редактировать');






















