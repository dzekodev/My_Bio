<?php

session_start();

$errors =array();

// connect to the database
$IP='localhost';
$User='root';
$Password='';
$DBName='user_register';
$db = mysqli_connect($IP,$User,$Password,$DBName) or die("Error :\n   Could not connect to the DB.");

$Email="";
if(isset($_POST['reg_user'])){
    // receive all input values
    $FullName = mysqli_real_escape_string($db,$_POST['fullname']);
    $BirthDate = mysqli_real_escape_string($db,$_POST['birth']);
    $Gender =  mysqli_real_escape_string($db,$_POST['gender']);
    $Country =  mysqli_real_escape_string($db,$_POST['country']);
    $Email =  mysqli_real_escape_string($db,$_POST['email']);
    $Password =  mysqli_real_escape_string($db,$_POST['password']);
    $ConfPw = mysqli_real_escape_string($db,$_POST['Confpassword']);
    if(empty($FullName)){
        array_push($errors,"FullName is Required");
    }
    if(empty($BirthDate)){
        array_push($errors,"BirthDate is Required");
    }
    if(empty($Gender)){
        array_push($errors,"Gender is Required");
    }
    if(empty($Email)){
        array_push($errors,"Email is Required");
    }
    if(empty($Password)){
        array_push($errors,"Password is Required");
    }
    if(empty($ConfPw)){
        array_push($errors,"Confirm Password is Required");
    }
    if($Password!=$ConfPw){
        array_push($errors,"Password is not match");
    }
}

//Check if email is already in DB 
$email_check_query = "SELECT * FROM user_register WHERE email='$Email' LIMIT 1";
$result = mysqli_query($db,$email_check_query);
$UserExisting= mysqli_fetch_assoc($result);
if($UserExisting){
    // if user exists
    if ($UserExisting['email']===$Email) {
        array_push($errors, "This email is already exists");
      }
}
//register user if there are no errors in the form
if(count($errors)==0){
    $Password =md5($Password_1);
    $query = "INSERT INTO user_register (fullname,birth_date,gender,country,email,pswrd) VALUES('$FullName','$BirthDate','$Gender','$Country','$Email','$Password')"
    mysqli_query($db,$query);
    $_SESSION['username'] = $FullName;
    $_SESSION['Success']="You are now logged in";
    header('location: index.php')
}