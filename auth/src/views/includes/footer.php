    <!-- Minimal scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const root = document.documentElement;
            const stored = localStorage.getItem('theme-preference');
            if (stored) root.dataset.theme = stored;
            document.getElementById('themeToggle').addEventListener('click', () => {
                const next = root.dataset.theme === 'dark' ? 'light' : 'dark';
                root.dataset.theme = next;
                localStorage.setItem('theme-preference', next);
            });
        })();
    </script>
    </body>

    </html>