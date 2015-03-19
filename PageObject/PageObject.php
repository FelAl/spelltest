<?php
  class GlobalJS
  {
    #Important parameters
    const jQueryActive = 'jQuery.active == 0';
    const WaitingTime  =  10000;
  }

  class UpdateOrderPage
  {
    # UpdateOrderPage::PressEnter
    # UpdateOrderPage::PlusButton
    # UpdateOrderPage::ClickOnTheFirstPositionInOrder
    #CSS selectors, XPath selectors, and JS JQuery selectors
    #JS
    const CloseUiWidgetOverlay             = "$('#ssJsFlash').html('').click();"; # Зелёная полоска вверху страницы, появляется после сохранения заказа или изменения статуса позиции
    const ClickOnTheFirstPositionInOrder   = '$(".tiny.radius.button.updateOPstatus").click();'; # Клик на первую позицию в списке
                                                                                                 # Работоет только в том случае если в списке одна позиция
    const PressEnter                       = "$('input#photo_link').trigger(jQuery.Event('keypress', {keyCode: 13}));"; # Нажатие клавиши enter

    const PlusButton                       = '$(".explode_positions.no_position_purchase_comment.button.tiny.radius").click();'; # Кнопка + для разворачивания списка одинаковых позиций
    #XPath
    #CSS
  }
  class ViewOrderPage
  {
    #CSS selectors, XPath selectors, and JS JQuery selectors
  }

  class ViewClientPage
  {
    #ViewClientPage::Client_balance

    
    #CSS selectors, XPath selectors, and JS JQuery selectors
    #jQuery
    const ClientBalance = '$(".even:contains("Баланс клиента") td").contents()[0];'; # Возвращает число написанное в балансе клиента
    #XPath
    const OverCharging     = '//*[@id="yw8"]/div[3]/button'; # Кнопка Начислить в форме, которая появляется после нажатия Начислить баланс   
    #CSS
    const CSSClientBalance = 'html.js body div#page.container div.row div.nine div#content div#tabs.ui-tabs div#tabs-client.ui-tabs-panel div.span-8 table#yw2.detail-view tbody tr.even td';# Число рублей из баланса клиента
   
    #CSS
    const Client_balance = '#yw2 .even td';




  }



















?>