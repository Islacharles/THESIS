<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the session
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #4A3AFF;
            color: white;
            padding: 10px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 60px;
        }

        .sidebar h1 {
            font-size: 24px;
            margin: 10px 0;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            margin-bottom: 50px;
            display: block;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .sidebar .bottom-links a {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .sidebar .bottom-links a i {
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            background-color: #F5F5F5;
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-left: 1000px;
        }

        .header .user-info {
            display: flex;
            align-items: left;
        }

        .header .user-info .notification {
            display: flex;
            align-items: center;
        }

        .header .user-info .vertical-rule {
            border-left: 1px solid #E0E0E0;
            height: 40px;
            margin: 0 20px;
        }

        .header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .header .user-info span {
            font-size: 16px;
        }

        .create-btn {
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    background-color: #4A3AFF; /* Primary color */
    color: white;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.create-btn:hover {
    background-color: #3a2ca8;
}

.overlay {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8); /* Darker overlay for better contrast */
    z-index: 999; /* Ensure it's above other content */
}

.create-panel {
    display: none; /* Initially hidden */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 500px;
    background-color: #ffffff;
    padding: 30px; /* Increased padding for better spacing */
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Softer shadow */
    z-index: 1000; /* Ensure it's above the overlay */
    transition: opacity 0.3s ease, transform 0.3s ease; /* Smooth transition */
    opacity: 0;
    transform: translate(-50%, -40%); /* Adjusted for smoother appearance */
}

.create-panel.show {
    display: block;
    opacity: 1;
    transform: translate(-50%, -50%); /* Reset on show */
}

.child-info {
    display: none; /* Initially hidden */
    overflow-y: auto; /* Enable vertical scrolling */
    max-height: 300px; /* Set a max height for scrolling */
    padding: 10px; /* Add some padding */
    border: 1px solid #ccc; /* Optional: Add border to distinguish the section */
    border-radius: 4px; /* Match the input style */
    background-color: #f9f9f9; /* Optional: Light background for the child info section */
    margin-top: 15px; /* Space above child info */
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    cursor: pointer;
    background: none;
    border: none;
    color: #333;
}

.create-panel h2 {
    margin-top: 0;
    font-size: 20px;
    color: #333; /* Darker title color */
}

.create-panel label {
    display: block;
    margin: 10px 0 5px;
    font-weight: 500; /* Slightly bolder labels */
}

.create-panel input {
    width: calc(100% - 16px); /* Full width minus padding */
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.3s ease; /* Smooth transition for border color */
}

.create-panel input:focus {
    border-color: #4A3AFF; /* Highlight border on focus */
    outline: none; /* Remove default outline */
}
.create-panel button[type="button"] {
    padding: 12px 24px; /* Consistent padding */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer on hover */
    background-color: #007BFF; /* Primary color for buttons */
    color: white; /* Text color */
    border: none; /* No border */
    border-radius: 4px; /* Rounded corners */
    transition: background-color 0.3s ease; /* Smooth transition */
    margin-right: 10px; /* Space between buttons */
}

.create-panel button[type="button"]:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.create-panel button[type="button"]:focus {
    outline: none; /* Remove default outline */
    box-shadow: 0 0 5px rgba(74, 58, 255, 0.5); /* Focus effect */
}

.create-panel button[type="submit"] {
    padding: 12px 24px;
    font-size: 16px;
    cursor: pointer;
    background-color: #007BFF; /* Submit button color */
    color: white;
    border: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.create-panel button[type="submit"]:hover {
    background-color: #0056b3; /* Darker shade on hover */
}
td i {
    cursor: pointer; /* Change cursor to pointer for better UX */
    margin: 0 5px;  /* Space between icons */
    font-size: 1.2em; /* Size of the icons */
}


        .table-container {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            position: relative;
            overflow-x: auto;
        }

        .table-container .search-bar-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-container .search-bar-container .create-btn {
            margin-right: 20px;
            background-color: #4A3AFF;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .table-container .search-bar-container .search-bar {
            display: flex;
            align-items: center;
            background-color: #EDEDED;
            padding: 10px;
            border-radius: 5px;
            flex-grow: 1;
            cursor: text;
        }

        .table-container .search-bar-container .search-bar i {
            margin-right: 10px;
        }

        .table-container .search-bar-container .search-bar input {
            border: none;
            background: none;
            outline: none;
            font-size: 16px;
            flex-grow: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #E0E0FF;
        }

        table tr:nth-child(even) {
            background-color: #F9F9F9;
        }

        table tr:hover {
            background-color: #F1F1F1;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #4A3AFF;
            text-decoration: none;
            margin: 0 10px;
            font-size: 16px;
        }

        .pagination .page-number {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #E0E0FF;
            border-radius: 50%;
            margin: 0 5px;
        }

        .pagination .page-number.active {
            background-color: #4A3AFF;
            color: white;
        }

        hr {
            border: 0;
            height: 1px;
            background: #E0E0E0;
            margin: 20px 0;
        }

        /* Responsive styles */
        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .header .user-info {
                margin-right: 20px; /* Adjust if needed */
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .header .user-info {
                margin-right: 0;
            }

            .table-container {
                padding: 10px;
            }

            .table-container .search-bar-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-container .search-bar-container .create-btn {
                margin-right: 0;
                margin-bottom: 10px;
            }

            .table-container .search-bar-container .search-bar {
                width: 100%;
            }

            table th, table td {
                padding: 10px;
                font-size: 14px;
            }

            .pagination a, .pagination .page-number {
                font-size: 14px;
                margin: 0 5px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .sidebar img {
                width: 60px;
                height: 60px;
            }

            .sidebar h1 {
                font-size: 20px;
            }

            .header .user-info span {
                font-size: 14px;
            }

            .table-container {
                padding: 5px;
            }

            .table-container .search-bar-container .create-btn {
                padding: 8px 16px;
            }

            table th, table td {
                padding: 8px;
            }

            .pagination a, .pagination .page-number {
                font-size: 12px;
                margin: 0 3px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
    <img src="logo/logo.png" alt="Logo">
        <a href="#">Attendance</a>
        <a href="student.php">Student Records</a>
        <a href="parent.php">Parent Records</a>
        <a href="staff.php">Admin/Staff Records</a>
        <a href="#">Pick-Up Records</a>
        <a href="events.php">Events</a>
        <div class="bottom-links">
            <a href="#">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="#">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="user-info">
                <div class="notification">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="vertical-rule"></div>
                <div class="profile">
                <img alt="User profile picture" height="40" src="https://oaidalleapiprodscus.blob.core.windows.net/private/org-Hh5RPsKhtBPsWCFSiEKnUJ6x/user-8qgiVpCV0U0b7zDjfFInHgjl/img-UaqtED2tWdF8Jj5BdVptvrvZ.png?st=2024-09-08T03%3A49%3A50Z&amp;se=2024-09-08T05%3A49%3A50Z&amp;sp=r&amp;sv=2024-08-04&amp;sr=b&amp;rscd=inline&amp;rsct=image/png&amp;skoid=d505667d-d6c1-4a0a-bac7-5c84a87759f8&amp;sktid=a48cca56-e6da-484e-a814-9c849652bcb3&amp;skt=2024-09-07T23%3A47%3A39Z&amp;ske=2024-09-08T23%3A47%3A39Z&amp;sks=b&amp;skv=2024-08-04&amp;sig=18zzyGV2lWaM/BQ7/LoacKemQW7r9eD1vJOq3I7Ssss%3D" width="40"/>
                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span><br>
                <span><?php echo htmlspecialchars($_SESSION['user_role']); ?></span>
                </div>
            </div>
        </div>
        <div class="table-container">
        <div class="search-bar-container">
        <button class="create-btn" id="create-btn">CREATE</button>
        <div class="overlay" id="overlay"></div>
        <div class="create-panel" id="create-panel">
    <button class="close-btn" id="close-btn">&times;</button>
    <form id="parent-form" action="submit_form.php" method="post">
        <h2>Parent Account Creation</h2>
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required>

        <label for="contactnumber">Contact Number:</label>
        <input type="tel" id="contactnumber" name="contact_number" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Submit</button>
    </form>

    <div id="child-info" style="display: none;">
        <h2>Child Information</h2>
        <form id="child-form" action="child_submit_form.php" method="post">
    <label for="childname">Child's Name:</label>
    <input type="text" id="childname" name="child_name" required>

    <label for="childid">Child's Student ID:</label>
    <input type="text" id="childid" name="child_id" required>

    <label for="childage">Child's Age:</label>
    <input type="number" id="childage" name="child_age" required>

    <label for="childteacher">Child's Teacher:</label>
    <select id="childteacher" name="child_teacher" required>
        <option value="">Select a teacher</option>
        <?php
        // Fetch teachers from the database and populate options
        include 'connection.php';
        $teacher_sql = "SELECT id, fullname FROM admin_staff WHERE role = 'teacher'";
        $result = $conn->query($teacher_sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['fullname']) . '</option>';
            }
        } else {
            echo '<option value="">No teachers found</option>';
        }
        ?>
    </select>

    <label for="childgrade">Child's Grade:</label>
    <input type="text" id="childgrade" name="child_grade" required>

    <label for="childsection">Child's Section:</label>
    <input type="text" id="childsection" name="child_section" required>

    <label for="childaddress">Child's Address:</label>
    <input type="text" id="childaddress" name="child_address" required>

    
    <button type="submit">Submit</button>
</form>
    </div>
</div>

<script>
document.getElementById('create-btn').onclick = function() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('create-panel').classList.add('show');
};

document.getElementById('close-btn').onclick = function() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('create-panel').classList.remove('show');
};

// Handle parent form submission
document.getElementById('parent-form').onsubmit = async function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    try {
        const response = await fetch('submit_form.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        if (data.success) {
            document.getElementById('parent-form').style.display = 'none';
            document.getElementById('child-info').style.display = 'block';

            // Add hidden parent ID input to child form
            const parentIdInput = document.createElement('input');
            parentIdInput.type = 'hidden';
            parentIdInput.name = 'parent_id';
            parentIdInput.value = data.parent_id;
            document.getElementById('child-form').appendChild(parentIdInput);
            
            this.reset();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while submitting the parent form. Please try again.');
    }
};

// Handle child form submission
document.getElementById('child-form').onsubmit = async function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    // Check if parent_id is in formData
    console.log('Child form data before submission:', Object.fromEntries(formData));

    try {
        const response = await fetch('child_submit_form.php', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        if (data.success) {
    alert('Child account created successfully!');
    location.reload();
} else {
    alert(data.message); // This will show detailed error message
}

    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while creating the child account. Please try again.');
    }
};
</script>

            <div class="search-bar">
            <input type="text" id="search" placeholder="Search..." onkeyup="performSearch(event)">
            </div>
        </div>
        <?php
include 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pagination variables
$itemsPerPage = 10; // Items per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from the URL
$offset = ($currentPage - 1) * $itemsPerPage; // Offset for SQL query

// Query to select records from the admin_staff table with LIMIT for pagination
$sql = "SELECT id, fullname, contact_number, email, address, password FROM parent_acc LIMIT $offset, $itemsPerPage";
$result = $conn->query($sql);

// Count total records for pagination
$totalSql = "SELECT COUNT(*) as total FROM parent_acc";
$totalResult = $conn->query($totalSql);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages


echo '<table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Email Address</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['fullname']) . '</td>
                <td>' . htmlspecialchars($row['contact_number']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['address']) . '</td>
                <td>
                    <i class="fas fa-pen" title="Edit" onclick="location.href=\'edit_staff.php?id=' . $row['id'] . '\'"></i>
                    <i class="fas fa-trash" title="Delete" onclick="location.href=\'delete_staff.php?id=' . $row['id'] . '\'"></i>
                    <i class="fas fa-eye" title="View" onclick="showChildInfo(' . $row['id'] . ')"></i>
                </td>
              </tr>';
    }
} else {
    echo '<tr><td colspan="5">No records found</td></tr>';
}

