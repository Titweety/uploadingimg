<?php
require_once 'img.database.php';
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $uploadOk = 1;


    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    } else {
        echo "File upload error.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) { // 500KB limit
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if(!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";

    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;

            $sql = "INSERT INTO images (image_path) VALUES ('$imagePath')";
            if ($conn->query($sql) === TRUE) {
                echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="image" id="image">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>

<?php
?>