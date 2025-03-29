<?php

session_start();
require_once "db/db.php";
require_once "utils/functions.php";

if (!$_SESSION['ADMIN']) redirect("log");
$ADMIN = $_SESSION['ADMIN'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dash</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- datatable css -->
    <link href="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.css" rel="stylesheet">
    <!-- css -->
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <nav class="navbar d-flex justify-content-between align-items-center fs-3 mb-3 text-white bg-black">
        <p class="m-3">Bookings Table</p>
        <ul class="d-flex align-items-center">
            <li class="nav-items">
                <a href="cars">Cars</a>
            </li>
            <li class="nav-items">
                <a href="table">Tables</a>
            </li>
            <li class="nav-items">
                <button class="btn btn-primary" id="logout" type="button">Logout</button>
            </li>
        </ul>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-body-secondary">
                <span class="h5">All Bookings</span>
                <br>
                Manage existing bookings or add new ones
            </div>
            <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddBooking">
                <i class="fa-solid fa-user-plus"></i>
                Add new bookings
            </button>
        </div>


        <table class="table table-bordered table-striped table-hover align-middle" id="myTable" style="width: 100%;">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Car & Model</th>
                    <th>Image</th>
                    <th>State</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>From</th>
                    <th>Until</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- add booking -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddBooking" style="width: 600px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Add Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="post" id="insertForm" accept="image/*">
                <!-- name & email -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Customer's Name</label>
                        <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Customer's Email</label>
                        <input type="text" class="form-control" name="email" placeholder="name@example.com" required>
                    </div>
                </div>
                <!-- car name & model -->
                <div class="mb-3">
                    <label class="form-label">Car Name & Model</label>
                    <select name="car" class="form-control" required>
                        <option value="">Select Car</option>
                        <option value="BMW X6">BMW X6</option>
                        <option value="Toyota Camry">Toyota Camry</option>
                        <option value="Nissan Juke">Nissan Juke</option>
                        <option value="Mercedes C300">Mercedes C300</option>
                        <option value="Range Rover Evoque">Range Rover Evoque</option>
                    </select>
                </div>
                <!-- image & preview image -->
                <div class="row mb-3">
                    <label class="form-control">Upload Image</label>
                    <div class="col-2">
                        <img src="images/image.png" class="preview_img">
                    </div>
                    <div class="file-upload text-secondary">
                        <input type="file" name="image" class="image" accept="image/*" required>
                        <span class="fs-4 fw-2">Choose file...</span>
                        <span>Or drag and drop file here</span>
                    </div>
                </div>
                <!-- state and address -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">State</label>
                        <select name="state" class="form-control" required>
                            <option value="">Select State</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="Anambra">Anambra</option>
                            <option value="Bauchi">Bauchi</option>
                            <option value="Bayelsa">Bayelsa</option>
                            <option value="Benue">Benue</option>
                            <option value="Borno">Borno</option>
                            <option value="Cross River">Cross River</option>
                            <option value="Delta">Delta</option>
                            <option value="Ebonyi">Ebonyi</option>
                            <option value="Edo">Edo</option>
                            <option value="Ekiti">Ekiti</option>
                            <option value="Enugu">Enugu</option>
                            <option value="Gombe">Gombe</option>
                            <option value="Imo">Imo</option>
                            <option value="Jigawa">Jigawa</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Kano">Kano</option>
                            <option value="Katsina">Katsina</option>
                            <option value="Kebbi">Kebbi</option>
                            <option value="Kogi">Kogi</option>
                            <option value="Kwara">Kwara</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Nasarawa">Nasarawa</option>
                            <option value="Niger">Niger</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Ondo">Ondo</option>
                            <option value="Osun">Osun</option>
                            <option value="Oyo">Oyo</option>
                            <option value="Plateau">Plateau</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Sokoto">Sokoto</option>
                            <option value="Taraba">Taraba</option>
                            <option value="Yobe">Yobe</option>
                            <option value="Zamfara">Zamfara</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" placeholder="33 Amokwe Agbani Road" required>
                    </div>
                </div>
                <!-- price & dates -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Price</label>
                        <input type="range" id="priceSlider" class="form-control" name="price" min="500000" max="5000000" step="100000" value="500000" required>
                        <span id="priceValue">N500,000</span>
                    </div>
                    <div class="col">
                        <label class="form-label">Booked From</label>
                        <input type="date" class="form-control" name="from" placeholder="Enter State" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Booked Until</label>
                        <input type="date" class="form-control" name="until" placeholder="Enter State" required>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary me-1" type="submit" id="insertBtn">Submit</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- edit booking details -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditBooking" style="width: 600px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Edit user</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="post" id="editForm" accept="image/*">
                <input type="hidden" name="id" id="id">
                <!-- name & email -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Customer's Name</label>
                        <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Customer's Email</label>
                        <input type="text" class="form-control" name="email" placeholder="name@example.com" required>
                    </div>
                </div>
                <!-- car name & model -->
                <div class="mb-3">
                    <label class="form-label">Car Name & Model</label>
                    <select name="car" class="form-control" required>
                        <option value="">Select Car</option>
                        <option value="BMW X6">BMW X6</option>
                        <option value="Toyota Camry">Toyota Camry</option>
                        <option value="Nissan Juke">Nissan Juke</option>
                        <option value="Mercedes C300">Mercedes C300</option>
                        <option value="Range Rover Evoque">Range Rover Evoque</option>
                    </select>
                </div>
                <!-- image & preview image -->
                <div class="row mb-3">
                    <label class="form-control">Upload Image</label>
                    <div class="col-2">
                        <img src="images/image.png" class="preview_img">
                    </div>
                    <div class="file-upload text-secondary">
                        <input type="file" name="image" class="image" accept="image/*">
                        <input type="hidden" name="image_old" id="image_old">
                        <span class="fs-4 fw-2">Choose file...</span>
                        <span>Or drag and drop file here</span>
                    </div>
                </div>
                <!-- state and address -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">State</label>
                        <select name="state" class="form-control" required>
                            <option value="">Select State</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="Anambra">Anambra</option>
                            <option value="Bauchi">Bauchi</option>
                            <option value="Bayelsa">Bayelsa</option>
                            <option value="Benue">Benue</option>
                            <option value="Borno">Borno</option>
                            <option value="Cross River">Cross River</option>
                            <option value="Delta">Delta</option>
                            <option value="Ebonyi">Ebonyi</option>
                            <option value="Edo">Edo</option>
                            <option value="Ekiti">Ekiti</option>
                            <option value="Enugu">Enugu</option>
                            <option value="Gombe">Gombe</option>
                            <option value="Imo">Imo</option>
                            <option value="Jigawa">Jigawa</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Kano">Kano</option>
                            <option value="Katsina">Katsina</option>
                            <option value="Kebbi">Kebbi</option>
                            <option value="Kogi">Kogi</option>
                            <option value="Kwara">Kwara</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Nasarawa">Nasarawa</option>
                            <option value="Niger">Niger</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Ondo">Ondo</option>
                            <option value="Osun">Osun</option>
                            <option value="Oyo">Oyo</option>
                            <option value="Plateau">Plateau</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Sokoto">Sokoto</option>
                            <option value="Taraba">Taraba</option>
                            <option value="Yobe">Yobe</option>
                            <option value="Zamfara">Zamfara</option>
                        </select>
                    </div>
                    <div class="col">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" placeholder="33 Amokwe Agbani Road" required>
                    </div>
                </div>
                <!-- price & dates -->
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Price</label>
                        <input type="range" id="editPriceSlider" class="form-control" name="price" min="500000" max="5000000" step="100000" value="" required>
                        <span id="editPriceValue"></span>
                    </div>
                    <div class="col">
                        <label class="form-label">Booked From</label>
                        <input type="date" class="form-control" name="from" placeholder="Enter State" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Booked Until</label>
                        <input type="date" class="form-control" name="until" placeholder="Enter State" required>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary me-1" type="submit" id="editBtn">Update</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- toast container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <!-- success toast -->
        <div class="toast align-items-center text-bg-success" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>Success!</strong>
                    <span id="successMsg"></span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>

        <!-- Error toast -->
        <div class="toast align-items-center text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>Error!</strong>
                    <span id="errorMsg"></span>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>


    <!-- bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- datatable JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-2.2.2/datatables.min.js"></script>

    <!-- ajax js -->
    <script src="js/main.js"></script>

</body>

</html>