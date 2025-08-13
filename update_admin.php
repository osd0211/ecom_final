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

$admin_id = $_GET['admin_id'] ?? '';

if ($admin_id === '') {
    die("Invalid admin ID.");
}

// Kategori bilgisini veritabanından çek
$admin_query = "SELECT * FROM admin_table WHERE admin_id = '$admin_id'";
$admin_result = $conn->query($admin_query);

if (!$admin_result || $admin_result->num_rows === 0) {
    die("Admin not found.");
}

$admin = $admin_result->fetch_assoc();

// Form gönderildiğinde güncelleme işlemini yap
if (isset($_POST['adminupdate'])) {
    $admin_id = $_POST['admin_id'];
    $admin_name = $_POST['admin_name'];
    $admin_surname = $_POST['admin_surname'];
    $admin_username = $_POST['admin_username'];
    $admin_pass = $_POST['admin_pass'];

    // Güncelleme sorgusu
    $update_query = "UPDATE admin_table 
                     SET admin_name='$admin_name', 
                         admin_surname='$admin_surname',
                         admin_username='$admin_username',
                         admin_pass='$admin_pass'
                     WHERE admin_id='$admin_id'";
    $update_result = $conn->query($update_query);

    if ($update_result) {
        header("Location: admin_list.php");
        exit();
    } else {
        echo "Error updating admin.";
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
            <h1>UPDATE ADMIN</h1>
        </div> <!-- #content-header -->  
        
        <div class="portlet">
            <div class="portlet-header"></div> <!-- /.portlet-header -->
            <div class="portlet-content">
                <form action="" method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-md-2">Admin Name</label>
                        <div class="col-md-10">
                            <input type="text" name="admin_name" class="form-control" placeholder="Admin Name" value="<?php echo $admin['admin_name']; ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2">Admin Surname</label>
                        <div class="col-md-10">
                            <input type="text" name="admin_surname" class="form-control" placeholder="Admin Surname" value="<?php echo $admin['admin_surname']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">Admin Username</label>
                        <div class="col-md-10">
                            <input type="text" name="admin_username" class="form-control" placeholder="Admin Username" value="<?php echo $admin['admin_username']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-2">Admin Password</label>
                        <div class="col-md-10">
                            <input type="text" name="admin_pass" class="form-control" placeholder="Admin Password" value="<?php echo $admin['admin_pass']; ?>" required>
                        </div>
                    </div>
                    
                    <input type="hidden" name="admin_id" value="<?php echo $admin['admin_id']; ?>">
                    <button type="submit" name="adminupdate" class="btn btn-warning">Submit</button>
                </form>
            </div> <!-- /.portlet-content -->
        </div> <!-- /.portlet -->
    </div> <!-- #content -->
</div> <!-- #wrapper -->
<?php include('footer.php'); ?>
</body>
</html>
