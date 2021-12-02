<?php 

include "config.php";

if (isset($_POST['now'])) {

	if ($_POST['now'] == 'login') {
		$user = $_POST['username'];
		$pw = $_POST['password'];

		$query = $con->query("SELECT * FROM user");
		$status = false;
		while ($row = mysqli_fetch_assoc($query)) {
			if ($user == $row['username']) {
				if (password_verify($pw, $row['password'])) {
					session_start();
					$_SESSION['user'] = $user;
					$_SESSION['success'] = "Login Berhasil";
					header("Location: ../index.php");
					exit();
				}else {
					$status = true;
				}
			}else {
				$status = true;
			}
		}

		if ($status) {
			$_SESSION['failed'] = "Username/Password Salah!";
			header("Location: ../index.php");
		}	
	}else {
		$user = $_POST['username'];
		$pw = $_POST['password'];
		$cpw = $_POST['confirm_password'];

		$query = $con->query("SELECT * FROM user WHERE username = '$user'");
		$data = mysqli_fetch_assoc($query);

		if ($data == NULL) {
			if ($pw == $cpw) {
				echo "berhasil";
				$rpw = password_hash($pw, PASSWORD_DEFAULT);
				$con->query("INSERT INTO `user`(`id`, `username`, `password`) VALUES (NULL,'$user','$rpw')");
				$_SESSION['success'] = "Register Berhasil, Silahkan Login!";
				header("Location: ../index.php");
				exit();
			}else{
				$_SESSION['failed'] = "Konfirmasi password yang anda masukan tidak sama, Coba masukan kembali!";
				header("Location: ../index.php");
				exit();
			}
		}else {
			$_SESSION['failed'] = "Username yang anda masukan, sudah tersedia, Coba kembali!";
			header("Location: ../index.php");
			exit();
		}
	}
}

if (isset($_GET['pin'])) {
	$pin = $_GET['pin'];
	$name = $_GET['nama'];
	$pin = str_replace('LngLat(', '', $pin);
	$pin = str_replace(')', '', $pin);
	$arr = explode(" ", $pin);
	$lng = str_replace(',', '', $arr[0]);
	$lnt = str_replace(',', '', $arr[1]);
	$user_name = $_SESSION['user'];
	$rs = $con->query("INSERT INTO `pin`(`lng`, `lnt`, `nama`, `user_name`) VALUES ('$lng', '$lnt', '$name', '$user_name')");

	if ($rs) {
		$_SESSION['success'] = "Tambah pin berhasil!";
		header("Location: ../index.php");
	}else {
		$_SESSION['failed'] = "Tambah pin gagal!";
		header("Location: ../index.php");
	}
}

if (isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$rs = $con->query("DELETE FROM `pin`WHERE id = '$id'");

	if ($rs) {
		$_SESSION['success'] = "Hapus pin berhasil!";
		header("Location: ../index.php");
	}else {
		$_SESSION['failed'] = "Tambah pin gagal!";
		header("Location: ../index.php");
	}
}


if (isset($_GET['logout'])) {
	unset($_SESSION['user']);

    $_SESSION['success'] = "Logout berhasil!";
    header("Location: ../index.php");
}

