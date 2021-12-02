<?php 

include "php-native/config.php";

if (isset($_SESSION['user'])) {
    $emparray = array();
    $result = $con->query("SELECT * from pin WHERE user_name = '".$_SESSION['user']."' ");
    while($row = mysqli_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<title>YogsMAP</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/normalize.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/template.css" rel="stylesheet">
    <link href="css/hover-min.css" rel="stylesheet">
	<link href='https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css' rel='stylesheet' />
    <link href="bootstrap-checkbox/awesome-bootstrap-checkbox.min.css" rel="stylesheet">
    <link rel="icon" href="image/icon.png" type="image/x-icon">

    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.sticky.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mapbase.js"></script>
    <script type="text/javascript" src="js/accounting.js"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js'></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="" style="margin-left: 1em">
					<img src="image/nav.png" height="36">
				</a>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="" class="hvr-underline-reveal">Home</a></li>
					<li><a  data-toggle="modal" href='#modal-about' class="hvr-underline-reveal">About</a></li>
					<li>
                        <?php if (isset($_SESSION['user'])): ?>
                        <a data-toggle="modal" onclick="return confirm('Yakin?')" href="php-native/logic.php?logout=true" class="hvr-rotate" title="Lgoin">
                            <i class="fa fa-sign-out"></i> Logout
                        </a>
                        <?php else: ?>
                        <a data-toggle="modal" href='#modal-login' class="hvr-rotate" title="Lgoin">
                            <i class="fa fa-sign-in"></i> Login
                        </a>	
                        <?php endif ?>
                    </li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="sidebar">
		<button class="sidebar-btn"><i class="fa fa-angle-left"></i></button>
		<?php if (isset($_SESSION['user'])): ?>
			<button class="btn btn-primary btn-block" onclick="addMarker()"><i class="fa fa-plus"></i> Tambah Marker</button>
			<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-delete" ><i class="fa fa-times"></i> Hapus Marker</button>
			<button class="btn btn-primary btn-block" onclick="showCostum()" ><i class="fa fa-map-marker"></i> Lihat Costum Marker</button>
		<?php endif ?>
		<button class="btn btn-primary btn-block" onclick="showRumahSakit()"><i class="fa fa-building"></i> Rumah Sakit</button>
		<button class="btn btn-primary btn-block" onclick="showKantorPos()"><i class="fa fa-building"></i> Kantor Pos</button>
		<button class="btn btn-primary btn-block" onclick="showPendidikan()"><i class="fa fa-building"></i> Bangunan Pendidikan</button>
		<button class="btn btn-primary btn-block" onclick="reset()"><i class="fa fa-refresh"></i> Reset Marker</button>
	</div>

	<div class="container-fluid top20px">

		<div class="row">
			<div>
				<div id="map-view" style="top: 0; bottom: 0; width: 100%; height: 96vh" ></div>
			</div>
		</div>
	</div>

	<footer class="page-break-top" id="tempat-nih">
		<div class="footer-divider"></div>
		<div class="container">
			<div class="row">
				<div class="clearfix page-break-top"></div>
				<div class="hr5"></div>
				<div class="col-md-12">
					<p class="text-center"><small>Copyright <strong>HMin1</strong> &copy; 2021<a href=""></a>. All Right Reserved</small></p>
				</div>
			</div>
		</div>
	</footer>

	<div class="modal fade" id="modal-login">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Login</h4>
				</div>
				<form method="POST" action="php-native/logic.php">
				<div class="modal-body">
					<input type="hidden" name="now" value="login">
					<div class="form-group">
						<label for="">Username:</label>
						<input type="text" class="form-control" name="username" required>
					</div>
					<div class="form-group">
						<label for="">Password :</label>
						<input type="password" class="form-control" name="password" required>
					</div>
					<p>Belum punya aku? Register <a onclick="register()" style="cursor: pointer;"><b>Di sini</b></a></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Login</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-register">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Register</h4>
				</div>
				<form method="POST" action="php-native/logic.php">
				<div class="modal-body">
					<input type="hidden" name="now" value="register">
					<div class="form-group">
						<label for="">Username:</label>
						<input type="text" class="form-control" name="username" required>
					</div>
					<div class="form-group">
						<label for="">Password :</label>
						<input type="password" class="form-control" name="password" required>
					</div>
					<div class="form-group">
						<label for="">Confirm Password :</label>
						<input type="password" class="form-control" name="confirm_password" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Register</button>
				</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-map" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
					</h4>
				</div>

				<!-- Demografi Kependudukan -->
				<div class="modal-body">
					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
								<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
							</li>
							<li role="presentation">
								<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
							</li>
							<li role="presentation">
								<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
							</li>
							<li role="presentation">
								<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
							</li>
							<li role="presentation">
								<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
							</li>
							<li role="presentation">
								<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
							</li>
							<li role="presentation">
								<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
							</li>
							<li role="presentation">
								<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content" style="margin-top: 1em">
							<div role="tabpanel" class="tab-pane active" id="kependudukan">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-3">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="populasi"></h1>
										<p>Jumlah Penduduk (2016)</p>
									</div>
									<div class="col-md-3">
										<i class="fa fa-fw fa-globe fa-4x"></i>
										<h1 id="luasWilayah"></h1>
										<p>Luas Wilayah (Km<sup>2</sup>)</p>
									</div>
									<div class="col-md-3">
										<i class="fa fa-fw fa-map fa-4x"></i>
										<h1 id="kawasan"></h1>
										<p>Kawasan</p>
									</div>
									<div class="col-md-3">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahKelurahan"></h1>
										<p>Jumlah Kelurahan</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
								<!-- Line Graph Populasi dan Kepdatan Penduduk -->
								<div class="text-center">
									<div id="statistik"></div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="usiaSekolah">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<img src="image/osis_sd.png" height="80px">
										<h1 id="usiaSD"></h1>
										<p>Usia SD (7-12 Tahun)</p>
									</div>
									<div class="col-md-4">
										<img src="image/osis_sma.png" height="80px">
										<h1 id="usiaSMP"></h1>
										<p>Usia SMP (13-15 Tahun)</p>
									</div>
									<div class="col-md-4">
										<img src="image/osis_sma.png" height="80px">
										<h1 id="usiaSMA"></h1>
										<p>Usia SMA (16-18 Tahun)</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="sekolahDasar">
								<!-- Jumlah Sekolah -->
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahSD"></h1>
										<p>Sekolah Dasar</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-user fa-4x"></i>
										<h1 id="jumlahPtkSD"></h1>
										<p>PTK Sekolah Dasar</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="jumlahPdSD"></h1>
										<p>Peserta Didik<br/>Sekolah Dasar</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahSMP"></h1>
										<p>Sekolah Menengah Pertama</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-user fa-4x"></i>
										<h1 id="jumlahPtkSMP"></h1>
										<p>PTK Sekolah Menengah Pertama</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="jumlahPdSMP"></h1>
										<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahSMA"></h1>
										<p>Sekolah Menengah Atas</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-user fa-4x"></i>
										<h1 id="jumlahPtkSMA"></h1>
										<p>PTK Sekolah Menengah Atas</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="jumlahPdSMA"></h1>
										<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahSMK"></h1>
										<p>Sekolah Menengah Kejuruan</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-user fa-4x"></i>
										<h1 id="jumlahPtkSMK"></h1>
										<p>PTK Sekolah Menengah Kejuruan</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="jumlahPdSMK"></h1>
										<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
									</div>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
								<div class="row text-center inner-tab-modal">
									<div class="col-md-4">
										<i class="fa fa-fw fa-home fa-4x"></i>
										<h1 id="jumlahKeaksaraan"></h1>
										<p>Sekolah Keaksaraan</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-user fa-4x"></i>
										<h1 id="jumlahPtkKeaksaraan"></h1>
										<p>PTK Sekolah Keaksaraan</p>
									</div>
									<div class="col-md-4">
										<i class="fa fa-fw fa-users fa-4x"></i>
										<h1 id="jumlahPdKeaksaraan"></h1>
										<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<p class="text-muted text-center">
						Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-about" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Tentang Aplikasi</h4>
				</div>
				<div class="modal-body">
					<p style="text-align: justify;">YogsMAP adalah GIS yang dibangun dengan PHP Native, MapBox API dan Bootsrap. YogsMAP ini menampilkan data area di yogyakarta, serta bisa menampilkan lokasi-lokasi yang diinginkan.</p>
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>Nama Team</td>
								<td width="50" class="text-center">:</td>
								<td>HMin1</td>
							</tr>
							<tr>
								<td>Universitas</td>
								<td width="50" class="text-center">:</td>
								<td><a href="https://uad.ac.id/id/">Universitas UAD</a></td>
							</tr>
							<tr>
								<td>Fakultas</td>
								<td width="50" class="text-center">:</td>
								<td><a href="https://fti.uad.ac.id/">Teknologi Industri</a></td>
							</tr>
							<tr>
								<td>Prodi</td>
								<td width="50" class="text-center">:</td>
								<td><a href="https://tif.uad.ac.id/">Teknik Informatika</a></td>
							</tr>
							<tr>
								<td>Angkatan</td>
								<td width="50" class="text-center">:</td>
								<td>2019</td>
							</tr>
						</tbody>
					</table>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="notifikasi" tabindex="-1" role="dialog" aria-labelledby="notifikasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifikasiLabel">Notifikasi</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <?php if (isset($_SESSION['failed'])): ?>
                        <h5 class="text-danger"><?php echo $_SESSION['failed'] ?></h5>
                    <?php endif ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <h5 class="text-success"><?php echo $_SESSION['success'] ?></h5>
                    <?php endif ?>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-info" tabindex="-1" role="dialog" aria-labelledby="notifikasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifikasiLabel">Notifikasi</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="text-align: center;">
                     <h5 class="text-info">Silahkan klik lokasi yang ingin dibuat marker!</h5>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['user'])): ?>
    <div class="modal fade" id="modal-delete" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Hapus Marker</h4>
				</div>
				<div class="modal-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pin</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $no = 1; 
								$result = $con->query("SELECT * from pin WHERE user_name = '".$_SESSION['user']."' "); ?>
							<?php if (mysqli_num_rows($result) < 1): ?>
                            <tr>
                                <td colspan="3" align="center">No Data</td>
                            </tr>
                            <?php else: ?>   
								<?php while ($row = mysqli_fetch_assoc($result)): ?>
									<tr>
										<td><?php echo $no++ ?></td>
										<td><?php echo $row['nama'] ?></td>
										<td><a class="btn btn-danger" href="php-native/logic.php?delete=<?php echo $row['id'] ?>"><i class="fa fa=trash"></i> Hapus</a></td>
									</tr>
								<?php endwhile ?>
							<?php endif ?>
						</tbody>
					</table>	
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    <?php endif ?>

	<script>
		const sidebar = document.querySelector('.sidebar');
		const btnSdbr = document.querySelector('.fa-angle-left');

		document.querySelector('.sidebar-btn').onclick = function () {
		  sidebar.classList.toggle('sidebar_small');
		  btnSdbr.classList.toggle('fa-angle-right')
		  btnSdbr.classList.toggle('fa-angle-left')
		}
		mapboxgl.accessToken = 'pk.eyJ1IjoicmVkb2syNSIsImEiOiJja3ZxOGxrdjI0NDhpMnVxZnB5cmZvbTdhIn0.BexWaptf8QYfClgq9iSuiQ';
			const map = new mapboxgl.Map({
			container: 'map-view', // container ID
			style: 'mapbox://styles/mapbox/streets-v11', // style URL
			center: [110.36444, -7.80139], // starting position [lng, lat]
			zoom: 12 // starting zoom
		});

		<?php if (isset($_SESSION['user'])): ?>
		let costumMaker = <?php echo json_encode($emparray); ?>	
		<?php endif ?>
		
		let jum = dataKoor['features'].length
		let jum1 = dataRumahSakit['features'].length

		var colors = ['#445C3C', '#FDA77F', '#C9D99E', '#FAE8C8', '#FAE8C8'];

		function register() {
			$('#modal-login').modal('hide'); 
			$('#modal-register').modal('show'); 
		}

		function getRandomInt(min, max) {
		    min = Math.ceil(min);
		    max = Math.floor(max);
		    return Math.floor(Math.random() * (max - min + 1)) + min;
		}

		function dataStatistik(data, kawasan) {
			var chart = Highcharts.chart('statistik', {
				chart: {
					type: 'line',
					style: { fontFamily: 'PT Sans'}
				},
				title: {
					text: 'Statistik Kependudukan di Kecamatan ' + kawasan + ' Tahun 2008 - 2016'
				},
				subtitle: {
					text: 'Sumber: Portal Data Bandung'
				},
				plotOptions: {
					line: {
						dataLabels: {
							enabled: true
						},
						enableMouseTracking: false
					}
				},
				xAxis: {
					categories: ['2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016'],
					title: {
						text: 'Tahun'
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Jumlah Penduduk'
					}
				},
				series: [{
					name: 'Jumlah Populasi',
					data: [
						data.pop_2008,
						data.pop_2009, 
						data.pop_2010, 
						data.pop_2011, 
						data.pop_2012, 
						data.pop_2013, 
						data.pop_2014, 
						data.pop_2015,
						data.pop_2016
					]
				},
				{
					name: 'Jumlah Kepadatan Penduduk (km2)',
					data: [
						data.pdt_2008, 
						data.pdt_2009, 
						data.pdt_2010, 
						data.pdt_2011, 
						data.pdt_2012, 
						data.pdt_2013, 
						data.pdt_2014, 
						data.pdt_2015,
						data.pdt_2016
					]
				},
				{
					name: 'Jumlah Populasi Pria',
					data: [
						data.pop_pria_2008, 
						data.pop_pria_2009, 
						data.pop_pria_2010, 
						data.pop_pria_2011, 
						data.pop_pria_2012, 
						data.pop_pria_2013, 
						data.pop_pria_2014, 
						data.pop_pria_2015,
						data.pop_pria_2016
					],
					visible: false
				},
				{
					name: 'Jumlah Kepadatan Penduduk Pria (km2)',
					data: [
						data.pdt_pria_2008, 
						data.pdt_pria_2009, 
						data.pdt_pria_2010, 
						data.pdt_pria_2011, 
						data.pdt_pria_2012, 
						data.pdt_pria_2013, 
						data.pdt_pria_2014, 
						data.pdt_pria_2015,
						data.pdt_pria_2016
					],
					visible: false
				},
				{
					name: 'Jumlah Populasi Wanita',
					data: [
						data.pop_wanita_2008,
						data.pop_wanita_2009,
						data.pop_wanita_2010,
						data.pop_wanita_2011,
						data.pop_wanita_2012,
						data.pop_wanita_2013,
						data.pop_wanita_2014,
						data.pop_wanita_2015,
						data.pop_wanita_2016
					],
					visible: false
				},
				{
					name: 'Jumlah Kepadatan Penduduk Wanita (km2)',
					data: [
						data.pdt_wanita_2009, 
						data.pdt_wanita_2009, 
						data.pdt_wanita_2010, 
						data.pdt_wanita_2011, 
						data.pdt_wanita_2012, 
						data.pdt_wanita_2013, 
						data.pdt_wanita_2014, 
						data.pdt_wanita_2015,
						data.pdt_wanita_2016
					],
					visible: false
				}]
			});

			return chart;
		}

		map.on('load', () => {
			for(let i = 0; i < jum ; i++){
				// Add a data source containing GeoJSON data.
				map.addSource(dataKoor["features"][i]["properties"]['NAMOBJ']+i, {
					'type': 'geojson',
					'data': {
						'type': 'Feature',
						'geometry': {
							'type': 'Polygon',
							'coordinates': 
							dataKoor["features"][i]["geometry"]["coordinates"]
						}
					}
				});
			 
				// Add a new layer to visualize the polygon.
				map.addLayer({
					'id': dataKoor["features"][i]["properties"]['NAMOBJ']+i,
					'type': 'fill',
					'source': dataKoor["features"][i]["properties"]['NAMOBJ']+i, // reference the data source
					'layout': {},
					'paint': {
						'fill-color': colors[getRandomInt(0,4)], // blue color fill
						'fill-opacity': 0.5
					}
				});

				// Add a black outline around the polygon.
				map.addLayer({
					'id': 'outline'+i,
					'type': 'line',
					'source':  dataKoor["features"][i]["properties"]['NAMOBJ']+i,
					'layout': {},
					'paint': {
					'line-color': '#000',
					'line-width': .5
					}
				});

				map.on('click', dataKoor["features"][i]["properties"]['NAMOBJ']+i, (e) => {
					// $("#modal-map .modal-body").html('<h5 style="line-height:2em">Kecamatan: '+dataKoor["features"][i]["properties"]['NAMOBJ']+' <br>Kelurahan: '+dataKoor["features"][i]["properties"]['WADMKC']+' <br>Kota: Yogyakarta<br>Provinsi: Daerah Istimewa Yogyakarta</h5>');

					$.getJSON('js/data-dummy.json', function (result) {
					var resultLength = result.length;
					var randomInt = getRandomInt(0, 29);
					for (var j = 0 ; j < resultLength; j++) {
						if (j == randomInt) {
							dataStatistik(result[j], dataKoor["features"][i]["properties"]['NAMOBJ']);

							$('#namaKecamatan').text('Demografi Kependudukan & Pendidikan di Kecamatan ' + dataKoor["features"][i]["properties"]['NAMOBJ']);

							$('#luasWilayah').text(result[j].luas_wilayah);
							$('#kawasan').text(dataKoor["features"][i]["properties"]['WADMKC']);
							$('#populasi').text(accounting.formatNumber(result[j].pop_2016, 0, '.'));
							$('#jumlahKelurahan').text(result[j].jumlah_kelurahan);

							$('#jumlahSD').text(accounting.formatNumber(result[j].jml_sd, 0, '.'));
							$('#jumlahSMP').text(accounting.formatNumber(result[j].jml_smp, 0, '.'));
							$('#jumlahSMA').text(accounting.formatNumber(result[j].jml_sma, 0, '.'));
							$('#jumlahSMK').text(accounting.formatNumber(result[j].jml_smk, 0, '.'));
							$('#jumlahKeaksaraan').text(accounting.formatNumber(result[j].jml_keaksaraan, 0, '.'));

							$('#usiaSD').text(accounting.formatNumber(result[j].umur_7_12, 0, '.'));
							$('#usiaSMP').text(accounting.formatNumber(result[j].umur_13_15, 0, '.'));
							$('#usiaSMA').text(accounting.formatNumber(result[j].umur_16_18, 0, '.'));

							$('#jumlahPtkSD').text(accounting.formatNumber(result[j].jml_ptk_sd, 0, '.'));
							$('#jumlahPtkSMP').text(accounting.formatNumber(result[j].jml_ptk_smp, 0, '.'));
							$('#jumlahPtkSMA').text(accounting.formatNumber(result[j].jml_ptk_sma, 0, '.'));
							$('#jumlahPtkSMK').text(accounting.formatNumber(result[j].jml_ptk_smk, 0, '.'));
							$('#jumlahPtkKeaksaraan').text(accounting.formatNumber(result[j].jml_ptk_keaksaraan, 0, '.'));

							$('#jumlahPdSD').text(accounting.formatNumber(result[j].jml_pd_sd, 0, '.'));
							$('#jumlahPdSMP').text(accounting.formatNumber(result[j].jml_pd_smp, 0, '.'));
							$('#jumlahPdSMA').text(accounting.formatNumber(result[j].jml_pd_sma, 0, '.'));
							$('#jumlahPdSMK').text(accounting.formatNumber(result[j].jml_pd_smk, 0, '.'));
							$('#jumlahPdKeaksaraan').text(accounting.formatNumber(result[j].jml_pd_keaksaraan, 0, '.'));
						};
					}
				});

					$('#modal-map').modal('show'); 
				});

				map.on('mouseenter', dataKoor["features"][i]["properties"]['NAMOBJ']+i, function (e) {
					// Change the cursor style as a UI indicator.
					map.getCanvas().style.cursor = 'pointer';
					map.setPaintProperty('outline'+i, 'line-width', 5);
					
				});

				map.on('mouseleave', dataKoor["features"][i]["properties"]['NAMOBJ']+i, () => {
					map.getCanvas().style.cursor = '';
					map.setPaintProperty('outline'+i, 'line-width', .5);
				});
			}
		});

		let rumahSakit = false;
		let kantorPos = false;
		let pendidikan = false;
		let kostum = false;
		let cc;

		function showRumahSakit() {
			if (kantorPos) {$( ".marker" ).remove(); kantorPos = false;}
			if (pendidikan) {$( ".marker" ).remove(); pendidikan = false}
			if (kostum) {$( ".marker" ).remove(); kostum = false}


			if(document.getElementsByClassName('marker').length == 0){
				$('#modal-map').remove();
				for (let i = 0; i < jum1 ; i++) {
					// create a HTML element for each feature
					const el = document.createElement('div');
					el.className = 'marker';
					 
					// make a marker for each feature and add it to the map
					new mapboxgl.Marker(el)
					.setLngLat(dataRumahSakit["features"][i]["geometry"]["coordinates"])
					.setPopup(
					
					new mapboxgl.Popup({offset: 25}) // add popups
						.setHTML(
							`<h5>Rumah Sakit `+(i+1)+`</h5>`
						)
					)
					.addTo(map)
				}
				rumahSakit = true;
			}else {
				$( ".marker" ).remove();
				document.getElementById('tempat-nih').insertAdjacentHTML('afterend', `
				<div class="modal fade" id="modal-map" data-backdrop="static">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
								</h4>
							</div>

							<!-- Demografi Kependudukan -->
							<div class="modal-body">
								<div role="tabpanel">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
										</li>
										<li role="presentation">
											<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
										</li>
										<li role="presentation">
											<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
										</li>
										<li role="presentation">
											<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
										</li>
										<li role="presentation">
											<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content" style="margin-top: 1em">
										<div role="tabpanel" class="tab-pane active" id="kependudukan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-3">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="populasi"></h1>
													<p>Jumlah Penduduk (2016)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-globe fa-4x"></i>
													<h1 id="luasWilayah"></h1>
													<p>Luas Wilayah (Km<sup>2</sup>)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-map fa-4x"></i>
													<h1 id="kawasan"></h1>
													<p>Kawasan</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKelurahan"></h1>
													<p>Jumlah Kelurahan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
											<!-- Line Graph Populasi dan Kepdatan Penduduk -->
											<div class="text-center">
												<div id="statistik"></div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="usiaSekolah">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<img src="image/osis_sd.png" height="80px">
													<h1 id="usiaSD"></h1>
													<p>Usia SD (7-12 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMP"></h1>
													<p>Usia SMP (13-15 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMA"></h1>
													<p>Usia SMA (16-18 Tahun)</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahDasar">
											<!-- Jumlah Sekolah -->
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSD"></h1>
													<p>Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSD"></h1>
													<p>PTK Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSD"></h1>
													<p>Peserta Didik<br/>Sekolah Dasar</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMP"></h1>
													<p>Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMP"></h1>
													<p>PTK Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMP"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMA"></h1>
													<p>Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMA"></h1>
													<p>PTK Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMA"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMK"></h1>
													<p>Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMK"></h1>
													<p>PTK Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMK"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKeaksaraan"></h1>
													<p>Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkKeaksaraan"></h1>
													<p>PTK Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdKeaksaraan"></h1>
													<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<p class="text-muted text-center">
									Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
								</p>
							</div>
						</div>
					</div>
				</div>
				`)
				rumahSakit = false;
			}
		}
		function showKantorPos() {
			if (rumahSakit) {$( ".marker" ).remove(); rumahSakit = false}
			if (pendidikan) {$( ".marker" ).remove(); pendidikan = false}
			if (kostum) {$( ".marker" ).remove(); kostum = false}


			if(document.getElementsByClassName('marker').length == 0){
				$('#modal-map').remove();
				kantorPos = true;
				for (let i = 0; i < jum1 ; i++) {
					// create a HTML element for each feature
					const el = document.createElement('div');
					el.className = 'marker';
					 
					// make a marker for each feature and add it to the map
					new mapboxgl.Marker(el)
					.setLngLat(dataKantorPos["features"][i]["geometry"]["coordinates"])
					.setPopup(
					
					new mapboxgl.Popup({offset: 25}) // add popups
						.setHTML(
							`<h5>Kantor Pos `+(i+1)+`</h5>`
						)
					)
					.addTo(map)
				}
			}else {
				$( ".marker" ).remove();
				document.getElementById('tempat-nih').insertAdjacentHTML('afterend', `
				<div class="modal fade" id="modal-map" data-backdrop="static">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
								</h4>
							</div>

							<!-- Demografi Kependudukan -->
							<div class="modal-body">
								<div role="tabpanel">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
										</li>
										<li role="presentation">
											<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
										</li>
										<li role="presentation">
											<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
										</li>
										<li role="presentation">
											<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
										</li>
										<li role="presentation">
											<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content" style="margin-top: 1em">
										<div role="tabpanel" class="tab-pane active" id="kependudukan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-3">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="populasi"></h1>
													<p>Jumlah Penduduk (2016)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-globe fa-4x"></i>
													<h1 id="luasWilayah"></h1>
													<p>Luas Wilayah (Km<sup>2</sup>)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-map fa-4x"></i>
													<h1 id="kawasan"></h1>
													<p>Kawasan</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKelurahan"></h1>
													<p>Jumlah Kelurahan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
											<!-- Line Graph Populasi dan Kepdatan Penduduk -->
											<div class="text-center">
												<div id="statistik"></div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="usiaSekolah">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<img src="image/osis_sd.png" height="80px">
													<h1 id="usiaSD"></h1>
													<p>Usia SD (7-12 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMP"></h1>
													<p>Usia SMP (13-15 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMA"></h1>
													<p>Usia SMA (16-18 Tahun)</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahDasar">
											<!-- Jumlah Sekolah -->
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSD"></h1>
													<p>Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSD"></h1>
													<p>PTK Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSD"></h1>
													<p>Peserta Didik<br/>Sekolah Dasar</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMP"></h1>
													<p>Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMP"></h1>
													<p>PTK Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMP"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMA"></h1>
													<p>Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMA"></h1>
													<p>PTK Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMA"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMK"></h1>
													<p>Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMK"></h1>
													<p>PTK Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMK"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKeaksaraan"></h1>
													<p>Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkKeaksaraan"></h1>
													<p>PTK Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdKeaksaraan"></h1>
													<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<p class="text-muted text-center">
									Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
								</p>
							</div>
						</div>
					</div>
				</div>
				`)
				kantorPos = false;
			}
		}
		function showPendidikan() {
			if (rumahSakit) {$( ".marker" ).remove(); rumahSakit = false}
			if (kantorPos) {$( ".marker" ).remove(); kantorPos = false}
			if (kostum) {$( ".marker" ).remove(); kostum = false}


			if(document.getElementsByClassName('marker').length == 0){
				$('#modal-map').remove();
				pendidikan = true;
				for (let i = 0; i < jum1 ; i++) {
					// create a HTML element for each feature
					const el = document.createElement('div');
					el.className = 'marker';
					 
					// make a marker for each feature and add it to the map
					new mapboxgl.Marker(el)
					.setLngLat(dataPendidikan["features"][i]["geometry"]["coordinates"])
					.setPopup(
					
					new mapboxgl.Popup({offset: 25}) // add popups
						.setHTML(
							`<h5>Bangunan Pendidikan `+(i+1)+`</h5>`
						)
					)
					.addTo(map)
				}
			}else {
				$( ".marker" ).remove();
				document.getElementById('tempat-nih').insertAdjacentHTML('afterend', `
				<div class="modal fade" id="modal-map" data-backdrop="static">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
								</h4>
							</div>

							<!-- Demografi Kependudukan -->
							<div class="modal-body">
								<div role="tabpanel">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
										</li>
										<li role="presentation">
											<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
										</li>
										<li role="presentation">
											<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
										</li>
										<li role="presentation">
											<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
										</li>
										<li role="presentation">
											<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content" style="margin-top: 1em">
										<div role="tabpanel" class="tab-pane active" id="kependudukan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-3">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="populasi"></h1>
													<p>Jumlah Penduduk (2016)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-globe fa-4x"></i>
													<h1 id="luasWilayah"></h1>
													<p>Luas Wilayah (Km<sup>2</sup>)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-map fa-4x"></i>
													<h1 id="kawasan"></h1>
													<p>Kawasan</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKelurahan"></h1>
													<p>Jumlah Kelurahan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
											<!-- Line Graph Populasi dan Kepdatan Penduduk -->
											<div class="text-center">
												<div id="statistik"></div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="usiaSekolah">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<img src="image/osis_sd.png" height="80px">
													<h1 id="usiaSD"></h1>
													<p>Usia SD (7-12 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMP"></h1>
													<p>Usia SMP (13-15 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMA"></h1>
													<p>Usia SMA (16-18 Tahun)</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahDasar">
											<!-- Jumlah Sekolah -->
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSD"></h1>
													<p>Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSD"></h1>
													<p>PTK Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSD"></h1>
													<p>Peserta Didik<br/>Sekolah Dasar</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMP"></h1>
													<p>Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMP"></h1>
													<p>PTK Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMP"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMA"></h1>
													<p>Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMA"></h1>
													<p>PTK Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMA"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMK"></h1>
													<p>Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMK"></h1>
													<p>PTK Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMK"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKeaksaraan"></h1>
													<p>Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkKeaksaraan"></h1>
													<p>PTK Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdKeaksaraan"></h1>
													<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<p class="text-muted text-center">
									Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
								</p>
							</div>
						</div>
					</div>
				</div>
				`)
				pendidikan = false;
			}
		}
		
		function reset() {
			$( ".marker" ).remove();
			if (rumahSakit || kantorPos || pendidikan || kostum) {
				document.getElementById('tempat-nih').insertAdjacentHTML('afterend', `
				<div class="modal fade" id="modal-map" data-backdrop="static">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title">
									<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
								</h4>
							</div>

							<!-- Demografi Kependudukan -->
							<div class="modal-body">
								<div role="tabpanel">
									<!-- Nav tabs -->
									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
										</li>
										<li role="presentation">
											<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
										</li>
										<li role="presentation">
											<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
										</li>
										<li role="presentation">
											<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
										</li>
										<li role="presentation">
											<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
										</li>
										<li role="presentation">
											<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content" style="margin-top: 1em">
										<div role="tabpanel" class="tab-pane active" id="kependudukan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-3">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="populasi"></h1>
													<p>Jumlah Penduduk (2016)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-globe fa-4x"></i>
													<h1 id="luasWilayah"></h1>
													<p>Luas Wilayah (Km<sup>2</sup>)</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-map fa-4x"></i>
													<h1 id="kawasan"></h1>
													<p>Kawasan</p>
												</div>
												<div class="col-md-3">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKelurahan"></h1>
													<p>Jumlah Kelurahan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
											<!-- Line Graph Populasi dan Kepdatan Penduduk -->
											<div class="text-center">
												<div id="statistik"></div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="usiaSekolah">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<img src="image/osis_sd.png" height="80px">
													<h1 id="usiaSD"></h1>
													<p>Usia SD (7-12 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMP"></h1>
													<p>Usia SMP (13-15 Tahun)</p>
												</div>
												<div class="col-md-4">
													<img src="image/osis_sma.png" height="80px">
													<h1 id="usiaSMA"></h1>
													<p>Usia SMA (16-18 Tahun)</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahDasar">
											<!-- Jumlah Sekolah -->
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSD"></h1>
													<p>Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSD"></h1>
													<p>PTK Sekolah Dasar</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSD"></h1>
													<p>Peserta Didik<br/>Sekolah Dasar</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMP"></h1>
													<p>Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMP"></h1>
													<p>PTK Sekolah Menengah Pertama</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMP"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMA"></h1>
													<p>Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMA"></h1>
													<p>PTK Sekolah Menengah Atas</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMA"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahSMK"></h1>
													<p>Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkSMK"></h1>
													<p>PTK Sekolah Menengah Kejuruan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdSMK"></h1>
													<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
												</div>
											</div>
										</div>
										<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
											<div class="row text-center inner-tab-modal">
												<div class="col-md-4">
													<i class="fa fa-fw fa-home fa-4x"></i>
													<h1 id="jumlahKeaksaraan"></h1>
													<p>Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-user fa-4x"></i>
													<h1 id="jumlahPtkKeaksaraan"></h1>
													<p>PTK Sekolah Keaksaraan</p>
												</div>
												<div class="col-md-4">
													<i class="fa fa-fw fa-users fa-4x"></i>
													<h1 id="jumlahPdKeaksaraan"></h1>
													<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<p class="text-muted text-center">
									Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
								</p>
							</div>
						</div>
					</div>
				</div>
				`)
			}
			rumahSakit = false;
			kantorPos = false;
			pendidikan = false;
			kostum = false;
		}

		<?php if (isset($_SESSION['user'])): ?>
		function showCostum() {
			if (costumMaker['length'] == 0) {
				$("#modal-info .modal-body").html('<h5 class="text-danger">Costum maker tidak ada!</h5>');
            	$("#modal-info").modal('show');
			}else{
				if (rumahSakit) {$( ".marker" ).remove(); rumahSakit = false}
				if (kantorPos) {$( ".marker" ).remove(); kantorPos = false}
				if (pendidikan) {$( ".marker" ).remove(); pendidikan = false}

				if(document.getElementsByClassName('marker').length == 0){
					$('#modal-map').remove();
					kostum = true;
					for (let i = 0; i < costumMaker['length'] ; i++) {
						let koor = [costumMaker[i]['lng'], costumMaker[i]['lnt']]
						// create a HTML element for each feature
						const el = document.createElement('div');
						el.className = 'marker';
						 
						// make a marker for each feature and add it to the map
						new mapboxgl.Marker(el)
						.setLngLat(koor)
						.setPopup(
						
						new mapboxgl.Popup({offset: 25}) // add popups
							.setHTML(
								`<h5>`+costumMaker[i]['nama']+`</h5>`
							)
						)
						.addTo(map)
					}
				}else {
					$( ".marker" ).remove();
					document.getElementById('tempat-nih').insertAdjacentHTML('afterend', `
					<div class="modal fade" id="modal-map" data-backdrop="static">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">
										<i class="fa fa-fw fa-bar-chart"> </i> <span id="namaKecamatan"></span>
									</h4>
								</div>

								<!-- Demografi Kependudukan -->
								<div class="modal-body">
									<div role="tabpanel">
										<!-- Nav tabs -->
										<ul class="nav nav-tabs" role="tablist">
											<li role="presentation" class="active">
												<a href="#kependudukan" aria-controls="kependudukan" role="tab" data-toggle="tab">Kependudukan</a>
											</li>
											<li role="presentation">
												<a href="#statistikPenduduk" aria-controls="statistikPenduduk" role="tab" data-toggle="tab">Statistik Penduduk</a>
											</li>
											<li role="presentation">
												<a href="#usiaSekolah" aria-controls="tab" role="tab" data-toggle="tab">Usia Sekolah</a>
											</li>
											<li role="presentation">
												<a href="#sekolahDasar" aria-controls="tab" role="tab" data-toggle="tab">SD</a>
											</li>
											<li role="presentation">
												<a href="#sekolahMenengahPertama" aria-controls="tab" role="tab" data-toggle="tab">SMP</a>
											</li>
											<li role="presentation">
												<a href="#sekolahMenengahAtas" aria-controls="tab" role="tab" data-toggle="tab">SMA</a>
											</li>
											<li role="presentation">
												<a href="#sekolahMenengahKejuruan" aria-controls="tab" role="tab" data-toggle="tab">SMK</a>
											</li>
											<li role="presentation">
												<a href="#sekolahKeaksaraan" aria-controls="tab" role="tab" data-toggle="tab">Sekolah Keaksaraan</a>
											</li>
										</ul>

										<!-- Tab panes -->
										<div class="tab-content" style="margin-top: 1em">
											<div role="tabpanel" class="tab-pane active" id="kependudukan">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-3">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="populasi"></h1>
														<p>Jumlah Penduduk (2016)</p>
													</div>
													<div class="col-md-3">
														<i class="fa fa-fw fa-globe fa-4x"></i>
														<h1 id="luasWilayah"></h1>
														<p>Luas Wilayah (Km<sup>2</sup>)</p>
													</div>
													<div class="col-md-3">
														<i class="fa fa-fw fa-map fa-4x"></i>
														<h1 id="kawasan"></h1>
														<p>Kawasan</p>
													</div>
													<div class="col-md-3">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahKelurahan"></h1>
														<p>Jumlah Kelurahan</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="statistikPenduduk">
												<!-- Line Graph Populasi dan Kepdatan Penduduk -->
												<div class="text-center">
													<div id="statistik"></div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="usiaSekolah">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<img src="image/osis_sd.png" height="80px">
														<h1 id="usiaSD"></h1>
														<p>Usia SD (7-12 Tahun)</p>
													</div>
													<div class="col-md-4">
														<img src="image/osis_sma.png" height="80px">
														<h1 id="usiaSMP"></h1>
														<p>Usia SMP (13-15 Tahun)</p>
													</div>
													<div class="col-md-4">
														<img src="image/osis_sma.png" height="80px">
														<h1 id="usiaSMA"></h1>
														<p>Usia SMA (16-18 Tahun)</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="sekolahDasar">
												<!-- Jumlah Sekolah -->
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahSD"></h1>
														<p>Sekolah Dasar</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-user fa-4x"></i>
														<h1 id="jumlahPtkSD"></h1>
														<p>PTK Sekolah Dasar</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="jumlahPdSD"></h1>
														<p>Peserta Didik<br/>Sekolah Dasar</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="sekolahMenengahPertama">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahSMP"></h1>
														<p>Sekolah Menengah Pertama</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-user fa-4x"></i>
														<h1 id="jumlahPtkSMP"></h1>
														<p>PTK Sekolah Menengah Pertama</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="jumlahPdSMP"></h1>
														<p>Peserta Didik<br/>Sekolah Menengah Pertama</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="sekolahMenengahAtas">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahSMA"></h1>
														<p>Sekolah Menengah Atas</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-user fa-4x"></i>
														<h1 id="jumlahPtkSMA"></h1>
														<p>PTK Sekolah Menengah Atas</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="jumlahPdSMA"></h1>
														<p>Peserta Didik<br/>Sekolah Menengah Atas</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="sekolahMenengahKejuruan">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahSMK"></h1>
														<p>Sekolah Menengah Kejuruan</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-user fa-4x"></i>
														<h1 id="jumlahPtkSMK"></h1>
														<p>PTK Sekolah Menengah Kejuruan</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="jumlahPdSMK"></h1>
														<p>Peserta Didik<br/>Sekolah Menengah Kejuruan</p>
													</div>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="sekolahKeaksaraan">
												<div class="row text-center inner-tab-modal">
													<div class="col-md-4">
														<i class="fa fa-fw fa-home fa-4x"></i>
														<h1 id="jumlahKeaksaraan"></h1>
														<p>Sekolah Keaksaraan</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-user fa-4x"></i>
														<h1 id="jumlahPtkKeaksaraan"></h1>
														<p>PTK Sekolah Keaksaraan</p>
													</div>
													<div class="col-md-4">
														<i class="fa fa-fw fa-users fa-4x"></i>
														<h1 id="jumlahPdKeaksaraan"></h1>
														<p>Peserta Didik<br/>Sekolah Keaksaraan</p>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<p class="text-muted text-center">
										Catatan: Data di atas bukan data sebenarnya (<b>data dummy</b>) dari kawasan tersebut, data di atas digunakan hanya untuk tujuan desain.
									</p>
								</div>
							</div>
						</div>
					</div>
					`)
					kostum = false;
				}
			}
		}
		function addMarker() {
			$('#modal-map').remove();
			$("#modal-info .modal-body").html('<h5 class="text-info">Silahkan klik lokasi yang ingin dibuat marker!</h5>');
			$('#modal-info').modal('show');
			if(document.getElementsByClassName('marker').length == 0){
				map.on('click', (e) => {
				    var coords = `lat: ${e.lngLat.lat} <br> lng: ${e.lngLat.lng}`;

				    // create the popup
				    var popup = new mapboxgl.Popup().setText(coords);

				    // create DOM element for the marker
				    var el = document.createElement('div');
				    el.id = 'marker';

				    cc = e.lngLat;

				    // create the marker
				    new mapboxgl.Marker(el)
				        .setLngLat(e.lngLat)
				        .setPopup(popup)
				        .addTo(map);

					var namePin = prompt("Masukan Nama Lokasi Marker");
					while(!namePin){
						var namePin = prompt("Masukan Nama Lokasi Marker");
					};

					window.location = "php-native/logic.php?pin="+cc+"&nama="+namePin;   
			  });
			}	
		}
		<?php endif ?>


    <?php if (isset($_SESSION['failed']) OR isset($_SESSION['success'])): ?>
        $(document).ready(function(){
            $("#notifikasi").modal('show');
        });
        <?php 
            if (isset($_SESSION['failed'])) {
                unset($_SESSION['failed']); 
            }
            if (isset($_SESSION['success'])) {
                unset($_SESSION['success']);
            }
        ?>
    <?php endif ?>	

	</script>
</body>
</html>