        </main>
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('is-open');
            });

            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    sidebar.classList.remove('is-open');
                }
            });
        }
    </script>
</body>
</html>
