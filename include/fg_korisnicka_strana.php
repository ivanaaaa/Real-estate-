<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 06-Jan-18
 * Time: 10:09 PM
 */

require_once("class.phpmailer.php");
require_once("formvalidator.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;

    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;

    var $error_message;
    private $db_host;

    //-----Initialization -------
    function FGMembersite()
    {
        $this->sitename = 'Smesti se.com';
        $this->rand_key = '0iQx5oBk66oVZep';
    }

    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;

    }
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }

    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }

    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }

    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
            return false;
        }

        $formvars = array();

        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }

        $this->CollectRegistrationSubmission($formvars);

        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }

        if(!$this->SendUserConfirmationEmail($formvars))
        {
            return false;
        }

        $this->SendAdminIntimationEmail($formvars);

        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Ве молиме напишете го кодот за потврда");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }

        $this->SendUserWelcomeEmail($user_rec);

        $this->SendAdminIntimationOnRegComplete($user_rec);

        return true;
    }

    function Login()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("Корисничкото име не е внесено!");
            return false;
        }

        if(empty($_POST['password']))
        {
            $this->HandleError("Лозинката не е внесена!");
            return false;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }

        $_SESSION[$this->GetLoginSessionVar()] = $username;

        return true;
    }

    function CheckLogin()
    {
        if(!isset($_SESSION)){ session_start(); }

        $sessionvar = $this->GetLoginSessionVar();

        if(empty($_SESSION[$sessionvar]))
        {
            return false;
        }
        return true;
    }

    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }
	function User_id(){
		return isset($_SESSION['id_of_user'])?$_SESSION['id_of_user']:'';
	}
	function User_type(){
		return isset($_SESSION['type'])?$_SESSION['type']:'nema tip';
	}
    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }

    function LogOut()
    {
        session_start();

        $sessionvar = $this->GetLoginSessionVar();

        $_SESSION[$sessionvar]=NULL;

        unset($_SESSION[$sessionvar]);
    }

    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Не е внесено еmail !");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }

    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Не е внесено email!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("Нема внесено код за ресетирање!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);

        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Невалиден код за ресетирање!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }

        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Грешка при промена на лозинка");
            return false;
        }

        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Грешка при праќање на нова лозинка");
            return false;
        }
        return true;
    }

    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Не сте логирани!");
            return false;
        }

        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Не е внесено стара лозинка!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("Не е внесено нова лозинка!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }

        $pwd = trim($_POST['oldpwd']);

        if($user_rec['password'] != md5($pwd))
        {
            $this->HandleError("Старата лозинка не се совпаѓа!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);

        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }

    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }

    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }

    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }

    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }

    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }
    //-------Private Helper functions-----------

    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }

    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysqli_error($this->connection));
    }

    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    }

    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }

    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Логирањето со датабазата е науспешно!");
            return false;
        }
        $username = $this->SanitizeForSQL($username);
        $pwdmd5 = md5($password);
        $qry = "Select * from $this->tablename where username='$username' and password='$pwdmd5' and confirmcode='y' "; // koga ke se vrati od mail potvrda togas ke moze da se logira

        $result = mysqli_query($this->connection, $qry);

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Грешка при логирање. Корисничкото име или лозинката не се совпаѓаат");
            return false;
        }

        $row = mysqli_fetch_assoc($result);


        $_SESSION['name_of_user']  = $row['ime'];
		$_SESSION['type'] = $row['tip_korisnik'];
        $_SESSION['email_of_user'] = $row['email'];
		$_SESSION['id_of_user'] = $row['id'];
		
        return true;
    }

    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Логирањето со базата е науспешно!");
            return false;
        }
        $confirmcode = $this->SanitizeForSQL($_GET['code']);

        $result = mysqli_query($this->connection,"Select ime, email from $this->tablename where confirmcode='$confirmcode'");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Погрешен код за потврда.");
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];

        $qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";

        if(!mysqli_query($this->connection, $qry))
        {
            $this->HandleDBError("Грешка при внес на податоци во табелата\nquery:$qry");
            return false;
        }
        return true;
    }

    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,10);

        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }

    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);
		$user_id = $user_rec['id'];

        $qry = "Update $this->tablename Set password='".md5($newpwd)."' Where  id='$user_id'";

        if(!mysqli_query($this->connection, $qry))
        {
            $this->HandleDBError("Грешка при промена на лозинка\nquery:$qry");
            return false;
        }
		
        return true;
    }

    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Неуспешно поврзување со базата!");
            return false;
        }
        $email = $this->SanitizeForSQL($email);

        $result = mysqli_query($this->connection,"Select * from $this->tablename where email='$email'");

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Не постои корисник со email: $email");
            return false;
        }
        $user_rec = mysqli_fetch_assoc($result);


        return true;
    }

    function SendUserWelcomeEmail(&$user_rec)
    {
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($user_rec['email'],$user_rec['name']);

        $mailer->Subject = "Добредојдовте на ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Здраво ".$user_rec['name']."\r\n\r\n".
            "Добредојдовте! Вашата регистрација на ".$this->sitename." е комплетирана.\r\n".
            "\r\n".
            "Честитки,\r\n".
            "Администраторот\r\n".
            $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Неуспешно праќање на добредојде email на корисникот.");
            return false;
        }
        return true;
    }

    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($this->admin_email);

        $mailer->Subject = "Регистрацијата е комплетна: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Нов корисник регистриран на ".$this->sitename."\r\n".
            "Име: ".$user_rec['name']."\r\n".
            "Email адреса: ".$user_rec['email']."\r\n";

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function GetResetPasswordCode($email)
    {
        return substr(md5($email.$this->sitename.$this->rand_key),0,10);
    }

    function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['name']);

        $mailer->Subject = "Лозинката за ресетирање побарана на ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $link = $this->GetAbsoluteURLFolder().
            '/resetpwd.php?email='.
            urlencode($email).'&code='.
            urlencode($this->GetResetPasswordCode($email));

        $mailer->Body ="Здраво ".$user_rec['name']."\r\n\r\n".
            "Има барање за ресестирање на вашата лозинка на  ".$this->sitename."\r\n".
            "Ве молиме кликнете на линкот подолу за да го комплетирате барањето: \r\n".$link."\r\n".
            "Поздрав,\r\n".
            "Администраторот\r\n".
            $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['ime']);

        $mailer->Subject = "Вашата нова лозинка за ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Здраво ".$user_rec['ime']."\r\n\r\n".
            "Вашата лозинка е ресетирана успешно. ".
            "Ова е вашата ажурирана најава :\r\n".
            "корисничко име :".$user_rec['username']."\r\n".
            "password:$new_password\r\n".
            "\r\n".
            "Најавете се овде: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
            "\r\n".
            "Поздрав,\r\n".
            "Администраторот\r\n".
            $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Автоматска потврда на формата: случај 2 неуспешно");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("name","req","Ве молиме пополнете во полето за Име");
        $validator->addValidation("email","email","Внесот за Email треба да биде валидна email адреса");
        $validator->addValidation("email","req","Ве молиме пополнете во полето за Email");
        $validator->addValidation("username","req","Ве молиме пополнете во полето за Корисничко име");
        $validator->addValidation("password","req","Ве молиме пополнете во полето за Password");


        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }
        return true;
    }

    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
    }

    function SendUserConfirmationEmail(&$formvars)
    {
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($formvars['email'],$formvars['name']);

        $mailer->Subject = "Ваша регистрација на ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $confirmcode = $formvars['confirmcode'];

        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode;

        $mailer->Body ="Здраво ".$formvars['name']."\r\n\r\n".
            "Ви благодариме за вашата регистрација на ".$this->sitename."\r\n".
            "Ве молиме кликнете на линкот подоле за да ја потврдите вашата регистрација.\r\n".
            "$confirm_url\r\n".
            "\r\n".
            "Поздрав,\r\n".
            "Администраторот\r\n".
            $this->sitename;

        if(!$mailer->Send())
        {
            $this->HandleError("Неуспешно праќање на email за потврда на регистрација.");
            return false;
        }
        return true;
    }
    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }

    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($this->admin_email);

        $mailer->Subject = "Нова регистрација: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Нов корисник регистриран на ".$this->sitename."\r\n".
            "Име: ".$formvars['name']."\r\n".
            "Email address: ".$formvars['email']."\r\n".
            "UserName: ".$formvars['username'];

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Конекцијата со базата е неуспешна!");
            return false;
        }
        /**
        if(!$this->Ensuretable())
        {
            return false;
        }
         */
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("Овој email е веќе регистриран");
            return false;
        }

        if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("Ова корисничко име е веќе искористено. Ве молиме пробајте со друго корисничко име");
            return false;
        }
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Внесувањето во базата е науспешно!");
            return false;
        }
        return true;
    }

    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename where $fieldname='".$field_val."'";
        $result = mysqli_query($this->connection, $qry);
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }

    function DBLogin()
    {

        $this->connection = mysqli_connect($this->db_host,$this->username,$this->pwd);

        if(!$this->connection)
        {
            $this->HandleDBError("Поврзувањето со базата е неуспешно! Дали сте сигурни дека податоците за најава се внесени точно?");
            return false;
        }
        if(!mysqli_select_db($this->connection, $this->database))
        {
            $this->HandleDBError('Неуспешно селектирање на базата: '.$this->database.' Ве молиме бидете сигурни дали името на базата е точно');
            return false;
        }
        if(!mysqli_query($this->connection,"SET NAMES 'UTF8'"))
        {
            $this->HandleDBError('Грешка при поставување на utf8 кодирање');
            return false;
        }
        return true;
    }
