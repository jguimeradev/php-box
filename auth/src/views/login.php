<?php include 'includes/header.php'; ?>

<body>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg topbar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-accent" href="index">Acme Identity</a>
            <div class="d-flex align-items-center gap-2">
                <a href="signup" class="btn btn-outline-primary btn-sm">Sign up</a>
                <!--        <button class="btn btn-sm theme-toggle" id="themeToggle">Toggle Theme</button> -->
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="container" style="padding-top:90px;">
        <section class="auth-center">

            <h3 class="mb-2">Log in</h3>
            <p class="muted small mb-4">Access your account using email and password.</p>

            <form id="loginForm" method="POST" action="/login">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" class="form-control" type="email" placeholder="you@example.com" />
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" name="password" class="form-control" type="password" minlength="6" placeholder="••••••••" />
                    <div class="invalid-feedback">Password (min 6 characters).</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" name="remember" type="checkbox" id="remember">
                        <label class="form-check-label muted small" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="small">Forgot password?</a>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Log in</button>
                </div>

                <p class="text-center small muted mt-3">Don't have an account? <a href="signup">Sign up</a></p>
            </form>

        </section>
    </main>
    <?php include 'includes/footer.php'; ?>