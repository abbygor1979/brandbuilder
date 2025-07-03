import 'bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Theme switcher
    const themeSwitch = document.getElementById('themeSwitch');
    if (themeSwitch) {
        themeSwitch.addEventListener('change', function() {
            document.body.classList.toggle('bg-dark', this.value === 'dark');
            document.body.classList.toggle('text-white', this.value === 'dark');
            localStorage.setItem('theme', this.value);
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            themeSwitch.value = savedTheme;
            document.body.classList.toggle('bg-dark', savedTheme === 'dark');
            document.body.classList.toggle('text-white', savedTheme === 'dark');
        }
    }

    // Initialize selectpickers
    if (typeof $.fn.selectpicker !== 'undefined') {
        $('.selectpicker').selectpicker();
    }

    // Poll brand status
    const progressBar = document.querySelector('#generationProgress .progress-bar');
    if (progressBar) {
        const brandId = window.location.pathname.split('/').pop();
        setInterval(() => {
            fetch(`/api/brands/${brandId}/status`, {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(response => response.json())
            .then(data => {
                progressBar.style.width = `${data.progress}%`;
                progressBar.setAttribute('aria-valuenow', data.progress);
                progressBar.textContent = `${data.progress}%`;
                if (data.status === 'completed' || data.status === 'failed') {
                    location.reload();
                }
            });
        }, 5000);
    }
});