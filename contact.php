<?php
require_once 'includes/config.php';

$page_title = 'Contact Us';
$extra_js = '<script src="' . BASE_URL . '/assets/js/contact.js" defer></script>';

require_once 'includes/templates/header.php';
?>

<header class="page-header">
    <h1>About & Contact</h1>
    <p>Discover our mission, meet our dedicated team, and find out how to get in touch with us.</p>
</header>

<!-- Section 1: About Us -->
<section class="page-section hidden">
    <div class="container">
        <div class="about-section-grid">
            <div class="about-content">
                <h2>Our Mission</h2>
                <p>To provide seamless and universal access to a comprehensive collection of academic resources, fostering an environment of learning, research, and innovation for all students and faculty of Uganda Martyrs University.</p>
                <h2>About The Library</h2>
                <p>Our library at the Lubaga Branch is a modern, quiet space designed for study and collaboration. It houses thousands of physical volumes, computer labs, and dedicated reading areas. This online portal is an extension of our commitment to making knowledge accessible anytime, anywhere.</p>
            </div>
            <div class="about-image">
                 <img src="<?php echo BASE_URL; ?>/assets/images/about_library.jpg" alt="University Library Interior">
            </div>
        </div>
    </div>
</section>

<!-- Section 2: Meet the Team -->
<section class="page-section hidden" style="background-color: var(--light-gray);">
    <div class="container">
        <h2 class="section-title">Meet The Team</h2>
        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="team-card">
                <div class="team-card-img">
                    <img src="<?php echo BASE_URL; ?>/assets/images/staff1.jpg" alt="Head Librarian">
                </div>
                <h4>Dr. Emily Nabatanzi</h4>
                <span class="role">Head Librarian</span>
                <p>Oversees all library operations and strategic acquisitions.</p>
            </div>
            <!-- Team Member 2 -->
            <div class="team-card">
                <div class="team-card-img">
                     <img src="<?php echo BASE_URL; ?>/assets/images/staff2.jpg" alt="IT Support Specialist">
                </div>
                <h4>Peter Okello</h4>
                <span class="role">IT Support Specialist</span>
                <p>Manages the digital library system and provides tech support.</p>
            </div>
            <!-- Team Member 3 -->
            <div class="team-card">
                 <div class="team-card-img">
                     <img src="<?php echo BASE_URL; ?>/assets/images/staff3.jpg" alt="Circulation Desk Chief">
                </div>
                <h4>Maria Nakato</h4>
                <span class="role">Circulation Desk Chief</span>
                <p>Manages book borrowing, returns, and user assistance.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: FAQ & Feedback Form -->
<section class="page-section hidden">
    <div class="container">
        <h2 class="section-title">Help & Feedback</h2>
        <div class="contact-faq-grid">
            <!-- FAQ Column -->
            <div class="faq-section">
                <h3>Frequently Asked Questions</h3>
                <div class="faq-item">
                    <button class="faq-question">How do I borrow a book?</button>
                    <div class="faq-answer">
                        <p>You must be logged in. Navigate to the book's detail page and click "Borrow". It will be added to your account dashboard with a due date.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">What is the loan period for books?</button>
                    <div class="faq-answer">
                        <p>The standard loan period for most books is 14 days. Overdue items may restrict further borrowing until they are returned.</p>
                    </div>
                </div>
                 <div class="faq-item">
                    <button class="faq-question">Can I access study materials offline?</button>
                    <div class="faq-answer">
                        <p>Yes. Materials like PDFs, Past Papers, and PowerPoint presentations are available for direct download. Click the "Download" button on the material's detail page.</p>
                    </div>
                </div>
                 <div class="faq-item">
                    <button class="faq-question">How do I reset my password?</button>
                    <div class="faq-answer">
                        <p>For password resets, please contact the library IT support at <a href="mailto:it.support.library@umu.ac.ug">it.support.library@umu.ac.ug</a> or visit the circulation desk in person.</p>
                    </div>
                </div>
            </div>
<!-- Feedback Form Column -->
<div class="contact-form-section">
    <h3>Send Us a Message</h3>
     <!-- The form action can be set to a PHP script later if you want to process emails -->
     <form action="#" method="post">
        <div class="form-group">
            <!-- Input comes BEFORE the label -->
            <input type="text" id="name" name="name" placeholder=" " required>
            <label for="name">Your Name</label>
        </div>
        <div class="form-group">
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">Your Email</label>
        </div>
        <div class="form-group">
            <textarea id="message" name="message" rows="4" placeholder=" " required></textarea>
            <label for="message">Your Message</label>
        </div>
        <button type="submit" class="btn btn-primary">Send Feedback</button>
    </form>
</div>
        </div>
    </div>
</section>

<!-- Section 4: Get In Touch (Map and Contact Details) -->
<section class="page-section hidden" style="background-color: var(--light-gray);">
    <div class="container">
         <h2 class="section-title">Visit or Connect With Us</h2>
        <div class="get-in-touch-grid">
            <div class="contact-info-box">
                <h3>Contact Information</h3>
                <div class="contact-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p><strong>Uganda Martyrs University - Rubaga Campus</strong><br>Rubaga Road, Kampala, Uganda</p>
                </div>
                 <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <p><strong>Phone Support</strong><br>+256 123 456 789</p>
                </div>
                 <div class="contact-info-item">
                    <i class="fas fa-envelope"></i>
                    <p><strong>General Inquiries</strong><br><a href="mailto:library@umu.ac.ug" style="color:#fff;">library@umu.ac.ug</a></p>
                </div>
                <hr style="border-color: rgba(255,255,255,0.2); margin: 30px 0;">
                <div class="contact-socials">
                    <a href="#" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="map-container">
                 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7571874245353!2d32.55591307496465!3d0.3060612996887079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177da3a693636281%3A0x6291a138b1a8d76a!2sUganda%20Martyrs%20University%20-%20Rubaga%20Campus!5e0!3m2!1sen!2sug!4v1695556789123!5m2!1sen!2sug" 
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/templates/footer.php'; ?>