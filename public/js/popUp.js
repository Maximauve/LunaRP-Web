const errPopUp = document.getElementById('error-popup') ?? "";
if (errPopUp !== "") {

	const errPopUpButton = document.getElementById('error-close');

	errPopUpButton.addEventListener('click', () => {
		while (errPopUp.firstChild) {
			errPopUp.removeChild(errPopUp.lastChild);
		}
		errPopUp.remove();
	});
}

const succPopUp = document.getElementById('success-popup') ?? "";
if (succPopUp !== "") {

	const succPopUpButton = document.getElementById('success-close');

	succPopUpButton.addEventListener('click', () => {
		while (succPopUp.firstChild) {
			succPopUp.removeChild(succPopUp.lastChild);
		}
		succPopUp.remove();
	});
}
