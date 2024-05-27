<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_POST['assign_car'])) {
    $user = $_POST['user'];
    $car = $_POST['car'];
    $start_date = $_POST['start_date'];
    $return_date = $_POST['return_date'];
    
    // Insert new booking into tblbooking table
    $sql = "INSERT INTO tblbooking (userEmail, VehicleId, FromDate, ToDate, Status) VALUES (:user, :car, :start_date, :return_date, 1)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':user', $user, PDO::PARAM_STR);
    $query->bindParam(':car', $car, PDO::PARAM_STR);
    $query->bindParam(':start_date', $start_date, PDO::PARAM_STR);
    $query->bindParam(':return_date', $return_date, PDO::PARAM_STR);
    $query->execute();

    $msg="Car Assigned Successfully";
}

// Fetch users
$sql_users = "SELECT id, FullName, EmailId FROM tblusers";
$query_users = $dbh->prepare($sql_users);
$query_users->execute();
$users = $query_users->fetchAll(PDO::FETCH_ASSOC);

// Fetch cars
$sql_cars = "SELECT id, VehiclesTitle FROM tblvehicles";
$query_cars = $dbh->prepare($sql_cars);
$query_cars->execute();
$cars = $query_cars->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing bookings
$sql_bookings = "SELECT b.id, u.FullName, v.VehiclesTitle, b.FromDate, b.ToDate, b.message, b.Status, b.PostingDate, b.TotalPrice FROM tblbooking b JOIN tblvehicles v ON b.VehicleId = v.id JOIN tblusers u ON b.userEmail = u.EmailId";
$query_bookings = $dbh->prepare($sql_bookings);
$query_bookings->execute();
$bookings = $query_bookings->fetchAll(PDO::FETCH_ASSOC);


 
 
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
if(isset($_REQUEST['eid']))
	{
$eid=intval($_GET['eid']);
$status="2";
$sql = "UPDATE tblbooking SET Status=:status WHERE  id=:eid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':eid',$eid, PDO::PARAM_STR);
$query -> execute();

$msg="Booking Successfully Cancelled";
}


if(isset($_REQUEST['aeid']))
	{
$aeid=intval($_GET['aeid']);
$status=1;

$sql = "UPDATE tblbooking SET Status=:status WHERE  id=:aeid";
$query = $dbh->prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query-> bindParam(':aeid',$aeid, PDO::PARAM_STR);
$query -> execute();

$msg="Booking Successfully Confirmed";
}

// 

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
    
    <title>Car Rental Portal | Admin Manage Bookings</title>

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
    <!-- Admin Stye -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 <?php include('includes/header.php');?>
   

    <div class="ts-main-content">
		<?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Manage Bookings</h2>

                        <div class="panel panel-default">
                            <div class="panel-heading">Assign Car to User</div>
                            <div class="panel-body">
                                <?php if(isset($msg)){ ?>
                                    <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
                                <?php } ?>
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="user">Select User:</label>
                                        <select class="form-control" id="user" name="user">
                                            <?php 
                                            foreach ($users as $user) {
                                                echo "<option value='".$user['EmailId']."'>".$user['FullName']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="car">Select Car:</label>
                                        <select class="form-control" id="car" name="car">
                                            <?php 
                                            foreach ($cars as $car) {
                                                echo "<option value='".$car['id']."'>".$car['VehiclesTitle']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date">Start Date:</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                    </div>
                                    <div class="form-group">
                                        <label for="return_date">Return Date:</label>
                                        <input type="date" class="form-control" id="return_date" name="return_date">
                                    </div>
                                    <button type="submit" name="assign_car" class="btn btn-primary">Assign Car</button>
                                </form>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Bookings Info</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Vehicle</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Posting date</th>
                                            <th>Action</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
$sql = "SELECT b.id, u.FullName, v.VehiclesTitle, b.FromDate, b.ToDate, b.message, b.Status, b.PostingDate , b.TotalPrice
        FROM tblbooking b 
        JOIN tblvehicles v ON b.VehicleId = v.id 
        JOIN tblusers u ON b.userEmail = u.EmailId";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);
$cnt = 1;
if($query->rowCount() > 0)
{
foreach ($results as $result) {
    ?>
    <tr>
        <td><?php echo htmlentities($cnt); ?></td>
        <td><?php echo htmlentities($result['FullName']); ?></td>
        <td><?php echo htmlentities($result['VehiclesTitle']); ?></td>
        <td><?php echo htmlentities($result['FromDate']); ?></td>
        <td><?php echo htmlentities($result['ToDate']); ?></td>
        <td><?php echo htmlentities($result['message']); ?></td>
        <td><?php 
            if ($result['Status'] == 0) {
                echo htmlentities('Not Confirmed yet');
            } else if ($result['Status'] == 1) {
                echo htmlentities('Confirmed');
            } else {
                echo htmlentities('Cancelled');
            }
        ?></td>

        <td><?php echo htmlentities($result['PostingDate']); ?></td>
        <td>
            <a href="manage-bookings.php?aeid=<?php echo htmlentities($result['id']); ?>" onclick="return confirm('Do you really want to Confirm this booking')">Confirm</a> /
            <a href="manage-bookings.php?eid=<?php echo htmlentities($result['id']); ?>" onclick="return confirm('Do you really want to Cancel this Booking')">Cancel</a>
        </td>
        <td><?php echo htmlentities($result['TotalPrice']); ?></td>
    </tr>
    <?php 
    $cnt++;
} }
?>



										
										</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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