/**
    function Ensuretable()
    {
        $result = mysqli_query("SHOW COLUMNS FROM $this->tablename");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }

    function CreateTable()
    {
        $qry = "Create Table $this->tablename (".
            "id INT NOT NULL AUTO_INCREMENT ,".
            "ime VARCHAR( 128 ) NOT NULL ,".
            "email VARCHAR( 64 ) NOT NULL ,".
            "phone_number VARCHAR( 16 ) NOT NULL ,".
            "username VARCHAR( 16 ) NOT NULL ,".
            "password VARCHAR( 32 ) NOT NULL ,".
            "confirmcode VARCHAR(32) ,".
            "PRIMARY KEY ( id )".
            ")";

        if(!mysqli_query($qry,$this->connection))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
*/
    function InsertIntoDB(&$formvars)
    {

        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);

        $formvars['confirmcode'] = $confirmcode;

        $insert_query = 'insert into '.$this->tablename.'(
                ime,
                email,
                username,
                password,
                confirmcode
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['name']) . '",
                "' . $this->SanitizeForSQL($formvars['email']) . '",
                "' . $this->SanitizeForSQL($formvars['username']) . '",
                "' . md5($formvars['password']) . '",
                "' . $confirmcode . '"
                )';
        if(!mysqli_query($this->connection, $insert_query))
        {
            $this->HandleDBError("Грешка при внесувањето на податоците во табелата\nquery:$insert_query");
            return false;
        }
        return true;
    }
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
            $ret_str = mysqli_real_escape_string($this->connection, $str);
        }
        else
        {
            $ret_str = addslashes( $str );
        }
        return $ret_str;
    }

    /*
       Sanitize() function removes any potential threat from the
       data submitted. Prevents email injections or any other hacker attempts.
       if $remove_nl is true, newline chracters are removed from the input.
       */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
            );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
}
?>