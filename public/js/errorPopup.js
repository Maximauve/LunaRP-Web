const popUp = document.getElementById('error-popup') ?? "";
if (popUp !== "") {

	const popUpButton = document.getElementById('error-close');

	popUpButton.addEventListener('click', () => {
		while (popUp.firstChild) {
			popUp.removeChild(popUp.lastChild);
		}
		popUp.remove();
	});
}