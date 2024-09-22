<!DOCTYPE html>
<html>

<head>
	<title>Zorella</title>
</head>

<body>

	<?php
	include "Include.php";

	$options = ['cost' => 12,];


	$paymentinfo = "CREATE TABLE paymentinfo(
		paymentinfoid int(100) AUTO_INCREMENT PRIMARY KEY,
		payment_id int(100) NOT NULL,
		total_price DECIMAL(20, 2) NOT NULL,
		downpayment DECIMAL(20, 2) NOT NULL,
		price_to_pay DECIMAL(20, 2) NOT NULL,
		remaining_balance DECIMAL(20, 2) NOT NULL,
		pax_price DECIMAL(20, 2) NOT NULL,
		installment_number int(10) NOT NULL,
		due_date TEXT,
		paid_date TIMESTAMP NULL,
		terms TEXT,
		confirmation_pdf varchar(150) NULL,
		payment_pdf varchar(150) NULL,
		adminid int(200) NOT NULL,
		userid int(200) NOT NULL
		)";

	if (mysqli_query($conn, $paymentinfo)) {
		echo "Table paymentinfo";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$tourbookings = "CREATE TABLE tourbooking(
		tour_bookid int(100) AUTO_INCREMENT PRIMARY KEY,
		tourid int(100) NOT NULL,
		pax int(20) NOT NULL,
		tour_title varchar(50) NOT NULL,
		tour_date DATE NOT NULL,
		userid int(200) NOT NULL	
		)";

	if (mysqli_query($conn, $tourbookings)) {
		echo "Table tourbookings";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$tourpackage = "CREATE TABLE tourpackage(
		tourid int(100) AUTO_INCREMENT PRIMARY KEY,
		title varchar(50) NULL,
		price varchar(100) NOT NULL,
		description varchar(255) NOT NULL,
		duration  varchar(50) NOT NULL,
		dates TEXT NOT NULL,
		img varchar(100) NOT NULL,
		itinerary TEXT,
		type varchar(25) NOT NULL,
		adminid int(200) NOT NULL
		)";

	if (mysqli_query($conn, $tourpackage)) {
		echo "Table tourpackage";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$payment = "CREATE TABLE payment(
		paymentid int(100) AUTO_INCREMENT PRIMARY KEY,
		payment_type varchar(50) NOT NULL,
		payment_method varchar(50) NOT NULL,
		installment_type varchar(50) NULL,
		booking_type varchar(50) NOT NULL,
		booking_id INT(100) NOT NULL,
		branch varchar(20) NOT NULL,
		UNIQUE (booking_type, booking_id),
		userid int(200) NOT NULL
		)";

	if (mysqli_query($conn, $payment)) {
		echo "Table payment";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$leadpersoninfo = "CREATE TABLE leadpersoninfo(
		leadid int(100) AUTO_INCREMENT PRIMARY KEY,
		number varchar(20) NOT NULL,
		email varchar(30) NOT NULL,
		lastname varchar(30) NOT NULL,
		firstname varchar(30) NOT NULL,
		middlename varchar(30) NULL,
		position varchar(30) NULL,
		pickup varchar(50) NULL,
		booking_id int(100) NOT NULL,
		userid int(200) NOT NULL
		)";

	if (mysqli_query($conn, $leadpersoninfo)) {
		echo "Table leadpersoninfo";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$booking = "CREATE TABLE booking(
		bookid int(100) AUTO_INCREMENT PRIMARY KEY,
		booking_type varchar(50) NOT NULL,
		booking_id INT(100) NOT NULL,
		status varchar(50) NOT NULL,
		branch varchar(50) NOT NULL,
		UNIQUE (booking_type, booking_id),
		userid int(200) NOT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)";

	if (mysqli_query($conn, $booking)) {
		echo "Table booking";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}



	$hotel = "CREATE TABLE hotel(
		hotelid int(100) AUTO_INCREMENT PRIMARY KEY,
		hotel varchar(50) NOT NULL,
		attraction varchar(100) NOT NULL,
		ticketid int(200) NOT NULL,
		userid int(200) NOT NULL
		)";

	if (mysqli_query($conn, $hotel)) {
		echo "Table hotel";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$educational = "CREATE TABLE educational(
		educationalid int(100) AUTO_INCREMENT PRIMARY KEY,
		hotel varchar(50) NULL,
		attraction varchar(100) NOT NULL,
		pax int(30) NOT NULL,
		sdate date NOT NULL,
		edate date NOT NULL,
		status varchar(50) NOT NULL,
		userid int(200) NOT NULL,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)";

	if (mysqli_query($conn, $educational)) {
		echo "Table educational";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$personalinfo = "CREATE TABLE personalinfo(
		personalinfoid int(100) AUTO_INCREMENT PRIMARY KEY,
		number varchar(20) NOT NULL,
		email varchar(20) NOT NULL,
		lastname varchar(30) NOT NULL,
		firstname varchar(30) NOT NULL,
		middlename varchar(30) NULL,
		age varchar(10) NOT NULL,
		bdate date NOT NULL,
		sex varchar(10) NOT NULL,
		address varchar(100) NOT NULL,
		civil varchar(15) NOT NULL,
		passport varchar(70)  NOT NULL,
		validid varchar(70)  NOT NULL,
		ticketid int(100)  NOT NULL,
		booking_type varchar(50)  NOT NULL,
		userid varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $personalinfo)) {
		echo "Table Information";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$ticket = "CREATE TABLE ticket(
		ticketid int(100) AUTO_INCREMENT PRIMARY KEY,
		branch varchar(20) NOT NULL,
		flighttype varchar(20) NOT NULL,
		origin varchar(30) NOT NULL,
		destination varchar(30) NOT NULL,
		classtype varchar(30) NOT NULL,
		departure date NOT NULL,
		arrival date NOT NULL,
		directflight varchar(10) NOT NULL,
		adult int(10) NOT NULL,
		child int(10)  NULL,
		infant int(10)  NULL,
		senior int(10)  NULL,
		airline varchar(30) NOT NULL,
		status varchar(30) NOT NULL,
		dateCreated TIMESTAMP NOT NULL,
		tickettype varchar(30) NOT NULL,
		ticket varchar(30) NULL,
		userid varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $ticket)) {
		echo "Table ticket";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}


	$quotation = "CREATE TABLE quotation(
		quotationid int(100) AUTO_INCREMENT PRIMARY KEY,
		title varchar(255) NOT NULL,
		branch varchar(20) NOT NULL,
		origin varchar(255) NOT NULL,
		destination varchar(255) NOT NULL,
		date varchar(255) NOT NULL,
		days varchar(255) NOT NULL,
		pax varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		reply varchar(255) NOT NULL,
		status varchar(255) NOT NULL,
		dateCreated TIMESTAMP NOT NULL,
		userid varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $quotation)) {
		echo "Table quotation";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$ratings = "CREATE TABLE ratings(
		ratingsid int(100) AUTO_INCREMENT PRIMARY KEY,
		feedback varchar(255) NOT NULL,
		ratings varchar(255) NOT NULL,
		dateCreated TIMESTAMP NOT NULL,
		userid varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $ratings)) {
		echo "Table ratings";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$admin = "CREATE TABLE admin(
		adminid int(100) AUTO_INCREMENT PRIMARY KEY,
		password varchar(255) NOT NULL,
		name varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		verification_code varchar(255) NOT NULL,
		email_verified varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $admin)) {
		echo "Table admin";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	$hash_pass = password_hash("Admin123", PASSWORD_BCRYPT, $options);
	$query = "INSERT INTO admin(password, name, email)
                        VALUES('$hash_pass', 'Administrator', 'admin@gmail.com' )";

	if (mysqli_query($conn, $query)) {
		echo "Insert";
	} else {
		echo mysqli_error($conn);
	}

	$user = "CREATE TABLE user(
		userid int(100) AUTO_INCREMENT PRIMARY KEY,
		firstname varchar(255) NOT NULL,
		lastname varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		password varchar(255) NOT NULL,
		verification_code varchar(255) NOT NULL,
		email_verified varchar(255) NOT NULL
		)";

	if (mysqli_query($conn, $user)) {
		echo "Table user";
	} else {
		echo "Error creating Table: " . mysqli_error($conn);
	}

	?>

</body>

</html>