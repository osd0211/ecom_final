<?php
include("config.php");
// include("logged_in_check.php");

// Veritabanı bağlantısını yap
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "berkhoca_db";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// URL'den order_id al
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($order_id === null) {
    die("No order ID provided.");
}

// Sipariş detaylarını order_detail_table ve product_table ile birleştirerek çek
$sql = "SELECT od.*, p.product_name, p.product_image, p.product_price 
        FROM order_detail_table od 
        INNER JOIN product_table p 
        ON od.product_id = p.product_id 
        WHERE od.order_id = '$order_id'";
$result = $conn->query($sql);

?>

<?php include('header.php'); ?>

<body>
    <div id="wrapper">
        <?php include('top_bar.php'); ?>
        <?php include('left_sidebar.php'); ?>

        <div id="content">
            <div id="content-header">
                <h1>ORDER DETAILS</h1>
            </div>

            <div id="content-container">
                <div class="col-md-12">
                    <h4 class="heading">ORDER DETAILS TABLE</h4>
                    <table class="table table-bordered table-highlight">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                                <th>Image</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                $count = 0;
                                $total_price = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $count++;
                                    $total_price += ($row['product_price'] * $row['quantity']);
                            ?>
                                    <tr>
                                        <td><?php echo $count ?></td>
                                        <td><?php echo $row["product_id"] ?></td>
                                        <td><?php echo $row["product_name"] ?></td>
                                        <td><?php echo $row["quantity"] ?></td>
                                        <td><?php echo $row["product_price"] ?></td>
                                        <td><?php echo $row["product_price"] * $row["quantity"] ?></td>
                                        <td><img src="product_images/<?php echo $row["product_image"] ?>" alt="Product Image" style="max-width: 100px;"></td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php if ($total_price > 0) : ?>
                        <h4>Total Price: <?php echo $total_price ?></h4>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
</body>

</html>
