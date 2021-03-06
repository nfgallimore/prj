<?php

require_once 'includes/config.php';

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

$loggedIn = true;
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
	$loggedIn = false;
}
else {
	header("location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty(trim($_POST["username"]))) {
		$username_err = "Please enter a username.";
	} 
	else {
		$sql = "SELECT id FROM Users WHERE Username = ?";
		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = trim($_POST["username"]);
			if (mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
				if (mysqli_stmt_num_rows($stmt) == 1) {
					$username_err = "This username is already taken.";
				}
				else {
					$username = trim($_POST["username"]);
				}
			}
		}
		else {
			echo "Oops! Something went wrong. Please try again later.";
		}
	}
	mysqli_stmt_close($stmt);
	if (empty(trim($_POST['password']))) {
		$password_err = "Please enter a password.";
	} 
	else if (strlen(trim($_POST['password'])) < 6) {
		$password_err = "Password must have atleast 6 characters.";
	} 
	else {
		$password = trim($_POST['password']);
	}

	if (empty(trim($_POST["confirm_password"]))) {
		$confirm_password_err = 'Please confirm password.';
	} 
	else {
		$confirm_password = trim($_POST['confirm_password']);
		if ($password != $confirm_password) {
			$confirm_password_err = 'Password did not match.';
		}
	}

	if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

		$sql = "INSERT INTO Users (Username, Password) VALUES (?, ?)";

		if ($stmt = mysqli_prepare($link, $sql)) {

			mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

			$param_username = $username;
			$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

			if (mysqli_stmt_execute($stmt)) {
				header("location: login.php");
			} 
			else {
				echo $stmt->error;
				echo "Something went wrong. Please try again later.";
			}
		}
		mysqli_stmt_close($stmt);
	}
}
mysqli_close($link);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Sign Up</title>
	<?php include("includes/header_includes.php"); ?>

</head>
<body>
	<div class="page-header">
		<h1><span> Welcome </span> to the University of Akron Course Catalog!</h1>
	</div>

	<?php include("includes/nav_bar.php") ?>

	<div class="wrapper">
		<h2>Register</h2>
		<p>Please fill out this form to create an account.</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
				<label>Username:<sup>*</sup></label>
				<input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
				<span class="help-block"><?php echo $username_err; ?></span>
			</div>
			<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
				<label>Password:<sup>*</sup></label>
				<input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
				<span class="help-block"><?php echo $password_err; ?></span>
			</div>
			<div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
				<label>Confirm Password:<sup>*</sup></label>
				<input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
				<span class="help-block"><?php echo $confirm_password_err; ?></span>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-info" value="Submit">
				<input type="reset" class="btn btn-success" value="Reset">
			</div>
			<p>Already have an account? <a href="login.php">Login here</a>.</p>
		</form>
	</div>
		
	<?php include("includes/footer.php"); ?>

</body>
</html>