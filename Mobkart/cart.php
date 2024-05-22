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
    <link rel="stylesheet" href="cart.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Mobkart | The Smart Store</title>
</head>
<body>
  <main>
  <div id="liveAlertPlaceholder"></div>
  <section class="h-100">
  <div class="container h-100 py-5">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-10 result">



        <?php 
        $conn = new mysqli("localhost", "root", "", "project_retail");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $getUserId = $_SESSION['userid'];
        
        //checking user query to database query
        $stmt = "SELECT * FROM `cart` WHERE `uid` = '".$getUserId."'";
        $result = $conn->query($stmt); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
          <h3 class="fw-normal mb-0 text-black">Your Cart has <?php echo $result->num_rows; ?> items</h3>
<!--           <div>
            <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!" class="text-body">price <i
                  class="fas fa-angle-down mt-1"></i></a></p>
          </div> -->
        </div>
        <div class="result">
        <?php 
        if ($result->num_rows > 0) {
            $totalOrder = 0;
            // Output data of each row
            while($row = $result->fetch_assoc()) { 
                      $stmt2 = "SELECT * FROM `product` WHERE `pid` = '".$row['pid']."'";
                      $prodResult = $conn->query($stmt2);
                      $prod = $prodResult->fetch_assoc();
                      $totalOrder = $totalOrder + $prod['pprice']*$row['quantity'];?>
                <div class="card rounded-3 mb-4 remResult">
                <div class="card-body p-4">
                  <div class="row d-flex justify-content-between align-items-center">
                    <div class="col-md-2 col-lg-2 col-xl-2">
                      <img
                        src="<?php echo $prod['ppic'] ?>""
                        class="img-fluid rounded-3" alt="<?php echo $prod['pname'] ?>">
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-3">
                      <p class="lead fw-normal mb-2"><?php echo $prod['pname'] ?></p>
                      <p><span class="text-muted">Category: </span><?php echo $prod['pcatagory'] ?></p>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                        <i class="bi bi-dash fs-4 min"  value="less" id="<?php echo $prod['pid'] ?>"></i>
                      </button>
        
                      <input id="<?php echo $prod['pid'] ?>" min="0" name="quantity" value="<?php echo $row['quantity'] ?>" type="number"
                        class="form-control valList"/ readonly>
        
                      <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()" value="add">
                        <i class="bi bi-plus fs-4 add" id="<?php echo $prod['pid'] ?>"></i>
                      </button>
                    </div>
                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                      <h5 class="mb-0">Rs. <?php echo $prod['pprice']*$row['quantity'] ?></h5>
                    </div>
                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                      <i class="bi bi-trash-fill fs-3 text-danger rem" value="remove" id="<?php echo $prod['pid'] ?>"></i>
                    </div>
                  </div>
                </div>
              </div>
           <?php }
        } else {
            echo '<p class="text-center"><h2>Your Cart is Empty :(</h2></p>'; ?>
            <div class="container 80vh"><p><img src="public/images/Empty_Cart.png" alt="Sad Cart Face" class="mx-auto my-auto d-block align-middle"></p></div>
      <?php  }
        
        $stmt = 0;
        $conn->close();
        ?>

        
</div>




<!--         <div class="card mb-4">
          <div class="card-body p-4 d-flex flex-row">
            <div data-mdb-input-init class="form-outline flex-fill">
              <input type="text" id="form1" class="form-control form-control-lg" />
              <label class="form-label" for="form1">Discound code</label>
            </div>
            <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-warning btn-lg ms-3">Apply</button>
          </div>
        </div> -->
        <?php 
        if($result->num_rows > 0){
          echo'        
          <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="fw-normal mb-0 text-black">Your Outstanding Amount: Rs. '.$totalOrder.'</h4>
        </div>
          <div class="card border-0">
          <form method ="post" action ="checkout.php">
          <div class="card-body text-center">
            <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-warning btn-block btn-lg">Proceed to Pay</button>
          </div>
          </form>
        </div>';
        }

      ?>

      </div>
    </div>
  </div>
</section>
  </main>

  <script>
    let butminList = document.querySelectorAll('.min');
    let butplusList = document.querySelectorAll('.add');
    let butremList = document.querySelectorAll('.rem');

    //console.log(butplusList);
    butminList.forEach((i) => {
      i.addEventListener('click', () => {
        const idVal = i.id;
        //console.log(idVal);
        $.ajax({
            url: 'cartLogic.php',
            type: 'POST',
            data: { prodId: idVal, op: "minus"},//+$(".cat").attr('id'),
            success: function(result){
                $reply = result;
                console.log($reply);
                location.reload();
                //let resultDiv = document.getElementsByClassName('remResult');
/*                 while(resultDiv[0]) {
                  resultDiv[0].parentNode.removeChild(resultDiv[0])
                } */
                //alert($reply);
                //$(".result").append($reply);
            } 
        });
      })
    }) 


    butplusList.forEach((i) => {
      i.addEventListener('click', () => {
        const idVal = i.id;
        //console.log(idVal);
        $.ajax({
            url: 'cartLogic.php',
            type: 'POST',
            data: { prodId: idVal, op: "plus"},//+$(".cat").attr('id'),
            success: function(result){
                $reply = result;
                console.log($reply);
                location.reload();
                //let resultDiv = document.getElementsByClassName('remResult');
/*                 while(resultDiv[0]) {
                  resultDiv[0].parentNode.removeChild(resultDiv[0])
                } */
                //alert($reply);
                //$(".result").append($reply);
            } 
        });
      })
    }) 

    butremList.forEach((i) => {
      i.addEventListener('click', () => {
        const idVal = i.id;
        //console.log(idVal);
        $.ajax({
            url: 'cartLogic.php',
            type: 'POST',
            data: { prodId: idVal, op: "remove"},//+$(".cat").attr('id'),
            success: function(result){
                $reply = result;
                console.log($reply);
                location.reload();
                //let resultDiv = document.getElementsByClassName('remResult');
/*                 while(resultDiv[0]) {
                  resultDiv[0].parentNode.removeChild(resultDiv[0])
                } */
                //alert($reply);
                //$(".result").append($reply);
            } 
        });
      })
    }) 

/*     let valList = document.querySelectorAll('.valList');
    valList.forEach((i) => {
      i.addEventListener('input', () => {
        const idVal = i.id;
        console.log(idVal);
      })
    }); */

  </script>
        <script>
        const queryString = window.location.search;
        console.log(queryString);
        const urlParams = new URLSearchParams(queryString);
        const errorStatus = urlParams.get('error');
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

        if (errorStatus == "exceededstock"){
              appendAlert(' One or more items in your cart exceeded our current available stock. \n Please remove someof your items or try again later. Sorry for the inconvenience caused.', 'danger', `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-octagon-fill" viewBox="0 0 16 16">
  <path d="M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353zM8 4c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995A.905.905 0 0 1 8 4m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
</svg>`);
        }
      </script>
</body>
</html>