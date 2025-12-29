<?php
require 'conn.php';
session_start();

// Cek login via QR lebih dulu, sebelum HTML dikirim
if (isset($_GET['uid']) && isset($_GET['pwd'])) {
  $username = $_GET['uid'];
  $password = $_GET['pwd'];
  loginUser($username, $password, $conn);
}

// Cek sesi, redirect kalau udah login
if (isset($_SESSION['role'])) {
  if ($_SESSION['role'] == 'Admin') {
    header('Location: page/admin/index.php');
  } elseif ($_SESSION['role'] == 'Injection') {
    header('Location: page/inj/index.php');
  } elseif ($_SESSION['role'] == 'Assy') {
    header('Location: page/assy/index.php');
  } else {
    header('Location: index.php');
  }
}

// Fungsi login
function loginUser($username, $password, $conn)
{
  $query = "SELECT * FROM user WHERE username = '$username'";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);

  if ($data && $password == $data['password']) {
    $_SESSION['role'] = $data['role'];
    $_SESSION['username'] = $data['username'];
    if ($data['role'] == 'Admin') {
      header('Location: page/admin/index.php');
    } elseif ($data['role'] == 'Injection') {
      header('Location: page/inj/index.php');
    } elseif ($data['role'] == 'Assy') {
      header('Location: page/assy/index.php');
    } else {
      header('Location: index.php');
    }
  } else {
    echo "<script>alert('Login gagal! Username atau password salah.')</script>";
  }
}

// Login via form
if (isset($_POST['btn_login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  loginUser($username, $password, $conn);
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
  <script src="js/color-modes.js"></script>
  <title>AC System</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/sign-in.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <!-- Import Themes -->
  <?php include 'library/themes.php'; ?>

  <!-- Main Content -->
  <main class="form-signin w-100 m-auto">
    <form action="" method="POST" autocomplete="off">
      <h1 class="h3 mb-3 text-center">Production INJ</h1>
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingUsername" placeholder="Username" name="username" required autofocus autocomplete="off">
        <label for="floatingUsername">Username</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
        <label for="floatingPassword">Password</label>
      </div>
      <button class="btn btn-primary w-100 py-2" type="submit" name="btn_login">Sign in</button>
    </form>
  </main>
  <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>