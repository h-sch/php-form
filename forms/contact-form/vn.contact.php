<?php

// Clean up the input values
foreach($_POST as $key => $value) 
{
	if(ini_get('magic_quotes_gpc'))
		$_POST[$key] = stripslashes($_POST[$key]);
	
	$_POST[$key] = htmlspecialchars(strip_tags($_POST[$key]));
}

// Assign the input values to variables for easy reference
$name     = isset($_POST["contact_name"]) ?  $_POST["contact_name"] : "";
$email    = isset($_POST["contact_email"]) ? $_POST["contact_email"] : "";
$ctsubject    = isset($_POST["contact_subject"]) ? $_POST["contact_subject"] : "";
$message  = isset($_POST["contact_message"]) ? $_POST["contact_message"] : "";

// Test input values for errors
$res = array();

if(strlen($name) < 2) 
{
	if(!$name) {
		$res[] = "You must enter a name.";
	} else {
		$res[] = "Name must be at least 2 characters.";
	}
}
if(!$email) 
{
	$res[] = "You must enter an email.";
} else if(!validEmail($email)) {
	$res[] = "You must enter a valid email.";
}
if(strlen($ctsubject) < 2) 
{
	if(!$ctsubject) {
		$res[] = "You must enter a message subject.";
	} else {
		$res[] = "Subject must be at least 2 characters.";
	}
}

if(strlen($message) < 10) 
{
	if(!$message) {
		$res[] = "You must enter a message.";
	} else {
		$res[] = "Message must be at least 10 characters.";
	}
}

if($res) 
{
	// Output errors and die with a failure message
	$errortext = "";
	
   foreach($errors as $error) 
   {
		$errortext .= "<li>".$error."</li>";
	}
	
   die("<div class='thanks failure'>The following errors occured:<ul>". $errortext ."</ul></div>");
}
// Send the email
$to = "youremail@yourdomain.com";
$subject = "$ctsubject";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: ".$_POST['contact_name']."  <".$_POST['contact_email'].">\r\n"; 
$headers .= "Reply-To: ".$_POST["contact_email"]."\r\n";
$message = "<table>
<tr><td>Name:</td><td>".$_POST['contact_name']."</td></tr>
<tr><td>Email:</td><td>".$_POST['contact_email']."</td></tr>
<tr><td>Subject:</td><td>".$_POST['contact_subject']."</td></tr>
<tr><td>Message:</td><td>".$_POST['contact_message']."</td></tr>
</table>" ;

mail($to, $subject, $message, $headers);

// Die with a success message
die("<p class='success'><i class='fa fa-check'></i><br/>Your message has been sent. We will contact you shortly.</p>");

// A function that checks to see if
// an email is valid
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

