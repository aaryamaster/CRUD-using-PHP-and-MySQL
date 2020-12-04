<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$First_Name = $Last_Name = $Marks = "";
$First_Name_err = $Last_Name_err = $Marks_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate First Name
    $input_First_name = trim($_POST["First_Name"]);
    if(empty($input_First_Name)){
        $First_name_err = "Please enter First Name.";
    } elseif(!filter_var($input_First_Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $First_Name_err = "Please enter a valid First Name.";
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
        $Marks_err = "Please enter the Marks.";     
    } elseif(!ctype_digit($input_Marks)){
        $Marks_err = "Please enter a positive integer value.";
    } else{
        $Marks = $input_Marks;
    }
    
    // Check input errors before inserting in database
    if(empty($First_Name_err) && empty($Last_Name_err) && empty($Marks_err)) {
        // Prepare an update statement
        $sql = "UPDATE Students SET First_Name=?, Last_Name=?, Marks=? WHERE Id=?";
         
        if($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_First_Name, $param_Last_Name, $param_Marks, $param_Id);
            
            // Set parameters
            $param_First_Name = $First_Name;
            $param_Last_Name = $Last_Name;
            $param_Marks = $Marks;
            $param_Id = $Id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM Students WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $First_Name = $row["First_Name"];
                    $Last_Name = $row["Last_Name"];
                    $Marks = $row["Marks"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($First_Name_err)) ? 'has-error' : ''; ?>">
                            <label>First Name</label>
                            <input type="text" name="First_Name" class="form-control" value="<?php echo $First_Name; ?>">
                            <span class="help-block"><?php echo $First_Name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($last_Name_err)) ? 'has-error' : ''; ?>">
                            <label>Last Name</label>
                            <textarea name="Last_Name" class="form-control"><?php echo $Last_Name; ?></textarea>
                            <span class="help-block"><?php echo $Last_Name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($Marks_err)) ? 'has-error' : ''; ?>">
                            <label>Marks</label>
                            <input type="text" name="Marks" class="form-control" value="<?php echo $Marks; ?>">
                            <span class="help-block"><?php echo $Marks_err;?></span>
                        </div>
                        <input type="hidden" name="Id" value="<?php echo $Id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
