<?php
session_start();
include('includes/config.php');

$sql_bookings = "SELECT b.id, u.FullName, v.VehiclesTitle, v.VehiclesBrand, b.FromDate, b.ToDate, b.VehicleId, b.Status, b.message, b.PostingDate, br.BrandName
                 FROM tblbooking b 
                 JOIN tblvehicles v ON b.VehicleId = v.id 
                 JOIN tblusers u ON b.userEmail = u.EmailId
                 JOIN tblbrands br ON v.VehiclesBrand = br.id"; 
$query_bookings = $dbh->prepare($sql_bookings);
$query_bookings->execute();
$bookings = $query_bookings->fetchAll(PDO::FETCH_ASSOC);

$booking_dates = [];
foreach ($bookings as $booking) {
    $current_date = strtotime($booking['FromDate']);
    $end_date = strtotime($booking['ToDate']);
    while ($current_date <= $end_date) {
        $date_str = date('Y-m-d', $current_date);
        if (!isset($booking_dates[$date_str])) {
            $booking_dates[$date_str] = [];
        }
        if (!isset($booking_dates[$date_str][$booking['VehicleId']])) {
            $booking_dates[$date_str][$booking['VehicleId']] = [];
        }
        $booking_dates[$date_str][$booking['VehicleId']][] = ['user' => $booking['FullName'], 'status' => $booking['Status'], 'id' => $booking['id']];
        $current_date = strtotime('+1 day', $current_date);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

</head>
<body>

<?php include('includes/header.php');?>
   

    <div class="ts-main-content">
		<?php include('includes/leftbar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title">Calendar</h2>

    <div id="calendar"></div>
    
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: false,
                events: [
                    <?php
                    foreach ($bookings as $booking) {
                        $vehicleTitle = htmlspecialchars($booking['VehiclesTitle'], ENT_QUOTES, 'UTF-8');
                        $brandName = htmlspecialchars($booking['BrandName'], ENT_QUOTES, 'UTF-8');
                        $userFullName = htmlspecialchars($booking['FullName'], ENT_QUOTES, 'UTF-8');
                        $status = $booking['Status'];

                        $title = "{$vehicleTitle}-({$userFullName}) [{$brandName}]";

                        $start = $booking['FromDate'];
                        $end = date('Y-m-d', strtotime($booking['ToDate'] . ' +1 day')); 

                        $color = 'blue'; 

                        if ($status == 2) {
                            $color = 'red'; 
                        } else if ($status == 1) {
                            $color = 'green'; 
                        }

                        $current_date = strtotime($start);
                        $end_date = strtotime($booking['ToDate']);
                        $conflict = false;
                        while ($current_date <= $end_date) {
                            $date_str = date('Y-m-d', $current_date);
                            if (isset($booking_dates[$date_str][$booking['VehicleId']])) {
                                foreach ($booking_dates[$date_str][$booking['VehicleId']] as $book) {
                                    if ($book['user'] !== $userFullName && $book['status'] == 1) {
                                        $conflict = true; 
                                    }
                                }
                            }
                            $current_date = strtotime('+1 day', $current_date);
                        }

                        if ($status == 2 && !$conflict) {
                            $color = 'red'; // Canceled booking without conflict
                        } else if ($conflict) {
                            $color = 'orange'; // Conflicting booking
                        }  if ($status == 2 && $conflict){
                            $color = 'red';
                        }

                        echo "{title: '$title', start: '$start', end: '$end', color: '$color', allDay: true},";
                    }
                    ?>
                ]
            });
        });
    </script>
</body>
</html>
