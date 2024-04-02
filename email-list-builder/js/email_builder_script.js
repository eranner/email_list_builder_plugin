const email_list_form = document.getElementById('email_list_form')
const firstInput = document.getElementById('first_input')
const secondInput = document.getElementById('second_input')
const email_input = document.querySelector('input[name="email"]');
const name_input = document.querySelector('input[name="name"]');
const address_input = document.querySelector('input[name="address"]');
const city_input = document.querySelector('input[name="city"]');
const state_input = document.querySelector('input[name="state"]');
const zip_input = document.querySelector('input[name="zip"]');
const phone_input = document.querySelector('input[name="phone"]');
let mainPageForm = document.getElementById('main-page-form')
let thankYouMessage = document.createElement('p')

// let clientTagline = document.querySelector('#email_builder_tagline')
// let emailHeader = document.getElementById('email_builder_header')
const email_list_builder_button = document.getElementById('email_list_builder_button')
// const errorMessage = document.getElementById('email_list_error_message')
if(email_list_builder_button){
email_list_builder_button.addEventListener('click', (e, email_list_form) => {
    e.preventDefault();
    const name = name_input.value;
    const email = email_input.value;
    const address = address_input.value;
    const city = city_input.value;
    const state = state_input.value;
    const zip = zip_input.value;
    const phone = phone_input.value;

    if (name_input.value == '' || email_input.value == ''){
        errorMessage.style.opacity = 1
        setTimeout(()=> {
            errorMessage.style.opacity = 0
        }, 3000)
        console.log('nope')

        return;
    }

    let formData = new FormData(email_list_form);
    formData.append('name', name);
    formData.append('email', email);
    formData.append('address', address);
    formData.append('city', city);
    formData.append('state', state);
    formData.append('zip', zip);
    formData.append('phone', phone);
    fetch(emailBuilderData.plugin_path, {
        method: 'POST', 
        body: formData
    })  .then(console.log(formData))
        .then(response => response.json())
        .then(data => console.log(data))
        .then(email_input.value = '')
        .then(name_input.value = '')
        .then(address_input.value = '')
        .then(city_input.value = '')
        .then(state_input.value = '')
        .then(zip_input.value = '')
        .then(phone_input.value = '')
        .then((email_list_form)=> {
            email_list_builder_button.textContent = 'Thank you!'
            email_list_builder_button.disabled = true;
            email_list_builder_button.style.display = 'none'
            thankYouMessage.innerHTML = 'A member of our team will be in touch soon.'
            thankYouMessage.style.textAlign = 'center'
            mainPageForm.appendChild(thankYouMessage)
        })
        // .then(()=> {
        //     firstInput.style.display = 'none' 
        //     secondInput.style.display = 'none'
        //     email_list_builder_button.style.display = 'none'
        // })
        .catch(error => console.error('Error:', error));

})

// window.addEventListener('resize', () => {
//     if(clientTagline && window.innerWidth < 992 ){
//         clientTagline.style.fontSize = '2rem'
//         emailHeader.style.fontSize = '3rem'
//     }
// })
}