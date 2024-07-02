<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username']) && isset($_SESSION['userType']) && in_array($_SESSION['userType'], ['principal', 'chairperson', 'registrar', 'faculty'])) {
include('../assets/includes/header.php');
include('../assets/includes/navbar_faculty.php');
require '../db_conn.php';

?>
 <?php
if (isset($_SESSION['id'])) {
    // Check if session variable 'id' is set
    $user_id = $_SESSION['id'];

    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);
            // Change $row to $admin
            if (!isset($admin['image']) || empty($admin['image'])) {
                $profile_picture = "../assets/img/user.png";
            } else {
                $profile_picture = "../uploads/" . $admin['image'];
            }
        }
    }
}
?>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Account Settings</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Account Settings</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

<script>
    <?php
    // Check if the session message exists and show it as a SweetAlert
    if (isset($_SESSION['message'])) {
        echo "Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{$_SESSION['message']}',
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'my-sweetalert',
                }
            });";
        unset($_SESSION['message']); // Clear the session message after displaying it
    }

    if (isset($_SESSION['message_danger'])) {
        echo "Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{$_SESSION['message_danger']}',
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'my-sweetalert',
                }
            });";
        unset($_SESSION['message_danger']); // Clear the session message after displaying it
    }
    ?>
</script>

<div class="modal fade" id="change_image" tabindex="-1" aria-labelledby="changeImageLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="changeImageLabel" style="font-weight: bold;">
                    <i class="bi bi-funnel"></i>&nbsp; Filter Option
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form method="POST" enctype="multipart/form-data" id="imageForm" action="crud_account.php">
                    <?php
                    $id = $_SESSION['id'];
                    $ret = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
                    while ($row = mysqli_fetch_array($ret)) {
                        $profile_picture = empty($row['image']) ? "../assets/img/user.png" : "../uploads/" . $row['image'];
                    ?>
                        <input type="hidden" name="oldpic" value="<?php echo $row['image']; ?>">
                        <div class="form-group text-center">
                            <img id="previewImage" src="<?= $profile_picture ?>" alt="Profile Picture" class="rounded-circle" width="300" style="border-radius: 50%; width: 300px; height: 300px;">
                        </div>
                        <div class="form-group px-4">
                            <div class="form-group mt-2 col-md-12">
                                <input type="file" id="image" name="image" class="form-control" id="customFile" placeholder=" " onchange="previewFile()" required>
                                <span style="color:red; font-size:12px;">Only jpg / jpeg / png / gif format allowed.</span>
                            </div>
                        </div>
                    <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="change_profile" class="btn btn-success">Apply Filter</button>
            </div>
            </form>
        </div>
    </div>
</div>
    
    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

              <div style="position: relative;">
                  <img src="<?= isset($profile_picture) ? $profile_picture : '../assets/img/user.png' ?>" alt="Profile Picture" class="rounded-circle mb-3" 
                  width="100" style="border-radius: 50%; width: 100px; height: 100px;">
              </div>
              <span>
                  <h2>
                      <?php
                      require '../db_conn.php';
                      $user_id = $_SESSION['user_id'];

                      $query = "SELECT firstName, middleName, lastName, designation FROM faculty WHERE id = $user_id";
                      $result = mysqli_query($conn, $query);

                      if ($result) {
                          if (mysqli_num_rows($result) > 0) {
                              $faculty = mysqli_fetch_assoc($result);
                              // Display the faculty information
                              echo "<h2 class='fw-bold mb-0 mt-2' style='color: #000;'>{$faculty['firstName']} {$faculty['middleName'][0]}. {$faculty['lastName']}</h2>";

                              // Wrap the designation in a div and apply CSS to center it
                              echo "<div style='text-align: center;'>";
                              echo "<h3 class='small mt-0'>{$faculty['designation']}</h3>";
                              echo "</div>";
                          } else {
                              echo "<h6 class='fw-bold mb-0 mt-2' style='color: #000;'>User Full Name</h6>";
                              echo "<h3 class='small mt-0'>Designation</h3>";
                          }
                      } else {
                          echo "Error executing query: " . mysqli_error($conn);
                      }
                      ?>
                  </h2>
              </span>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
           <ul class="nav nav-tabs nav-tabs-bordered">
              <li class="nav-item">
                <button id="profile-overview-tab" class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
              </li>
              <li class="nav-item">
                <button id="profile-edit-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
              </li>
              <li class="nav-item">
                <button id="profile-settings-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
              </li>
              <li class="nav-item">
                <button id="profile-change-password-tab" class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
              </li>
            </ul>


              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Profile Details</h5>

                  <?php
                  require '../db_conn.php';

                  $user_id = $_SESSION['user_id'];

                  $query = "SELECT emp_number, lastName, firstName, middleName, designation, rank, department, status FROM faculty WHERE id = $user_id";

                  $result = mysqli_query($conn, $query); 

                  $data = [];

                  if ($result) {
                      $data = mysqli_fetch_assoc($result); 
                  }

                  ?>
                  <div class="row">
                      <div class="col-lg-3 col-md-4 label">Employee Number</div>
                      <div class="col-lg-9 col-md-8"><?php echo isset($data['emp_number']) ? $data['emp_number'] : ""; ?></div>
                  </div>

                 <div class="row">
                    <div class="col-lg-3 col-md-4 label">Full Name</div>
                    <div class="col-lg-9 col-md-8">
                        <?php
                        $fullName = isset($data['firstName']) ? $data['firstName'] : "";
                        $middleName = isset($data['middleName']) ? $data['middleName'] : "";
                        $lastName = isset($data['lastName']) ? $data['lastName'] : "";
                        
                        // Concatenate the first name, middle name (if available), and last name
                        echo $fullName;
                        if (!empty($middleName)) {
                            echo ' ' . $middleName;
                        }
                        echo ' ' . $lastName;
                        ?>
                    </div>
                </div>

                  <div class="row">
                      <div class="col-lg-3 col-md-4 label">Designation</div>
                      <div class="col-lg-9 col-md-8"><?php echo isset($data['designation']) ? $data['designation'] : ""; ?></div>
                  </div>

                  <div class="row">
                      <div class="col-lg-3 col-md-4 label">Rank</div>
                      <div class="col-lg-9 col-md-8"><?php echo isset($data['rank']) ? $data['rank'] : ""; ?></div>
                  </div>

                  <div class="row">
                      <div class="col-lg-3 col-md-4 label">Department</div>
                      <div class="col-lg-9 col-md-8"><?php echo isset($data['department']) ? $data['department'] : ""; ?></div>
                  </div>

                  <div class="row">
                      <div class="col-lg-3 col-md-4 label">Status</div>
                      <div class="col-lg-9 col-md-8"><?php echo isset($data['status']) ? $data['status'] : ""; ?></div>
                  </div>


                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  
                    <div class="row mb-3">
                      <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                      <div class="col-md-8 col-lg-9">
                        <div style="position: relative;">
                            <img src="<?= isset($profile_picture) ? $profile_picture : '../assets/img/user.png' ?>" alt="Profile Picture" class="img-thumbnail" width="100" style="width: 100px; height: 100px;">
                        </div>

                        <div class="pt-2">
                          <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#change_image" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                        </div>
                      </div>
                    </div>

                    <?php
                    require '../db_conn.php';

                    // Assuming session_start() is called before accessing $_SESSION
                    $user_id = $_SESSION['user_id'];

                    $query = "SELECT * FROM faculty WHERE id = $user_id";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "Error executing query: " . mysqli_error($conn);
                    } else {
                        $row = mysqli_fetch_assoc($result);
                    ?>
                    <form action="crud_account.php" method="POST">
                    <div class="row mb-3">
                        <label for="firstName" class="col-md-4 col-lg-3 col-form-label">First Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="firstName" type="text" class="form-control" id="firstName" value="<?= $row['firstName'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="middleName" class="col-md-4 col-lg-3 col-form-label">Middle Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="middleName" type="text" class="form-control" id="middleName" value="<?= $row['middleName'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="lastName" class="col-md-4 col-lg-3 col-form-label">Last Name</label>
                        <div class="col-md-8 col-lg-9">
                            <input name="lastName" type="text" class="form-control" id="lastName" value="<?= $row['lastName'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="gender" class="col-md-4 col-lg-3 col-form-label">Gender</label>
                        <div class="col-md-8 col-lg-9">
                            <select class="form-select" id="gender" name="gender" aria-label="State" required>
                                <option value="Male" <?php if(isset($row['gender']) && $row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if(isset($row['gender']) && $row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="rank" class="col-md-4 col-lg-3 col-form-label">Rank</label>
                        <div class="col-md-8 col-lg-9">
                            <select class="form-select" id="rank" name="rank" aria-label="State" required>
                                <option selected disabled value>Select Rank</option>
                                <option value="Instructor I" <?php if(isset($row['rank']) && $row['rank'] == 'Instructor I') echo 'selected'; ?>>Instructor I</option>
                                <option value="Instructor II" <?php if(isset($row['rank']) && $row['rank'] == 'Instructor II') echo 'selected'; ?>>Instructor II</option>
                                <option value="Instructor III" <?php if(isset($row['rank']) && $row['rank'] == 'Instructor III') echo 'selected'; ?>>Instructor III</option>
                                <option value="Asst. Prof. I" <?php if(isset($row['rank']) && $row['rank'] == 'Asst. Prof. I') echo 'selected'; ?>>Asst. Prof. I</option>
                                <option value="Asst. Prof. II" <?php if(isset($row['rank']) && $row['rank'] == 'Asst. Prof. II') echo 'selected'; ?>>Asst. Prof. II</option>
                                <option value="Asst. Prof. III" <?php if(isset($row['rank']) && $row['rank'] == 'Asst. Prof. III') echo 'selected'; ?>>Asst. Prof. III</option>
                                <option value="Asst. Prof. IV" <?php if(isset($row['rank']) && $row['rank'] == 'Asst. Prof. IV') echo 'selected'; ?>>Asst. Prof. IV</option>
                                <option value="Assoc. Prof. I" <?php if(isset($row['rank']) && $row['rank'] == 'Assoc. Prof. I') echo 'selected'; ?>>Assoc. Prof. I</option>
                                <option value="Assoc. Prof. II" <?php if(isset($row['rank']) && $row['rank'] == 'Assoc. Prof. II') echo 'selected'; ?>>Assoc. Prof. II</option>
                                <option value="Assoc. Prof. III" <?php if(isset($row['rank']) && $row['rank'] == 'Assoc. Prof. III') echo 'selected'; ?>>Assoc. Prof. III</option>
                                <option value="Assoc. Prof. IV" <?php if(isset($row['rank']) && $row['rank'] == 'Assoc. Prof. IV') echo 'selected'; ?>>Assoc. Prof. IV</option>
                                <option value="Assoc. Prof. V" <?php if(isset($row['rank']) && $row['rank'] == 'Assoc. Prof. V') echo 'selected'; ?>>Assoc. Prof. V</option>
                                <option value="Professor I" <?php if(isset($row['rank']) && $row['rank'] == 'Professor I') echo 'selected'; ?>>Professor I</option>
                                <option value="Professor II" <?php if(isset($row['rank']) && $row['rank'] == 'Professor II') echo 'selected'; ?>>Professor II</option>
                                <option value="Professor III" <?php if(isset($row['rank']) && $row['rank'] == 'Professor III') echo 'selected'; ?>>Professor III</option>
                                <option value="Professor IV" <?php if(isset($row['rank']) && $row['rank'] == 'Professor IV') echo 'selected'; ?>>Professor IV</option>
                                <option value="Professor VI" <?php if(isset($row['rank']) && $row['rank'] == 'Professor VI') echo 'selected'; ?>>Professor VI</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="designation" class="col-md-4 col-lg-3 col-form-label">Designation</label>
                        <div class="col-md-8 col-lg-9">
                            <select class="form-select" id="designation" name="designation" aria-label="State" required>
                                <option selected disabled value>Select Designation</option>
                                <option value="Principal" <?php if(isset($row['designation']) && $row['designation'] == 'Principal') echo 'selected'; ?>>Principal</option>
                                <option value="Chairperson" <?php if(isset($row['designation']) && $row['designation'] == 'Chairperson') echo 'selected'; ?>>Chairperson</option>
                                <option value="Registrar" <?php if(isset($row['designation']) && $row['designation'] == 'Registrar') echo 'selected'; ?>>Registrar</option>
                                <option value="Faculty" <?php if(isset($row['designation']) && $row['designation'] == 'Faculty') echo 'selected'; ?>>Faculty</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="department" class="col-md-4 col-lg-3 col-form-label">Department</label>
                        <div class="col-md-8 col-lg-9">
                            <select class="form-select" id="department" name="department" aria-label="State" required>
                                <option selected disabled value>Select Department</option>
                                <option value="Elementary" <?php if(isset($row['department']) && $row['department'] == 'Elementary') echo 'selected'; ?>>Elementary</option>
                                <option value="High School" <?php if(isset($row['department']) && $row['department'] == 'High School') echo 'selected'; ?>>High School</option>
                                <option value="Senior High School" <?php if(isset($row['department']) && $row['department'] == 'Senior High School') echo 'selected'; ?>>Senior High School</option>
                            </select>
                        </div>
                    </div>

                    <?php
                    }
                    ?>


                    <div class="text-center">
                      <button type="submit" name="edit_profile" class="btn btn-success">Save Changes</button>
                    </div>
                  </form><!-- End Profile Edit Form -->

                </div>

                <div class="tab-pane fade pt-3" id="profile-settings">

<?php
$user_id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["username"];
    $email = $row["email"];
} else {
    $username = '';
    $email = '';
}
?>
<form action="crud_account.php" method="POST">
<div class="row mb-3">
    <label for="username" class="col-md-4 col-lg-4 col-form-label">Username</label>
    <div class="col-md-8 col-lg-8">
        <input name="username" type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>">
    </div>
</div>

<div class="row mb-3">
    <label for="email" class="col-md-4 col-lg-4 col-form-label">Email</label>
    <div class="col-md-8 col-lg-8">
        <input name="email" type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>">
    </div>
</div>

<div class="text-center">
    <button type="submit" name="update_account_settings" class="btn btn-success">Save Changes</button>
</div>
</form>

                </div>

                <div class="tab-pane fade pt-3" id="profile-change-password">
<!-- Change Password Form -->
<form action="crud_account.php" method="POST">

<div class="row">
  <label for="currentPassword" class="col-md-4 col-lg-4 col-form-label">Current Password</label>
  <div class="col-md-8 col-lg-8">
    <div class="input-group">
        <input type="password" name="current_password" id="current_password" placeholder="Enter current password" class="form-control" required>
        <button class="btn btn-outline-secondary password-toggle-btn" type="button" data-target="current_password" style="border-color: #dee2e6;">
            <i class="bi bi-eye" id="passwordIcon"></i>
        </button>
    </div>
  </div>
</div>

<div class="row mt-3">
  <label for="new_password" class="col-md-4 col-lg-4 col-form-label">New Password</label>
  <div class="col-md-8 col-lg-8">
    <div class="input-group">
        <input type="password" name="new_password" id="new_password" placeholder="Enter new password" class="form-control" required onkeyup="checkPasswordStrength()">
        <button class="btn btn-outline-secondary password-toggle-btn" type="button" data-target="new_password" style="border-color: #dee2e6;">
            <i class="bi bi-eye" id="passwordIcon"></i>
        </button>
    </div>
    <div id="password-strength" class="password-strength"></div>
     <div id="password-suggestions" class="password-suggestions"></div>
  </div>
</div>


<div class="row mt-2">
  <label for="confirm_password" class="col-md-4 col-lg-4 col-form-label">Re-enter New Password</label>
  <div class="col-md-8 col-lg-8">
    <div class="input-group">
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm password" class="form-control" required onkeyup="checkPasswordMatch()">
        <button class="btn btn-outline-secondary password-toggle-btn" type="button" data-target="confirm_password" style="border-color: #dee2e6;">
            <i class="bi bi-eye" id="passwordIcon"></i>
        </button>
    </div>
    <div id="password-match-message" class="password-confirm"></div>
  </div>
</div>

<span class="text-secondary small mt-2">Please note that you will need to log in again after changing your password.</span> 

<div class="text-center">
  <button type="submit" name="edit_password" class="btn btn-success mt-3">Change Password</button>
</div>
</form><!-- End Change Password Form -->
                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

<?php 
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../faculty-portal.php");
    exit();
}
 ?>
<?php
include('../assets/includes/footer.php');
?>
<style>
  .password-strength {
    margin-top: 5px;
    font-size: 12px;
  }
   .password-confirm {
    margin-top: 5px;
    font-size: 12px;
  }
  .weak {
    color: #dc3545; /* Bootstrap 4 danger color */
  }
  .medium {
    color: #ffc107; /* Bootstrap 4 warning color */
  }
  .strong {
    color: #28a745; /* Bootstrap 4 success color */
  }
  .password-suggestions {
    margin-top: 8px;
    font-size: 13px;
  }
</style>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const overviewTab = document.getElementById('profile-overview-tab');
    const editTab = document.getElementById('profile-edit-tab');
    const settingsTab = document.getElementById('profile-settings-tab');
    const changePasswordTab = document.getElementById('profile-change-password-tab');

    const tabItems = [overviewTab, editTab, settingsTab, changePasswordTab];

    tabItems.forEach(tab => {
      tab.addEventListener('click', function() {
        const selectedTabId = tab.getAttribute('id');
        localStorage.setItem('selectedProfileTab', selectedTabId);

        // Remove active class from all tabs
        tabItems.forEach(item => {
          item.classList.remove('active');
        });

        // Add active class to the clicked tab
        tab.classList.add('active');
      });
    });

    const selectedProfileTab = localStorage.getItem('selectedProfileTab');
    if (selectedProfileTab) {
      const selectedTab = document.getElementById(selectedProfileTab);
      if (selectedTab) {
        selectedTab.click(); // Trigger click event to set active tab
      }
    }
  });
