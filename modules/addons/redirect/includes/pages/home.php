<?php

use Illuminate\Database\Capsule\Manager as Capsule;

if (!defined("WHMCS")) die("This file cannot be accessed directly");

if($_REQUEST['p'] == '')
{
  if($_REQUEST['success'])
  {
    echo ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alert").slideUp(); }, 2500);});</script>';
    echo '<div class="alert alert-success" style="font-size:14px"><strong><span class="fas fa-check"></span> '.$LANG['success'].'</strong> '.$LANG['successhelp'].'</div>';
  }

  echo '<h1><span class="fas fa-home"></span> '.$LANG['home'].'</h1>';

  echo '<p><a class="btn btn-primary" href="'.$modulelink.'&p=manage" role="button"><i class="fas fa-plus"></i> '.$LANG['createnewredirect'].'</a></p>';
  echo '<div class="panel panel-default">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>'.$LANG['title'].'</th>
                <th>'.$LANG['oldurl'].'</th>
                <th>'.$LANG['newurl'].'</th>
                <th>'.$LANG['time'].'</th>
                <th>'.$LANG['status'].'</th>
                <th></th>
              </tr>
            </thead>
            <tbody>';
  $result = Capsule::table('mod_redirect')->orderBy('id', 'DESC')->get();
  foreach ($result as $data)
  {
    $id     = $data->id;
    $title  = $data->title;
    $oldurl = $data->oldurl;
    $newurl = $data->newurl;
    $retime = $data->retime;
    $status = $data->status;

    $statusuot = ($status) ? '<span class="label closed">'.$LANG['disable'].'</span>' : '<span class="label active">'.$LANG['enable'].'</span>';

    $statusbuttons = ($status) ? '<a href="'.$modulelink.'&p=enable&id='.$id.'" class="btn btn-default btn-sm"><i class="fas fa-check"></i> '.$LANG['enable'].'</a>' : '<a href="'.$modulelink.'&p=disable&id='.$id.'" class="btn btn-warning btn-sm"><i class="fas fa-ban"></i> '.$LANG['disable'].'</a>';

       echo '<tr>
                <td style="padding-top: 13px;">'.$title.'</td>
                <td style="padding-top: 13px;"><a href="'.$oldurl.'" target="_blank">'.$oldurl.'</a></td>
                <td style="padding-top: 13px;"><a href="'.$newurl.'" target="_blank">'.$newurl.'</a></td>
                <td style="padding-top: 13px;">'.$retime.'</td>
                <td style="padding-top: 13px;">'.$statusuot.'</td>
                <td>
                  '.$statusbuttons.'
                  <a href="'.$modulelink.'&p=manage&id='.$id.'" class="btn btn-success btn-sm"><i class="fas fa-edit"></i> '.$LANG['edit'].'</a>
                  <a href="'.$modulelink.'&p=delete&id='.$id.'" class="btn btn-danger btn-sm" onclick="return confirm(\''.$LANG['deletehelp'].'\');"><i class="fas fa-trash"></i> '.$LANG['delete'].'</a>
                </td>
              </tr>';
  }

  if(!$id)
  {
    echo '<tr>
            <td colspan="6" class="text-center">'.$LANG['norecordsfound'].'</td>
          </tr>';
  }

      echo '</tbody>
          </table>
        </div>';
}

if($_REQUEST['p'] == 'manage')
{
  $id     = $_REQUEST['id'];
  $retime = 0;

  if($id)
  {
    $data = Capsule::table('mod_redirect')->where('id', $id)->first();

    $title  = $data->title;
    $oldurl = $data->oldurl;
    $newurl = $data->newurl;
    $retime = $data->retime;
    $status = $data->status;
  }

  echo '<h1><span class="fas fa-edit"></span> '; if($id){ echo $LANG['editredirect'];}else{ echo $LANG['createnewredirect'];} echo'</h1>';

  echo '<form name="form" action="'.$modulelink.'&p=save&id='.$id.'" method="POST" class="form-horizontal">
          <div class="panel panel-default">
            <div class="panel-heading">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
            <div class="panel-body">
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['title'].'</label>
                <div class="col-md-6">
                  <input type="text" name="title" value="'.$title.'" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['oldurl'].'</label>
                <div class="col-md-6">
                  <input type="text" name="oldurl" value="'.$oldurl.'" class="form-control">
                  <span class="help-block">'.$LANG['fullurlhelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['newurl'].'</label>
                <div class="col-md-6">
                  <input type="text" name="newurl" value="'.$newurl.'" class="form-control">
                  <span class="help-block">'.$LANG['fullurlhelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['redirecttime'].'</label>
                <div class="col-md-4">
                  <input type="text" name="retime" value="'.$retime.'" class="form-control">
                  <span class="help-block">'.$LANG['redirecttimehelp'].'</span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">'.$LANG['disable'].'</label>
                <div class="col-md-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="status" '; if ($status){ echo'CHECKED'; } echo '>'.$LANG['disablehelp'].'
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-footer">
              <a href="'.$modulelink.'" class="btn btn-danger btn-sm"><i class="fas fa-chevron-left"></i> '.$LANG['back'].'</a>
              <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i> '.$LANG['savechanges'].'</button>
              <button type="reset" class="btn btn-default btn-sm"><i class="fas fa-times"></i> '.$LANG['cancel'].'</button>
            </div>
          </div>
        </form>';
}

if($_REQUEST['p'] == 'save')
{
  $id     = $_REQUEST['id'];
  $title  = $_REQUEST['title'];
  $oldurl = $_REQUEST['oldurl'];
  $newurl = $_REQUEST['newurl'];
  $retime = $_REQUEST['retime'];
  $status = $_REQUEST['status'];

  if(!$status)
  {
    $status = '0';
  }

  if($id)
  {
    Capsule::table('mod_redirect')->where('id', $id)->update(array(
                                                              'title'  => $title,
                                                              'oldurl' => $oldurl,
                                                              'newurl' => $newurl,
                                                              'retime' => $retime,
                                                              'status' => $status,
                                                              ));
  }
  else
  {
    Capsule::table('mod_redirect')->insert(array(
                                                              'title'  => $title,
                                                              'oldurl' => $oldurl,
                                                              'newurl' => $newurl,
                                                              'retime' => $retime,
                                                              'status' => $status,
                                                              ));
  }

  header('Location: '.$modulelink.'&success=true');
  exit;
}

if($_REQUEST['p'] == 'delete')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_redirect')->where('id', $id)->delete();

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'enable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_redirect')->where('id', $id)->update(array('status' => ''));

  header('Location: '.$modulelink);
  exit;
}

if($_REQUEST['p'] == 'disable')
{
  $id = $_REQUEST['id'];

  Capsule::table('mod_redirect')->where('id', $id)->update(array('status' => 'on'));

  header('Location: '.$modulelink);
  exit;
}
