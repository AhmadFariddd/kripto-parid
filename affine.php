<?php
session_start(); // Start the session
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Affine Cipher Encrypt & Decrypt (PHP)</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    color: #fff;
  }
  .container {
    background: rgba(255,255,255,0.1);
    padding: 2rem 3rem;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    max-width: 450px;
    width: 100%;
    margin: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  h1 {
    font-weight: 600;
    text-align: center;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    letter-spacing: 2px;
  }
  label {
    font-weight: 600;
    margin-bottom: 0.3rem;
    margin-top: 1rem;
    width: 100%;
    text-align: center;
    display: block;
  }
  input[type="text"], textarea {
    width: 400px;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    border: none;
    font-size: 1rem;
    resize: vertical;
    font-family: 'Poppins', sans-serif;
    margin-left: auto;
    margin-right: auto;
    display: block;
  }
  textarea {
    min-height: 100px;
  }
  .button-group {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
    width: 100%;
  }
  button {
    background: #5a3ea0;
    border: none;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease;
    flex: 1;
    max-width: 150px;
  }
  button:hover {
    background: #7b57c9;
  }
  .result {
    margin-top: 1.5rem;
    background: rgba(255,255,255,0.2);
    padding: 1rem;
    border-radius: 10px;
    word-wrap: break-word;
    min-height: 60px;
    font-size: 1.1rem;
    color: #f0e6ff;
    user-select: all;
    white-space: pre-wrap;
    width: 90%;
    margin-left: auto;
    margin-right: auto;
  }
  footer {
    background: rgba(0,0,0,0.25);
    color: #ddd;
    font-size: 0.9rem;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
    width: 100%;
    position: fixed;
    bottom: 0;
    left: 0;
  }
  @media (max-width: 500px) {
    .container {
      padding: 1.5rem;
      max-width: 90vw;
    }
    button {
      padding: 0.6rem 1rem;
      font-size: 0.9rem;
      max-width: none;
      flex: 1;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Affine Cipher</h1>
    <form method="post" action="">
      <label for="inputText">Text</label>
      <textarea id="inputText" name="inputText" placeholder="Enter your text here..." required><?php echo isset($_POST['inputText']) ? htmlspecialchars($_POST['inputText']) : ''; ?></textarea>
      <label for="keyA">Key a (invers modular mod26)</label>
      <input type="text" id="keyA" name="keyA" placeholder="Enter key a" maxlength="3" required value="<?php echo isset($_POST['keyA']) ? htmlspecialchars($_POST['keyA']) : ''; ?>" />
      <label for="keyB">Key b</label>
      <input type="text" id="keyB" name="keyB" placeholder="Enter key b" maxlength="3" required value="<?php echo isset($_POST['keyB']) ? htmlspecialchars($_POST['keyB']) : ''; ?>" />
      <div class="button-group">
        <button type="submit" name="action" value="encrypt" title="Encrypt the text">Encrypt</button>
        <button type="submit" name="action" value="decrypt" title="Decrypt the text">Decrypt</button>
      </div>
    </form>
    <div class="result" aria-live="polite" aria-atomic="true">
    <?php
      // Calculate gcd function
      function gcd($a, $b) {
          while ($b != 0) {
              $temp = $b;
              $b = $a % $b;
              $a = $temp;
          }
          return $a;
      }

      // Calculate modular inverse of a under modulo m, m=26
      function modInverse($a, $m) {
          $a = $a % $m;
          for ($x = 1; $x < $m; $x++) {
              if (($a * $x) % $m == 1) {
                  return $x;
              }
          }
          return -1; // no inverse if not found
      }

      // Check if character is alphabet letter
      function isLetter($c) {
          $code = ord($c);
          return (($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 122));
      }

      // Affine Encrypt
      function affineEncrypt($plaintext, $a, $b) {
          $m = 26;
          $a = intval($a);
          $b = intval($b);

          if (gcd($a, $m) !== 1) {
              return ["Error: Key 'a' must be coprime with 26.", []];
          }

          $result = '';
          $steps = [];
          $length = strlen($plaintext);

          for ($i = 0; $i < $length; $i++) {
              $c = $plaintext[$i];
              if (isLetter($c)) {
                  $isUpper = (ord($c) >= 65 && ord($c) <= 90);
                  $base = $isUpper ? 65 : 97;
                  $pPos = ord($c) - $base;
                  $encPos = ($a * $pPos + $b) % $m;
                  $encChar = chr($base + $encPos);
                  $result .= $encChar;
                  $steps[] = "Encrypting '$c': E(x) = ($a * $pPos + $b) mod 26 = $encPos -> '$encChar'";
              } else {
                  $result .= $c;
              }
          }

          return [$result, $steps];
      }

      // Affine Decrypt
      function affineDecrypt($ciphertext, $a, $b) {
          $m = 26;
          $a = intval($a);
          $b = intval($b);

          if (gcd($a, $m) !== 1) {
              return ["Error: Key 'a' must be coprime with 26.", []];
          }

          $a_inv = modInverse($a, $m);
          if ($a_inv == -1) {
              return ["Error: Key 'a' has no modular inverse modulo 26.", []];
          }

          $result = '';
          $steps = [];
          $length = strlen($ciphertext);

          for ($i = 0; $i < $length; $i++) {
              $c = $ciphertext[$i];
              if (isLetter($c)) {
                  $isUpper = (ord($c) >= 65 && ord($c) <= 90);
                  $base = $isUpper ? 65 : 97;
                  $cPos = ord($c) - $base;
                  $decPos = ($a_inv * ($cPos - $b + $m)) % $m;
                  $decChar = chr($base + $decPos);
                  $result .= $decChar;
                  $steps[] = "Decrypting '$c': D(x) = $a_inv * ($cPos - $b) mod 26 = $decPos -> '$decChar'";
              } else {
                  $result .= $c;
              }
          }

          return [$result, $steps];
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $text = $_POST['inputText'] ?? '';
          $keyA = $_POST['keyA'] ?? '';
          $keyB = $_POST['keyB'] ?? '';
          $action = $_POST['action'] ?? '';

          if (trim($text) === '') {
              echo 'Please enter text.';
          } elseif (!is_numeric($keyA) || !is_numeric($keyB)) {
              echo 'Keys "a" and "b" must be numeric.';
          } else {
              if ($action === 'encrypt') {
                  list($encryptedText, $steps) = affineEncrypt($text, $keyA, $keyB);
                  echo "Encrypted Text: $encryptedText\n";
                  if (!empty($steps)) {
                      echo "Steps:\n" . implode("\n", $steps);
                  }
              } elseif ($action === 'decrypt') {
                  list($decryptedText, $steps) = affineDecrypt($text, $keyA, $keyB);
                  echo "Decrypted Text: $decryptedText\n";
                  if (!empty($steps)) {
                      echo "Steps:\n" . implode("\n", $steps);
                  }
              } else {
                  echo 'Unknown action.';
              }
          }
      }
    ?>
    </div>
  </div>

  <footer>
    &copy; 2024 Ahmad Farid Adhe Riyadi/23080960115
  </footer>
</body>
</html>

