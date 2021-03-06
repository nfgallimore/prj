<?php

require_once 'includes/config.php';

if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  	header("location: login.php");
    exit;
}

$courseid = htmlspecialchars($_GET['id']);
$title = htmlspecialchars($_GET['title']);


$recommended = $timespent = $reason = $grade = $gpa = $comment = "";
$recommended_err = $timespent_err = $reason_err = $grade_err = $gpa_err = $comment_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (empty(trim($_POST["recommended"]))) {
		$recommended_err = "Please enter a rating.";
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
	if (empty(trim($_POST["gpa"])) || $_POST["gpa"] < 0 || $_POST["gpa"] > 4) {
		$gpa_err = "Please enter a GPA between 0 and 4.0";
	}
	if (empty(trim($_POST["comment"]))) {
		$comment_err = "Please enter a commment.";
	}
	if (empty($recommended_err) && empty($timespent_err) && empty($reason_err) && empty($grade_err) && empty($gpa_err) && empty($comment_err)) {
		$sql = 'INSERT INTO Evaluations (CourseID, UserID, Recommended, TimeSpent, Reason, Grade, GPA, Comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?);';
		$userid = $_SESSION["userid"];
		$recommended = trim($_POST['recommended']);
		$timespent = trim($_POST['timespent']);
		$reason = trim($_POST['reason']);
		$grade = trim($_POST['grade']);
		$gpa = trim($_POST['gpa']);
		$comment = trim($_POST['comment']);

		if ($stmt = mysqli_prepare($link, $sql)) {
			mysqli_stmt_bind_param($stmt, "iiddssds", $courseid, $userid, $recommended, $timespent, $reason, $grade, $gpa, $comment);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			echo "<script> location.href='index.php'; </script>";
			exit;
		}
	}
	mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<title>Evaluate <?php echo $title; ?></title>
  	<?php include("includes/header_includes.php"); ?>

</head>
<body>
	<div class="page-header">
		<h1><span> Evaluate </span> <?php echo $title?></h1>
	</div>

	<?php include("includes/nav_bar.php"); ?>

	<form id="evalform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $courseid; ?>" method="post">
		<div class="left input-container">
			<div id="rating" class="display-block">
				<div class="form-group <?php echo (!empty($recommended_err)) ? 'has-error' : ''; ?>">
					<label>Rating:<sup>*</sup></label><br />
					<label class="radio-inline"><input type="radio" name="recommended" value="1">1</label>
					<label class="radio-inline"><input type="radio" name="recommended" value="2">2</label>
					<label class="radio-inline"><input type="radio" name="recommended" value="3" checked>3</label>	
					<label class="radio-inline"><input type="radio" name="recommended" value="4">4</label>
					<label class="radio-inline"><input type="radio" name="recommended" value="5">5</label>
					<span class="help-block"><?php echo $recommended_err; ?></span>
				</div>
			</div>
			<div id="timespent" class="form-group display-block <?php echo (!empty($timespent_err)) ? 'has-error' : ''; ?>">
				<label>Hours spent per week:<sup>*</sup></label><br />
				<label class="radio-inline"><input type="radio" name="timespent" value="1">1</label>
				<label class="radio-inline"><input type="radio" name="timespent" value="5">5</label>
				<label class="radio-inline"><input type="radio" name="timespent" value="10" checked>10</label>
				<label class="radio-inline"><input type="radio" name="timespent" value="15">15</label>
				<label class="radio-inline"><input type="radio" name="timespent" value="20">20+</label>	
				<span class="help-block"><?php echo $timespent_err; ?></span>
			</div>
			<div id="reason" class="form-group <?php echo (!empty($reason_err)) ? 'has-error' : ''; ?>">
				<label>Reason for taking course:<sup>*</sup></label><br />
				<label class="radio-inline"><input type="radio" name="reason" value="For fun" checked>For fun</label>
				<label class="radio-inline"><input type="radio" name="reason" value="Required">Required</label>
				<span class="help-block"><?php echo $reason_err; ?></span>
			</div>
			<div id="grade" class="form-group <?php echo (!empty($grade_err)) ? 'has-error' : ''; ?>">
				<label for="grade">Grade received:<sup>*</sup></label><br />
			  	<select name="grade" class="form-control">
					<option value="A">A</option>
					<option>A-</option>
					<option>B+</option>
					<option>B</option>
					<option>B-</option>
					<option>C+</option>
					<option>C</option>
					<option>C-</option>
					<option>D+</option>
					<option>D</option>
					<option>D-</option>
					<option>F</option>
				</select>
				<span class="help-block"><?php echo $grade_err; ?></span>
			</div>
			<div id="gpa" class="form-group <?php echo (!empty($gpa_err)) ? 'has-error' : ''; ?>">
				<label>Current GPA:<sup>*</sup></label>
				<input type="text" name="gpa" class="form-control bfh-number" data-min="1" data-max="10" value="<?php echo $gpa; ?>">
				<span class="help-block"><?php echo $gpa_err; ?></span>
			</div>
			<div class="left rt-textarea <?php echo (!empty($comment_err)) ? 'has-error' : ''; ?>">
				<textarea name="comment" cols="70" rows="15" form="evalform" placeholder="Please enter a comment.*"></textarea>
				<div class="error"><?php echo $comment_err; ?></div>
			</div>
			 <div class="buttons">
				<input type="submit" class="btn btn-primary" value="Submit">
				<input type="reset" class="btn btn-default" value="Reset">
			</div>
		</div>
	</form>

<!-- 	<?php include("includes/footer.php"); ?>
 -->
</body>
