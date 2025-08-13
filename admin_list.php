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

// Yönetici silme işlemi
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $delete_sql = "DELETE FROM admin_table WHERE admin_id = $delete_id";
  $conn->query($delete_sql);
  header("Location: admin_list.php");
}

// Durum değiştirme işlemi
if (isset($_GET['activate_id'])) {
  $activate_id = $_GET['activate_id'];
  $activate_sql = "UPDATE admin_table SET admin_status = 1 WHERE admin_id = $activate_id";
  $conn->query($activate_sql);
  header("Location: admin_list.php");
}

if (isset($_GET['deactivate_id'])) {
  $deactivate_id = $_GET['deactivate_id'];
  $deactivate_sql = "UPDATE admin_table SET admin_status = 0 WHERE admin_id = $deactivate_id";
  $conn->query($deactivate_sql);
  header("Location: admin_list.php");
}

// Veritabanından verileri alarak tabloya ekle
$sql = "SELECT * FROM admin_table";
$result = $conn->query($sql);

?>

<?php include('header.php'); ?>

<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        
        <div id="content-header">
            <h1>ADMIN LIST</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">

            <div class="col-md-12">
                <h4 class="heading">ADMIN LIST TABLE</h4>
                <table class="table table-bordered table-highlight">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Status</th>
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
                            <td><?php echo $row["admin_name"] ?></td>
                            <td><?php echo $row["admin_surname"] ?></td>
                            <td><?php echo $row["admin_username"] ?></td>
                            <td><?php echo $row["admin_status"] == 1 ? 'Active' : 'Passive'; ?></td>
                            <td>

                                <a href="update_admin.php?admin_id=<?php echo $row["admin_id"] ?>">
                                    <button type="button" name="updateadmin" class="btn btn-info btn-sm">Update</button>
                                </a>



                                <a href="admin_list.php?delete_id=<?php echo $row['admin_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                <?php if ($row['admin_status'] == 0): ?>
                                    <a href="admin_list.php?activate_id=<?php echo $row['admin_id']; ?>" class="btn btn-success btn-sm">Activate</a>
                                <?php else: ?>
                                    <a href="admin_list.php?deactivate_id=<?php echo $row['admin_id']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6'>No records found</td></tr>";
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
