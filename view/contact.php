<div class="contact-hero">
    <div class="container text-center">
        <h1>Contact Us</h1>
        <p class="lead">We'd love to hear from you. Get in touch with our team.</p>
    </div>
</div>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <h2>Send us a Message</h2>
                <p class="mb-4">Have questions or suggestions? Fill out the form below and we'll get back to you as soon as possible.</p>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <p>Your message has been sent successfully! We'll respond to you shortly.</p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert-danger">
                        <p><?php echo htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endif; ?>

                <form action="/contact" method="POST" class="contact-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="contact-info">
                    <h3>Contact Information</h3>
                    <p>We're here to help with any questions about our services, feedback, or partnership opportunities.</p>

                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Our Office</h4>
                            <p>123 Gallery Street<br>San Francisco, CA 94107</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email Us</h4>
                            <p><a href="mailto:info@photogallery.com">info@photogallery.com</a></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h4>Call Us</h4>
                            <p><a href="tel:+14155552671">+1 (415) 555-2671</a></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h4>Business Hours</h4>
                            <p>Monday - Friday: 9AM - 5PM<br>Weekend: Closed</p>
                        </div>
                    </div>

                    <div class="social-links mt-4">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2>Frequently Asked Questions</h2>
                <p class="lead">Find quick answers to common questions</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="faq-item">
                    <h4>How do I create an account?</h4>
                    <p>You can create an account by clicking the "Sign Up" button in the top navigation and filling out the registration form with your details.</p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="faq-item">
                    <h4>Is there a free plan available?</h4>
                    <p>Yes, we offer a free basic plan that includes storage for up to 500 photos and limited gallery options.</p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="faq-item">
                    <h4>How can I share my galleries with friends?</h4>
                    <p>Once logged in, you can generate shareable links to your galleries or send direct invitations via email from your dashboard.</p>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="faq-item">
                    <h4>What file formats are supported?</h4>
                    <p>We support all major image formats including JPEG, PNG, GIF, and even RAW formats for premium users.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map-section">
    <div id="map" class="contact-map">
        <!-- Map will be loaded here via JavaScript -->
<!--        if i even decide to make that-->
        <img src="https://via.placeholder.com/1200x400?text=Interactive+Map" alt="Location Map" class="img-fluid w-100">
    </div>
</section>
