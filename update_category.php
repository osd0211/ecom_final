<?php
// session_start();
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

$category_id = $_GET['category_id'] ?? '';

if ($category_id === '') {
    die("Invalid category ID.");
}

// Kategori bilgisini veritabanından çek
$category_query = "SELECT * FROM category_table WHERE category_id = '$category_id'";
$category_result = $conn->query($category_query);

if (!$category_result || $category_result->num_rows === 0) {
    die("Category not found.");
}

$category = $category_result->fetch_assoc();

// Form gönderildiğinde güncelleme işlemini yap
if (isset($_POST['categoryupdate'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    $category_detail = $_POST['category_detail'];

    // Güncelleme sorgusu
    $update_query = "UPDATE category_table 
                     SET category_name='$category_name', 
                         category_detail='$category_detail' 
                     WHERE category_id='$category_id'";
    $update_result = $conn->query($update_query);

    if ($update_result) {
        header("Location: category_list.php");
        exit();
    } else {
        echo "Error updating category.";
    }
}
?>

<?php include('header.php'); ?>

<body>
<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">
        <div id="content-header">
            <h1>UPDATE CATEGORY</h1>
        </div> <!-- #content-header -->  
        
        <div class="portlet">
            <div class="portlet-header"></div> <!-- /.portlet-header -->
            <div class="portlet-content">
                <form action="" method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-md-2">Category Name</label>
                        <div class="col-md-10">
                            <input type="text" name="category_name" class="form-control" placeholder="Category Name" value="<?php echo $category['category_name']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2">Category Detail</label>
                        <div class="col-md-10">
                            <input type="text" name="category_detail" class="form-control" placeholder="Category Detail" value="<?php echo $category['category_detail']; ?>" required>
                        </div>
                    </div>
                    
                    <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                    <button type="submit" name="categoryupdate" class="btn btn-warning">Submit</button>
                </form>
            </div> <!-- /.portlet-content -->
        </div> <!-- /.portlet -->
    </div> <!-- #content -->
</div> <!-- #wrapper -->
<?php include('footer.php'); ?>
</body>
</html>