echo '  </tbody>
      </table>';

// Close the database connection
$conn->close();
?>
<div id="childInfoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="childInfoContent"></div>
    </div>
</div>

<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>

<script>
   function showChildInfo(parentId) {
    fetch('child_info.php?parent_id=' + parentId)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                const childInfoHtml = data.map(child => `
                    <p><strong>Student ID:</strong> ${child.child_id}</p>
                    <p><strong>Name:</strong> ${child.child_name}</p>
                    <p><strong>Section:</strong> ${child.child_section}</p>
                    <p><strong>Address:</strong> ${child.child_address}</p>
                    <p><strong>Adviser:</strong> ${child.child_adviser}</p>
                `).join('');

                document.getElementById('childInfoContent').innerHTML = childInfoHtml;
            } else {
                document.getElementById('childInfoContent').innerHTML = `<p>No child information found.</p>`;
            }
            document.getElementById('childInfoModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching child information:', error));
}

function closeModal() {
    document.getElementById('childInfoModal').style.display = 'none';
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    var modal = document.getElementById('childInfoModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>
            <hr>
            <div class="pagination" id="pagination"></div>
            <script>
    const totalItems = <?php echo $totalItems; ?>; 
const itemsPerPage = <?php echo $itemsPerPage; ?>; 
let currentPage = <?php echo $currentPage; ?>; 

function renderPagination() {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = ''; // Clear previous pagination

    const totalPages = Math.ceil(totalItems / itemsPerPage);

    // Previous button
    const prevLink = document.createElement('a');
    prevLink.innerHTML = '«';
    prevLink.className = currentPage === 1 ? 'disabled' : '';
    prevLink.onclick = function() {
        if (currentPage > 1) {
            currentPage--;
            updatePage();
        }
    };
    pagination.appendChild(prevLink);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageNumber = document.createElement('div');
        pageNumber.innerHTML = i;
        pageNumber.className = `page-number ${i === currentPage ? 'active' : ''}`;
        pageNumber.onclick = function() {
            currentPage = i;
            updatePage();
        };
        pagination.appendChild(pageNumber);
    }

    // Next button
    const nextLink = document.createElement('a');
    nextLink.innerHTML = '»';
    nextLink.className = currentPage === totalPages ? 'disabled' : '';
    nextLink.onclick = function() {
        if (currentPage < totalPages) {
            currentPage++;
            updatePage();
        }
    };
    pagination.appendChild(nextLink);
}

function updatePage() {
    window.location.href = '?page=' + currentPage; // Redirect to the correct page
}

// Initial rendering
renderPagination();
</script>
            </div>
        </div>
    </div>

    <script src="script/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>
