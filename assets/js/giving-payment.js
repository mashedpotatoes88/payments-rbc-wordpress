(function () {
  const widget = document.getElementById('giving-widget');
  const form = document.getElementById('givingForm');
  const mpesa = document.getElementById('mpesaFields');
  const options = widget.querySelectorAll('.payment-option');
  const paymentMethodInput = document.getElementById('paymentMethod');

  function clearActive() {
    options.forEach(opt => opt.classList.remove('active'));
  }

  function getSelectedProvider() {
    return paymentMethodInput.value || null;
  }

  function toggleFieldsAndProvider() {
    mpesa.classList.add('hidden');

    const provider = getSelectedProvider();
    if (!provider) return;

    if (provider === 'mpesa') {
      mpesa.classList.remove('hidden');
    }
  }

  // Handle clicking payment options
    // remove paypal
  
  options.forEach(option => {
    provider = option.dataset.provider;
    if (provider === 'paypal') {
      option.classList.add('disabled');
      return;
    }
    option.addEventListener('click', () => {
      const provider = option.dataset.provider;

      if (!provider) return;

      clearActive();
      option.classList.add('active');

      paymentMethodInput.value = provider;
      toggleFieldsAndProvider();
    });
  });

  form.addEventListener('submit', async function (e) {
    const provider = getSelectedProvider();
    if (!provider) {
      e.preventDefault();
      alert('Please select a payment method.');
      return;
    }
    if (provider === 'mpesa') {
      e.preventDefault();

      const formData = new FormData(form);

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'Accept': 'application/json'
          }
        });

        const data = await res.json();

        if (data && data['service response'] && data['service response'].data) {
          alert('M-Pesa prompt sent to your phone. Please complete the payment.');
        } else {
          alert('Failed to initiate M-Pesa payment. Please try again.');
        }
      } catch (err) {
        console.error(err);
        alert('Network error. Please try again.');
      }
    }
    // else: card / PayPal continue with normal redirect
  });
})();
