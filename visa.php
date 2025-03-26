
<!DOCTYPE html>
<html>
<head>
    <title>Visa Application System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8em;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }
        .form-group {
            margin: 1.5rem 0;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #34495e;
            font-weight: 500;
        }
        input, select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }
        button[type="submit"] {
            background: #3498db;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        button[type="submit"]:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52,152,219,0.3);
        }
        .alert {
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out;
        }
        .error {
            background: #ffeef0;
            color: #ff4757;
            border: 2px solid #ff4757;
        }
        .success {
            background: #e8f7ee;
            color: #2ed573;
            border: 2px solid #2ed573;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Online Visa Application</h2>
        <form id="visaForm">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" id="fullName" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Passport Number</label>
                <input type="text" id="passport" placeholder="Enter 8-10 character passport number" pattern=".{8,10}">
            </div>
            <div class="form-group">
                <label>Destination Country</label>
                <select id="destination" required>
                    <option value="">Select Destination</option>
                    <option value="USA">United States</option>
                    <option value="CAN">Canada</option>
                    <option value="IND">India</option>
                    <option value="UK">United Kingdom</option>
                    <option value="AUS">Australia</option>
                </select>
            </div>
            <button type="submit">Submit Application</button>
        </form>
        <div id="systemResponse"></div>
    </div>
    <script>
        document.getElementById('visaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch('', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                document.getElementById('systemResponse').innerHTML = data.message;
                document.getElementById('systemResponse').className = `alert ${data.status}`;
            });
        });
    </script>
</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "visa_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['fullName']);
    $passport = trim($_POST['passport']);
    $destination = trim($_POST['destination']);
    
    if (empty($name) || empty($passport) || empty($destination)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit;
    }
    
    if (strlen($passport) < 8 || strlen($passport) > 10) {
        echo json_encode(["status" => "error", "message" => "Invalid passport number! Must be 8-10 characters."]);
        exit;
    }
    
    $stmt = $conn->prepare("INSERT INTO visa_applications (full_name, passport_number, destination) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $passport, $destination);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "✅ Visa application submitted successfully! Our team will contact you shortly."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Application submission failed: " . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>

