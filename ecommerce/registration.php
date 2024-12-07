<?php
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"]."/app/config/Directories.php");
    require_once(ROOT_DIR."includes/header.php");
    

    if(isset($_SESSION["success"])){
        $messageSuccess = $_SESSION["success"];
        unset($_SESSION["success"]);

    }


    if(isset($_SESSION["error"])){
        $messageError = $_SESSION["error"];
        unset($_SESSION["error"]);

    }

  
?>

    <!-- Navbar -->
    <?php
    require_once("includes\\navbar.php");

?>

    <!-- Registration Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Create Your Account</h4>
                    </div>
                    <div class="card-body">

                    <!-- Message Response -->
                    <?php if (isset( $messageSuccess)){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><?php echo  $messageSuccess; ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>
    

                    <?php if (isset($messageError)){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><strong><?php echo $messageError; ?></strong></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php } ?>

                        <form action="<?php echo BASE_URL;?>app/auth/register.php" method="POST">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p>Already have an account? <a href="login.html" class="text-primary">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once(ROOT_DIR."includes/footer.php");?>