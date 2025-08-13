<?php
include("config.php");
include("logged_in_check.php");
?>

<?php include('header.php'); ?>

<body>
<div id="wrapper">
    <?php include('top_bar.php'); ?>
    <?php include('left_sidebar.php'); ?>
    
    <div id="content">
        <div id="content-header">
            <h1>ADD Category</h1>
        </div> <!-- #content-header -->  
        
        <div class="portlet">
            <div class="portlet-header"></div> <!-- /.portlet-header -->
            <div class="portlet-content">
                <form  action="auth.php" method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-md-2">Category Name</label>
                        <div class="col-md-10">
                            <input type="text" name="category_name"  class="form-control" placeholder="Category Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Category Detail</label>
                        <div class="col-md-10">
                            <input type="text" name="category_detail" class="form-control" placeholder="Category Detail">
                        </div>
                    </div>
                   
                    </div>
                    
                   
                        
                    </div>
                    <button type="submit" name="categoryadd" class="btn btn-warning">Submit</button>
                </form>
            </div> <!-- /.portlet-content -->
        </div> <!-- /.portlet -->
    </div> <!-- #content -->
</div> <!-- #wrapper -->
<?php include('footer.php'); ?>
</body>
</html>