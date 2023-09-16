// Get references to the elements
var transactionsInput = document.getElementById('transactions');
var exchangeInput = document.getElementById('Exchange_Blade');
var Exchange_Blade_hidden = document.getElementById('Exchange_Blade_hidden');
var radioButtons = document.querySelectorAll('input[name="flexRadioDefault"]');
var mainBladeInput = document.getElementById('exchange-input').value;
var exchangeInputSecond = document.getElementById('Exchange_Blade_second');

// Initialize Bootstrap tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});


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
        var Exchange_Value_hidden = radioValue * 1000;
        // Update the Exchange Blade input value
        exchangeInput.value = exchangeValue;
        Exchange_Blade_hidden.value = Exchange_Value_hidden;

        // Update the Exchange_Blade_second input
        exchangeInputSecond.value = exchangeValue;

        let ex = Exchange_Value_hidden; // Removed the assignment of exchangeInputSecond.value to ex
        console.log(ex);
    } else {
        // If no radio button is selected, clear the Exchange Blade input
        exchangeInput.value = '';
    }
}

// Add event listener to the form submission
document.getElementById('Exchange').addEventListener('click', function(e) {

    var transactions = parseInt(transactionsInput.value);
    var selectedPlan = document.querySelector('input[name="flexRadioDefault"]:checked');

    if (isNaN(transactions) || transactions <= 0) {
        alert('Please enter a valid number of transactions. transactions must be greater than 0');
        e.preventDefault();

        return;
    }

    if (!selectedPlan) {
        alert('Please select an exchange plan.');
    e.preventDefault();

        return;
    }

    var planValue = parseInt(selectedPlan.value);
    var totalExchange = transactions * planValue;

    // Check if the total exchange exceeds the main blade value
    if(transactions > mainBladeInput){
        alert('You do not have enough main blade for this exchange.');
        e.preventDefault();
        return;
    }

    // Update the Exchange_Blade input
    exchangeInput.value = totalExchange;
});
