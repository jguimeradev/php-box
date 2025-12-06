<?php include 'includes/header.php'; ?>

<body>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg topbar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-accent" href="index">Acme Identity</a>
            <div class="d-flex align-items-center gap-2">
                <a href="login" class="btn btn-outline-primary btn-sm">Log in</a>
                <!--   <button class="btn btn-sm theme-toggle" id="themeToggle">Toggle Theme</button> -->
            </div>
        </div>
    </nav>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <!-- Content -->
    <main class="container" style="padding-top:90px;">
        <section class="auth-center">

            <h3 class="mb-2">Create account</h3>
            <p class="muted small mb-4">Start your free account — all fields are client-side validated for the demo.</p>

            <form id="signupForm" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Full name</label>
                    <input id="name" class="form-control" type="text" placeholder="Jane Doe" />
                    <div class="invalid-feedback">Please provide your name.</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" type="email" placeholder="you@example.com" />
                    <div class="invalid-feedback">Please provide a valid email.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" class="form-control" type="password" minlength="8" placeholder="At least 8 characters" />
                    <div class="invalid-feedback">Password must be at least 8 characters.</div>
                </div>

                <div class="mb-3">
                    <label for="confirm" class="form-label">Confirm password</label>
                    <input id="confirm" class="form-control" type="password" minlength="8" placeholder="Repeat password" />
                    <div class="invalid-feedback" id="confirmFeedback">Passwords must match.</div>
                </div>

                <div class="mb-3 form-check">
                    <input id="agree" class="form-check-input" type="checkbox" />
                    <label class="form-check-label muted small" for="agree">I agree to the <a href="#">terms</a>.</label>
                    <div class="invalid-feedback">You must accept the terms.</div>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Create account</button>
                </div>

                <p class="text-center small muted mt-3">Already have an account? <a href="login">Sign in</a></p>
            </form>

        </section>
    </main>

    <?php include 'includes/footer.php'; ?>