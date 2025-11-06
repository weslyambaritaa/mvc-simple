<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>.error { color: red; } .success { color: green; }</style>
</head>
<body>
    <h2>Registrasi</h2>
    <form id="registerForm">
        <div>
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
    
    <div id="message"></div>
    <br>
    <a href="index.php?url=auth/showLogin">Sudah punya akun? Login</a>

    <script>
        // Implementasi AJAX 
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah form submit biasa
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('message');

            // Menggunakan Fetch API (AJAX modern)
            fetch('index.php?url=auth/processRegister', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Menampilkan respon dari server
                messageDiv.textContent = data.message;
                if (data.success) {
                    messageDiv.className = 'success';
                    document.getElementById('registerForm').reset();
                } else {
                    messageDiv.className = 'error';
                }
            })
            .catch(error => {
                messageDiv.className = 'error';
                messageDiv.textContent = 'Terjadi error: ' + error;
            });
        });
    </script>
</body>
</html>