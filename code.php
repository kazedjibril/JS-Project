<?php
    session_start();
    //required variables for validations
    $email = $password = $firstname = $middlename = $lastname = $phone = $password_conf = $errors = "";
    $emailErr = $passwordErr = $firstnameErr = $lastnameErr = $phoneErr = $new_p_fnameErr = $new_p_lnameErr = $new_p_phoneErr = $password_confErr = $emailPasswordErr =$errorsMessage = $emptyMessage = " ";

    //REMOVE SOME POSSIBLE ISSUES IN ENTERED DATA FUNCTION BEGINS [AKA check_input_text($text)]
		function check_input_text($text) {
          //remove unnecessary characters
          $text = trim($text);
          //remove backslashes
          $text = stripslashes($text);
          //convert all text in HTML enteties
          $text = htmlspecialchars($text);
          return $text;
    }
    //REMOVE SOME POSSIBLE ISSUES IN ENTERED DATA FUNCTION ENDS [AKA check_input_text($text)]
    //BEGIN LOGIN FUNCTION AKA [login()]
    function login(){
        //connect to the database
        include('connection.php');
				global $email,$emailErr,$password,$passwordErr,$emailPasswordErr;
				//check email emptiness
				if(empty($_POST['email'])){
						//save the $emailErr
						$emailErr = "* Email is required";
				//check password emptiness
				} else if (empty($_POST['password'])) {
						// save $passwordErr and save $email
						$email = check_input_text($_POST['email']) ;
						$passwordErr = "* Password is required";
				} else {
							// check if e-mail address is well-formed
							if (!filter_var(check_input_text($_POST['email']), FILTER_VALIDATE_EMAIL)) {
									//save $emailErr
							 		$emailErr = "* Invalid email format";
							} else if (!preg_match("/^[a-zA-Z0-9 ]*$/", check_input_text($_POST['password']))) {
									//save $passwordErr
									$passwordErr = "* no symbols";
							} else {
									//check email entry and save it into email
									$email = check_input_text($_POST['email']) ;
									//check password entry and save it into password
									$password = check_input_text($_POST['password']);
									//bring data from data
									$query = "SELECT * ";
                  $query.= "FROM customer ";
                  $query.= "WHERE customer_email='".$email."' ";
                  $query.= "AND customer_password='".$password."'";
                  $res = mysqli_query($con,$query);
									//check for possible error if not
									if(mysqli_num_rows($res)==1){
											$row=mysqli_fetch_assoc($res);
											//create a session so that we can easily truck that the patient is logged in
											$_SESSION['c_email'] = $row['customer_email'];
											//redirect the page to the home page as a logged in
											header( "Location: index.php" );
						  		}else {
                      $emailPasswordErr = "** Wrong email or password**";
                  }
								}
						}
				mysqli_close($con);
		}
    //END LOGIN FUNCTION AKA [login()]
    //LOGOUT FUNCTION BEGINS [AKA logout()]
		function logout(){
				session_start();
				session_unset();
				session_destroy();
				session_write_close();
				setcookie(session_name(),'',0,'/');
				session_regenerate_id(true);
				header( "Location: index.php" );
		}
    //LOGOUT FUNCTION ENDS [AKA logout()]
    //CHECK IF SOMEONE IS TRYING TO LOGIN
    if(isset($_POST['login'])){
        login();
    }
    //END OF LOGIN CHECK
    //CHECK IF SOMEONE IS TRYING TO LOGOUT
    if(isset($_GET['logout'])){
        logout();
    }
    //END OF LOGIN CHECK
?>
