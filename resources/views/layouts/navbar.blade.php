<script>
// Add active class to current page link
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link-effect');
    
    navLinks.forEach(link => {
        const linkUrl = link.getAttribute('href');
        if (linkUrl === currentUrl) {
            link.classList.add('active');
        }
    });
});
</script>