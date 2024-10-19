<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body> 
    <div class="top">
            <h1>IQAC - Internal Quality Assurance CELL</h1>
    </div>
    <div class="container">
        <div class="left">
            <img src="./assets/kec.jfif" alt="" width="650px" style="border-radius:20px 0px 0px 20px; margin-top:6px">
        </div>
        <div class="box form-box">
            <?php
                // Include your database configuration file
                include("php/config.php");

                if(isset($_POST['submit'])){
                    $username = mysqli_real_escape_string($con, $_POST['username']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);

                    // Convert inputs to lowercase
                    $username = strtolower($username);
                    $password = strtolower($password);
                                        
                    
                    
                    // Use prepared statements to prevent SQL injection
                    $stmt = $con->prepare("SELECT * FROM users WHERE username=? AND password=?");
                    $stmt->bind_param("ss", $username, $password);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // Check if the user exists
                    if($result->num_rows == 1){
                        $row = $result->fetch_assoc();
                        $_SESSION['valid'] = true;
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_type'] = $row['user_type'];

                        // Redirect to admin dashboard if username and password are "admin"
                        if($username == "admin" && $password == "admin") {
                            header("Location: dashboard_admin.php");
                            exit; // Exit after redirection
                        }
                        else if($_SESSION['user_type'] == "Hod"){
                            header("Location: dashboard_hod.php");
                            exit;
                        }
                        else if($_SESSION['user_type'] == "Staff"){
                            // Redirect to regular home.php
                            header("Location: dashboard_staff.php");
                            exit; // Exit after redirection
                        }
                        else{
                            echo "<div class='message'>
                            <p> Wrong username or password</p>
                            </div> <br>";
                            echo "<a href='index.php'><button class='btn'>Go Back</button>";
                        }
                    } else {
                        echo "<div class='message'>
                        <p> Wrong username or password</p>
                        </div> <br>";
                        echo "<a href='index.php'><button class='btn'>Go Back</button>";
                    }
                } else { 
            ?>
            <img src="./assets/klogo.png" alt="" height="30px" width="20px" >
            <h2>Kongu Engineering College</h2>
            <header>
                Login
            </header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field">
                    <input type="submit" name="submit" id="btn" class="btn" value="Login">
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
<div class="bottom">
        <a href="files.php"><B>click here to download IQAC file formats</B></a>
    </div>
</body>
</html>
