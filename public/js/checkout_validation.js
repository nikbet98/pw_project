document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('checkout_form');
    
    // funzioni di validazione
    const validateExpiryMonth = () => {
        const expiryMonth = document.getElementById('expiry_month');
        const month = parseInt(expiryMonth.value);
        const error = document.getElementById('expiry_month_error');

        if(isNaN(month) | month < 1 | month > 12){
            expiryMonth.classList.add('is_invalid');
            error.textContent = "Invalid month";
            error.style.display = "block";
            return false;
        } else {
            expiryMonth.classList.remove('is_invalid');
            error.style.display = "none";
            return true;
        }
    };

    // funzione per validare l'anno
    const validateExpiryYear = () => {
        const expiryYear = document.getElementById('expiry_year');
        const error = document.getElementById('expiry_year_error');
        const year = parseInt(expiryYear.value);
        const currentYear = new Date().getFullYear();


        if(isNaN(year) | year < currentYear | year > currentYear + 10){
            expiryYear.classList.add('is_invalid');
            error.textContent = "Invalid year";
            error.style.display = "block";
            return false;
        }else{
            expiryYear.classList.remove('is_invalid');
            error.style.display = "none";
            return true;
        };
    };

    const validateCVV = () => {
        const cvv = document.getElementById('cvv');
        const error = document.getElementById('cvv_error');
        const regex = /^\d{3,4}$/;

        if(!regex.test(cvv.value)){
            cvv.classList.add('is_invalid');
            error.textContent = "Invalid CVV";
            error.style.display = "block";
            return false;
        }else{
            cvv.classList.remove('is_invalid');
            error.style.display = "none";
            return true;
        };
    };

    const validateCardNumber = () => {
        const cardNumber = document.getElementById('credit_card_number');
        const error = document.getElementById('card_number_error');
        const regex = /^\d{16}$/;

        if(!regex.test(cardNumber.value)){
            cardNumber.classList.add('is_invalid');
            error.textContent = "Invalid card number";
            error.style.display = "block";
            return false;
        }else{
            cardNumber.classList.remove('is_invalid');
            error.style.display = "none";
            return true;
        };
    };

    const validateAddress = () => {
        const address = document.getElementById('shipping_address');
        const error = document.getElementById('shipping_address_error');

        if(address.value.length < 5 | address.value.length > 100 | address.value.trim === ''){
            address.classList.add('is_invalid');
            error.textContent = "Invalid address";
            error.style.display = "block";
            return false;
        }else{
            address.classList.remove('is_invalid');
            error.style.display = "none";
            return true;
        };
    };
 

    // event listener
    document.getElementById('expiry_month').addEventListener('input', validateExpiryMonth);
    document.getElementById('expiry_year').addEventListener('input', validateExpiryYear);
    document.getElementById('cvv').addEventListener('input', validateCVV);
    document.getElementById('credit_card_number').addEventListener('input', validateCardNumber);
    document.getElementById('shipping_address').addEventListener('input', validateAddress);



    form.addEventListener('submit', function (event) {
        if(!validateExpiryMonth() | !validateExpiryYear() | !validateCVV() | !validateCardNumber() | !validateAddress()){
            event.preventDefault();
        }
    });
});