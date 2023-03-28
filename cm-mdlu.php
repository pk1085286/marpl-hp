// This code defines some common modules for a marketplace website, including user registration, user login, product listing, product detail, product search, and product


<?php

// Define database connection details
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "marketplace";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Module: User Registration
function register_user($username, $password, $email) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Module: User Login
function login_user($username, $password) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Module: Product Listing
function list_products() {
    global $conn;
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    } else {
        return false;
    }
}

// Module: Product Detail
function get_product($id) {
    global $conn;
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        return $product;
    } else {
        return false;
    }
}

// Module: Product Search
function search_products($keywords) {
    global $conn;
    $sql = "SELECT * FROM products WHERE name LIKE '%$keywords%' OR description LIKE '%$keywords%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    } else {
        return false;
    }
}

// Module: Product Purchase
function purchase_product($product_id, $quantity, $shipping_address) {
    global $conn;
    $user_id = $_SESSION["user_id"];
    $product = get_product($product_id);
    $price = $product["price"];
    $total_price = $price * $quantity;
    $sql = "INSERT INTO orders (user_id, product_id, quantity, price, total_price, shipping_address) VALUES ('$user_id', '$product_id', '$quantity', '$price', '$total_price', '$shipping_address')";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

?>
