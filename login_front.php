<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="theme.css">
</head>
<body>
    <div class="video-background">
        <video autoplay muted loop id="bg-video">
            <source src="assets/1620050-hd_1920_1080_25fps.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>
    </div>

    <div class="login-container">
        <h1>Login</h1>
        
        <!-- Login form -->
        <form action="login_back.php" method="POST">
            <label for="userRole">Select Role</label>
            <select id="userRole" name="userRole" required>
                <option value="" disabled selected>Select your role</option>
                <option value="admin">Admin</option>
                <option value="qa_officer">QA Officer</option>
            </select>

            <label for="userId">User ID</label>
            <input type="text" id="userId" name="userId" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
