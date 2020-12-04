<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$First_Name = $Last_Name = $Marks = "";
$First_Name_err = $Last_Name_err = $Marks_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate First Name
    $input_First_Name = trim($_POST["First_Name"]);
    if(empty($input_First_Name)){
       $First_Name_err = "Please enter First name.";
       }elseif(!filter_var($input_First_Name,FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $First_Name_err = "Please enter a valid First name.";
       } else{
        $First_Name = $input_First_Name;
    }
    
    // Validate Last Name
    $input_Last_Name = trim($_POST["Last_Name"]);
    if(empty($input_Last_Name)){
        $Last_Name_err = "Please enter Last Name.";
        } else{
        $Last_Name = $input_Last_Name;
    }
    
    // Validate Marks
    $input_Marks = trim($_POST["Marks"]);
    if(empty($input_Marks)){
        $salary_err = "Please enter the Marks.";     
    } elseif(!ctype_digit($input_Marks)){
        $Maarks_err = "Please enter a positive integer value.";
    } else{
        $Marks = $input_Marks;
    }
    
    // Check input errors before inserting in database
    if(empty($First_Name_err) && empty($Last_Name_err) && empty($Marks_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO Students (First_Name, Last_Name, Marks) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_First_name, $param_Last_Name, $param_Marks);
            
            // Set parameters
            $param_First_name = $First_Name;
            $param_Last_Name = $Last_Name;
            $param_Marks = $Marks;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
				exit();
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add Student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($First_Name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="First Name" class="form-control" value="<?php echo $First_Name; ?>">
                            <span class="help-block"><?php echo $First_Name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Last_Name_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <textarea name="Last Name" class="form-control"><?php echo $Last_Name; ?></textarea>
                            <span class="help-block"><?php echo $Last_Name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Marks_err)) ? 'has-error' : ''; ?>">
                            <label>Marks</label>
                            <input type="text" name="Marks" class="form-control" value="<?php echo $Marks; ?>">
                            <span class="help-block"><?php echo $Marks_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>