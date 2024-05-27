<?php 
session_start();
include('includes/config.php');
error_reporting(0);

$model = isset($_GET['model']) ? $_GET['model'] : '';
$brand = isset($_GET['brand']) ? $_GET['brand'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$seats = isset($_GET['seats']) ? $_GET['seats'] : '';

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="keywords" content="">
<meta name="description" content="">
<title>Car Rental Portal | Check Cars</title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<body>


<!--Header--> 
<?php include('includes/header.php');?>

<section class="page-header listing_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Check Cars</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="#">Home</a></li>
        <li>Check Cars</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>

<section class="listing-page">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-md-push-3">
                <div class="result-sorting-wrapper">
                    <div class="sorting-count">
                        <?php 
                        $sql = "SELECT COUNT(*) as count FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand";

                        if (!empty($model) || !empty($brand) || !empty($min_price) || !empty($max_price) || !empty($seats)) {
                            $sql .= " WHERE";
                            $conditions = array();
                            if (!empty($model)) {
                                $conditions[] = " VehiclesTitle LIKE '%$model%'";
                            }
                            if (!empty($brand)) {
                                $conditions[] = " BrandName LIKE '%$brand%'";
                            }
                            if (!empty($min_price)) {
                                $conditions[] = " PricePerDay >= $min_price";
                            }
                            if (!empty($max_price)) {
                                $conditions[] = " PricePerDay <= $max_price";
                            }
                            if (!empty($seats)) {
                                $conditions[] = " SeatingCapacity = $seats";
                            }
                            $sql .= implode(" AND ", $conditions);
                        }

                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $countResult = $query->fetch(PDO::FETCH_ASSOC);
                        $cnt = $countResult['count'];
                        ?>
                        <p><span><?php echo htmlentities($cnt);?> Available Cars</span></p>
                    </div>
                </div>
                <?php 
                $sql = "SELECT tblvehicles.*, tblbrands.BrandName FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand";

                if (!empty($model) || !empty($brand) || !empty($min_price) || !empty($max_price) || !empty($seats)) {
                    $sql .= " WHERE";
                    $conditions = array();
                    if (!empty($model)) {
                        $conditions[] = " VehiclesTitle LIKE '%$model%'";
                    }
                    if (!empty($brand)) {
                        $conditions[] = " BrandName LIKE '%$brand%'";
                    }
                    if (!empty($min_price)) {
                        $conditions[] = " PricePerDay >= $min_price";
                    }
                    if (!empty($max_price)) {
                        $conditions[] = " PricePerDay <= $max_price";
                    }
                    if (!empty($seats)) {
                        $conditions[] = " SeatingCapacity = $seats";
                    }
                    $sql .= implode(" AND ", $conditions);
                }

                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0) {
                    foreach($results as $result) {  ?>
                        <div class="product-listing-m gray-bg">
                            <div class="product-listing-img">
                                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" class="img-responsive" alt="Image" />
                            </div>
                            <div class="product-listing-content">
                                <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></a></h5>
                                <p class="list-price">$<?php echo htmlentities($result->PricePerDay);?> Per Day</p>
                                <ul>
                                    <li><i class="fa fa-user" aria-hidden="true"></i><?php echo htmlentities($result->SeatingCapacity);?> seats</li>
                                    <li><i class="fa fa-calendar" aria-hidden="true"></i><?php echo htmlentities($result->ModelYear);?> model</li>
                                    <li><i class="fa fa-car" aria-hidden="true"></i><?php echo htmlentities($result->FuelType);?></li>
                                </ul>
                                <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>" class="btn">View Details <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a>
                            </div>
                        </div>
                <?php }} ?>
            </div>
            <aside class="col-md-3 col-md-pull-9">
                <div class="sidebar_widget">
                    <div class="widget_heading">
                        <h5><i class="fa fa-filter" aria-hidden="true"></i> Find Your Car </h5>
                    </div>
                    <form method="GET" action="">
                        <div class="form-group">
                            <label for="model">Model:</label>
                            <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlentities($model); ?>">
                        </div>
                        <div class="form-group">
                            <label for="brand">Brand:</label>
                            <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlentities($brand); ?>">
                        </div>
                        <div class="form-group">
                            <label for="min_price">Minimum Price per Day:</label>
                            <input type="text" class="form-control" id="min_price" name="min_price" value="<?php echo htmlentities($min_price); ?>">
                        </div>
                        <div class="form-group">
                            <label for="max_price">Maximum Price per Day:</label>
                            <input type="text" class="form-control" id="max_price" name="max_price" value="<?php echo htmlentities($max_price); ?>">
                        </div>
                        <div class="form-group">
                            <label for="seats">Number of Seats:</label>
                            <input type="text" class="form-control" id="seats" name="seats" value="<?php echo htmlentities($seats); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>

                <div class="sidebar_widget">
                    <div class="widget_heading">
                        <h5><i class="fa fa-car" aria-hidden="true"></i> Recently Listed Cars</h5>
                    </div>
                    <div class="recent_addedcars">
                        <ul>
                            <?php 
                            $sql = "SELECT tblvehicles.*, tblbrands.BrandName FROM tblvehicles JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand ORDER BY id DESC LIMIT 4";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            if($query->rowCount() > 0) {
                                foreach($results as $result) { ?>
                                    <li class="gray-bg">
                                        <div class="recent_post_img">
                                            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>">
                                                <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>" alt="image">
                                            </a>
                                        </div>
                                        <div class="recent_post_title">
                                            <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id);?>">
                                                <?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?>
                                            </a>
                                            <p class="widget_price">$<?php echo htmlentities($result->PricePerDay);?> Per Day</p>
                                        </div>
                                    </li>
                            <?php }} ?>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<!--Footer -->
<?php include('includes/footer.php');?>

<!--Login-Form -->
<?php include('includes/login.php');?>

<?php include('includes/registration.php');?>
<?php include('includes/forgotpassword.php');?>
<!-- Scripts --> 
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 


<script src="assets/js/bootstrap-slider.min.js"></script> 

<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>

