<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: bold;
      color: #555;
    }

    input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }

    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #007BFF;
      color: white;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .message {
      text-align: center;
      color: red;
      margin-bottom: 1rem;
    }

    .success {
      color: green;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Reset Password</h2>

    <?php
    require_once "../../Controller/userscontroller.php";
    $controller = new userscontroller();

    $message = "";
    $success = false;

    if (!isset($_GET['reset_code'])) {
      $message = "Missing reset code.";
    } else {
      $reset_code = $_GET['reset_code'];
      $user = $controller->verifyResetCode($reset_code);

      if (!$user) {
        $message = "Invalid reset code.";
      } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
          $message = "Passwords do not match.";
        } elseif (strlen($newPassword) < 6) {
          $message = "Password must be at least 6 characters long.";
        } else {
          $controller->updatePassword($user['id'], $newPassword);
          $message = "Password updated successfully.";
          $success = true;
        }
      }
    }
    ?>

    <?php if (!empty($message)): ?>
      <div class="message <?= $success ? 'success' : '' ?>">
        <?= htmlspecialchars($message) ?>
        <?php if ($success): ?>
          <br>Redirecting to login page...
          <script>
            setTimeout(() => {
              window.location.href = "index1.php";
            }, 3000);
          </script>
        <?php else: ?>
          <br><a href="index1.php">Back to login</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if (!$success): ?>
      <form method="POST">
        <label for="new-password">New Password:</label>
        <input type="password" name="new_password" id="new-password" required>

        <label for="confirm-password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm-password" required>

        <button type="submit">Change Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>

</html>
