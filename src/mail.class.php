<?php

class Mail{

	 /**
     * Connects to the specified Inbox
     * @param $mailserver - eg: mail.site.com
     * @param $port       - eg: 110
     * @param $username   - eg: maail
     * @param $password   - eg: mypassword
     * @param $server_type- eg: POP3 OR IMAP OR NNTP
     * @return void
     */

	public static function connect($mailserver, $port, $username, $password, $server_type, $searchquery){
		$mbox = imap_open("{".$mailserver.":".$port."/".$server_type."}", $username, $password );
		if($mbox)
		{  
			Mail::getMails($mbox,$searchquery);
		}
		else
		{
		 	$msg = "Could not connect. Please check you login credentials in Settings";
		}

	}

	/**
     * Gets the number of mails in the inbox
     * @param  $mbox 
     * @return no_mails
     */
	public static function getMailCount($mbox){
		if ($hdr = imap_check($mbox))
		{
		  $msgCount = $hdr->Nmsgs;
		  $result = "Number of mails in inbox: ".$msgCount."\n";
		}
		else
		{
		  $result = "Failed to get mail";
		}
		return $result;
	}

	/**
     * Downloads the attachement and saves it in a specified location
     * @param $strFileType - Type of the file
     * @param $strFileName - Name of the file
     * @param $fileContent - The encoded file content
     * @param $location    - eg: 'attachements/'
     * @return status of the downloaded file - TRUE/FALSE
     */
	public static function downloadFile($strFileType, $strFileName, $fileContent, $location){
	   	$ContentType = "application/octet-stream";
	   
	   	if ($strFileType == ".asf") 
	   		$ContentType = "video/x-ms-asf";
	   	if ($strFileType == ".avi")
	   		$ContentType = "video/avi";
	   	if ($strFileType == ".doc" || $strFileType == ".docx")
	   		$ContentType = "application/msword";
	   	if ($strFileType == ".zip")
	   		$ContentType = "application/zip";
	   	if ($strFileType == ".xls")
	   		$ContentType = "application/vnd.ms-excel";
	   	if ($strFileType == ".gif")
	   		$ContentType = "image/gif";
	   	if ($strFileType == ".jpg" || $strFileType == ".jpeg")
	   		$ContentType = "image/jpeg";
	   	if ($strFileType == ".png")
	   		$ContentType = "image/png";
	   	if ($strFileType == ".wav")
	   		$ContentType = "audio/wav";
	   	if ($strFileType == ".mp3")
	   		$ContentType = "audio/mpeg3";
	   	if ($strFileType == ".mpg" || $strFileType == ".mpeg")
	   		$ContentType = "video/mpeg";
	   	if ($strFileType == ".rtf")
	   		$ContentType = "application/rtf";
	   	if ($strFileType == ".htm" || $strFileType == ".html")
	   		$ContentType = "text/html";
	   	if ($strFileType == ".xml") 
	   		$ContentType = "text/xml";
	   	if ($strFileType == ".xsl") 
	   		$ContentType = "text/xsl";
	   	if ($strFileType == ".css") 
	   		$ContentType = "text/css";
	   	if ($strFileType == ".php") 
	   		$ContentType = "text/php";
	   	if ($strFileType == ".asp") 
	   		$ContentType = "text/asp";
	   	if ($strFileType == ".pdf")
	   		$ContentType = "application/pdf";
	   
		header ("Content-Type: $ContentType"); 
		header ("Content-Disposition: attachment; filename=$strFileName; size=$fileSize;"); 
		
		if (substr($ContentType,0,4) == "text") {
			$attachment =  imap_qprint($fileContent);
		} else {
			$attachment = imap_base64($fileContent);
		}

		if(file_put_contents($location.$strFileName, $attachment))
		{
			return "TRUE";
		}
		else
		{
			return "FALSE";
		}
	}

	/**
     * Gets the email mime-type according to the email structure
     * @param $structure - Type of the file
     * @return MimeType
     */
	public static function getMimeType(&$structure) {
   		$primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
		if($structure->subtype) {
		   	return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
		}
   		return "TEXT/PLAIN";
    }

