    </main> <!-- End of main-content -->

    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-about">
                <h4>About UMU Library</h4>
                <p>Your gateway to knowledge and success. We provide access to a vast collection of books, study materials, and digital resources to support your academic journey at Uganda Martyrs University.</p>
            </div>
            <div class="footer-contact">
                <h4>Contact Us</h4>
                <p><i class="fas fa-map-marker-alt"></i> Rubaga Road, Kampala, Uganda</p>
                <p><i class="fas fa-phone"></i> +256 123 456 789</p>
                <p><i class="fas fa-envelope"></i> library@umu.ac.ug</p>
            </div>
            <div class="footer-qr">
                <h4>Quick Access</h4>
                <img src="<?php echo BASE_URL; ?>/assets/images/qr-code.png" alt="QR Code to Library Portal">
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Main JavaScript File -->
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
    <?php if (isset($extra_js)) echo $extra_js; // For page-specific JS ?>
</body>
</html>