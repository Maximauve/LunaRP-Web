const email = document.getElementById('email');
const errMail = document.getElementById('err_mail');
const password = document.getElementById('password');
const passwordConfirm = document.getElementById('password_confirm');
const passwordErr = document.getElementById('err_password');
const submit = document.getElementById('submit');
const mailRegex = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
email.addEventListener('input', () => {
	if (mailRegex.test(email.value)) {
		submit.disabled = false;
		errMail.innerHTML = '';
	} else {
		submit.disabled = true;
		errMail.innerHTML = 'This email is not valid';
	}
});
passwordConfirm.addEventListener('input', () => {
	if ((password.value != "") && (password.value === passwordConfirm.value)) {
		submit.disabled = false;
		passwordErr.innerHTML = '';
	} else {
		submit.disabled = true;
		passwordErr.innerHTML = 'Passwords do not match';
	}
});