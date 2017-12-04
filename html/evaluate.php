<?php 
session_start();

if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
    exit;
}
$courseid = $_GET['id'];

require_once '../includes/config.php';

$recommended = $timespent = $reason = $grade = $gpa = "";
$recommended_err = $timespent_err = $reason_err = $grade_err = $gpa_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty(trim($_POST["recommended"]))) {
		$recommended_err = "Please enter a recommendation.";
	}
	if (empty(trim($_POST["timespent"]))) {
		$timespent_err = "Please enter the time spent.";
	}
	if (empty(trim($_POST["reason"]))) {
		$reason_err = "Please enter a reason for taking the course.";
	}
	if (empty(trim($_POST["grade"]))) {
		$grade_err = "Please enter a grade.";
	}
	if (empty(trim($_POST["gpa"]))) {
		$gpa_err = "Please enter your GPA.";
	}
	if (empty($recommended_err) && empty($timespent_err) && empty($reason_err) && empty($grade_err) && empty($gpa_err)) {
		$sql = 'INSERT INTO Evaluations (CourseID, UserID, Recommended, TimeSpent, Reason, Grade, GPA) VALUES (?, ?, ?, ?, ?, ?, ?);';
		$userid = $_SESSION["userid"];
		$recommended = trim($_POST['recommended']);
		$timespent = trim($_POST['timespent']);
		$reason = trim($_POST['reason']);
		$grade = trim($_POST['grade']);
		$gpa = trim($_POST['gpa']);

		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "iiddssd", $courseid, $userid, $recommended, $timespent, $reason, $grade, $gpa);

			if (mysqli_stmt_execute($stmt)) {
				echo $sql;
			}
			else {
				echo "Something went wrong. Please try again later.";
                echo $sql;
            }
			mysqli_stmt_close($stmt);
		}
	}
	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<title>Evaluate <?php echo $title ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
    <style type="text/css">
		body{ font: 14px sans-serif; }
		.wrapper{ width: 350px; padding: 20px; }
	</style>
</head>
<body>
	<form action="<?php echo "evaluate.php/q?id=" . $courseid; ?>" method="post">
	<div class="form-group <?php echo (!empty($recommended_err)) ? 'has-error' : ''; ?>">
		<label>Recommended:<sup>*</sup></label>
		<input type="text" name="recommended" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $recommended; ?>">
	</div>
	<div class="form-group <?php echo (!empty($timespent_err)) ? 'has-error' : ''; ?>">
		<label>Hours spent per week:<sup>*</sup></label>
		<input type="text" name="timespent" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $timespent; ?>">
	</div>
	<div class="form-group <?php echo (!empty($reason_err)) ? 'has-error' : ''; ?>">
		<label>Reason for taking course:<sup>*</sup></label>
		<input type="text" name="reason" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $reason; ?>">
	</div>
	<div class="form-group <?php echo (!empty($grade_err)) ? 'has-error' : ''; ?>">
		<label>Grade received:<sup>*</sup></label>
		<input type="text" name="grade" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $grade; ?>">
	</div>
	<div class="form-group <?php echo (!empty($gpa_err)) ? 'has-error' : ''; ?>">
		<label>Current GPA:<sup>*</sup></label>
		<input type="text" name="gpa" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $gpa; ?>">
	</div>
	<div class="form-group">
		<input type="submit" class="btn btn-primary" value="Submit">
		<input type="reset" class="btn btn-default" value="Reset">
	</div>
	</form>
</body>