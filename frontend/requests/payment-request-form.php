<!-- This code goes somewhere in the frontend (browser) -->

<?php 
if (!defined('ABSPATH')) {
    exit;
}
// get list of giving causes 
$giving_causes = require GIVING_PLUGIN_PATH . 'includes/config/giving-causes.php';

// endpoint
$endpoint = esc_url(rest_url('giving/v1/payment'));

// submit function
if (isset($_POST['giving_submit'])) {
  $amount = intval($_POST['amount'] ?? 0);
  $phone = sanitize_text_field($_POST['phone'] ?? 0);
  // validate
  if ($amount <= 0 || empty($phone)) {
      echo '<p style="color:red;">Invalid input.</p>';
      return;
  }
  echo '<p>Processing payment...</p>';
  // POST request
  $response = wp_remote_post($endpoint, [
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'body' => json_encode([
        'amount' => $amount,
        'phone'  => $phone,
    ]),
    'timeout' => 20,
  ]);

  if (is_wp_error($response)) {
    echo '<p style="color:red;">Request failed.</p>';
    return;
  }

  $body = wp_remote_retrieve_body($response);

  echo '<pre>';
  echo esc_html($body);
  echo '</pre>';
}
?>

<div id="giving-widget">

<style>
:root {
    --blue-primary-colour: #00adef;
    --blue-secondary-colour: #160d47;
    --white-primary-colour: #e6e6e6;
    --font-1: ;
    --font-2: ;
    --border-radius-1: ;
    --border-radius-2: ;
    --box-shadow: ;
}
#giving-widget * {
  box-sizing: border-box;
  font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}

#giving-widget {
  max-width: 520px;
  margin: auto;
  background: #ffffff;
  padding: 32px;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

#giving-widget h2 {
  text-align: center;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 24px;
  color: #1f2937;
}

#giving-widget label {
  font-weight: 600;
  display: block;
  margin-bottom: 6px;
  color: #374151;
}

#giving-widget input,
#giving-widget select {
  width: 100%;
  padding: 12px;
  border-radius: 8px;
  border: 1px solid #d1d5db;
  background: #f9fafb;
  font-size: 15px;
}

#giving-widget input:focus,
#giving-widget select:focus {
  outline: none;
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37,99,235,.25);
}

#giving-widget .group {
  margin-bottom: 18px;
}

#giving-widget .radio-option {
  display: flex;
  align-items: center;
  padding: 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  cursor: pointer;
  margin-bottom: 10px;
  background: #f9fafb;
}

#giving-widget .radio-option input {
  margin-right: 10px;
}

#giving-widget .hidden {
  display: none;
}

#giving-widget button {
  width: 100%;
  padding: 14px;
  border-radius: 10px;
  border: none;
  background: #2563eb;
  color: #ffffff;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
}

#giving-widget button:hover {
  background: #1d4ed8;
}
</style>

<!-- FORM LOGIC -->
<h2>Ways to Give</h2>

<form id="givingForm" method="post">

  <!-- CAUSES -->
  <div class="group">
    <label>Giving Cause</label>
    <select name="giving_cause" required>
      <option value="">Select a cause</option>
      <?php foreach ($giving_causes as $value => $label): ?>
        <option value="<?= htmlspecialchars($value) ?>">
            <?= htmlspecialchars($label) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="group">
    <label>Amount (Ksh.)</label>
    <input type="number" name="amount" min="1" placeholder="e.g. 500" required>
  </div>

  <div class="group">
    <label>Payment Method</label>

    <label class="radio-option">
      <input type="radio" name="payment_method" value="card" checked>
      Card Payment
    </label>

    <label class="radio-option">
      <input type="radio" name="payment_method" value="mpesa">
      M-Pesa
    </label>

    <label class="radio-option">
      <input type="radio" name="payment_method" value="paypal">
      PayPal
    </label>
  </div>

  <div id="cardFields" class="group hidden">
    <label>Card Number</label>
    <input type="text" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
  </div>

  <div id="mpesaFields" class="group hidden">
    <label>M-Pesa Phone (2547XXXXXXXX)</label>
    <input type="tel" name="mpesa_phone" pattern="^2547[0-9]{8}$">
  </div>

  <div id="paypalFields" class="group hidden">
    <label>PayPal Email</label>
    <input type="email" name="paypal_email">
  </div>

  <!-- Gravity Forms / WPCM can hook here -->
  <button type="submit" name="giving_submit">Give Now</button>

</form>

<script>
(function () {
  const radios = document.querySelectorAll('#givingForm input[name="payment_method"]');
  const card = document.getElementById('cardFields');
  const mpesa = document.getElementById('mpesaFields');
  const paypal = document.getElementById('paypalFields');

  function toggleFields() {
    card.classList.add('hidden');
    mpesa.classList.add('hidden');
    paypal.classList.add('hidden');

    const selected = document.querySelector('#givingForm input[name="payment_method"]:checked').value;
    if (selected === 'card') card.classList.remove('hidden');
    if (selected === 'mpesa') mpesa.classList.remove('hidden');
    if (selected === 'paypal') paypal.classList.remove('hidden');
  }

  radios.forEach(r => r.addEventListener('change', toggleFields));
  toggleFields();
})(); 
</script>

</div>
