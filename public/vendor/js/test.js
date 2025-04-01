
window.border_color_input_pass = false
window.back_color_input_pass = false

function unhidePassword() {

    const passwordFields = document.querySelectorAll('input.password');
    if(!window.border_color_input_pass){
        field = document.querySelector("input.password");
        window.border_color_input_pass = window.getComputedStyle(field).borderColor
        window.back_color_input_pass = window.getComputedStyle(field).backgroundColor
    }
    console.log(window.border_color)
    passwordFields.forEach(field => {
        if (field) {
            if(field.type === 'password'){
                field.type =  'text'; // Перемикаємо видимість пароля
                field.style.borderColor = 'green'; // Змінюємо колір рамки
                field.style.backgroundColor = 'lightyellow'; // Змінюємо фон
            }else{
                field.type = 'password'; // Перемикаємо видимість пароля
                field.style.borderColor = window.border_color_input_pass; // Змінюємо колір рамки
                field.style.backgroundColor = window.back_color_input_pass; // Змінюємо фон
            }
        }
    });
}
document.addEventListener("moonshine:init", () => {
    MoonShine.onCallback('myFunction', function(response, element, events, component) {
        if(response.status === 200) {
            component.$dispatch('toast', {type: 'primary', text: response.data.message})
        } else {
            component.$dispatch('toast', {type: 'error', text: 'Error'})
        }

        location.reload(); // Перезавантажуємо сторінку
    })
})