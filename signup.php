<?php
  session_start();
  $error ="";
  if (array_key_exists("logout", $_GET)) {
    unset($_SESSION['id']);
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";  
  } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
      header("Location: loggedinpage.php");
  }
  if (array_key_exists("submit", $_POST)) {
    include("connection.php");
    if( !$_POST['email']) {
      $error .=" miss email <br>";
    }

    if (!$_POST['password']) {
     $error .=" miss password <br>";
    }

    if( $error != "") {
      $error ="Error(s): <br>".$error;
    } else {

      if ($_POST['signUp'] == 1) {
        // this is for sign up
        $query = "SELECT id
         FROM `users` 
         WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
        $result = mysqli_query($link,$query);
        if(mysqli_num_rows($result) > 0){
          $error =" email is token";
        } else {
          // echo $query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
          $query = "INSERT INTO `users` (`email`, `password`) 
          VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."'
            , '".mysqli_real_escape_string($link, $_POST['password'])."')";
          if(mysqli_query($link, $query)){
            // echo "insert successfully <br>";
            
            $_SESSION["id"] = mysqli_insert_id($link);
            echo "this is insertid";
            echo mysqli_insert_id($link);
            echo "after set";
            echo $_SESSION['id'];
            
            if($_POST['stayLoggedIn'] == '1'){
              setcookie('id', mysqli_insert_id($link), time() + 60*60*24*365);
            }
            $query= "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
            mysqli_query($link,$query);
            header("Location: loggedinpage.php"); 
          } else {
            $error =  "<p>error for insert data into database</p>";  
          }
        }
      } else {
        // try to sign in
        // check email exit and compare 
        $query = "SELECT *
        FROM `users` 
        WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
        $result = mysqli_query($link,$query);
        $row = mysqli_fetch_array($result);
        if(array_key_exists("id", $row)){
          // have the email and try to compare the passward        
          $hashedPassword = md5(md5($row['id']).$_POST['password']);
          if($hashedPassword == $row['password']){
            $_SESSION["id"] = $row['id'];
            if($_POST['stayLoggedIn'] == '1'){
              setcookie('id', $row['id'], time() + 60*60*24*365);
            }
            header("Location: loggedinpage.php"); 
          } else {
            $error = "no such password email combination";  
          }
        } else {
          $error = "no such password email combination";
        }


        //============
      } 
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <?php 
    include("header.php");
  ?>
  <body>
    <div id="homepage" class="container">
      <h1>Diary</h1>
      <div id="error"><?php if(error != ""){
        echo "<div class='alert alert-danger' role='alert'>".$error."</div>";
      }?></div> 
      <form id="signup" method="post">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="your email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="password">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="repassword" placeholder="enter password again">
        </div>
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
          Remember me
        </label>
        <div class="form-group">
          <input type="hidden" class="form-control" name="signUp" value="1">
        </div>
        <div class="form-group">
          <input type="submit"  class="btn btn-primary" name="submit" value="Sign Up">
        </div>
        <p>
          <a class = "toggletag" href="#">Log in</a>
        </p>
      </form>
      <form id="login" method="post">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="your email">
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="password">
        </div>
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="stayLoggedIn" value=1>
          Remember me
        </label>
        <div class="form-group">
          <input type="hidden" class="form-control" name="signUp" value="0">
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" name="submit" value="Log In">
        </div>
        <p>
          <a class = "toggletag" href="#">Sgin up</a>
        </p>
      </form>
    </div>
    <?php 
      include("footer.php");
    ?> 
  </body>
</html>
