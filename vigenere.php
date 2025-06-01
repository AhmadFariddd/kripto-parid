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
<title>Vigenère Cipher Encrypt & Decrypt (PHP)</title>
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
    <h1>Vigenère Cipher</h1>
    <form method="post" action="">
      <label for="inputText">Text</label>
      <textarea id="inputText" name="inputText" placeholder="Enter your text here..." required><?php echo isset($_POST['inputText']) ? htmlspecialchars($_POST['inputText']) : ''; ?></textarea>
      <label for="keyInput">Key</label>
      <input type="text" id="keyInput" name="keyInput" placeholder="Enter cipher key" maxlength="50" required value="<?php echo isset($_POST['keyInput']) ? htmlspecialchars($_POST['keyInput']) : ''; ?>" />
      <div class="button-group">
        <button type="submit" name="action" value="encrypt" title="Encrypt the text">Encrypt</button>
        <button type="submit" name="action" value="decrypt" title="Decrypt the text">Decrypt</button>
      </div>
    </form>
    <div class="result" aria-live="polite" aria-atomic="true">
    <?php
      // Helper function to clean key: only uppercase A-Z
      function cleanKey($key) {
          return preg_replace('/[^A-Z]/', '', strtoupper($key));
      }

      // Vigenère Encrypt function
      function vigenereEncrypt($plaintext, $key) {
          $key = cleanKey($key);
          if (strlen($key) === 0) return "Key must contain at least one alphabetic character.";
          $result = '';
          $keyIndex = 0;
          $keyLength = strlen($key);
          $len = strlen($plaintext);
          $steps = [];

          for ($i = 0; $i < $len; $i++) {
              $c = $plaintext[$i];
              $code = ord($c);
              if (($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 122)) {
                  $isUpper = ($code >= 65 && $code <= 90);
                  $base = $isUpper ? 65 : 97;
                  $pPos = $code - $base;
                  $kPos = ord($key[$keyIndex % $keyLength]) - 65;
                  $encPos = ($pPos + $kPos) % 26;
                  $result .= chr($base + $encPos);
                  $steps[] = "Encrypting '$c' with key '{$key[$keyIndex % $keyLength]}' -> '" . chr($base + $encPos) . "'";
                  $keyIndex++;
              } else {
                  $result .= $c;
              }
          }
          return [$result, $steps];
      }

      // Vigenère Decrypt function
      function vigenereDecrypt($ciphertext, $key) {
          $key = cleanKey($key);
          if (strlen($key) === 0) return "Key must contain at least one alphabetic character.";
          $result = '';
          $keyIndex = 0;
          $keyLength = strlen($key);
          $len = strlen($ciphertext);
          $steps = [];

          for ($i = 0; $i < $len; $i++) {
              $c = $ciphertext[$i];
              $code = ord($c);
              if (($code >= 65 && $code <= 90) || ($code >= 97 && $code <= 122)) {
                  $isUpper = ($code >= 65 && $code <= 90);
                  $base = $isUpper ? 65 : 97;
                  $cPos = $code - $base;
                  $kPos = ord($key[$keyIndex % $keyLength]) - 65;
                  $decPos = ($cPos - $kPos + 26) % 26;
                  $result .= chr($base + $decPos);
                  $steps[] = "Decrypting '$c' with key '{$key[$keyIndex % $keyLength]}' -> '" . chr($base + $decPos) . "'";
                  $keyIndex++;
              } else {
                  $result .= $c;
              }
          }
          return [$result, $steps];
      }

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $text = $_POST['inputText'] ?? '';
          $key = $_POST['keyInput'] ?? '';
          $action = $_POST['action'] ?? '';

          if (trim($text) === '') {
              echo 'Please enter text.';
          } elseif (trim($key) === '') {
              echo 'Please enter a key.';
          } else {
              if ($action === 'encrypt') {
                  list($encryptedText, $steps) = vigenereEncrypt($text, $key);
                  echo "Encrypted Text: $encryptedText\n";
                  echo "Steps:\n" . implode("\n", $steps);
              } elseif ($action === 'decrypt') {
                  list($decryptedText, $steps) = vigenereDecrypt($text, $key);
                  echo "Decrypted Text: $decryptedText\n";
                  echo "Steps:\n" . implode("\n", $steps);
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

