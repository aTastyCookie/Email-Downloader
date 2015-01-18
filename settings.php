<?php include ('header.php'); ?>
<?php
include('src/config.php');
include('src/mail.class.php');
if($_POST){
  if(!empty($_POST['inputMailServer']) && !empty($_POST['inputPort']) && !empty($_POST['inputServerType']) && !empty($_POST['inputEmail']) && !empty($_POST['inputPassword'])){
    $msg = Mail::saveSettings($_POST['inputMailServer'], $_POST['inputPort'], $_POST['inputEmail'], $_POST['inputPassword'], $_POST['inputServerType']);
  }else{
    $msg = "Please input all fields.";
  }
}else{
   $data = Mail::getSettings();
   extract($data);
}
?>    
<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="index.php">Email Downloader</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li><a href="index.php">Home</a></li>
          <li class="active"><a href="settings.php">Settings</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <h3>Settings</h3>
  <p class="text-info"> Please save your email Settings </p>
  <?php if(isset($msg)){ ?>
  <div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $msg; ?>
  </div>
  <?php } ?>
  <form class="form-horizontal" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
    <div class="control-group">
      <label class="control-label" for="inputMailServer">Mail Server</label>
      <div class="controls">
        <input type="text" id="inputMailServer" name="inputMailServer" placeholder="Eg: imap.gmail.com" value="<?php if(isset($inputMailServer))echo $inputMailServer; ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="inputPort">Port</label>
      <div class="controls">
        <input type="text" id="inputPort" name="inputPort" placeholder="Eg: 993" value="<?php if(isset($inputPort))echo $inputPort; ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="inputServerType">Server Type</label>
      <div class="controls">
        <input type="text" id="inputServerType" name="inputServerType" placeholder="imap/ssl" value="<?php if(isset($inputServerType))echo $inputServerType; ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="inputUsername">Email Address</label>
      <div class="controls">
        <input type="text" id="inputEmail" name="inputEmail" placeholder="someone@gmail.com" value="<?php if(isset($inputEmail))echo $inputEmail; ?>">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="inputPassword">Password</label>
      <div class="controls">
        <input type="password" id="inputPassword" name="inputPassword" placeholder="" value="<?php if(isset($inputPassword))echo $inputPassword; ?>">
      </div>
    </div>
    <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Save Settings</button>
    </div>
  </div>        
  </form>
</div>
<?php include ('footer.php'); ?>

