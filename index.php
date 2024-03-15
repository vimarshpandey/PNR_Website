<!DOCTYPE html>
<html>

  <head>

    <title>PNR Status</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./img/smallicon.png">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap@5.3.2.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600&family=Playfair+Display&family=Poppins:wght@200;300;400;500;600;700;800&display=swap');
      body
      {
          font-family: 'Poppins', sans-serif;
      }

      .table-rounded
      {
        border: 4px solid #d5c47c; /* Increase the border width as needed */
        border-radius: 30px; /* Adjust the border-radius for rounded corners */
      }
    </style>

  </head>

  <body class="bg-dark">
    <div class="container">
      <header class="text-center">
        <div class="row">
          <div class="col-4">
            <img src="./img/icon.png" height="50px"><span class="h5 text-light">&nbsp;PNR Status</span>
          </div>
          <div class="col-8 mt-2">
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
              <li><a href="#pnrstatus" class="nav-link px-2 link-warning">PNR Status</a></li>&nbsp;&nbsp;
              <li><a href="#fullform" class="nav-link px-2 link-warning">Full Form</a></li>&nbsp;&nbsp;
              <li><a href="#aboutus" class="nav-link px-2 link-warning">About Us</a></li>
            </ul>
          </div>
        </div>
      </header>

      <div style="background-color: rgb(253, 162, 4);" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center rounded-5">
        <div class="col-md-8 p-lg-5 mx-auto my-5">
          <h1 class="display-3 fw-bold">Check the Status of Your PNR</h1>
          <h3 class="fw-normal text-muted mb-3">"Experience seamless PNR status checks on our user-friendly website – stay informed about your train reservation in just a click!"</h3>
        </div>
        <!-- <div class="product-device shadow-sm d-none d-md-block">dhdgbtdehregnddt</div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block">gnfgbdfb</div> -->
      </div>

      <div style="background-color: rgb(255, 200, 0);" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center rounded-5">
        <div class="col-md-12 p-lg-5 mx-auto my-5" id="pnrstatus">
            <h1 class="fw-bold">Enter the PNR number</h1><br>
            <form class="text-center" method="post" action="">
                <input type="text" class="form-control w-25 mx-auto" id="pnr" name="pnr" placeholder="Ex. 1234567890" required>
                <div class="text-center">
                  <div class="g-recaptcha mt-3" data-sitekey="6LcsC5opAAAAAKZPZrQRj611EXzM_4P5EDUpRhBQ" style="display: inline-block;"></div>
                </div>
                <button type="submit" name="submit" class="btn btn-primary mt-3">Check Status</button>
            </form>
            <!-- Display data -->
            <?php
              session_start();

              // Check if last request time is set in session
              if (isset($_SESSION['last_request_time']))
              {
                  $lastRequestTime = $_SESSION['last_request_time'];
                  $currentTime = time();
                  $timeDifference = $currentTime - $lastRequestTime;
                  
                  // Check if the time difference is less than 5 minutes (300 seconds)
                  if ($timeDifference < 180) {
                      // Display a message indicating the user must wait
                      echo '<div class="alert alert-warning mt-3" role="alert">Please wait for 3 minutes before making another request.</div>';
                      exit; // Exit the script
                  }
              }
              
              // Set the current time as the last request time in session
              $_SESSION['last_request_time'] = time();
              if (isset($_POST['submit']))
              {
                if(isset($_POST['g-recaptcha-response']))
                {
                  $recaptcha = $_POST['g-recaptcha-response'];
                  if(!$recaptcha)
                  {
                    echo '<script>alert("Please go back and check recaptcha box")</script>';
                    exit;
                  }
                  else
                  {
                    $secret = "6LcsC5opAAAAAIoTEIUJJOBmqB_jjUwI07MUiPQR";
                    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $recaptcha;
                    $response = file_get_contents($url);
                    $responsekeys = json_decode($response, true);

                    if($responsekeys['success'])
                    {
                      $user_pnr = $_POST['pnr'];

                      $api_url = 'https://travel.paytm.com/api/trains/v1/status?vertical=train&client=web&is_genuine_pnr_web_request=1&pnr_number=' . urlencode($user_pnr);
                      $response = file_get_contents($api_url);

                      if ($response === false)
                      {
                          die('Failed to fetch data from the API');
                      }

                      $data = json_decode($response, true);

                      if ($data === null)
                      {
                          die('Error decoding JSON data');
                      }

                      if (isset($data['body']))
                      {
                        $train_number = $data['body']['train_number'];
                        $train_name = $data['body']['train_name'];
                        $boarding_date = $data['body']['date'];
                        $from = $data['body']['pulse_data']['journey_src'];
                        $to = $data['body']['pulse_data']['journey_dest'];
                        $boarding_point = $data['body']['boarding_station']['station_name'];
                        $reservation_upto = $data['body']['reservation_upto']['station_name'];
                        $travel_class = $data['body']['class'];
                
                        // Extract passenger details
                        $passenger_details = $data['body']['pax_info'];
                    ?>
                      <div class="h4 mt-5">You Queried for PNR number <?php echo htmlspecialchars($user_pnr); ?></div>
                      <table class="table table-warning table-hover table-rounded mt-3">
                          <thead>
                            <tr>
                              <th>Train Number</th>
                              <th>Train Name</th>
                              <th>Boarding Date<br>(YYYY-MM-DD)</th>
                              <th>From</th>
                              <th>To</th>
                              <th>Borading Point</th>
                              <th>Reservation Upto</th>
                              <th>Class</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td><?php echo $train_number; ?></td>
                              <td><?php echo $train_name; ?></td>
                              <td><?php echo $boarding_date; ?></td>
                              <td><?php echo $from; ?></td>
                              <td><?php echo $to; ?></td>
                              <td><?php echo $boarding_point; ?></td>
                              <td><?php echo $reservation_upto; ?></td>
                              <td><?php echo $travel_class; ?></td>
                            </tr>
                          </tbody>
                        </table>

                        <table class="table table-warning table-hover mt-3">
                          <thead>
                              <tr>
                                  <th>Passenger Name</th>
                                  <th>Booking Status</th>
                                  <th>Current Status</th>
                                  <th>Departure from<br>Boarding Station</th>
                                  <th>Arrival at<br>Destination</th>
                                  <th>Remarks (if any)</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                                foreach ($passenger_details as $passenger)
                                {
                                  echo '<tr>';
                                  echo '<td>' . $passenger['passengerName'] . '</td>';
                                  echo '<td>' . $passenger['bookingStatus'] . " / " . $passenger['bookingBerthNo'] . '</td>';
                                  echo '<td>' . $passenger['currentStatus'] . " / " . $passenger['currentCoachId'] . " / " . $passenger['currentBerthNo'] . " / " . $passenger['currentBerthCode'] . '</td>';
                                  echo '<td>' . $data['body']['boarding_station']['departure_time'] . '</td>';
                                  echo '<td>' . $data['body']['reservation_upto']['arrival_time'] . '</td>';
                                  echo '<td>' . $data['body']['pnr_message'] . '</td>';
                                  echo '</tr>';
                                }
                              ?>
                          </tbody>
                        </table>
                        <?php
                            }
                            else
                            {
                              echo '<div class="h4 mt-5">No data available for the entered PNR number.</div>';
                            }
                          }
                        }
                      }
                    }
                  ?>
        </div>
      </div>

      <div style="background-color: rgb(248, 215, 66);" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center rounded-5">
        <h1 class="fw-bold" id="fullform">Basic Full Form</h1><br>
            <table class="table table-warning table-hover table-rounded mt-3">
              <thead>
                  <tr>
                      <th>Word</th>
                      <th>Full Form</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>CAN / MOD</td>
                      <td>Cancelled or Modified Passenger</td>
                  </tr>
                  
                  <tr>
                    <td>CNF / Confirmed</td>
                    <td>Confirmed (Coach/Berth number will be available after chart preparation)</td>
                  </tr>

                  <tr>
                    <td>RAC</td>
                    <td>Reservation Against Cancellation</td>
                  </tr>

                  <tr>
                    <td>WL</td>
                    <td>Waiting List Number</td>
                  </tr>

                  <tr>
                    <td>RLWL</td>
                    <td>Remote Location Wait List</td>
                  </tr>

                  <tr>
                    <td>GNWL</td>
                    <td>General Wait List</td>
                  </tr>

                  <tr>
                    <td>PQWL</td>
                    <td>Pooled Quota Wait List</td>
                  </tr>

                  <tr>
                    <td>REGRET/WL</td>
                    <td>No More Booking Permitted</td>
                  </tr>

                  <tr>
                    <td>RELEASED</td>
                    <td>Ticket Not Cancelled but Alternative Accommodation Provided</td>
                  </tr>

                  <tr>
                    <td>R#</td>
                    <td>RAC Coach Number Berth Number</td>
                  </tr>

                  <tr>
                    <td>WEBCAN</td>
                    <td>Railway Counter Ticket Passenger cancelled through internet and Refund not collected</td>
                  </tr>

                  <tr>
                    <td>WEBCANRF</td>
                    <td>Railway Counter Ticket Passenger cancelled through internet and Refund collected</td>
                  </tr>

                  <tr>
                    <td>RQWL</td>
                    <td>Roadside Quota Waitlist</td>
                  </tr>

                  <tr>
                    <td>DPWL</td>
                    <td>Duty Pass Waitlist</td>
                  </tr>

                  <tr>
                    <td>TQWL</td>
                    <td>Tatkal Quota Waitlist</td>
                  </tr>

                  <tr>
                    <td>NT</td>
                    <td>Passenger Not Turned Up</td>
                  </tr>

                  <tr>
                    <td>LB / UB / MB / SL / SU</td>
                    <td>Lower Berth / Upper Berts / Middle Birth / Side Lower / Side Upper</td>
                  </tr>

                  <tr>
                    <td>WS / CB / CP / SM / NC</td>
                    <td>Window Side / Cabin / Coupe / Side Middle / No Choice</td>
                  </tr>

                  <tr>
                    <td>1A / 2A / 3A / FC</td>
                    <td>First Class AC / AC 2 Tier / AC 3 Tier / First Class</td>
                  </tr>

                  <tr>
                    <td>CC / EC</td>
                    <td>Chair Car / Executive Class</td>
                  </tr>

                  <tr>
                    <td>3E / SL / 2S</td>
                    <td>AC 3 Tier Economy / Sleeper Class / Second Sitting</td>
                  </tr>
              </tbody>
            </table>
        <!-- <div class="product-device shadow-sm d-none d-md-block">dhdgbtdehregnddt</div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block">gnfgbdfb</div> -->
      </div>

      <div style="background-color: rgb(245, 232, 131);" class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center rounded-5">
        <div class="col-md-8 p-lg-5 mx-auto" id="aboutus">
          <h1 class="display-3 fw-bold">About Us</h1>
          <div class="fw-normal text-muted">
            <p>Welcome to PNR Status Checker, your go-to platform for checking the status of your train reservations. We understand the importance of staying informed about your journey, and that's why we provide a simple and reliable tool to check PNR status effortlessly.</p>

            <p>Our mission is to make your travel experience smoother by offering a user-friendly interface that fetches the latest information about your train bookings. Whether you're a frequent traveler or planning a one-time journey, we've got you covered.</p>
        
            <p>At PNR Status Checker, we are passionate about delivering accurate and timely updates. Our team is dedicated to ensuring that you have the information you need, right at your fingertips. Feel free to use our service and make your train travel stress-free!</p>
          </div>
        </div>
        <div class="product-device d-none d-md-block">
          <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="#pnrstatus" class="nav-link px-2 link-dark">PNR Status</a></li>
            <li class="nav-item"><a href="#fullform" class="nav-link px-2 link-dark">Full Forms</a></li>
          </ul>
        </div>
        <div class="product-device product-device-2 d-none d-md-block"><p class="text-center">&copy; 2024 Vimpandey. All right reserved</p></div>
      </div>
    </div>
  </body>

  <script src="./js/scroll.js"></script>

</html>