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

  // SUBMIT FORM
  const form = document.getElementById('givingForm');

  form.addEventListener('submit', async function(e) {
    e.preventDefault();

    // conditional pre-set
        // 1. provider
    let provider = null;
    if (form.mpesa_phone && form.mpesa_phone.value) {
      provider = 'mpesa';
    }

    const data = {
      amount: form.amount.value,
      phone: form.mpesa_phone.value,
      provider: provider
    };

    try {
      const res = await fetch(GivingConfig.paymentUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const json = await res.json();
      console.log(json);
      alert("Payment initiated.");
    } catch (err) {
      console.error(err);
      alert("Payment failed.");
    }
  });

})();