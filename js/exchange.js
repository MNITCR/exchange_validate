// Get references to the elements
var transactionsInput = document.getElementById('transactions');
var exchangeInput = document.getElementById('Exchange_Blade');
var radioButtons = document.querySelectorAll('input[name="flexRadioDefault"]');
var mainBladeInput = document.getElementById('exchange-input').value;
//<?php echo $main_bland; ?>

// Add event listeners to the radio buttons and the transactions input
transactionsInput.addEventListener('input', calculateExchange);
radioButtons.forEach(function(radioButton) {
    radioButton.addEventListener('change', calculateExchange);
});

// Function to calculate the exchange value
function calculateExchange() {
    var transactions = parseFloat(transactionsInput.value);
    var selectedRadio = document.querySelector('input[name="flexRadioDefault"]:checked');

    if (selectedRadio) {
        var radioValue = parseFloat(selectedRadio.value);
        var exchangeValue = transactions * radioValue * 1000; // Convert GB to MB

        // Update the Exchange Blade input value
        exchangeInput.value = exchangeValue;
    } else {
        // If no radio button is selected, clear the Exchange Blade input
        exchangeInput.value = '';
    }
}

// Add event listener to the form submission
document.getElementById('exchange-form').addEventListener('submit', function(e) {
    e.preventDefault();

    var transactions = parseInt(transactionsInput.value);
    var selectedPlan = document.querySelector('input[name="flexRadioDefault"]:checked');

    if (isNaN(transactions) || transactions <= 0) {
        alert('Please enter a valid number of transactions.');
        return;
    }

    if (!selectedPlan) {
        alert('Please select an exchange plan.');
        return;
    }

    var planValue = parseInt(selectedPlan.value);
    var totalExchange = transactions * planValue;

    // Check if the total exchange exceeds the main blade value
    if (transactions > mainBladeInput) {
        alert('You do not have enough main blade for this exchange.');
        return;
    }
    else{
        alert('You have enough exchange');
        console.log(planValue);
        console.log(transactions);
    }

    // Update the Exchange_Blade input
    exchangeInput.value = totalExchange;
});
