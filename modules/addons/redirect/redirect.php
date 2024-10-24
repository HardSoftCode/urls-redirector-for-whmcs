<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

function redirect_config()
{
  $configarray = array(
  "name" => "URLs Redirector",
  "description" => "The URLs redirector module will redirect any page on WHMCS to a specific URL of your choice.",
  "version" => "2.3.0",
  "author" => "<a href=\"http://www.hardsoftcode.com\" target=\"_blank\">HSC</a>",
  "language" => "english",
  "fields" => array(
  "delete" => array ("FriendlyName" => "Delete Modul DB", "Type" => "yesno", "Size" => "25", "Description" => "Tick this box to delete the module database on deactivating"),
  ));
  return $configarray;
}

function redirect_activate()
{
  try
  {
    if(!Capsule::schema()->hasTable('mod_redirect'))
    {
      Capsule::schema()->create('mod_redirect', function ($table)
      {
        $table->increments('id');
        $table->text('title');
        $table->text('oldurl');
        $table->text('newurl');
        $table->text('retime');
        $table->text('status');
       });
     }
  }
  catch (\Exception $e)
  {
    return array('status'=>'error','description'=>'Unable to create table mod_redirect: ' .$e->getMessage());
  }

  return array('status'=>'success','description'=>'Module activated successfully. Click configuration to configure the module');
}

function redirect_deactivate()
{
  $delete = Capsule::table('tbladdonmodules')->where('module', 'redirect')->where('setting', 'delete')->first();

  if($delete->value)
  {
    try
    {
      Capsule::schema()->dropIfExists('mod_redirect');
    }
    catch (\Exception $e)
    {
      return array('status'=>'error','description'=>'Unable to drop tables: ' .$e->getMessage());
    }
  }

  return array('status'=>'success','description'=>'Module deactivated successfully');
}

function redirect_output($vars)
{
  $modulelink = $vars['modulelink'];
  $LANG       = $vars['_lang'];

  require(dirname( __FILE__ ).'/includes/pages/menu.php');

  if(file_exists(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php'))
  {
    require(dirname( __FILE__ ).'/includes/pages/'.$_REQUEST['a'].'.php');
  }
  else
  {
    require(dirname( __FILE__ ).'/includes/pages/home.php');
  }
}
