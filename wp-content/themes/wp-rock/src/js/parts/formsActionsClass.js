export class FormsActionsClass {
    constructor(popupInstance, validateField) {
        this.popupInstance = popupInstance;
        this.validateField = validateField; // function
    }

    async init() {
        this.checkFormFields();

        this.loginFormFetch();
        this.restorePasswordFormFetch();
        this.getAccessFormFetch();
        this.setNewPasswordFormFetch();
        this.buyProgrammFormFetch();
    }

    loginFormFetch() {
        const loginForm = document.querySelector('.js-login-form');
        if (!loginForm) return;

        loginForm &&
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                // @ts-ignore
                const res = await this.fetchToAction(loginForm, 'user_login_ajax');

                this.setDataToRespContainer(res, loginForm);
                if (res.success) {
                    window.location.reload();
                }
            });
    };

    checkFormFields() {
        const formInputs = document.querySelectorAll('input');
        if (!formInputs) return;
        let repeatPassword = '';

        const checkAllInputs = (target) => {
            let allInputsValid = true;

            const parentForm = target.closest('form');

            if (!parentForm) return;

            const allInnerInputs = parentForm.querySelectorAll('input');
            const formSubmit = parentForm.querySelector('input[type="submit"]');

            allInnerInputs.forEach((input) => {
                const inputContainer = input.closest('.js-inner-input-wrapper');

                if (
                    !input.name ||
                    !input.value ||
                    input.type === 'hidden' ||
                    input.name === 'password' ||
                    input.name === 'password-repeat'
                )
                    return;

                const isValid = this.validateField(input.name, input.value);

                if (isValid) {
                    input && input.classList.add('valid');
                    input && input.classList.remove('not-valid');
                    inputContainer && inputContainer.classList.add('valid');
                    inputContainer && inputContainer.classList.remove('not-valid');
                } else {
                    input && input.classList.add('not-valid');
                    input && input.classList.remove('valid');
                    inputContainer && inputContainer.classList.add('not-valid');
                    inputContainer && inputContainer.classList.remove('valid');
                    allInputsValid = false;
                }
            });
            if (formSubmit) {
                formSubmit.disabled = !allInputsValid;
            }
        };

        const checkPasswordMatch = (target) => {
            const parentForm = target.closest('form');
            if (!parentForm) return;

            if (target.name === 'password-repeat') {
                repeatPassword = target.value;

                const passwordInput = parentForm.querySelector(
                    'input[name="password"]'
                );
                if (!passwordInput || target.name === 'password') return;

                const passwordsMatch = passwordInput.value === repeatPassword;

                target.classList.toggle('valid', passwordsMatch);
                target.classList.toggle('not-valid', !passwordsMatch);

                const formSubmit = parentForm.querySelector('input[type="submit"]');
                formSubmit && (formSubmit.disabled = !passwordsMatch);
            }
        };

        formInputs &&
            formInputs.forEach((input) => {
                input.addEventListener('change', (e) => {
                    checkAllInputs(e.target);
                    checkPasswordMatch(e.target);
                });
            });
    };

    restorePasswordFormFetch() {
        const forgotPasswordForm = document.querySelector('.js-forgot-password-form');

        if (!forgotPasswordForm) return;

        forgotPasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await this.fetchToAction(forgotPasswordForm, 'forgot_password');

            if (!res.success) {
                this.setDataToRespContainer(res, forgotPasswordForm);
            } else {
                this.popupInstance.openOnePopup('#forgot-password-popup');
            }
        });
    };

    getAccessFormFetch() {
        const getAccessForm = document.querySelector('.js-get-access-form');

        if (!getAccessForm) return;

        // Get access to programm: registering user and buying programm
        getAccessForm &&
            getAccessForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                //Regsiter and login user
                const res = await this.fetchToAction(getAccessForm, 'register_login_user');
                if (res.success) {
                    //FETCH TO MONO PAY SYSTEM
                    const payRes = await this.fetchToAction(getAccessForm, 'create_payment_action');
                    if (payRes.success) {
                        window.location.href = payRes.data.pageUrl;
                    }
                } else {
                    this.setDataToRespContainer(res, getAccessForm);
                }

            });
    };

    async fetchToAction(form, actionName) {
        const formData = new FormData(form);
        const resJSON = await fetch(`${var_from_php.ajax_url}?action=${actionName}`, {
            method: 'POST',
            body: formData,
        }
        );

        const res = await resJSON.json();
        return res;
    }

    setNewPasswordFormFetch() {
        const newPasswordForm = document.querySelector('.js-set-new-password-form');

        if (!newPasswordForm) return;

        newPasswordForm &&
            newPasswordForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const res = await this.fetchToAction(newPasswordForm, 'set_new_password');

                this.setDataToRespContainer(res, newPasswordForm);

            });
    };

    buyProgrammFormFetch() {
        const buyProgrammForm = document.querySelector('.js-buy-programm-form');

        if (!buyProgrammForm) return;

        // Buy programm by registered user
        buyProgrammForm &&
            buyProgrammForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                //FETCH TO MONO PAY SYSTEM ( Create pay account)
                const res = await this.fetchToAction(buyProgrammForm, 'create_payment_action');

                if (res.success) {
                    //If success return to pay page
                    window.location.href = res.data.pageUrl;
                }
            });
    }

    setDataToRespContainer(res, parentForm) {
        if (!parentForm || !res) return;

        const respContainer = parentForm.querySelector('.js-response-container');
        if (!respContainer) return;

        const additionalClass = res.success ? 'success' : 'error';
        const paragrahp = document.createElement('p');
        paragrahp.classList.add(additionalClass);
        paragrahp.innerText = res.data;
        respContainer.innerHTML = '';
        respContainer.appendChild(paragrahp);
    }
}