    /**
     * Function used to get the Message from the email
     * @param $mbox         - Imap Connection to the inbox
     * @param $email_number - The number of the email in the Inbox
     * @param $mime_type    - The Mime-Type of the email
     * @param $structure    - The structure of the email
     * @return $data
     */
    public static function getPart($mbox, $email_number, $mime_type, $structure = false, $part_number = false){
    	$prefix = NULL;
	   	if(!$structure)
	   	{
	   		$structure = imap_fetchstructure($mbox, $email_number);
	   	}
	   	if($structure){
	   		if($mime_type == self::getMimeType($structure))
	   		{
	   			if(!$part_number)
	   			{
	   				$part_number = "1";
	   			}
	   			$text = imap_fetchbody($mbox, $email_number, $part_number);
	   			if($structure->encoding == 3){
	   				return imap_base64($text);
	   			}else if($structure->encoding == 4) {
	   				return imap_qprint($text);
	   			}else{
	   				return $text;
	   			}
	   		}	   
			if($structure->type == 1)
			{
		   		while(list($index, $sub_structure) = each($structure->parts)) 
		   		{
		   			if($part_number)
		   			{
						$prefix = $part_number . '.';
		   			}
		   			$data = self::getPart($mbox, $email_number, $mime_type, $sub_structure, $prefix.($index + 1));
		   			if($data) 
		   			{
		   				return $data;
		   			}
		   		} 
	   		}
	   	} 
	   	return FALSE;
    }

     /**
     * Get the message body of the specific email according to the mailformat
     * @param $mbox         - Imap Connection to the inbox
     * @param $email_number - The number of the email in the Inbox
     * @return $msgBody
     */
    public static function getMsgBody($mbox, $email_number){

	   $dataText = self::getPart($mbox, $email_number, "TEXT/PLAIN");
	   $dataHTML = self::getPart($mbox, $email_number, "TEXT/HTML");
   
	   if ($dataHTML != ""){ //HTML
		  $msgBody    = $dataHTML;
	   	  $mailBody   = $msgBody;
	   }
	   else //TEXT
	   {
	   	  $msgBody    = str_replace("<br>","\n",$dataText);
	   	  $mailBody   = "<html><head><title>Message</title></head><body bgcolor=\"white\">$msgBody</body></html>";
	   }

       return htmlspecialchars($msgBody);
	}

	 /**
     * Query the email for attachements
     * @param $mbox         - Imap Connection to the inbox
     * @param $email_number - The number of the email in the Inbox
     * @param $location     - Where the attachments are to be saved in the file system eg: 'attachements/'
     * @return $fileNames   - An array of the name of the files saved in the location
     */
	public static function queryAttachments($mbox, $email_number, $location){

		$struct       = imap_fetchstructure($mbox,$email_number);
		$contentParts = count($struct->parts);
		$filenames    = array();
		$allFiles     = array();
		if ($contentParts >= 2)
	    {
			for ($i=2;$i<=$contentParts;$i++)
			{
		   		$att[$i-2] = imap_bodystruct($mbox,$email_number,$i);
		   	}
			for ($k=0;$k<sizeof($att);$k++)
			{
				if($att[$k]->ifparameters!=0){
					if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value == "US-ASCII"){

			   			if ($att[$k]->parameters[1]->value != "") 
			   			{
			   				$filenames[$k] = $att[$k]->parameters[1]->value;
			   			}

			   		}elseif ($att[$k]->parameters[0]->value != "iso-8859-1" &&    $att[$k]->parameters[0]->value != "ISO-8859-1"){

			   			$filenames[$k] = $att[$k]->parameters[0]->value;
			   		}
				}elseif($att[$k]->ifdparameters != 0){
					if ($att[$k]->dparameters[0]->value == "us-ascii" || $att[$k]->dparameters[0]->value == "US-ASCII"){

			   			if ($att[$k]->dparameters[1]->value != "") 
			   			{
			   				$filenames[$k] = $att[$k]->dparameters[1]->value;
			   			}

			   		}elseif ($att[$k]->dparameters[0]->value != "iso-8859-1" &&    $att[$k]->dparameters[0]->value != "ISO-8859-1"){

			   			$filenames[$k] = $att[$k]->dparameters[0]->value;
			   		}
				}					
				
		   		$strFileName = $filenames[$k];
			   	$strFileType = strrev(substr(strrev($strFileName),0,4));
			   	$fileContent = imap_fetchbody($mbox,$email_number,$k+2);

			   	if(!empty($strFileName)){
			   		$fileStatus  = self::downloadFile($strFileType, $strFileName, $fileContent, $location);	
			   		$allFiles[] = $strFileName;		   		
			   	}			   	
			}	
	    }

