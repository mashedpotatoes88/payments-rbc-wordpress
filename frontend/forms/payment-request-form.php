<!-- This code goes somewhere in the frontend (browser) -->

<?php 
if (!defined('ABSPATH')) {
    exit;
}
// get list of giving causes 
$giving_causes = require GIVING_PLUGIN_PATH . 'includes/config/giving-causes.php';
?>
<style>
:root {
    --blue-primary-colour: #00adef;
    --blue-secondary-colour: #160d47;
    --white-primary-colour: #e6e6e6;
    --gray-bg-colour: #f9fafb;
    --hover-colour: #9bd0e6;
    --font-1: ;
    --font-2: ;
    --border-radius-1: 8px;
    --border-radius-2: 16px;
    --box-shadow: ;
}

/* CUSTOM GLOBAL BOOTSTRAP */
#giving-widget .flex-custom {
  display: flex;
  align-items: center;
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
  margin-bottom: 22px;
}

#giving-widget .hidden {
  display: none;
}

#giving-widget button {
  width: 100%;
  padding: 14px;
  border-radius: 10px;
  border: none;
  background-color: var(--blue-secondary-colour);
  color: #ffffff;
  font-size: 18px;
  font-weight: 700;
  cursor: pointer;
}

#giving-widget button:hover {
  background: #1d4ed8;
}
/* ICONS AND IMAGES */
#giving-widget .provider-logo {
  max-width: 64px;
  max-height: 32px;
}
#giving-widget .btn-info {
  background-color: transparent;
}
/* FORM */
.givingForm {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}
/* Payment Options */
  /* Title of a Group */
  /* eg. 'Giving Cause', 'Amount' etc. */
#giving-widget .group .group-title {
  gap: 0.5rem;
}

  /* Under Payment Methods */
#giving-widget .payment-options > *{
  flex: 1;
}
#giving-widget .payment-options{
  gap: 2%;
}
#giving-widget .payment-option {
  /* size and spacing */
  padding: 12px;
  border: 1px solid #d1d5db;
  border-radius: var(--border-radius-1);
  justify-content: space-around;
  /*  */
  background: var(--gray-bg-colour);
  /* properties */
  cursor: pointer;
}

.text-payment-option{
  font-size: 13px;
  white-space: nowrap;
  margin: 0;
}

.text {
  font-size: 16px;
  margin: 0;
}

/* HOVER */
#giving-widget .payment-option:hover {
  border-color: var(--blue-secondary-colour);
}

#giving-widget .payment-option.active {
  border-color: var(--blue-secondary-colour);
}
</style>
<div id="giving-widget">
    <!-- FORM LOGIC -->
    <h2>Ways to Give</h2>
    <form id="givingForm" class="givingForm" method="POST" action="https://www.ridgewaysbaptistchurch.org/wp-json/giving/v1/payment">
    <input type="hidden" name="payment_method" id="paymentMethod">
    <!-- 1. CAUSES -->
    <div class="group">
        <label class="group-title flex-custom">
            <p class="text">Giving Cause</p>
            <img src="<?php echo GIVING_PLUGIN_URL; ?>frontend/forms/assets/icons/info.png" alt="more info" class="btn btn-info">
        </label>
        <select name="giving_cause" required>
        <option value="">Select a cause</option>
        <?php foreach ($giving_causes as $value => $label): ?>
            <option value="<?= htmlspecialchars($value) ?>">
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
        </select>
    </div>

        <!-- 2. AMOUNT -->
    <div class="group">
        <label class="group-title flex-custom">
            <p class="text">Amount (Ksh.)</p>
        </label>
        <input type="number" name="amount" min="1" placeholder="e.g. 500" required>
    </div>

        <!-- 3. PAYMENT METHOD -->
    <div class="group" value="">
        <label class="group-title flex-custom">
            <p class="text">Payment Method</p>
            <img src="<?php echo GIVING_PLUGIN_URL; ?>frontend/forms/assets/icons/info.png" alt="more info" class="btn btn-info">               
        </label>

        <div class="payment-options flex-custom">
            <label class="payment-option flex-custom" data-provider="dpo-pay">
                <img src="<?php echo GIVING_PLUGIN_URL; ?>frontend/forms/assets/icons/dpo-pay-logo.png" class="provider-logo">
                <p class="text-payment-option">Card</p>
            </label>
            
            <label class="payment-option flex-custom" data-provider="mpesa">
                <img src="<?php echo GIVING_PLUGIN_URL; ?>frontend/forms/assets/icons/mpesa-logo.png" class="provider-logo">
                <p class="text-payment-option">M-Pesa</p>
            </label>
            
            <label class="payment-option flex-custom" data-provider="paypal">
                <img src="<?php echo GIVING_PLUGIN_URL; ?>frontend/forms/assets/icons/paypal-monogram/PayPal-Monogram-FullColor-RGB.png" class="provider-logo">
                <p class="text-payment-option">Paypal</p>
            </label>
        </div>
    </div>

    <div id="mpesaFields" class="group hidden">
        <label>M-Pesa Phone (2547XXXXXXXX)</label>
        <input type="tel" name="mpesa_phone" pattern="^2547[0-9]{8}$">
    </div>

    <!-- Gravity Forms / WPCM can hook here -->
    <button type="submit" name="giving_submit">Give Now</button>

    </form>
</div>
</html>