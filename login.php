<?php
session_start();

// Hardcoded demo credentials
$validUsername = 'admin';
$validPassword = 'password123';

$message = '';

if (isset($_GET['logout'])) {
    // Logout action
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['username'])) {
    // Already logged in
    $loggedInUser = $_SESSION['username'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $validUsername && $password === $validPassword) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login Page - PHP Session</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
  body {
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #fff;
  }
  .container {
    background: rgba(255,255,255,0.1);
    padding: 3rem 2.5rem;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    width: 350px;
    text-align: center;
  }
  h1 {
    margin-bottom: 2rem;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
  }
  form {
    display: flex;
    flex-direction: column;
  }
  label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    text-align: left;
  }
  input[type="text"], input[type="password"] {
    padding: 0.6rem 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    border: none;
    font-size: 1rem;
    outline: none;
    font-family: 'Poppins', sans-serif;
  }
  button {
    background: #5a3ea0;
    border: none;
    padding: 0.75rem 1rem;
    font-size: 1.1rem;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  button:hover {
    background: #7b57c9;
  }
  .message {
    color: #ff7675;
    margin-bottom: 1rem;
    font-weight: 600;
  }
  .welcome {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
  }
  a.logout {
    display: inline-block;
    background: #5a3ea0;
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: background 0.3s ease;
  }
  a.logout:hover {
    background: #7b57c9;
  }
</style>
</head>
<body>
  <div class="container">
  <?php if (isset($loggedInUser)): ?>
    <h1>Welcome</h1>
    <p class="welcome">Hello, <strong><?php echo htmlspecialchars($loggedInUser); ?></strong>! You are logged in.</p>
    <a href="login.php?logout=1" class="logout" title="Logout">Logout</a>
  <?php else: ?>
    <h1>Login</h1>
    <?php if ($message): ?>
      <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="post" action="login.php" novalidate>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required autocomplete="username" />
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required autocomplete="current-password" />
      <button type="submit">Sign In</button>
    </form>
  <?php endif; ?>
  </div>
</body>
</html>

