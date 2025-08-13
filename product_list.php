<?php
include("config.php");
include("logged_in_check.php");

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
$sql = "SELECT p.*, c.category_name 
        FROM product_table p 
        LEFT JOIN category_table c 
        ON p.category_id = c.category_id";
$result = $conn->query($sql);
?>

<?php include('header.php'); ?>

<body>
<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        <div id="content-header">
            <h1>Product List</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">
            <div class="col-md-12">
                <h4 class="heading">PRODUCT LIST TABLE</h4>
                <table class="table table-bordered table-highlight">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Product Stock</th>
                            <th>Product Detail</th>
                            <th>Product Brand</th>
                            <th>Product Category</th>
                            <th>Product Image</th> <!-- Yeni sütun -->
                            <th>Actions</th>
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
                            <td><?php echo $row["product_name"] ?></td>
                            <td><?php echo $row["product_price"] ?></td>
                            <td><?php echo $row["product_stock"] ?></td>
                            <td><?php echo $row["product_detail"] ?></td>
                            <td><?php echo $row["product_brand"] ?></td>
                            <td><?php echo $row["category_name"] ?></td>
                            <td><img src="product_images/<?php echo $row["product_image"] ?>" alt="Product Image" style="max-width: 100px;"></td> <!-- Resim yolunu burada doğru şekilde belirtiyoruz -->
                            <td>
                                <a href="update_product.php?product_id=<?php echo $row["product_id"] ?>">
                                    <button type="button" name="updateproduct" class="btn btn-info">Update</button>
                                </a>
                                <form method="GET" action="auth.php" style="display: inline;">                                    
                                    <input type="hidden" name="product_id" value="<?php echo $row["product_id"] ?>">
                                    <button type="submit" name="deleteproduct" class="btn btn-danger">Delete</button>                                    
                                </form>
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
