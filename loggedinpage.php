<?php
  session_start();
  if(array_key_exists("id", $_COOKIE)){
    $_SESSION['id']=$_COOKIE['id'];
  }
  if(array_key_exists("id", $_SESSION)){
    include("connection.php");
    $query="SELECT diary 
    FROM `users` 
    WHERE id ='".mysqli_real_escape_string($link, $_SESSION['id'])."' LIMIT 1";
    $result = mysqli_query($link,$query);
    $row = mysqli_fetch_array($result);
    $diaryContent = $row['diary'];
    // print_r($row);


    // echo "<p>logged in! </p><a href='signup.php?logout=1'>log out</a>";
  } else {
    header("Location: signup.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<?php 
  include("header.php");
?>
<body>
<nav class="navbar navbar-light bg-faded navbar-fixed-top">
    <a class="navbar-brand" href="#">Diary</a>
  <div class="form-inline float-xs-right">
    <a href='signup.php?logout=1'><button class="btn btn-outline-success" type="submit">log out</button></a>
  </div>
</nav>
<div class="container-fluid">
  <textarea id="diary" class="font-control"><?php echo $diaryContent ?></textarea>
</div>

<?php 
 include("footer.php");
?> 
  </body>
</html>