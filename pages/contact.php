<?php
require_once __DIR__ . '/../includes/functions.php';

$formStatus = null;
$formMessage = '';
$contactValues = [
  'fullName' => '',
  'email' => '',
  'phone' => '',
  'purpose' => '',
  'message' => '',
];

$sendTo = 'info@thesaltship.com';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  $formType = trim((string) ($_POST['form_type'] ?? 'contact'));

  $safeLine = static function (string $value): string {
    return trim(str_replace(["\r", "\n"], ' ', $value));
  };

  $sendMail = static function (string $to, string $subject, string $body, string $replyTo = '') use ($safeLine): bool {
    $headers = [
      'MIME-Version: 1.0',
      'Content-Type: text/plain; charset=UTF-8',
      'From: The Saltship Website <noreply@thesaltship.com>',
    ];

    if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
      $headers[] = 'Reply-To: ' . $safeLine($replyTo);
    }

    return @mail($to, $safeLine($subject), $body, implode("\r\n", $headers));
  };

  if ($formType === 'home_quote') {
    $name = trim((string) ($_POST['fullName'] ?? ($_POST['name'] ?? '')));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $purpose = trim((string) ($_POST['purpose'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $purpose === '' || $message === '') {
      $formStatus = 'error';
      $formMessage = 'Please complete all required fields with a valid email address.';
    } else {
      $mailSubject = 'Website Home Quote Request - ' . $name;
      $mailBody = "New Home Quote Request\n\n"
        . "Name: {$name}\n"
        . "Email: {$email}\n"
        . "Phone: {$phone}\n"
        . "Purpose: {$purpose}\n"
        . "\n"
        . "Message:\n{$message}\n";

      if ($sendMail($sendTo, $mailSubject, $mailBody, $email)) {
        $formStatus = 'success';
        $formMessage = 'Your quote request has been sent successfully. Our team will contact you shortly.';
      } else {
        $formStatus = 'error';
        $formMessage = 'We could not send your quote request right now. Please try again in a moment.';
      }
    }
  } else {
    $contactValues['fullName'] = trim((string) ($_POST['fullName'] ?? ''));
    $contactValues['email'] = trim((string) ($_POST['email'] ?? ''));
    $contactValues['phone'] = trim((string) ($_POST['phone'] ?? ''));
    $contactValues['purpose'] = trim((string) ($_POST['purpose'] ?? ''));
    $contactValues['message'] = trim((string) ($_POST['message'] ?? ''));

    if (
      $contactValues['fullName'] === '' ||
      $contactValues['email'] === '' ||
      !filter_var($contactValues['email'], FILTER_VALIDATE_EMAIL) ||
      $contactValues['purpose'] === '' ||
      $contactValues['message'] === ''
    ) {
      $formStatus = 'error';
      $formMessage = 'Please complete all required fields with a valid email address.';
    } else {
      $mailSubject = 'Website Contact Form - ' . $contactValues['purpose'];
      $mailBody = "New Contact Form Submission\n\n"
        . "Name: {$contactValues['fullName']}\n"
        . "Email: {$contactValues['email']}\n"
        . "Phone: {$contactValues['phone']}\n"
        . "Purpose of Enquiry: {$contactValues['purpose']}\n\n"
        . "Message:\n{$contactValues['message']}\n";

      if ($sendMail($sendTo, $mailSubject, $mailBody, $contactValues['email'])) {
        $formStatus = 'success';
        $formMessage = 'Thanks. Your message has been sent successfully.';
        $contactValues = [
          'fullName' => '',
          'email' => '',
          'phone' => '',
          'purpose' => '',
          'message' => '',
        ];
      } else {
        $formStatus = 'error';
        $formMessage = 'We could not send your message right now. Please try again in a moment.';
      }
    }
  }
}

$pageTitle = 'Contact | The Saltship';
$pageDescription = 'Contact The Saltship for industrial and Himalayan salt inquiries, export quotes, and sample requests.';
$currentPage = 'contact';
$forceSolid = true;

$styles = [
  'assets/css/navbar.css',
  'assets/css/footer.css',
  'assets/css/pages/contact.css'
];

$scripts = [
  'assets/js/navbar.js',
  'assets/js/footer.js',
  'assets/js/contact.js'
];

include __DIR__ . '/../includes/head.php';
include __DIR__ . '/../includes/navbar.php';
?>

<main class="contact-page">
  <section class="contact-hero" data-contact-hero>
    <div class="contact-shell">
      <div class="contact-layout">
        <aside class="contact-intro reveal-on-scroll">
          <p class="contact-kicker">CONTACT US</p>
          <h1>Let's Start Your Saltship Order</h1>
          <p class="contact-lead">Share your requirement and our team will guide you with product options, MOQ, pricing, and shipping timelines.</p>

          <div class="contact-mini-cards">
            <a href="mailto:info@thesaltship.com" class="contact-mini-card">
              <span>Email Us</span>
              <strong>info@thesaltship.com</strong>
            </a>
            <a href="https://wa.me/923169396919" target="_blank" rel="noopener" class="contact-mini-card">
              <span>WhatsApp</span>
              <strong>+92 316 9396919</strong>
            </a>
          </div>
        </aside>

        <section class="contact-form-card reveal-on-scroll">
          <h2>Send Us a Message</h2>

          <?php if ($formStatus !== null): ?>
            <p class="contact-alert contact-alert--<?= h($formStatus) ?>"><?= h($formMessage) ?></p>
          <?php endif; ?>

          <form class="contact-form" id="contact-form" method="post" action="">
            <input type="hidden" name="form_type" value="contact">

            <div class="contact-grid-2">
              <label>
                <span>Full Name *</span>
                <input type="text" name="fullName" id="fullName" placeholder="Your full name" value="<?= h($contactValues['fullName']) ?>" required>
              </label>
              <label>
                <span>Email Address *</span>
                <input type="email" name="email" id="email" placeholder="you@example.com" value="<?= h($contactValues['email']) ?>" required>
              </label>
            </div>

            <div class="contact-grid-2">
              <label>
                <span>Phone Number</span>
                <input type="text" name="phone" id="phone" placeholder="+92 316 9396919" value="<?= h($contactValues['phone']) ?>">
              </label>
              <label>
                <span>Purpose of Enquiry *</span>
                <input type="text" name="purpose" id="purpose" placeholder="Describe your enquiry" value="<?= h($contactValues['purpose']) ?>" required>
              </label>
            </div>

            <label>
              <span>Message *</span>
              <textarea name="message" id="message" placeholder="Type your message here..." required><?= h($contactValues['message']) ?></textarea>
            </label>

            <button type="submit" class="contact-submit">Send Message</button>
          </form>
        </section>
      </div>
    </div>
  </section>

  <section class="contact-details" aria-label="Contact information">
    <div class="contact-shell">
      <div class="contact-details-head reveal-on-scroll">
        <p>Quick Contact Details</p>
        <h2>Reach The Saltship Team Your Way</h2>
      </div>

      <div class="contact-card-grid">
        <article class="contact-info-card reveal-on-scroll">
          <span class="contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m4 7 8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
          <p>Direct Contact</p>
          <h3>Email Us</h3>
          <a href="mailto:info@thesaltship.com">info@thesaltship.com</a>
        </article>

        <article class="contact-info-card reveal-on-scroll">
          <span class="contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M12 3.5a8.5 8.5 0 0 0-7.36 12.75L4 21l4.92-1.29A8.5 8.5 0 1 0 12 3.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9.2 9.4c.2-.4.4-.4.6-.4h.5c.2 0 .4 0 .5.4l.5 1.4c.1.2 0 .4-.1.6l-.3.4c.4.8 1 1.4 1.8 1.8l.4-.3c.2-.1.4-.2.6-.1l1.4.5c.3.1.4.3.4.5v.5c0 .2 0 .4-.4.6-.5.3-1.1.4-1.7.3-2.2-.4-4-2.2-4.4-4.4-.1-.6 0-1.2.3-1.7z" fill="currentColor"/></svg>
          </span>
          <p>Quick Chat</p>
          <h3>WhatsApp</h3>
          <a href="https://wa.me/923169396919" target="_blank" rel="noopener">+92 316 9396919</a>
        </article>

        <article class="contact-info-card reveal-on-scroll">
          <span class="contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5" fill="currentColor"/></svg>
          </span>
          <p>Main Office</p>
          <h3>Headquarters</h3>
          <span class="contact-static">Karachi, Pakistan</span>
        </article>

        <article class="contact-info-card reveal-on-scroll">
          <span class="contact-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M12 8v4l2.8 1.8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </span>
          <p>Support Window</p>
          <h3>Response Time</h3>
          <span class="contact-static">Within 24 hours</span>
        </article>
      </div>
    </div>
  </section>
</main>

<?php
include __DIR__ . '/../includes/footer.php';
include __DIR__ . '/../includes/foot.php';
?>
