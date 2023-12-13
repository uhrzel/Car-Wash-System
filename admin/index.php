<?php
session_start();
include('includes/config.php');

// Step 1: Generate Random CAPTCHA Code
function generateCaptchaCode($length = 6)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$captchaCode = '';
	for ($i = 0; $i < $length; $i++) {
		$captchaCode .= $characters[rand(0, strlen($characters) - 1)];
	}
	$_SESSION['captcha_code'] = md5($captchaCode); // Store hashed value in session
	return $captchaCode;
}

if (isset($_POST['login'])) {
	$uname = $_POST['username'];
	$password = md5($_POST['password']);

	// Step 2: Check CAPTCHA on Form Submission
	if (!isset($_POST['captcha']) || md5($_POST['captcha']) != $_SESSION['captcha_code']) {
		echo "<script>alert('Invalid CAPTCHA code');</script>";
	} else {
		$sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
		$query = $dbh->prepare($sql);
		$query->bindParam(':uname', $uname, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_OBJ);

		if ($query->rowCount() > 0) {
			$_SESSION['alogin'] = $_POST['username'];
			echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
		} else {
			echo "<script>alert('Invalid Details');</script>";
		}
	}
}

// Generate CAPTCHA code and store it in session
$captchaCode = generateCaptchaCode();
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>CWMS | Admin Sign in</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
	<!-- Custom CSS -->
	<link href="css/style.css" rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="css/morris.css" type="text/css" />
	<!-- Graph CSS -->
	<link href="css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<!-- jQuery -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<!-- //jQuery -->
	<link href='//fonts.googleapis.com/css?family=Roboto:700,500,300,100italic,100,400' rel='stylesheet' type='text/css' />
	<link href='//fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<!-- lined-icons -->
	<link rel="stylesheet" href="css/icon-font.min.css" type='text/css' />
	<!-- //lined-icons -->
</head>
<style>
	.captcha-output,
	.captcha {
		padding: 10px;
		font-size: 20px;
		border: 2px solid #ccc;
		border-radius: 5px;
		margin-bottom: 10px;
		background-color: #f8f8f8;
		color: #333;
		text-align: center;
	}

	.captcha-output[readonly] {
		background-color: #eee;
		cursor: not-allowed;
	}



	.captcha-agileits {
		margin-bottom: 20px;
	}

	.username {
		display: block;
		font-size: 16px;
		margin-bottom: 5px;
	}
</style>


<body>
	<div class="main-wthree">
		<div class="container">
			<div class="sin-w3-agile">
				<h2>Sign In</h2>
				<form method="post">
					<div class="username">
						<span class="username">Username:</span>
						<input type="text" name="username" class="name" placeholder="" required="">
						<div class="clearfix"></div>
					</div>
					<div class="password-agileits">
						<span class="username">Password:</span>
						<input type="password" name="password" class="password" placeholder="" required="">
						<div class="clearfix"></div>
					</div>
					<div class="captcha-Display-agileits">
						<span class="captcha-header">Captcha Text:</span>
						<input type="text" name="captcha-output" class="captcha-output" value="<?php echo $captchaCode; ?>" readonly>
						<div class="clearfix"></div>
					</div>

					<div class="captcha-agileits">
						<span class="username">CAPTCHA:</span>
						<input type="text" name="captcha" class="captcha" placeholder="Enter CAPTCHA" required="">
						<div class="clearfix"></div>
					</div>
					<div class="login-w3">
						<input type="submit" class="login" name="login" value="Sign In">
					</div>
					<div class="clearfix"></div>
				</form>
				<div class="back">
					<a href="../index.php">Back to home</a>
				</div>

			</div>
		</div>
	</div>
</body>

</html>