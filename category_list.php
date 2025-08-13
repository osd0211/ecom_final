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

// Veritabanından verileri alarak tabloya ekle
$sql = "SELECT * FROM category_table";
$result = $conn->query($sql);
?>

<?php include('header.php'); ?>

<body>

<div id="wrapper">

    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">      
        
        <div id="content-header">
            <h1>Category List</h1>
        </div> <!-- #content-header --> 

        <div id="content-container">

            <div class="col-md-12">
                <h4 class="heading">CATEGORY LIST TABLE</h4>
                <table class="table table-bordered table-highlight">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Category Detail</th>
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
                            <td><?php echo $row["category_name"] ?></td>
                            <td><?php echo $row["category_detail"] ?></td>
                            <td>
                                <a href="update_category.php?category_id=<?php echo $row["category_id"] ?>">
                                    <button type="button" name="updatecategory" class="btn btn-info">Update</button>
                                </a>
                                <form method="GET" action="auth.php" style="display: inline;">
                                    <input type="hidden" name="category_id" value="<?php echo $row["category_id"] ?>">
                                    <button type="submit" name="deletecategory" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='4'>No records found</td></tr>";
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
