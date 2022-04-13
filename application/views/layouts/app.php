<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Jekyll v3.8.5">
		<title><?= isset($title) ? $title : 'didi komputer' ?> - Codeigniter E-Commerce</title>

		<link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/navbar-fixed/">

		
		<!-- <link href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="/assets/libs/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="/assets/css/app.css"> -->
        <link href="<?= base_url('assets/'); ?>libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= base_url('assets/'); ?>css/app.css" rel="stylesheet">
        <link href="<?= base_url('assets/'); ?>libs/fontawesome/css/all.min.css" rel="stylesheet">
	

	</head>
	<body>
      
		<!-- Navbar -->
		<?php $this->load->view('layouts/_navbar'); ?>
		<!-- Endnavbar -->

		<!-- Content -->
		<?php $this->load->view($page); ?>
		<!-- End Content -->
        
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <script src="<?= base_url('assets/'); ?>libs/jquery/jquery-3.4.1.min.js"></script>
        <script src="<?= base_url('assets/'); ?>libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url('assets/'); ?>js/app.js"></script>
	
        
	</body>
</html>