window.onload = function() {
  // Create a Stripe client.
  let stripe = Stripe('pk_test_jSmuHaH6ydWh7tUWzQRR4exS00UxPuO6eS') ;
  // Create an instance of Elements.  
  let elements = stripe.elements(); 
  let redirect = '/home'

  let cardholderName = document.getElementById('cardholder-name');
  let cardButton = document.getElementById('card-button');
  let clientSecret = cardButton.dataset.secret;



  // Custom styling can be passed to options when creating an Element .
  // (Note that this demo uses a wider set of styles than the guide b elow.)
  var style = { 
    base: { 
      color: '#32325d', 
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',  
      fontSmoothing: 'antialiased', 
      fontSize: '16px', 
      '::placeholder': {  
        color: '#aab7c4'  
      } 
    },  
    invalid: {  
      color: '#fa755a', 
      iconColor: '#fa755a'  
    } 
  };
// ************************* END Styling ************************* //
// ************************* END Styling ************************* //

  // Create an instance of the card Element.
  var card = elements.create('card', { style: style });

  // Add an instance of the card Element into the `card-element` <div>.
  card.mount('#card-element');

  // Handle real-time validation errors from the card Element.
  card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });


  cardButton.addEventListener('click', () => {
    stripe.handleCardPayment(
      clientSecret, card, {
        payment_method_data: {
          billing_details: {name: cardholderName.value}
        }
      }
    ).then(function(result) {
      // Quand on reçoit une réponse
      if(result.error){
        document.getElementById("errors").innerText = result.error;
      } else {
        document.location.href = redirect;
      }
    });
  });
}
