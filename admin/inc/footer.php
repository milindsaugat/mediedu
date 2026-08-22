    </div> <!-- /.admin-content -->
  </div> <!-- /.admin-main -->
</div> <!-- /.admin-layout -->

<script>
// Sidebar Mobile Toggle
const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
const adminSidebar = document.getElementById('adminSidebar');
const sidebarBackdrop = document.getElementById('sidebarBackdrop');

function openSidebar() {
  if (adminSidebar && sidebarBackdrop) {
    adminSidebar.classList.add('active');
    sidebarBackdrop.classList.add('active');
  }
}

function closeSidebar() {
  if (adminSidebar && sidebarBackdrop) {
    adminSidebar.classList.remove('active');
    sidebarBackdrop.classList.remove('active');
  }
}

if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', openSidebar);
if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

// Auto Dismiss Alerts after 4 seconds
setTimeout(() => {
  document.querySelectorAll('.alert').forEach(alert => {
    alert.style.transition = 'opacity 0.5s ease';
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 500);
  });
}, 4000);
</script>
</body>
</html>
