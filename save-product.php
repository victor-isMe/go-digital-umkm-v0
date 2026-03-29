<?php

$targetFolder = "img/";

$fileName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $targetFolder . $fileName;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    $newProduct = [
        "id" => time(),
        "name" => $_POST["name"],
        "category" => $_POST["category"],
        "price" => (int)$_POST["price"],
        "stock" => (int)$_POST["stock"],
        "description" => $_POST["description"],
        "image" => $targetFile
    ];

    $file = "products.json";

    if (file_exists($file)) {
        $products = json_decode(file_get_contents($file), true);
    } else {
        $products = [];
    }

    $products[] = $newProduct;

    file_put_contents($file, json_encode($products, JSON_PRETTY_PRINT));

    echo "Produk berhasil ditambahkan!";
    header("Location: index.html");
} else {
    echo "Upload gambar gagal!";
}
