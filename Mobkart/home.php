<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="home.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Mobkart | The Smart Store</title>
</head>
<body>

<?php

//pid  pname  pcategory  pprice  pdesc  ppic  pavailable
// Create connection
if(isset($_SESSION['userid'])){
  $uid = $_SESSION['userid'];
}

//$uaddress = $_SESSION['uaddress'];
$con = new mysqli("localhost", "root", "", "project_retail");

// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}?>
<!--     <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Mobkart</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Categories
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Smartphones</a></li>
                  <li><a class="dropdown-item" href="#">Accessories</a></li>
                  <li><a class="dropdown-item" href="#">Earphones</a></li>
                </ul>
              </li>
            </ul>
            <div class="d-flex">
                <a href="login/moblogin.html" class="login-button">Login</a>
                <a href="registration/register.html" class="signup-button">Sign Up</a>
            </div>
          </div>
        </div>
      </nav> -->

      <!-- <nav class="navbar navbar-expand">
  <div class="navbar-logo">
      <img src="public/images/mobkartlogo.png" alt="Company Logo">
  </div>
  <ul class="navbar-links">
      <li><a href="#">Home</a></li>
      <li><a href="#">Categories</a></li>
  </ul>
  

  <div class="cart-container">
    <a href="#" class="cart">
      <img src="public/images/cart.png" alt="Cart">
      <span class="cart-number">2</span>
    </a>
  </div>

  <div class="navbar-actions">
      <a href="#" class="login">Login</a>
      <a href="#" class="signup">Register</a>
  </div>
  <div class="profile">
  <i class="bi bi-person-circle fs-4"></i>
  <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Profile</a></li>
                  <li><a class="dropdown-item" href="#">Log out</a></li>
                </ul>
</div>
</nav> -->

<nav class="dgs-navbar">
  <div class="nav-logo">
      <img src="public/images/mobkartlogo.png" alt="Company Logo">
  </div>
  <div class="navbar-dir">
      <div class="linkhome"><a href="home.php">Home</a></div>
    </div>
  
<?php if(isset($_SESSION['userid'])){?>
    <div class="final-actions">
      <div class="hello">Hello,</div>
      <div class="user"> <?php echo $_SESSION['username']?></div>
    </div>
  
    <div class="final-prop">
      <div class="cart-container">
        <a href="#" class="cart">
          <img src="public/images/cart.png" alt="Cart">
          <span class="cart-number"><script>
              // Function to fetch and update cart items
              function updateCartItems() {
                $.ajax({
                  url: 'cartLogo.php', // Replace with the path to your PHP script
                  type: 'GET',
                  success: function (response) {
                    $('.cart-number').text(response); // Update the content of the span element
                  },
                  error: function (xhr, status, error) {
                    console.error(error); // Log any errors to the console
                  }
                });
              }

              // Call the function initially to update cart items
              updateCartItems();

              // You can call the updateCartItems() function periodically if the cart items may change frequently
              // For example, you can use setInterval(updateCartItems, 5000); to update every 5 seconds
            </script></span>
        </a>
      </div>
      <div class="navprofile">
        <a href="profile\profile.php"><img src="public/images/pinkprofile.png"></a>
      </div>
    </div>
          <?php  }else{?>
            <div class="final-actions">
          <div class="hello"><a href="registration/login/moblogin.html">Login</a></div>
          <div class="hello"> <a href="registration/register.html">Register</a></div>
        </div>
          <?php } ?>
  </nav>

<!--   <div class="navbar-prop">
      <div class="cart-container">
        <a href="cart.php" class="cart">
          <img src="public/images/cart.png" alt="Cart">
          <span class="cart-number"> </span>
        </a>
      </div>
      <div class="navprofile">
        <a href="C:\xampp\htdocs\mobkart\profile\profile.html"><img src="public/images/pinkprofile.png"></a>
    </div></div>
</nav> -->


