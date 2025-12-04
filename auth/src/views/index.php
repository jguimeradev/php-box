<?php include 'includes/header.php'; ?>

<body>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg topbar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-accent" href="#">Acme Identity</a>

            <div class="d-flex align-items-center gap-2">
                <a href="login" class="btn btn-outline-primary btn-sm">Log in</a>
                <a href="signup" class="btn btn-primary btn-sm">Sign up</a>
                <!--   <button class="btn btn-sm theme-toggle" id="themeToggle">Toggle Theme</button> -->
            </div>
        </div>
    </nav>

    <!-- Main shell: sidebar + content -->
    <div class="container-fluid" style="padding-top:80px;">
        <div class="app-shell">

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="brand">Acme Identity</div>
                <p class="muted small mb-3">Admin dashboard</p>

                <nav>
                    <a href="index" class="active">Users</a>
                    <a href="#">Groups</a>
                    <a href="#">Settings</a>
                    <a href="#">Logs</a>
                </nav>

                <hr>
                <div class="small muted">Theme</div>
                <div class="mt-2">
                    <button class="btn btn-sm theme-toggle w-100" id="sideThemeToggle">Toggle Theme</button>
                </div>
            </aside>

            <!-- Content -->
            <section class="content">

                <!-- Header card -->
                <div class="card-surface d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-0">Users</h4>
                        <p class="muted small mb-0">Manage application users — create, edit, and remove entries.</p>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" id="exportBtn">Export</button>
                        <button class="btn btn-primary" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#userModal">Add user</button>
                    </div>
                </div>

                <!-- Table card -->
                <div class="card-surface">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="usersTable">
                            <thead>
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Full name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created</th>
                                    <th style="width:180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be managed by the demo JS. Initial sample rows inserted by HTML for progressive enhancement. -->
                                <tr data-id="1">
                                    <td>1</td>
                                    <td>Jane Doe</td>
                                    <td>jane@example.com</td>
                                    <td>User</td>
                                    <td>2025-01-03</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary editBtn">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger deleteBtn">Delete</button>
                                    </td>
                                </tr>
                                <tr data-id="2">
                                    <td>2</td>
                                    <td>John Smith</td>
                                    <td>john.smith@example.com</td>
                                    <td>Admin</td>
                                    <td>2025-02-11</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary editBtn">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger deleteBtn">Delete</button>
                                    </td>
                                </tr>
                                <tr data-id="3">
                                    <td>3</td>
                                    <td>Ana Torres</td>
                                    <td>ana.torres@example.com</td>
                                    <td>User</td>
                                    <td>2025-02-20</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary editBtn">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger deleteBtn">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Small footer card -->
                <div class="card-surface small muted">
                    Showing <span id="rowCount">3</span> users — this page is a front-end demo. Integrate with your backend to persist changes.
                </div>

            </section>
        </div>
    </div>

    <!-- User Modal (Create / Edit) -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true" aria-labelledby="userModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="userForm" class="needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">Add user</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="userId" value="">
                        <div class="mb-3">
                            <label class="form-label">Full name</label>
                            <input id="userFullname" class="form-control" required>
                            <div class="invalid-feedback">Full name is required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input id="userEmail" type="email" class="form-control" required>
                            <div class="invalid-feedback">Valid email required.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select id="userRole" class="form-select" required>
                                <option value="">Select role</option>
                                <option>Admin</option>
                                <option>User</option>
                            </select>
                            <div class="invalid-feedback">Role is required.</div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="modalSaveBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-0">Delete this user?</p>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger btn-sm" id="confirmDeleteBtn">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>