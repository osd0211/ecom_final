<?php

include("config.php");
// include("logged_in_check.php");

// Kullanıcı oturumu kontrolü
$username = $_POST['form_username'] ?? '';
$password = $_POST['form_password'] ?? '';

if (!empty($username) && !empty($password)) {
    $sql = "SELECT * FROM admin_table WHERE admin_username = '$username' AND admin_pass = '$password'";
    $result = berkhoca_query_parser($sql);

    if (!empty($result) && count($result) > 0) {
        $_SESSION['admin_id'] = $result[0]['admin_id'];
        $_SESSION['admin_username'] = $result[0]['admin_username'];

        // Yönlendirme: Başarılı giriş
        header("Location: dashboard.php");
        exit;
    } else {
        // Yönlendirme: Başarısız giriş
        session_destroy();
        header("Location: logout.php?warning=1");
        exit;
    }
}

// Admin ekleme işlemi
if (isset($_POST["adminadd"])) {
    $admin_name = $_POST["admin_name"];
    $admin_surname = $_POST["admin_surname"];
    $admin_username = $_POST["admin_username"];
    $admin_pass = $_POST["admin_pass"];

    if (!empty($admin_name) && !empty($admin_surname) && !empty($admin_username) && !empty($admin_pass)) {
        $sql = "INSERT INTO admin_table (admin_name, admin_surname, admin_username, admin_pass, admin_status) VALUES ('$admin_name', '$admin_surname', '$admin_username', '$admin_pass', 1)";
        $conn = new mysqli("localhost", "root", "", "berkhoca_db");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            // Yönlendirme: Başarılı ekleme
            header("Location: dashboard.php?success=1");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}

if (isset($_POST["addproduct"])) {
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $product_stock = $_POST["product_stock"];
    $product_detail = $_POST["product_detail"];
    $product_brand = $_POST["product_brand"];
    $category_id = $_POST["category_id"];

    // Resim dosyasını işle
    $target_dir = "product_images/"; // Resimlerin kaydedileceği klasör
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]); // Dosya yolu
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Resim dosyasını kontrol et
    if(isset($_POST["addproduct"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check !== false) {
            echo "Dosya bir resim - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "Dosya bir resim değil.";
            $uploadOk = 0;
        }
    }

    // Dosya zaten varsa kontrol et
    if (file_exists($target_file)) {
        echo "Üzgünüz, dosya zaten mevcut.";
        $uploadOk = 0;
    }

    // Dosya boyutunu kontrol et
    if ($_FILES["product_image"]["size"] > 500000) {
        echo "Üzgünüz, dosya çok büyük.";
        $uploadOk = 0;
    }

    // İzin verilen dosya türlerini kontrol et
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Üzgünüz, sadece JPG, JPEG, PNG ve GIF dosyaları izin verilir.";
        $uploadOk = 0;
    }

    // Hata varsa yüklemeyi iptal et
    if ($uploadOk == 0) {
        echo "Üzgünüz, dosyanız yüklenemedi.";
    } else { // Her şey yolundaysa dosyayı yükle
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            echo "Dosya başarıyla yüklendi.";

            // Dosya adını veritabanına kaydet
            $file_name = basename($_FILES["product_image"]["name"]);

            // Veritabanına ekleme işlemi
            $sql = "INSERT INTO product_table (product_name, product_price, product_stock, product_detail, product_brand, category_id, product_image) VALUES ('$product_name', '$product_price', '$product_stock', '$product_detail', '$product_brand', '$category_id', '$file_name')";
            $conn = new mysqli("localhost", "root", "", "berkhoca_db");

            if ($conn->connect_error) {
                die("Bağlantı hatası: " . $conn->connect_error);
            }

            if ($conn->query($sql) === TRUE) {
                // Yönlendirme: Başarılı ekleme
                header("Location: dashboard.php?success=1");
                exit;
            } else {
                echo "Hata: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        } else {
            echo "Üzgünüz, dosya yüklenirken bir hata oluştu.";
        }
    }
}
if (isset($_POST["categoryadd"])) {
    $category_name = $_POST["category_name"];
    $category_detail = $_POST["category_detail"];
   

    if (!empty($category_name) && !empty($category_detail)) {
        $sql = "INSERT INTO category_table (category_name, category_detail) VALUES ('$category_name', '$category_detail')";
        $conn = new mysqli("localhost", "root", "", "berkhoca_db");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            // Yönlendirme: Başarılı ekleme
            header("Location: dashboard.php?success=1");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}


if (isset($_GET["deleteproduct"])) {
    $product_id = $_GET["product_id"];

    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "berkhoca_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "DELETE FROM product_table WHERE product_id = '$product_id'";
    if ($conn->query($sql) === TRUE) {
       
        header("Location: product_list.php?success=1");
        exit;
    } else {
        header("Location: dashboard.php?lose=2");
    }

    $conn->close();
}

// Ürün güncelleme işlemi
if (isset($_POST["productupdate"])) {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $product_stock = $_POST["product_stock"];
    $product_detail = $_POST["product_detail"];
    $product_brand = $_POST["product_brand"];
    $category_id = $_POST["category_id"]; 

    if (!empty($product_id) && !empty($product_name) && !empty($product_price) && !empty($product_stock) && !empty($product_detail) && !empty($product_brand) && !empty($category_id)) {
        $sql = "UPDATE product_table SET product_name='$product_name', product_price='$product_price', product_stock='$product_stock', product_detail='$product_detail', product_brand='$product_brand', category_id='$category_id' WHERE product_id='$product_id'";
        $conn = new mysqli("localhost", "root", "", "berkhoca_db");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($conn->query($sql) === TRUE) {
            // Yönlendirme: Başarılı güncelleme
            header("Location: product_list.php?success=1");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Error occured Empty";
    }
}

if (isset($_GET["deletecategory"])) {
    $category_id = $_GET["category_id"];

    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "berkhoca_db";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "DELETE FROM category_table WHERE category_id = '$category_id'";
    if ($conn->query($sql) === TRUE) {
       
        header("Location: category_list.php?success=1");
        exit;
    } else {
        header("Location: dashboard.php?lose=2");
    }

    $conn->close();
}

// Resim silme işlemi
if (isset($_POST['deleteimage']) && $_POST['deleteimage'] == 1) {
    $old_image_path = "product_images/" . $product['product_image'];
    if (file_exists($old_image_path)) {
        unlink($old_image_path); // Eski resmi sil
    }
    $new_image_name = ''; // Resim alanını boşalt
}


?>

