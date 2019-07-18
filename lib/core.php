<?php
session_start();
require_once'config.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


//function for login
function login($username,$user_type,$password,$conn)  
    {
        $sql="select id from signup where username='$username' AND password='$password' AND user_type=$user_type";
        if($result=$conn->query($sql))
            {
                if($result->num_rows>0)
                        {
                            $row=$result->fetch_assoc();
                            $_SESSION["user_id"]=$row["id"];
                            $_SESSION["user_type"]=$user_type;
                            if($user_type==0)
                            header('Location:menu.php?user_type=0');
                            else
                            header('Location:menu.php?user_type=1');
                        }
                else
                        {
                            return false;
                        }

            }
    }

//function to logout
function logout()
  {
      unset($_SESSION["user_id"]);
      unset($_SESSION["user_type"]);
      header('Location:login.php');
  }
//function for ensuring whtehere admin is login or not
function auth_admin()
    {
        if(isset($_SESSION["admin_login"]))
            {
                 return true;
            }
        else
            {
                return false;           
            }

    }


//function to add image 
function add_image($file,$target_link,$file_name)
    {
                $myFile = $file;     //fetching uploaded image
                $targetpath=$target_link."/";
                $name=$file_name;
                $temp_name=$_FILES['file']['tmp_name'];
                if(move_uploaded_file($temp_name,$targetpath.$file_name))
                    {
                          return true;
                    }
                  else
                      {
                         return false;
                      }
                              
    }
  

//function to check inputes data
function test_input($data)
     {
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
    } 


//generte opt and also sending email
function generate_otp($email)
    {
          $otp=rand(1000,9999);
          $message="Yor OTP is $otp <br> Your OTP is valid for 2 minutes.<br>Team wevdeb";
          $_SESSION["sign_in_otp"]=$otp;
          send_mail($email,"tariyalaman39@gmail.com",$message,"Email Verification ");
    }

//function to check whether a username is already taken or not
  function check_username($username,$conn)
    {
        $sql="select id from signup where username='".$username."'";
        if($result=$conn->query($sql))
            {
                if($result->num_rows==0)
                    {
                        return true;
                    }
                  else
                    {
                        return false;  
                    }
            }
    }
//function for adding a user(from signup)
function add_user($username,$user_type,$pass,$conn)
    {
      $sql="insert into signup(username,password,user_type) values('$username','$pass','$user_type')"; 
            if($conn->query($sql))
          {
                    $_SESSION["user_id"]=$conn->insert_id;
                    $_SESSION["user_type"]=$user_type;         
                    if($user_type==0)
                    {
                         header('Location:menu.php?user_type=0');
                    }
                    else
                    header('Location:menu.php?user_type=1');
          }
          else
          {
                    return false;
          }
    }


//to send mail 
 function send_mail($to,$from,$body,$subject)
    {
            require_once('Mailer\PHPMailer_5.2.0\class.phpmailer.php');
            $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; // or 587
            $mail->IsHTML(true);
            $mail->Username = $from;
            $mail->Password = "fcupxqbx@5";
            $mail->SetFrom($from);
            $mail->Subject = $subject;
            $mail->Body =html_entity_decode($body);
            $mail->AddAddress($to);
            if(!$mail->Send())
                 {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                 }
            else 
                {
                    return true;
                }
    }

//function for user login i.e login from front end
    function user_login($email,$pass,$conn)
        {
            $sql="select id,progress from user_details where email='$email' AND password='$pass'";
            if($result=$conn->query($sql))
                {
                    if($result->num_rows>0)
                        {
                            $row=$result->fetch_assoc();
                            $_SESSION["user_login"]=$row["id"];
                            if($row["progress"]==1)
                                {
                                    generate_otp($email);
                                    header("location:otp.php");
                                }
                            else
                                {
                                    header("location:../index.php");
                                }
                        }
                      else
                        {
                            return false; 
                        }
                }
              else
                {
                      return false;
                }
        }
?>
