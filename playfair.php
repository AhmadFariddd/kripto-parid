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
<title>Playfair Cipher Encrypt & Decrypt (PHP)</title>
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
    <h1>Playfair Cipher</h1>
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
    /* Helper functions */

    function cleanText($text) {
        $text = strtoupper($text);
        $text = preg_replace('/[^A-Z]/', '', $text);
        $text = str_replace('J', 'I', $text);
        return $text;
    }

    /* Generate 5x5 matrix for key */
    function generateKeyMatrix($key) {
        $key = cleanText($key);
        $matrix = [];
        $used = [];

        for ($i = 0; $i < strlen($key); $i++) {
            $char = $key[$i];
            if (!in_array($char, $used)) {
                $used[] = $char;
            }
        }

        // Add remaining letters A-Z except J
        for ($char = ord('A'); $char <= ord('Z'); $char++) {
            $letter = chr($char);
            if ($letter == 'J') continue;
            if (!in_array($letter, $used)) {
                $used[] = $letter;
            }
        }

        // Fill matrix 5x5
        for ($row = 0; $row < 5; $row++) {
            $matrix[$row] = array_slice($used, $row * 5, 5);
        }

        return $matrix;
    }

    /* Find position of character in matrix */
    function findPosition($matrix, $char) {
        for ($r = 0; $r < 5; $r++) {
            for ($c = 0; $c < 5; $c++) {
                if ($matrix[$r][$c] === $char) {
                    return [$r, $c];
                }
            }
        }
        return [-1, -1]; // not found
    }

    /* Prepare plaintext (remove J, insert X between repeating letters and handle length) */
    function prepareText($text) {
        $text = cleanText($text);
        $prepared = '';
        $i = 0;
        $len = strlen($text);
        while ($i < $len) {
            $char1 = $text[$i];
            $char2 = ($i + 1) < $len ? $text[$i + 1] : 'X';

            if ($char1 == $char2) {
                $prepared .= $char1 . 'X';
                $i++;
            } else {
                $prepared .= $char1;
                if ($i + 1 < $len) {
                    $prepared .= $char2;
                    $i += 2;
                } else {
                    $prepared .= 'X';
                    $i++;
                }
            }
        }
        if (strlen($prepared) % 2 != 0) {
            $prepared .= 'X';
        }
        return $prepared;
    }

    /* Encrypt pair */
    function encryptPair($matrix, $a, $b) {
        list($row1, $col1) = findPosition($matrix, $a);
        list($row2, $col2) = findPosition($matrix, $b);

        if ($row1 == $row2) {
            // same row
            $col1 = ($col1 + 1) % 5;
            $col2 = ($col2 + 1) % 5;
        } elseif ($col1 == $col2) {
            // same column
            $row1 = ($row1 + 1) % 5;
            $row2 = ($row2 + 1) % 5;
        } else {
            // rectangle swap columns
            $temp = $col1;
            $col1 = $col2;
            $col2 = $temp;
        }
        return $matrix[$row1][$col1] . $matrix[$row2][$col2];
    }

    /* Decrypt pair */
    function decryptPair($matrix, $a, $b) {
        list($row1, $col1) = findPosition($matrix, $a);
        list($row2, $col2) = findPosition($matrix, $b);

        if ($row1 == $row2) {
            // same row
            $col1 = ($col1 + 4) % 5; // -1 mod 5
            $col2 = ($col2 + 4) % 5;
        } elseif ($col1 == $col2) {
            // same column
            $row1 = ($row1 + 4) % 5; 
            $row2 = ($row2 + 4) % 5;
        } else {
            // rectangle swap columns
            $temp = $col1;
            $col1 = $col2;
            $col2 = $temp;
        }
        return $matrix[$row1][$col1] . $matrix[$row2][$col2];
    }

    /* Encrypt text */
    function playfairEncrypt($plaintext, $key) {
        $matrix = generateKeyMatrix($key);
        $prepared = prepareText($plaintext);
        $len = strlen($prepared);
        $result = '';
        $steps = [];

        for ($i = 0; $i < $len; $i += 2) {
            $a = $prepared[$i];
            $b = $prepared[$i + 1];
            $enc = encryptPair($matrix, $a, $b);
            $result .= $enc;
            $steps[] = "Encrypting pair '$a$b' -> '$enc'";
        }
        return [$result, $steps];
    }

    /* Decrypt text */
    function playfairDecrypt($ciphertext, $key) {
        $matrix = generateKeyMatrix($key);
        $len = strlen($ciphertext);
        $result = '';
        $steps = [];

        for ($i = 0; $i < $len; $i += 2) {
            $a = $ciphertext[$i];
            $b = $ciphertext[$i + 1];
            $dec = decryptPair($matrix, $a, $b);
            $result .= $dec;
            $steps[] = "Decrypting pair '$a$b' -> '$dec'";
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
                list($encryptedText, $steps) = playfairEncrypt($text, $key);
                echo "Encrypted Text: $encryptedText\n";
                echo "Steps:\n" . implode("\n", $steps);
            } elseif ($action === 'decrypt') {
                // Ensure even length for ciphertext (playfair cipher ciphertext always even length)
                $text = strtoupper(preg_replace('/[^A-Z]/', '', str_replace('J', 'I', $text)));
                if (strlen($text) % 2 !== 0) {
                    echo "Ciphertext length must be even for Playfair decryption.";
                } else {
                    list($decryptedText, $steps) = playfairDecrypt($text, $key);
                    echo "Decrypted Text: $decryptedText\n";
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
