<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello World!</h1>
    <p>Selamat datang, <strong><?php echo htmlspecialchars($data['nama']); ?></strong>.</p> 
    
    <a href="index.php?url=auth/logout">Log Out</a>
</body>
</html>