</script>

<script>
function previewFile() {
    const preview = document.getElementById('previewImage');
    const fileInput = document.getElementById('image');
    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function (e) {
        preview.src = e.target.result;
    };

    reader.readAsDataURL(file);
}
</script>
<script>
    function checkPasswordStrength() {
        var password = document.getElementById("new_password").value;
        var passwordStrength = document.getElementById("password-strength");
        var passwordSuggestions = document.getElementById("password-suggestions");

        var weakRegex = /^.{0,5}$/;
        var mediumRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/;
        var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_#^+=(){}[\]|\\:;"'<>,.~`]).{8,}$/;

        if (strongRegex.test(password)) {
            passwordStrength.textContent = "Strong password";
            passwordStrength.className = "password-strength strong";
            passwordSuggestions.innerHTML = "";
        } else if (mediumRegex.test(password)) {
            passwordStrength.textContent = "Medium password";
            passwordStrength.className = "password-strength medium";
            passwordSuggestions.innerHTML = "<ul><li>Add special characters, uppercase, and lowercase letters</li></ul>";
        } else if (weakRegex.test(password)) {
            passwordStrength.textContent = "Weak password";
            passwordStrength.className = "password-strength weak";
            passwordSuggestions.innerHTML = "<ul><li>Make it longer, include numbers, uppercase and lowercase letters, and special characters</li></ul>";
        } else {
            passwordStrength.textContent = "Password is too short";
            passwordStrength.className = "password-strength";
            passwordSuggestions.innerHTML = "<ul><li>Make it at least 8 characters long, include uppercase and lowercase letters, numbers, and special characters</li></ul>";
        }
    }

    function checkPasswordMatch() {
        var password = document.getElementById("new_password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var matchMessage = document.getElementById("password-match-message");

        if (password === confirmPassword) {
            matchMessage.innerHTML = "Passwords match";
            matchMessage.style.color = "#28a745"; 
        } else {
            matchMessage.innerHTML = "Passwords do not match";
            matchMessage.style.color = "#dc3545"; 
        }
    }

    var passwordToggleButtons = document.querySelectorAll('.password-toggle-btn');

    passwordToggleButtons.forEach(function(button) {
        button.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var passwordInput = document.getElementById(targetId);
            var passwordIcon = this.querySelector('i'); // Change class name from '.password-icon' to 'i'

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        });
    });
</script>