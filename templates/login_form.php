<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
      <div class="logo">
        <img src="img/hyscaler-logo.svg" alt="logo" srcset="" />
      </div>
    </nav>
    <div class="entire-container login-grid">
        <div class="">
            <div class="login-container">
                <h2>Login</h2>
                <form action="process_login.php" method="POST">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required />
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required />
                    </div>
                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>
        </div>
        <div class="login-image">
            <img src="img/hero-image.webp" alt="Hero Image">
            <div class="chaos-dots-container"></div> 
        </div>
    </div>
    <div style="width:100%;height:400px;"></div>
    <?php 
    include('includes/footer.php'); 
    ?>
    <script>
        const chaosContainer = document.querySelector('.chaos-dots-container');
        const numChaosDots = 1000; 

        for (let i = 0; i < numChaosDots; i++) {
            const chaosDot = document.createElement('div');
            chaosDot.classList.add('chaos-dot');
            
            const randomAngle = Math.random() * (2 * Math.PI); 
            const randomRadius = Math.random() * 300; 

            const xPos = Math.cos(randomAngle) * randomRadius;
            const yPos = Math.sin(randomAngle) * randomRadius;

            chaosDot.style.setProperty('--x', `${xPos}px`);
            chaosDot.style.setProperty('--y', `${yPos}px`);

            chaosDot.style.animationDelay = `${Math.random() * 3}s`;
            
            chaosContainer.appendChild(chaosDot);
        }
    </script>
</body>
</html>
