<?php
require_once 'goutte.phar';

use Goutte\Client;
$client        = new Client();
$client->setAuth('UserName', 'Password');
$client->followRedirects(true);
$crawler = $client->request('GET', "http://testcrm.url.ru/");
$form = $crawler->selectButton("Войти")->form();
$crawler = $client->submit($form, array('LoginForm[username]' => 'testcm', 
                                        'LoginForm[password]' => '123098') );
#echo "---\n";
#echo $crawler->text();
#echo "---\n";

$main_domain   = "/http:\/\/testcrm//";
$main_domain2  =  "http://testcrm.url.ru";
#$url           = 'http://UserName:Password@testcrm.url.ru/';
#$url           = 'http://url.ru/';
#$crawler       = $client ->request('GET', $url);
#$input         = $crawler->html();
$all_links     = array();
$bad_links     = array();
$foreign_links = array();
$process_links = array();
$crawled_links = array();
$problem_page  = "";
$regexp        = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
$Second         = $client ->request('GET', "http://rutracker.org/forum/index.php");
$My_form        = $Second->filter("form[action='http://login.rutracker.org/forum/login.php']")->form();

#$crawler       = $client ->request('POST', "http://login.rutracker.org/forum", array('login_username' => "gusevdada", 'login_password' => "12345678"));
#echo $crawler->html();
#$crawler =  $client ->submit($My_form, array('login_username' => "gusevdada", 'login_password' => "12345678"));

#$arr = $client->getCookieJar()->all();
#foreach ($arr as $one)
#{
#  echo $one."\n";
#}
#echo $crawler->html();


function LoginInCRM($username, $userpassword)
{
  global $client, $crawler;;
  ####$client->setAuth('UserName', 'Password');
  ####$client->followRedirects(true);
  $crawler = $client->request('GET', "http://testcrm.url.ru/");
  #echo 'Before Login'."\n";
  #echo  $crawler->text();
  #echo "--------\n";
  $form = $crawler->selectButton("Войти")->form();
  #echo "Find sign in\n";
  $crawler = $client->submit($form, array('LoginForm[username]' => $username, 'LoginForm[password]' => $userpassword) );
  #echo "After login\n";
  #echo $crawler->text();
}
function IsThisRelativePath($url)
{
  $main_path = "/^\//";
  if (preg_match($main_path, $url))
  {
    return 1;
  }
  return 0;
}

function IsThisCRMLink($url) # +
{
   #global $main_domain; 
  $main_domain = '/http:\/\/testcrm/';
  if (preg_match($main_domain, $url))
  {
    return 1;
  }
  if (IsThisRelativePath($url))
  {
    return 1;
  }
  return 0;
}


function IsThisIgnoredLink($url)
{
  $ignore_string = '/&amp/';

  if (preg_match($ignore_string, $url))
  {
    echo "Yes, ignore this";
    return 1;
  }
  return 0;
}



function GrabAndTransformAllLinks($url) 
{
  global $main_domain2;
  $result = array();
  $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
  if(preg_match_all("/$regexp/siU", GETHTMLFromLink($url), $matches))
  {

  }
  foreach($matches[2] as $value)
  {
    /*
    if (IsThisIgnoredLink($value))
    {
      echo $value." this is value that must be ignored";
      continue;
    }
    */
    if (IsThisRelativePath($value))
    {
      $value = $main_domain2."$value";
    }
    array_push($result,  htmlspecialchars_decode($value));
    #echo $value."\n";
  }

  /*
  foreach($result as $one)
  {
    echo "$one\n";
  }
  */
  return $result;
}

function IfLinkNotAlreadyInArray($url)
{
  global $all_links;
  if (!in_array($url, $all_links))
  {
    return 1;
  }
  return 0;

}
function DistributionLinks ($url)
{
  global $all_links, $bad_links, $foreign_links;
  if(IfURLReturns200($url) and IsThisCRMLink($url) )
  {
    return 1;
  }
  if(!IfURLReturns200($url) and IsThisCRMLink($url) )
  {
    if (!in_array($url, $bad_links))
    {
      array_push($bad_links, $url);
      return 0;
    }
    return 0;
  }
  array_push($foreign_links, $url);
  return 0; 
}

function AddLinkInArray($url)
{
  global $all_links;
  if (IfLinkNotAlreadyInArray($url)) 
  {
    array_push($all_links, $url);
  }
  #$all_links = array_merge((array)$all_links,(array)$array);
}

