<?php 
include "config.php";
$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($db,$_POST['id']);
}
if($id > 0){

  // Check record exists
  $checkRecord = "SELECT * FROM tb_register WHERE student_id=".$id;
  $result1 = mysqli_query($db, $checkRecord);
  if(mysqli_num_rows($result1) >= 1 ){
			
    // Delete record
    $query = "DELETE FROM tb_register WHERE student_id=".$id;
    mysqli_query($db,$query);
    echo 1;
    exit;
  }else{
    echo 0;
    exit;
  }
}

echo 0;
exit;