<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="check-circle-fill" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>
<div id="liveAlertPlaceholder"></div>

      <div class="container banner">
        <video src="public\video\banner.mp4" width="100%" height="550px" autoplay loop muted></video>
      </div>
      <div class="container">      <div class="container mobkart-intro shadow border p-3">
          <h1>Welcome to Mobkart!</h1>
          <p class="lead fs-2">
            Mobkart is the one place for all your smart<s>phone</s> needs!
          </p>
          <p class="lead fs-3">
            <ul class="lead fs-3">
              <li>Fast Shipping across India, USA and Canada.</li>
              <li>Trendy and Newer brands.</li>
              <li>The best brands in your palm: anywhere & anytime. </li>
            </ul>        And more...
          </p>
          <p class="fs-4">All at budget-friendly prices!</p>
      </div></div>


      <div class="container text-center category mt-4">
        <p class="display-5">Check out our products: </p><br>
          <div class="bg-1 text-center mx-4 cat" id="Smartphone">
            <div class="cat-overlay">
              <div class="cat-text">
                Smartphones
              </div>
            </div>
            <img src="public/images/phone-trans.png" alt="" class="cat-resize">
          </div>
          <div class="bg-2 text-center mx-5 cat" id="Accessories">
          <div class="cat-overlay">
          <div class="cat-text">
                Accessories
              </div>
            </div>
            <img src="public/images/acc-trans.png" alt="" class="cat-resize">
          </div>
          <div class="bg-3 text-center mx-4 cat" id="Earphones">
          <div class="cat-overlay">
          <div class="cat-text">
                Earphones
              </div>
            </div>
            <img src="public/images/ear-trans.png" alt="" class="cat-resize">
          </div>
        </div>
        <div class="container mt-5 mb-3">
          <div class="row g-4 result">

          </div>
        </div>


        <footer class="footer">
          <div class="footer-container">
            <div class="footer-content">
              <label><center><h3><u>Contact Us</u></h3></center>
              <p style="text-align:justify;">LB Block 11,Sector 3,Kolkata, 700106<br>
              Email:<a href="mail-korte-hobe">mobkart.in@gmail.com</a>
              Phone: 123-456-7890</label>
            </div>
            <div class="footer-content">
              <label><center><h3><u>About Us</u></h3></center>
              <p style="text-align:justify;">Our company is one of the world's leading mobile related products
                selling company. We have more than 800 partners who help us to acheive our goals.
                Our priority is to provide best products at an affordable price ! </p></label>
            </div>
            <div class="footer-content" style="margin-left:5%;">
              <h3><u>Follow Us</u></h3>
              <ul>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">Instagram</a></li>
              </ul>
            </div>
          </div><br><br>
          <center>
            <p>&copy; MobKart All rights Reserved</p>
          </center>
        </footer>




        <script>

          catToggle = [0, 0, 0]
          //$(document).ready(function(){
              //$(".cat").on("click", function(){
                  //console.log($(".cat").attr('id'));
                  // start ajax code

                  const catList = document.querySelectorAll('.cat');
                  catList.forEach((i) => {
                    i.addEventListener('click', () => {
                      const idVal = i.id;
                      //console.log(idVal);
                      let resultDiv = document.getElementsByClassName('remClass');
                      $.ajax({
                          url: 'productview.php',
                          type: 'POST',
                          data: 'category='+idVal,//+$(".cat").attr('id'),
                          success: function(result){
                            while(resultDiv[0]) {
                              resultDiv[0].parentNode.removeChild(resultDiv[0])
                            }
                              $reply = result;
                              $(".result").append($reply);
                              // when chat goes down the scroll bar automatically comes to the bottom
                              //$(".form").scrollTop($(".form")[0].scrollHeight);
                          }
                      });
                    })
                  })
                  
              //});
         // });
      </script>


      <script>
        const queryString = window.location.search;
        console.log(queryString);
        const urlParams = new URLSearchParams(queryString);
        const orderStatus = urlParams.get('orderStatus');
        const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
          const appendAlert = (message, type, icon) => {
            const wrapper = document.createElement('div')
            wrapper.innerHTML = [
              `<div class="alert alert-${type} alert-dismissible mt-4 mx-5" role="alert">`,
              `   <div class="fs-5">${icon}${message}</div>`,
              '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
              '</div>'
            ].join('')
            alertPlaceholder.append(wrapper)
          }

        if (orderStatus == "success"){
              appendAlert(' Your order has been processed successfully! Thank you for ordering. Continue shopping in Mobkart to get the best brands in your hands!', 'success', `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</svg>`);
        }
      </script>

<script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
<script>
      const t1 = gsap.timeline();
      t1.fromTo(".dgs-navbar", {y: "-100%"}, {y: "0%", duration: 0.5, ease: "power1.in"})
</script>
</body>
</html>