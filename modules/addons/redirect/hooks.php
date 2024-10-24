<?php

use Illuminate\Database\Capsule\Manager as Capsule;

function Redirect_GetUrl()
{
  $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
  return $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

add_hook('ClientAreaPage', 265884, function($vars)
{
  if($data = Capsule::table('mod_redirect')->where('status', '0')->where('oldurl', Redirect_GetUrl())->first())
  {
    if($data->retime)
    {
      header("HTTP/1.1 301 Moved Permanently");
      header("refresh:{$data->retime};url={$data->newurl}",true,301);
    }
    else
    {
      header('Location: '.$data->newurl);
      exit;
    }
  }
});
