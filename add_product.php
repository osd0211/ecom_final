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

// Kategorileri veritabanından çek
$sql = "SELECT * FROM category_table";
$category_result = $conn->query($sql);
?>

<?php include('header.php'); ?>

<body>
    <div id="wrapper">
        <?php include('top_bar.php'); ?>
        <?php include('left_sidebar.php'); ?>
        
        <div id="content">
            <div id="content-header">
                <h1>ADD PRODUCT</h1>
            </div> <!-- #content-header -->  
            
            <div class="portlet">
                <div class="portlet-header"></div> <!-- /.portlet-header -->
                <div class="portlet-content">
                    <form action="auth.php" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-2">Product Name</label>
                            <div class="col-md-10">
                                <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2">Category Name</label>
                            <div class="col-md-10">
                                <select name="category_id" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    if ($category_result->num_rows > 0) {
                                        while($row = $category_result->fetch_assoc()) {
                                            echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2">Product Price</label>
                            <div class="col-md-10">
                                <input type="text" name="product_price" class="form-control" placeholder="Product Price" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2">Product Stock</label>
                            <div class="col-md-10">
                                <input type="text" name="product_stock" class="form-control" placeholder="Product Stock" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-2">Product Detail</label>
                            <div class="col-md-10">
                                <input type="text" name="product_detail" class="form-control" placeholder="Product Detail" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Product Brand</label>
                            <div class="col-md-10">
                                <input type="text" name="product_brand" class="form-control" placeholder="Product Brand" required>
                            </div>
                        </div>   
                    <div class="form-group">
                        <label class="col-md-2">Product Image</label>
                        <div class="col-md-10">
                            <input type="file" name="product_image" class="form-control" required >
                        </div>
                    </div>

                        <button type="submit" name="addproduct" class="btn btn-warning">Submit</button>
                    </form>
                </div> <!-- /.portlet-content -->
            </div> <!-- /.portlet -->
        </div> <!-- #content -->
    </div> <!-- #wrapper -->
    <?php include('footer.php'); ?>
</body>
</html>
