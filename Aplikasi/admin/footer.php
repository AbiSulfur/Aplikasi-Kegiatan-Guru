</main>

<!-- Modern footer -->
<footer class="text-center py-4" style="margin-left: var(--sidebar-width); background: white; border-top: 1px solid #e2e8f0;">
  <p class="text-muted mb-0">&copy; 2025 Aplikasi Kegiatan Guru. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Active navigation highlighting
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('href') === currentPath || 
        (currentPath.includes(link.getAttribute('href')) && link.getAttribute('href') !== '/')) {
      link.classList.add('active');
    }
  });
  
  // Search functionality placeholder
  const searchInput = document.querySelector('.search-box');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      // Add search functionality here
      console.log('Searching for:', this.value);
    });
  }
});
</script>
</body>
</html>
