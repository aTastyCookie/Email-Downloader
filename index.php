<?php include ('header.php'); ?>
<?php
include('src/config.php');
include('src/mail.class.php');

if(isset($_GET['delete']) && $_GET['delete']){
  Mail::deleteMail($_GET['delete']);
  header('location:index.php');
}

if($_POST){
  if(!empty($_POST['inputSearchQuery'])){
     $msg = Mail::mailQuery($_POST['inputSearchQuery']);
  }else{
     $msg = "Please input a search query";
  }
} 

$checkSettings = Mail::checkSettings();
$data = Mail::viewEmails();
$data = array_reverse($data); // Get Email by latest date first
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
          <li class="active"><a href="index.php">Home</a></li>
          <li><a href="settings.php">Settings</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Search Query Examples</h3>
  </div>

  <div class="modal-body">
    <ul>
      <li>ALL           - return all messages matching the rest of the criteria</li>
      <li>ANSWERED      - match messages with the \\ANSWERED flag set</li>
      <li>BCC "string"  - match messages with "string" in the Bcc: field</li>
      <li>BEFORE "date" - match messages with Date: before "date"</li>
      <li>BODY "string" - match messages with "string" in the body of the message</li>
      <li>CC "string"   - match messages with "string" in the Cc: field</li>
      <li>DELETED       - match deleted messages</li>
      <li>FLAGGED       - match messages with the \\FLAGGED (sometimes referred to as Important or Urgent) flag set</li>
      <li>FROM "string"    - match messages with "string" in the From: field</li>
      <li>KEYWORD "string" - match messages with "string" as a keyword</li>
      <li>NEW              - match new messages</li>
      <li>OLD              - match old messages</li>
      <li>ON "date"        - match messages with Date: matching "date"</li>
      <li>RECENT           - match messages with the \\RECENT flag set</li>
      <li>SEEN             - match messages that have been read (the \\SEEN flag is set)</li>
      <li>SINCE "date"     - match messages with Date: after "date"</li>
      <li>SUBJECT "string" - match messages with "string" in the Subject:</li>
      <li>TEXT "string"    - match messages with text "string"</li>
      <li>TO "string"      - match messages with "string" in the To:</li>
      <li>UNANSWERED       - match messages that have not been answered</li>
      <li>UNDELETED        - match messages that are not deleted</li>
      <li>UNFLAGGED          - match messages that are not flagged</li>
      <li>UNKEYWORD "string" - match messages that do not have the keyword "string"</li>
      <li>UNSEEN             - match messages which have not been read yet</li>
    </ul>
  </div>
</div>

<div class="container">
  <h3>Download Emails</h3>
  <?php if(isset($msg)){ ?>
  <div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <?php echo $msg; ?>
  </div>
  <?php } ?>
  <?php if($checkSettings == true){ ?>
    <!-- <p class="text-info pull-left" style="margin: 5px 0 0 0;"> Please input a Search Query </p> -->
    <a href="#myModal" role="button" class="btn btn-primary pull-left" data-toggle="modal" style="margin:0 0 30px 0;">Search Query Examples</a>
    <div style="clear:both;"></div>

  <form class="form-horizontal" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
    <div class="control-group">
      <label class="control-label" for="inputSearchQuery" style="width:90px;">Search Query</label>
      <div class="controls pull-left" style="margin-left:10px;">
        <input type="text" id="inputSearchQuery" name="inputSearchQuery" placeholder="Eg: <?php echo "ON &quot;2012-10-19&quot;";?>" value="">
      </div>    
        <button type="submit" class="btn pull-left" style="margin-left:10px;">Download Mails</button>
      </div>
  </form>
  <?php }else{ ?>
    <p class="text-info"> Please input your Email Settings in the <a href="settings.php" style="color:black;">Settings page.</a> </p>
 <?php } ?>
 <hr>
  <h3>All Mails</h3>
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Sender</th>
        <th>Subject</th>
        <th>Msg</th>
        <th>Attachments</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $email){ ?>
        <tr>
          <td><?php echo $email['date']; ?></td>
          <td><?php echo $email['sender']; ?></td>
          <td><?php echo $email['subject']; ?></td>
          <td><a target="_blank" href="<?php echo "email_body.php?id=".$email['mailID']."";?>" >View Email Message</a></td>
          <td>
            <?php foreach($email['attachements'] as $attachements){?>
                  <a target="_blank" class="icon-file" href="<?php echo $attachements['location'].$attachements['filename']; ?>" title="<?php echo $attachements['filename']; ?>"></a>

            <?php } ?>
          </td>
          <td><a class="icon-trash" href="<?php echo "index.php?delete=".$email['mailID']."";?>" ></a></td>
        </tr>
     <?php } ?>
    </tbody>
  </table>
</div>
<?php include ('footer.php'); ?>

