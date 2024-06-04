<!DOCTYPE html>
<html>

<head>
	<title>Pawffect</title>
</head>

<body>

	<?php
    include "Include.php";
    
	$options = ['cost' => 12,];

	$quotation = "CREATE TABLE quotation(
		quotationid int(100) AUTO_INCREMENT PRIMARY KEY,
		title varchar(255) NOT NULL,
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

	if(mysqli_query($conn, $quotation)) {
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

	if(mysqli_query($conn, $ratings)) {
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

	if(mysqli_query($conn, $admin)) {
	    echo "Table admin";
	} else {
	    echo "Error creating Table: " . mysqli_error($conn);
	}

	$hash_pass = password_hash("Admin123", PASSWORD_BCRYPT, $options);
	$query = "INSERT INTO admin(password, name, email)
                        VALUES('$hash_pass', 'Administrator', 'admin@gmail.com' )";

	if(mysqli_query($conn, $query)) {
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

	if(mysqli_query($conn, $user)) {
	    echo "Table user";
	} else {
	    echo "Error creating Table: " . mysqli_error($conn);
	}

	?>

</body>

</html>