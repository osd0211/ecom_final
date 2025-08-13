
<?php
include("config.php");

// Veritabanı bağlantısını yap
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "berkhoca_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Veritabanından verileri alarak tabloya ekle
$sql = "SELECT c.*, o.* 
        FROM checkout_table c 
        LEFT JOIN (
            SELECT * FROM order_detail_table 
            GROUP BY order_id
        ) o 
        ON c.order_id = o.order_id";
$result = $conn->query($sql);
?>

<?php include('header.php'); ?>

<body>
<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        <div id="content-header">
            <h1>ORDERS</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <div class="col-md-12">
                <h4 class="heading">ORDERS TABLE</h4>
                <table class="table table-bordered table-highlight">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $count = 0;
                            while ($row = $result->fetch_assoc()) {
                                $count++;
                        ?>
                        <tr>
                            <td><?php echo $count ?></td>
                            <td><?php echo $row["customer_name"] ?></td>
                            <td><?php echo $row["customer_surname"] ?></td>
                            <td><?php echo $row["customer_address"] ?></td>
                            <td><?php echo $row["customer_city"] ?></td>
                            <td><?php echo $row["customer_email"] ?></td>
                            <td><?php echo $row["customer_phone"] ?></td>
                            <td>
                                <a href="order_detail.php?order_id=<?php echo $row["order_id"] ?>" class="btn btn-info">Details</a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='8'>No records found</td></tr>"; // Sütun sayısı 8 oldu
                        }
                        ?>
                    </tbody>
                </table>
            </div> <!-- /.col -->
        </div> <!-- /.content-container -->
    </div> <!-- /#content -->
    <?php include('footer.php'); ?>
</div> <!-- /#wrapper -->
</body>
</html>
