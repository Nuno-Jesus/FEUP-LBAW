window.onload = function() {
    let paypal = document.getElementById("paypal-payment-form");
    let card = document.getElementById("card-payment-form");
    let transfer = document.getElementById("transfer-payment-form");
    hide(paypal);
    hide(card);
    hide(transfer);
    let selector = document.getElementById("payment-type");
    selector.addEventListener('change', update);
  }
  
  function update() {
    let selector = document.getElementById("payment-type");
    let paypal = document.getElementById("paypal-payment-form");
    let card = document.getElementById("card-payment-form");
    let transfer = document.getElementById("transfer-payment-form");
    hide(paypal);
    hide(card);
    hide(transfer);
    if(selector.value == "Paypal") {
      show(paypal);
    } else if(selector.value == "Card") {
      show(card);
    } else if(selector.value == "Transfer") {
      show(transfer);
    }
  }
  
  function hide(div) {
    div.disabled = true;
    div.style.display = "none";
  }
  
  function show(div) {
    div.disabled = false;
    div.style.display = "flex";
  }
  