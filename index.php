<!DOCTYPE html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap.min.css">
	<title>Registration Form</title>
	<style type="text/css">
		body{
			background-color: #e8e5e5;
		}
		td,th{
			width: 92px;
		}
		img{
			height: 80px;
			border-radius: 50%; 
			width:inherit;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-md-5">
		<div class="card border-primary mb-2" style="margin: 20px 0px 0px 25px;">
		  	<div class="card-header">Resgistration Form </div>
			<div class="card-body">
			    <h4 class="card-title">Enter your details</h4>
			    <form method="post" action="" id="register_form" enctype="multipart/form-data">
				  <fieldset>
				  	<?php //include('errors.php'); ?>
				  	<div class="form-group">
				      <label for="name">Name</label>
				      <input type="text" class="form-control" id="name" aria-describedby="name" placeholder="Enter name" name="s_name" maxlength="15" required>
				    </div>
				    <div class="form-group">
				      <label for="email">Email address</label>
				      <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="s_email" required>
				    </div>
				    <fieldset class="form-group">
				      <label>Gender</label>
				      <div class="form-check">
				        <label class="form-check-label">
				          <input type="radio" class="form-check-input" name="s_gender" id="optionsRadios1" value="Male" checked=""> Male
				        </label>&nbsp; &nbsp; &nbsp;
				        <label class="form-check-label">
				          <input type="radio" class="form-check-input" name="s_gender" id="optionsRadios2" value="Female"> Female
				        </label>
				      </div>
				    </fieldset>
				    <div class="form-group">
				      <label for="exampleSelect1">Class</label>
				      <select class="form-control" id="exampleSelect1" name="s_class" required>
				        <option value="10">10</option>
				        <option value="12">12</option>
				        <option value="Graduate">Graduate</option>
				        <option value="Post-Graduate">Post-Graduate</option>
				      </select>
				    </div>
				    <div class="form-group">
				      <label for="exampleInputFile">File input</label>
				      <input type="file" class="form-control-file" id="file" aria-describedby="fileHelp" name="image">
				      <small id="fileHelp" class="form-text text-muted">Image size should be less than 1 MB</small> 
				    </div>
				    
				    <button type="submit" class="btn btn-primary" name="register_submit" id="btn_add" style="float: right;">Submit</button>
				  </fieldset>
				</form> 
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="card border-primary mb-2" style="margin: 20px 0px 0px 0px;">
			<div class="card-body">
			  	
			  	<?php include('config.php');?>
			      <table class="table table-hover" id="table_data">
					  <thead>
					    <tr>
					      <th scope="col"></th>
					      <th scope="col">Name</th>
					      <th scope="col">Email</th>
					      <th scope="col">Gender</th>
					      <th scope="col">Class</th>
					      <th scope="col">Delete</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php 
					  		$query_records = "SELECT * FROM tb_register ORDER BY student_id DESC;";
					  		$result = mysqli_query($db,$query_records);

					  		if(mysqli_num_rows($result) >= 1){

										while($row = $result->fetch_assoc()) {
					  	?>
					    <tr class="table-active">
					      <td><img src="images/<?=$row['profile_pic'];?>" alt="Avatar"  width="45%"></td>
					      <th><?=$row['student_name'];?></th>
					      <td><?=$row['email'];?></td>
					      <td><?=$row['gender'];?></td>
					      <td><?=$row['class'];?></td>
					      <td><span class="badge badge-danger delete_record" data-id='<?=$row['student_id']; ?>'>Delete</span></td>
					    </tr>
					<?php }
						}
					    ?>
					  </tbody>
				  </table>
			</div>
		</div>
	</div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>  
        	$(document).ready(function(event){
            $("#btn_add").click(function (event) {
				event.preventDefault();
				if ($('#name').val() == '') {
					alert("Please enter your name");
				}
				if ($('#email').val() == '') {
					alert("Please enter your Email Id");
				}
				var form = $('form')[0];
				var fd = new FormData(form);
        		var files = $('#file')[0].files;
		        // Check file selected or not
		        if(files.length > 0 ){
		           fd.append('file',files[0]);
                $.ajax({
                            url: "./server.php",
                            type: 'POST',
                            data: fd,
                            cache:false,
                            enctype: 'multipart/form-data',
				            contentType: false,
				            processData: false,
                            dataType: 'Json',
                            success : function(result){
                                alert(result['message']);
			                if(result['success'] == true){
			                    var html = '<tr>';
							     html += '<td><img src="images/'+result['image_upload']+'" alt="Avatar"  width="45%"></td>';
							     html += '<td>'+result['name']+'</td>';
							     html += '<td>'+result['email']+'</td>';
							     html += '<td>'+result['gender']+'</td>';
							     html += '<td>'+result['class']+'</td>';
							     html += '<td><span class="badge badge-danger delete_record" data-id="'+result['student_id']+'">Delete</span></td></tr>';
							     $('#table_data').prepend(html);
			                }else if(result['success'] == false){
			                    alert(result['message']);
			                        }
			                    }
			            });
                }else{
		           alert("Please select a file.");
		        }
            });
           });

        	$(".delete_record").click(function (event) {
        		 var deleteid = $(this).data('id');
        		 var el = this;
        		$.ajax({
				    type:'POST',
				    url: "./delete.php",
				    data: { id:deleteid },
				    success: function(response){
				         if(response == 1){
				         	alert("Record deleted")
				             $(el).closest('tr').fadeOut(800,function(){
						       $(this).remove();
						    });
				         }else{
				             alert("Can't delete the row")
				         }
				    }

				     });
				});

        </script> 

</html>