	    if(isset($fileStatus) && $fileStatus == "TRUE")
	    {
	    	return $allFiles;
	    }
	    else
	    {
	    	return "FALSE";
	    }
	}

	/**
     * Get the mails from the inbox according to the specified imap_search query and saves the email info to the DB.
     * @param $mbox         - Imap Connection to the inbox
     * @param $searchquery  - The imap_search 
     * @return void
     */
	public static function getMails($mbox, $searchquery){		
		$result	      = imap_search($mbox,"".$searchquery."");
		
		if(!empty($result))
		{
			foreach($result as $email_number)
			{
				$overview  = imap_fetch_overview($mbox,$email_number,0);
				$message   = imap_fetchbody($mbox,$email_number,2);
				$body      = imap_fetchstructure($mbox,$email_number);
				$header    = imap_headerinfo($mbox, $email_number);	

				$subject =  $overview[0]->subject;
				$sender  =  $header->from[0]->mailbox . "@" . $header->from[0]->host;				
				$odate   =  $overview[0]->date;
				$date    =  date('Y-m-d H:i:s',strtotime($odate));
				$uid     =  $overview[0]->uid;

				$mailExists = self::checkMailExists($sender,$date);

				if($mailExists == "FALSE"){	
					$location  = self::getLocation($sender);				
					$msg       = self::getMsgBody($mbox, $email_number);
					$fileNames = self::queryAttachments($mbox, $email_number, $location);
					self::saveEmails($subject, $sender, $msg, $location, $date, $fileNames);
				}
			}
		}
	}

	/**
     * Check whether mail information already exists in the DB
     * @param $sender     - The email address of the sender
     * @param $date       - Date/Time the mail was sent
     * @return TRUE/FALSE
     */
	public static function checkMailExists($sender,$date){
		
		$sql    = "SELECT mailID FROM tblmails WHERE sender = '$sender' AND date = '$date'";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }

		if (mysql_num_rows($result) > 0) {
			return "TRUE";
		}else{
			return "FALSE";
		}
	}

	/**
     * Save the email info including the attachements to the DB
     * @param $subject   - Subject of the email
     * @param $sender    - The email address of the sender 
     * @param $msg       - The body message
     * @param $location  - The location where the attachements are going to be saved 
     * @param $date      - Date/Time the email was sent
     * @param $fileNames - The array of the names of the attachments downloaded 
     * @return void
     */
	public static function saveEmails($subject, $sender, $msg, $location, $date, $fileNames){
		$subject = self::sanitize($subject);
		$sender  = self::sanitize($sender);
		$msg     = self::sanitize($msg);

		$query  = " INSERT INTO tblmails SET sender = '$sender', subject = '$subject', msg = '$msg', date = '$date' ";
		$result = mysql_query($query);
        if (!$result) {
            die("Could not successfully run query ($query) from DB: " . mysql_error());
        }		
		$mailID = mysql_insert_id();

		if($fileNames != "FALSE"){
			foreach ($fileNames as $fileName){
				$query  = " INSERT INTO tblattachements SET location = '$location', filename = '$fileName', mailID = '$mailID'";
				$result = mysql_query($query);
		        if (!$result) {
		            die("Could not successfully run query ($query) from DB: " . mysql_error());
		        }		
			}
		}
		header('location:index.php');
	}

	/**
     * Get the location where the attachement is to be saved
     * @param $sender - Subject of the email
     * @return void
     */
	public static function getLocation($sender){
		$xyear  = date('Y');
		$xmonth = date('M');
		$xday   = date('d');
		$xpath  = "attachements/".$xyear."/".$xmonth."/".$xday."/".$sender."/";

		if(!is_dir($xpath)){
			if(!is_dir("attachements/".$xyear)){
				mkdir("attachements/".$xyear, 0777);
			}
			if(!is_dir("attachements/".$xyear."/".$xmonth)){
				mkdir("attachements/".$xyear."/".$xmonth, 0777);
			}
			if(!is_dir("attachements/".$xyear."/".$xmonth."/".$xday)){
				mkdir("attachements/".$xyear."/".$xmonth."/".$xday, 0777);
			}
			if(!is_dir("attachements/".$xyear."/".$xmonth."/".$xday."/".$sender)){
				mkdir("attachements/".$xyear."/".$xmonth."/".$xday."/".$sender, 0777);
			}
		}
		return $xpath;
	}

	/**
     * Sanitize Input Values
     * @param $value - eg: mail.site.com
     * @return Sanitized values
     */

	public static function sanitize($value){
		$value   = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES); 
		$value   = addslashes($value);

		return $value;
	}


	 /**
     * Save Email Settings
     * @param $mailserver - eg: mail.site.com
     * @param $port       - eg: 110
     * @param $username   - eg: maail
     * @param $password   - eg: mypassword
     * @param $server_type- eg: POP3 OR IMAP OR NNTP
     * @return $msg
     */
	public static function saveSettings($mailserver, $port, $username, $password, $server_type){

		$mailserver  = self::sanitize($mailserver);
		$port        = self::sanitize($port);
		$username    = self::sanitize($username);
		$server_type = self::sanitize($server_type);

		$sql    = "SELECT setID FROM tblsettings";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }

		if (mysql_num_rows($result) > 0) {
			$query  = " UPDATE tblsettings SET mailserver = '$mailserver', port = '$port', username = '$username', password = '$password' , server_type = '$server_type' ";
		}else{
			$query  = " INSERT INTO tblsettings SET mailserver = '$mailserver', port = '$port', username = '$username', password = '$password' , server_type = '$server_type' ";
		}

		$result = mysql_query($query);
        if (!$result) {
            die("Could not successfully run query ($query) from DB: " . mysql_error());
        }

        
        $msg = "Your settings have been saved successfully";

        return $msg;
	}

	/**
     * Get Email Settings
     * @return $email settings
     */
	public static function getSettings(){

		$data = array();

		$sql    = "SELECT * FROM tblsettings";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
        while($row = mysql_fetch_array($result)){
			$data['inputMailServer'] = self::removeSlashes($row['mailserver']);
			$data['inputPort']       = self::removeSlashes($row['port']);
			$data['inputEmail']      = self::removeSlashes($row['username']);
			$data['inputServerType'] = self::removeSlashes($row['server_type']);
			$data['inputPassword']   = self::removeSlashes($row['password']);
        }
        return $data;
	}

	/**
     * Strip Slashes input
     * @param $value
     * @return StripSlashed values
     */
	public static function removeSlashes($value){
		$value   = htmlspecialchars(stripslashes($value));
		return $value;
	}

	/**
     * Set up Mail Query
     * @param $mailQuery
     * @return StripSlashed values
     */
	public static function mailQuery($mailQuery){
		$data = self::getSettings();
		extract($data);
		self::connect($inputMailServer,$inputPort, $inputEmail, $inputPassword, $inputServerType,$mailQuery);
	}

	/**
     * View Emails
     * @return All Email Info 
     */
	public static function viewEmails(){
		$data = array();
		$sql    = "SELECT * FROM tblmails";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
        $i = 1;
        while($row = mysql_fetch_array($result)){
			$data[$i]['mailID']       = self::removeSlashes($row['mailID']);
			$data[$i]['subject']      = self::removeSlashes($row['subject']);
			$data[$i]['date']         = self::removeSlashes($row['date']);
			$data[$i]['sender']       = self::removeSlashes($row['sender']);
			$data[$i]['subject']      = self::removeSlashes($row['subject']);
			$data[$i]['attachements'] = self::emailAttachments($data[$i]['mailID']);
			$i++;
        }
        return $data;
	}


	/**
     * Get Specific Email's Attachements
     * @param  Email ID
     * @return All Attachement info
     */
	public static function emailAttachments($mailID){
		$data   = array();		
		$sql    = "SELECT * FROM tblattachements WHERE mailID = '$mailID'";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
        $i = 1;
        while($row = mysql_fetch_array($result)){
			$data[$i]['location']    = $row['location'];
			$data[$i]['filename'] = $row['filename'];
			$data[$i]['attachID']    = $row['attachID'];	
		$i++;		
        }
        return $data;
	}

	/**
     * Check Whether Email Settings have been setup
     * @return True/False
     */
	public static function checkSettings(){
		$sql    = "SELECT * FROM tblsettings";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
        if (mysql_num_rows($result) > 0) {
			return true;
		}else{
			return false;
		}
	}

	/**
     * View the Email Body
     * @param  Mail ID
     * @return True/False
     */
	public static function viewEmailBody($mailID){
		$sql    = "SELECT msg FROM tblmails WHERE mailID = '$mailID'";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
        while($row = mysql_fetch_array($result)){
			$data['msg'] = htmlspecialchars_decode($row['msg']);		
        }
        return $data;
	}

	/**
     * Delete Email Body
     * @param  Mail ID
     * @return True/False
     */
	public static function deleteMail($mailID){

		$attach = self::emailAttachments($mailID);
		if(!empty($attach)){
			foreach($attach as $a){
				$file = $a['location'].$a['filename'];
				unlink($file);
			}
			$sql    = "DELETE FROM tblattachements WHERE mailID = '$mailID'";
	        $result = mysql_query($sql);
	        if (!$result) {
	            die("Could not successfully run query ($sql) from DB: " . mysql_error());
	        }
		}

		$sql    = "DELETE FROM tblmails WHERE mailID = '$mailID'";
        $result = mysql_query($sql);
        if (!$result) {
            die("Could not successfully run query ($sql) from DB: " . mysql_error());
        }
	}
}