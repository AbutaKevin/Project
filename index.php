<?php
session_start();
include("connection.php");
include("function.php");


$user_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'];



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KeviBus Travellers</title>
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <script>
        function goToLogin() {
            window.location.href = 'login.php';
        }
        function goToSignUp() {
            window.location.href = 'SignUp.php';
        }
    </script>
</head>
<body>
    <div class="main">
        <section id="home">
            <div class="menu">
                <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="#home">
                            <img src="images/logo.png" style="width: 50px;" alt="Logo">
                            <span class="logo">evi<span>Bus</span>Travellers</span>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="#home" data-after="Home">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="#Destination" data-after="Service">Destinations</a></li>
                                <li class="nav-item"><a class="nav-link" href="#AboutUs" data-after="AboutUs">About Us</a></li>
                                <li class="nav-item"><a class="nav-link" href="#contact" data-after="Contact">Contact</a></li>
                                <li class="nav-item"><a class="nav-link" href="#">Need Help to book?</a></li>
                            </ul>
                            <header>
                                <div class="top-right-buttons">
                                    <?php
                                    if ($user_logged_in) {
                                        // If the user is logged in, show the dropdown
                                        echo '<div class="dropdown">
                                                <button class="dropbtn">Account <ion-icon name="caret-down-outline"></ion-icon></button>
                                                <div class="dropdown-content">
                                                <a href="Location.php"><i class="bi bi-geo-alt"></i> Book Now</a>
                                                <a href="MyAccount.php"><i class="bi bi-person-circle"></i> My Account</a>
                                                <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>

                                                </div>
                                              </div>';
                                    } else {
                                        // If the user is not logged in, show the login and signup buttons
                                        echo '<button class="login-button" onclick="goToLogin()">Login</button>
                                              <button class="signup-button" onclick="goToSignUp()">Sign Up</button>';
                                    }
                                    ?>
                                </div>
                            </header>
                        </div>
                    </div>
                </nav>


        <section class="welcome-section">
    <div class="container">
        <h2 class="animated-text"><span>Helloo,</span> Welcome to KeviBus Travellers</h2>
        <p>Connecting Dreams, Driving Your Journey, Our Commitment</p>
        <p>Here, your safety is our priority</p>
        <p>Travel with us today.</p>

        <?php
        if ($user_logged_in) {

           echo' <a href="Location.php" class="bn5">Book Now</a>';
        } else {
            echo' <a href="login.php" class="bn5">Please Login, To Book</a>';
        }
        ?>
    </div>
</section>
        </section>
        
        <section id="Destination">
        <h2 class="text-center mb-4">Our Top Destinations</h2> 
        <section class="destination-section">
          <div class="card">
            <img src="images/Nairobi.jpg" alt="Destination 1">
            <h5>NAIROBI</h5>
          </div>
      
          <div class="card">
            <img src="images/kisumu.jpg" alt="Destination 2">
            <h5>KISUMU</h5>
          </div>
      
          <div class="card">
            <img src="images/kisii.jpg" alt="Destination 3">
            <h5>KISII</h5>
          </div>
      
          <div class="card">
            <img src="images/Nakuru.jpg" alt="Destination 4">
            <h5>NAKURU</h5>
          </div>
      
          <div class="card">
            <img src="images/mombasa.jpg" alt="Destination 5">
            <h5>MOMBASA</h5>
          </div>
      
          <div class="card">
            <img src="images/malindi.jpg" alt="Destination 6">
            <h5>MALINDI</h5>
          </div>
        </section>
      </section>

    <section id="AboutUs">
      <section class="about-section">
    <div class="container">
      <h3>About Our Bus Service</h3>

      <div class="content">
        <p>Welcome to KeviBus, where we redefine your journey with a harmonious blend of comfort, reliability, and efficiency. As a premier bus service provider, we take immense pride in connecting communities, facilitating travel, and ensuring a seamless transportation experience for our passengers.</p>

        <h4>Our Commitment</h4>
        <p>At KeviBus, our commitment is simple: to offer a safe, convenient, and enjoyable journey for every traveler. We understand the importance of reliable transportation, and our team is dedicated to providing top-notch service at every step of your journey.</p>

        <h4>What Sets Us Apart</h4>
        <ul>
          <li><strong>Safety First:</strong> Your safety is our priority. Our fleet is maintained to the highest standards, and our experienced drivers undergo rigorous training to ensure a secure and worry-free travel experience.</li>

          <li><strong>Comfort on the Road:</strong> Sit back, relax, and enjoy the ride. Our modern and well-equipped buses are designed with your comfort in mind. Whether you're commuting to work or embarking on a leisurely trip, we've got you covered.</li>

          <li><strong>Punctuality Matters:</strong> We value your time. Our buses run on a strict schedule, ensuring that you reach your destination on time, every time. Count on us for punctuality and reliability.</li>

          <li><strong>Eco-Friendly Travel:</strong> We are committed to sustainability. Our efforts to reduce our carbon footprint include fuel-efficient vehicles and eco-friendly practices, making us a responsible choice for environmentally conscious travelers.</li>
        </ul>

        <h4>Our Team</h4>
        <p>Behind every successful journey is a dedicated team. From drivers to customer service representatives, our staff is passionate about providing exceptional service. We strive to exceed your expectations and make your travel experience with us memorable for all the right reasons.</p>

        <h4>Community Connection</h4>
        <p>KeviBus is more than a bus service – we're an integral part of the communities we serve. We believe in fostering connections and contributing to the well-being of the regions we operate in. Join us in creating a stronger, more connected community through reliable transportation.</p>

        <p>Choose KeviBus for a journey that goes beyond transportation – where excellence, safety, and customer satisfaction come together.</p>

        <p>Thank you for choosing us as your preferred bus service provider.</p>
      </div>
    </div>
  </section>
      </section>
    


    <section id="contact">
    <script>
        function showConfirmation() {
            alert("Email sent successfully!");
        }
    </script>
    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $to = "abutakevin254@gmail.com"; 


    $subject = "KeviBus Mail";


    $emailMessage = "Name: $name\n";
    $emailMessage .= "Email: $email\n";
    $emailMessage .= "Message:\n$message";

    if (mail($to, $subject, $emailMessage)) {
      header("Location: index.php");
      exit();
  } else {
      echo "Error sending email.";
  }
}
?>

    <section class="contact-section">
      <div class="container">
      <h2 class="text-center mb-4">Contact Us</h2>
  
        <div class="contact-form">
          <form action="index.php" method="post" onsubmit="showConfirmation()">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
  
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>
  
            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>
  
            <button type="submit" class="button submit-button">Submit</button>
          </form>
        </div>
      </div>
    </section>

    <footer>
    <p style="color: black;">For More about our company, Please contact Us on Our social media platforms or you can Email Us or Call Us: </p>
		   
      <h6 style="color:#FF0000">Email: kevibus2024@gmail.com</h6>
			<h6 style="color:#FF0000">Phone: 0106414842/0710731146</h6>
      <h6 style="color:#FF0000">Po Box: 102-00100 Nairobi</h6>

			
          
         
		<div class="social">
			<a href="https://web.facebook.com/"><ion-icon name="logo-facebook"></ion-icon></a>
			<a href="https://www.instagram.com/"><ion-icon name="logo-twitter"></ion-icon></a>
			<a href="https://twitter.com/"><ion-icon name="logo-instagram"></ion-icon></a>
		</div>
	
	</footer>
  <div class="copy"><p>&copy; 2024 KeviBus Travellers. All rights reserved.</p></div>
  </section>
          </div>
           
</body>
</html>