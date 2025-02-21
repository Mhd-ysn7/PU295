<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else {
    if(isset($_GET['del'])) {
        $id=$_GET['del'];
        $sql = "delete from tblbrands WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Page data updated successfully";
    }

    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $contact = $_POST['contact'];
        $dob = $_POST['dob'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];

        // Hash the password using md5 before storing it
        $hashed_password = md5($password);

        $sql = "INSERT INTO tblusers (FullName, EmailId, Password, ContactNo, dob, Address, City, Country) VALUES (:name, :email, :password, :contact, :dob, :address, :city, :country)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $query->bindParam(':contact', $contact, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->execute();
        $msg = "User added successfully";
    }
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    
    <title>Car Rental Portal | Admin Manage Feedback</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- Sandstone Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap Datatables -->
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <!-- Bootstrap social button library -->
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <!-- Bootstrap select -->
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <!-- Bootstrap file input -->
    <link rel="stylesheet" href="css/fileinput.min.css">
    <!-- Awesome Bootstrap checkbox -->
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <!-- Admin Stye -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap{
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Registered Users</h2>

                        <!-- Add User Form -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Add New User</div>
                            <div class="panel-body">
                                <?php if($msg){ ?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div><?php } ?>
                                <form method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Full Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 control-label">Contact Number</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="contact" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Date of Birth</label>
                                        <div class="col-sm-4">
                                            <input type="date" name="dob" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 control-label">Address</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">City</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="city" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 control-label">Country</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="country" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-8 col-sm-offset-2">
                                            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Registered Users Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Registered Users</div>
                            <div class="panel-body">
                                <?php
                                    $sql = "SELECT * FROM tblusers";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                ?>
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Contact no</th>
                                            <th>DOB</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Country</th>
                                            <th>Reg Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $cnt = 1;
                                            foreach($results as $result) {
                                        ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt);?></td>
                                            <td><?php echo htmlentities($result->FullName);?></td>
                                            <td><?php echo htmlentities($result->EmailId);?></td>
                                            <td><?php echo htmlentities($result->ContactNo);?></td>
                                            <td><?php echo htmlentities($result->dob);?></td>
                                            <td><?php echo htmlentities($result->Address);?></td>
                                            <td><?php echo htmlentities($result->City);?></td>
                                            <td><?php echo htmlentities($result->Country);?></td>
                                            <td><?php echo htmlentities($result->RegDate);?></td>
                                        </tr>
                                        <?php $cnt = $cnt + 1; } ?>
                                    </tbody>
                                </table>
                                <?php } else { ?>
                                    <div class="alert alert-warning">No users found.</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php } ?>
