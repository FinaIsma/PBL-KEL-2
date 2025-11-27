// Article Detail Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Back button functionality
    const backBtn = document.querySelector('.back-btn');
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            window.history.back();
        });
    }

    // Download button functionality
    const downloadBtn = document.querySelector('.btn-download');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // Implementasi download PDF
            console.log('Download PDF...');
            // window.location.href = 'path/to/pdf/file.pdf';
        });
    }
});