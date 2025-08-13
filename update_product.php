<?php
include("config.php");
include("logged_in_check.php");

$conn = new mysqli("localhost", "root", "", "berkhoca_db");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$product_id = $_GET['product_id'] ?? '';

if ($product_id === '') {
    die("Geçersiz ürün ID'si.");
}

// Ürün bilgisini ve kategorisini INNER JOIN ile çek
$product_query = "SELECT product_table.*, category_table.category_name 
                  FROM product_table 
                  INNER JOIN category_table 
                  ON product_table.category_id = category_table.category_id 
                  WHERE product_table.product_id = '$product_id'";
$product_result = $conn->query($product_query);

if (!$product_result || $product_result->num_rows === 0) {
    die("Ürün bulunamadı.");
}

$product = $product_result->fetch_assoc();

// Kategorileri veritabanından çek
$category_query = "SELECT * FROM category_table";
$category_result = $conn->query($category_query);

// Form gönderildiğinde güncelleme işlemini yap
if (isset($_POST['productupdate'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_stock = $_POST['product_stock'];
    $product_detail = $_POST['product_detail'];
    $product_brand = $_POST['product_brand'];
    $category_id = $_POST['category_id'];
    $old_image_name = $product['product_image']; // Eski resmin adını sakla

    // Kategori seçilmedi ise hata mesajı göster
    if ($category_id == '') {
        die("Lütfen bir kategori seçin.");
    }

    // Yeni resim yüklendi mi kontrol et
    if ($_FILES['product_image']['size'] > 0) {
        // Eski resmi silme işlemi
        $old_image_path = "product_images/" . $old_image_name; // Eski resmin yolu
        if (file_exists($old_image_path)) {
            unlink($old_image_path); // Eski resmi sil
        }

        // Yeni resmi yükleme işlemi
        $new_image_name = $_FILES['product_image']['name'];
        $new_image_tmp = $_FILES['product_image']['tmp_name'];
        $new_image_path = "product_images/" . $new_image_name;
        move_uploaded_file($new_image_tmp, $new_image_path);
    } else {
        $new_image_name = $old_image_name; // Yeni resim yüklenmediğinde eski resmin adını kullan
    }

    // Eğer resmi silme işareti varsa, eski resmi sil
    if (isset($_POST['delete_image_checkbox']) && $_POST['delete_image_checkbox'] == 1) {
        $old_image_path = "product_images/" . $old_image_name; // Eski resmin yolu
        if (file_exists($old_image_path)) {
            unlink($old_image_path); // Eski resmi sil
            $new_image_name = ''; // Resim adını boşalt
        }
    }

    // Güncelleme sorgusu
    $update_query = "UPDATE product_table 
                     SET product_name='$product_name', 
                         product_price='$product_price', 
                         product_stock='$product_stock', 
                         product_detail='$product_detail', 
                         product_brand='$product_brand',
                         category_id='$category_id', 
                         product_image='$new_image_name' 
                     WHERE product_id='$product_id'";
    $update_result = $conn->query($update_query);

    if ($update_result) {
        header("Location: product_list.php");
        exit();
    } else {
        echo "Ürün güncelleme hatası.";
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
            <h1>UPDATE PRODUCT</h1>
        </div> <!-- #content-header -->  
        
        <div class="portlet">
            <div class="portlet-header"></div> <!-- /.portlet-header -->
            <div class="portlet-content">
                <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-md-2">Product Name</label>
                        <div class="col-md-10">
                            <input type="text" name="product_name" class="form-control" placeholder="Product Name" value="<?php echo $product['product_name']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2">Category</label>
                        <div class="col-md-10">
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php
                                if ($category_result->num_rows > 0) {
                                    while ($row = $category_result->fetch_assoc()) {
                                        $selected = ($row['category_id'] == $product['category_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['category_id'] . "' $selected>" . $row['category_name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Product Price</label>
                        <div class="col-md-10">
                            <input type="text" name="product_price" class="form-control" placeholder="Product Price" value="<?php echo $product['product_price']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2">Product Stock</label>
                        <div class="col-md-10">
                            <input type="text" name="product_stock" class="form-control" placeholder="Product Stock" value="<?php echo $product['product_stock']; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-2">Product Detail</label>
                        <div class="col-md-10">
                            <input type="text" name="product_detail" class="form-control" placeholder="Product Detail" value="<?php echo $product['product_detail']; ?>">
                        </div>
                        </div>
                    <div class="form-group">
                        <label class="col-md-2">Product Brand</label>
                        <div class="col-md-10">
                            <input type="text" name="product_brand" class="form-control" placeholder="Product Brand" value="<?php echo $product['product_brand']; ?>">
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-2">Product Image</label>
                        <div class="col-md-10">
                            <input type="file" name="product_image" class="form-control">
                            <br>
                            <?php if (!empty($product['product_image'])) : ?>
                                  <!-- <img src="product_images/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>" style="max-width: 200px;">  -->
                                <br>
                                <label>
                                    <input type="checkbox" name="delete_image_checkbox" value="1"> Delete Image
                                </label>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-10">
                            <button type="submit" name="productupdate" class="btn btn-primary">Update Product</button>
                        </div>
                    </div>
                </form>
            </div> <!-- /.portlet-content -->
        </div> <!-- /.portlet -->
    </div> <!-- #content -->
</div> <!-- #wrapper -->

<?php include('footer.php'); ?>
</body>
</html>