function Process_Link($url)
{
  global $problem_page;
  global $main_domain2;
  global $main_domain;
  global $process_links, $crawled_links, $bad_links;
  $buf = array();
  #echo "BeforeIsThisCRMLink\n";
  if(IsThisCRMLink($url))
  {
    #echo 'IsThisCRM?'."\n";
    if (IfURLReturns200($url))
    {
      #echo 'IsThisReturns200?'."\n";
      if(!in_array($url, $crawled_links) and !in_array($url, $bad_links))
      {
        # if url not in crawled_links then push
        #add url in crawled_links
        array_push($crawled_links, $url);
        #!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! Important!! Show what goes to crawled!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        #echo "New url went to crawled ->".$url."\n";
      }
      #pop last process_links element
      $problem_page = end($process_links);
      array_pop($process_links);
      #get array of links from page with the this url
      #transform relative path in absolute in array
      $buf = GrabAndTransformAllLinks($url);
      #analyze each link and add it in right array
      foreach ($buf as $value)
      {
          # if link already in process_links or crawled_links or bad_links -> go to next link
        if (in_array($value, $bad_links) or in_array($value, $crawled_links) or in_array($value, $process_links))
        {
          continue;
        }
        # if link not in crawled_links or bad_links or process_links     -> add to process_links 
        if (!in_array($value, $bad_links) and !in_array($value, $crawled_links) and !in_array($value, $process_links))
        {
          array_push($process_links, $value);
          continue;
        }
        #show all links from page
        #echo $value."\n";
      }
    }
    else
    {
      #add in bad_links
      if (!in_array($url, $bad_links))
      {
        array_push($bad_links, $url);
        echo "Bad Link find! = ".$url."\n";
        echo "On page -> ".$problem_page."\n";
      }
      ##pop last process_links element
      array_pop($process_links);
    }

  }
  else
  {
    #pop last process_links element
    array_pop($process_links);
  }
}


function IfURLReturns200($url)
{
  global $client, $crawler;
  $crawler  = $client->request('GET', $url);
  $httpCode = $client -> getResponse()->getStatus();
  if ($httpCode == 200 or $httpCode == 301 or $httpCode ==302)
  {
    return 1;
  }
  return 0;
}

function GetHTMLFromLink($url)
{
    global $client, $crawler;
  $crawler = $client ->request('GET', $url);
  return $crawler->html();
}


function Crawl($url)
{
  global $process_links;
  #if process_links empty
  #  then return
  #echo 'Start'."\n";
  if(empty($process_links))
  {
    echo 'inIfOfCrawl'."\n";
    return;
  }
  #echo "Start Process"."\n";
  #If not empty, then process current url
  Process_Link($url);
  #Then crawl last url in process_links
  #Crawl(process_links.last());
  Crawl(end($process_links));
}

/*
AddLinksInArray(GrabAllLinks($url));

#$all_links =  array_merge($all_links, GrabAllLinks($url));
foreach(GrabAllLinks($url) as $value)
{
  #print IsThisCRMLink($value);
  if(IsThisCRMLink($value) OR IsThisRelativePath($value))
  {
      if(IsThisRelativePath($value) == 1)
      {
        print $main_domain.$value."  ".IfURLReturns200($main_domain.$value)."\n";
      }
      if (IsThisRelativePath($value) == 0)
      {
        print $value."  ".IfURLReturns200($value)."\n";
      }
      #print $value."  ".IfURLReturns200($value)."\n";
  }
  #print $value."  ".IfURLReturns200($value)."\n";
}
*/


#print_r($matches[2]);
#echo $matches[2];
#echo $matches[3];
#
#echo $crawler->text();
#echo $crawler->filter('a')->count();
#echo '---------------------------\n';
#$methods = get_class_methods($crawler);
#foreach ($methods as $method_name)
#{
#  echo "$method_name\n";
#}
#LoginInCRM('ksenia', 'ME3ahE8H');
$url = "http://testcrm.url.ru/index.php?r=order/admin";

echo "----------Start crawling!-----------\n";
array_push($process_links, $url);
Crawl($url);
################################################It works!





# Login in http://www.symfony-project.org/plugins/
/*
$client->setAuth('UserName', 'Password');
$crawler = $client->request('GET', "http://testcrm.url.ru/");
echo 'Before Login'."\n";
echo  $crawler->text();
echo "--------\n";
$form = $crawler->selectButton("Войти")->form();
echo "Find sign in\n";
$crawler = $client->submit($form, array('LoginForm[username]' => 'testcm', 'LoginForm[password]' => '123098') );
echo "After login\n";
echo $crawler->text();
*/

echo "----------------End crawling----------\n";
#echo IsThisRelativePath('asdfasdfasdfasdfasf/sdfasdf/asfd');

?>
