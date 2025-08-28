<?php 
include("config.php");
$error = "";
$msg = "";

if(isset($_POST['reg'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass = $_POST['pass'];
    $utype = $_POST['utype'];

    $uimage = $_FILES['uimage']['name'];
    $temp_name1 = $_FILES['uimage']['tmp_name'];
    $image_ext = strtolower(pathinfo($uimage, PATHINFO_EXTENSION));

    // Password hash
    $pass_hashed = sha1($pass);

    // Validate all fields filled
    if(empty($name) || empty($email) || empty($phone) || empty($pass) || empty($uimage)) {
        $error = "<p class='alert alert-warning'>Please fill all the fields</p>";
    }
    // Name letters and spaces only
    elseif(!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "<p class='alert alert-warning'>Name must contain letters and spaces only</p>";
    }
    // Email validation
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "<p class='alert alert-warning'>Invalid email format</p>";
    }
    // Phone 10 digits, start with 98
    elseif(!preg_match("/^98[0-9]{8}$/", $phone)) {
        $error = "<p class='alert alert-warning'>Phone number must be 10 digits and start with 98</p>";
    }
    // Image extension check
    elseif(!in_array($image_ext, ['jpg', 'jpeg', 'png'])) {
        $error = "<p class='alert alert-warning'>Only JPG, JPEG, PNG image files are allowed</p>";
    }
    else {
        // Check email duplicate
        $query = "SELECT * FROM user WHERE uemail='$email'";
        $res = mysqli_query($con, $query);
        if(mysqli_num_rows($res) > 0) {
            $error = "<p class='alert alert-warning'>Email ID already exists</p>";
        } else {
            // Insert user
            $sql = "INSERT INTO user (uname, uemail, uphone, upass, utype, uimage) 
                    VALUES ('$name', '$email', '$phone', '$pass_hashed', '$utype', '$uimage')";
            $result = mysqli_query($con, $sql);
            if($result) {
                move_uploaded_file($temp_name1, "admin/user/$uimage");
                $msg = "<p class='alert alert-success'>Registered Successfully</p>";
            } else {
                $error = "<p class='alert alert-danger'>Registration failed due to database error</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<title>Real Estate PHP - Register</title>
</head>
<body>

<div id="page-wrapper">
    <div class="row"> 
        <?php include("include/header.php"); ?>

        <div class="page-wrappers login-body full-row bg-gray">
            <div class="login-wrapper">
                <div class="container">
                    <div class="loginbox">
                        <div class="login-right">
                            <div class="login-right-wrap">
                                <h1>Register</h1>
                                <p class="account-subtitle">Access to our dashboard</p>

                                <?php echo $error; echo $msg; ?>

                                <form method="post" enctype="multipart/form-data" novalidate>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Your Name*" value="<?php if(isset($name)) echo htmlspecialchars($name); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Your Email*" value="<?php if(isset($email)) echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" placeholder="Your Phone (e.g. 9812345678)*" maxlength="10" value="<?php if(isset($phone)) echo htmlspecialchars($phone); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="pass" class="form-control" placeholder="Your Password*">
                                    </div>

                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="utype" value="user" <?php if(!isset($utype) || $utype=="user") echo "checked"; ?>>User
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="utype" value="agent" <?php if(isset($utype) && $utype=="agent") echo "checked"; ?>>Agent
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="utype" value="builder" <?php if(isset($utype) && $utype=="builder") echo "checked"; ?>>Builder
                                        </label>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label><b>User Image</b></label>
                                        <input class="form-control" name="uimage" type="file" accept=".jpg,.jpeg,.png">
                                    </div>

                                    <button class="btn btn-success mt-3" name="reg" value="Register" type="submit">Register</button>
                                </form>

                                <div class="login-or">
                                    <span class="or-line"></span>
                                    <span class="span-or">or</span>
                                </div>

                                <div class="text-center dont-have">Already have an account? <a href="login.php">Login</a></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("include/footer.php"); ?>
    </div>
</div>

<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>