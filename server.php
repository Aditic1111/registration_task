<?php
include('config.php');

//print_r($_POST);
$errors = array();
$target_dir = 'images/';
$_SESSION['success'] = "";
//echo $_FILES["image"]["size"];
	$name = mysqli_real_escape_string($db,$_POST['s_name']);
	$email = mysqli_real_escape_string($db,$_POST['s_email']);
	$class = mysqli_real_escape_string($db,$_POST['s_class']);
	$gender = mysqli_real_escape_string($db,$_POST['s_gender']);
	$image_upload = $_FILES['image']['name'];
	$target_file = $target_dir . basename($image_upload);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check file size

		if ($_FILES["image"]["size"] > 1000000) {
		 echo json_encode(array("success"=> false, "message" => "Please try again!, Image size should be less than 1 MB"));
		}

	if (empty($name)) { echo json_encode(array("success"=> false, "message" => "Please try again!, Name is required! Please enter name")); }
	if (empty($email)) { echo json_encode(array("success"=> false, "message" => "Please try again!, Email is required! Please enter email")); }

	if (!empty($email)) {
		$query_email = "SELECT email FROM tb_register WHERE email='$email'";
		$result1 = mysqli_query($db, $query_email);
		if(mysqli_num_rows($result1) >= 1 ){
			echo json_encode(array("success"=> false, "message" => "Please try again!, Email already exists!"));
			exit();
		}
	}
	//echo count($errors);
	if(count($errors) == 0){
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			&& $imageFileType != "gif" ) {
			  echo json_encode(array("success"=> false, "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."));
		}
		

		if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){

		$query_insert = "INSERT INTO tb_register (student_name, email, class, gender, profile_pic) VALUES('$name', '$email', '$class', '$gender', '$image_upload')";
		if(mysqli_query($db, $query_insert)){
			
			echo json_encode(array("success"=> true, 'message' => "Record uploaded succesfully", 'name' => $_POST['s_name'],
			  'email'  => $_POST['s_email'],
			  'class'  => $_POST['s_class'],
			  'gender'  => $_POST['s_gender'],
			  'image_upload'  => $_FILES['image']['name'])); 	
		}
		}else{
			echo json_encode(array("success"=> false, "message" => "Please try again!, Problem in uploading file! Image size should be less than 1 MB")); 
		}
	}



?>
<?php  if (count($errors) > 0) : ?>
	<div class="error">

		<?php foreach ($errors as $error) : ?>
			<small id="fileHelp" class="form-text text-danger"><?php echo $error ?></small>
		<?php endforeach ?>
	</div>
<?php  endif ?>