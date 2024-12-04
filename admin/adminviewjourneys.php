<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$dbname = "travelscapes";  

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$selectedRegions = [];
$selectedSeasons = [];
$selectedDays = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve filter values from the form
    $selectedRegions = isset($_POST["region"]) ? $_POST["region"] : [];
    $selectedSeasons = isset($_POST["season"]) ? $_POST["season"] : [];
    $selectedDays = isset($_POST["days"]) ? $_POST["days"] : [];
}

// Build the SQL query based on selected filters
$sql = "SELECT * FROM cities WHERE 1"; 


if (!empty($selectedRegions)) {
    if (!in_array("All", $selectedRegions)) {
        $sql .= " AND region IN ('" . implode("','", $selectedRegions) . "')";
    }
} else {
    $selectedRegions = ["All"]; 
}


if (!empty($selectedSeasons)) {
    if (!in_array("All", $selectedSeasons)) {
        $sql .= " AND season IN ('" . implode("','", $selectedSeasons) . "')";
    }
} else {
    $selectedSeasons = ["All"]; 
}

if (!empty($selectedDays)) {
    if (!in_array("All", $selectedDays)) {
        $sql .= " AND days IN ('" . implode("','", $selectedDays) . "')";
    }
} else {
    $selectedDays = ["All"]; 
}

$result = $conn->query($sql);

// Create an array to store the data
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travelscapes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET["delete"])) {
    // Get the journey ID to delete
    $journeyIdToDelete = $_GET["delete"];
    
    // Prepare a SQL statement to delete the journey
    $sql = "DELETE FROM cities WHERE cityid = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $journeyIdToDelete);

        if ($stmt->execute()) {
            // Deletion was successful
            echo "Journey with ID $journeyIdToDelete has been deleted.";
        } else {
            // Error occurred during deletion
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Error in the prepared statement
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="adminviewjourneys.css">
    <title>Available Journeys</title>
    <script>
        function toggleDropdown(filterName) {
            var dropdownContent = document.getElementById(filterName + "Dropdown");
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <br>
    <a class="back" href="admindashboard.php">Back to Dashboard</a>
    <h1>City Journeys</h1>
   
<br>
    <?php

    echo "<h3>Available Cities</h3>";
    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>City ID</th>"; 
    echo "<th>City</th>";
    echo "<th>Region</th>";
    echo "<th>Season</th>";
    echo "<th>Days</th>";
    echo "<th>Cost</th>";
    echo "<th>Action</th>";
    echo "</tr>";

 
    foreach ($data as $row) {
        echo "<tr>";
        echo "<td>" . $row["cityid"] . "</td>"; 
        echo "<td>" . $row["city"] . "</td>"; 
        echo "<td>" . $row["region"] . "</td>";
        echo "<td>" . $row["season"] . "</td>";
        echo "<td>" . $row["days"] . "</td>";
        echo "<td>" . $row["cost"] . "</td>";
        echo "<td><a href='?delete=" . $row["cityid"] . "' class='delete-button'>Delete Journey</a></td>";
        echo "</tr>";
    }

    echo "</table>";

    // $conn->close();
    ?>

    <?php
  if (isset($_GET["delete"])) {
    echo "<div id='successMessage' class='success-message'>";
    echo "Journey deleted successfully.";
    echo "</div>";
}

?>
<br><br>
    <a href="addjourney.html" class="insert-button">+ Add Journey</a>


    <script>
    let journeyIdToDelete = null;

    // Check if a "delete" query parameter is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("delete")) {
        // If the "delete" query parameter is present, show the confirmation modal
        journeyIdToDelete = urlParams.get("delete");
        const modal = document.getElementById("confirmationModal");
        modal.style.display = "block";
    }

    function deleteJourney() {
    if (journeyIdToDelete !== null) {
        // You can implement the deletion logic here using journeyIdToDelete
        // For example, you can use AJAX to delete the journey on the server.
        // After deletion, you can reload the page or update the UI as needed.

        // Set the "delete" query parameter in the URL
        window.location.href = "adminviewjourneys.php"
    }
}

    function closeModal() {
        journeyIdToDelete = null;
        const modal = document.getElementById("confirmationModal");
        modal.style.display = "none";
    }

//     function insertJourney() {
//     // You can implement the logic to insert a journey here.
//     // This function will be called when the "Add Journey" button is clicked.
//     // You can show a form or take the necessary actions to add a journey.
//     // Replace the comment with your insertion logic.
//     console.log("Insert a journey logic here.");
// }
</script>
</body>
</html>
