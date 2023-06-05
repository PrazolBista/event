<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Eventify</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		display: flex;
		align-items: center;
		/*background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>);*/
		background:#28a745;
	    background-repeat: no-repeat;
	    background-size: cover;
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    padding: .5em 0.8em;
    color: #000000b3;
	}
	.captcha
	{
		width: 50%;
		background: gray;
		text-align: center;
		font-size: 24px;
		font-weight: 700;
	}

</style>
<?php
include('db_connect.php');
$rand = rand(9999, 1000);
if(isset($_REQUEST['Login']))
{
	$username= $_REQUEST['username'];
	$password= $_REQUEST['password'];
	$captcha= $_REQUEST['captcha'];
	$captcharandom= $_REQUEST['captcha-rand'];


	if($captcha!=$captcharandom)
	{?>
		<script type="text/javascript">
			alert("Invalid Captcha ");
		</script>
	<?php
	}
	else
	{
		$select_query = mysqli_query($conn, "select * from users where username='$username' and password='$password'");
		$result = mysqli_num_rows($select_query);
		if($result>0)
		{?>
			<script type="text/javascript">
				alert("Login Success");
			</script>
		<?php
		}
		else
		{?>
			<script type="text/javascript">
				alert("Invalid email or password");
			</script>
		<?php
		}

	}
}


?>

<body>


  <main id="main" class=" bg-black">
  		<div id="login-left">
  			<div class="logo">
  				<img src="assets/uploads/admin-logo/itlogo1.png">
  			</div>
  		</div>

  		<div id="login-right">
  			<div class="w-100">
  				<h4 class="text-success text-center"><b><?php echo $_SESSION['system']['name'] ?></b></h4>
  				<br>

  			<div class="card col-md-8">
  				<div class="card-body">
  						
  					<form id="login-form" >
  						<div class="form-group">
  							<label for="username" class="control-label text-success">Username</label>
  							<input type="text" id="username" name="username" placeholder="Enter Username" required data-parsley-trigger="keyup"  class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Password</label>
  							<input type="password" id="password" name="password" placeholder="Enter Password" required data-parsley-trigger="keyup" class="form-control">
  						</div>
						<div>
							<div class="form-group">
  							<label for="captcha">Captcha</label>
  							<input type="text" id="captcha" name="captcha" placeholder="Enter Captcha" required data-parsley-trigger="keyup" class="form-control" />
							<input type="hidden" name="captcha-rand" value="<?php echo $rand; ?>"
							</div>	
						  <div class="form-group">
  							<label for="captcha">Captcha Code</label>
							<div class ="captcha"> 
								<?php echo $rand; ?> 
							</div>
						</div>
						<!-- <div class="form-group">
							<input type="submit" id="login" name="login" value="Login" class="btn btn-success" >
						</div> -->
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-success">Login</button></center>
  					</form>
  				</div>
  			</div>
  			</div>
  		</div>
   

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else if(resp == 2){
					location.href ='voting.php';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>