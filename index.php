<?php
  $link = mysqli_connect("79.170.40.224", "cl20-users-6oo", "XrygF!4ty", "cl20-users-6oo");

  if(mysqli_connect_error()){
    die("wrong");
  }
  // insert
  // $insert_query = "INSERT INTO `users` (`email`, `password`) VALUES('tony@hotmail.com', 'other123') ";
  // mysqli_query($link, $insert_query);

  // update
  $update_query ="UPDATE `users` SET email='wtf@hotmail.com',password='123' WHERE id = 1 LIMIT 1";
  mysqli_query($link, $update_query);

  // browse  
  $name = "chao'liu";
  $query = " SELECT * FROM users where name = '".mysqli_real_escape_string($link,$name)."'";
  echo $query;
  if ($result = mysqli_query($link, $query)){
   // $row = mysqli_fetch_array($result);
   // echo "email ".$row[1]." password ".$row[2];
    echo "have result";
    while($row = mysqli_fetch_array($result)){
      echo "<br>";
      // echo " name ".$row[1]."email ".$row[2]." password ".$row[3];   
      print_r($row);
    }
  }

?>