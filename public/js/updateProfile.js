function imgUpload(e) {
	const image = document.createElement('img');
	image.src = URL.createObjectURL(e.target.files[0]);
	image.classList.add('img-preview');
	const preview = document.getElementById('img-holder');

	const fileName = e.target.files[0].name;
	const fileSpan = document.createElement('span');
	fileSpan.innerHTML = fileName;
	fileSpan.classList.add('file-name');

	const button = document.createElement('button');
	const i = document.createElement('i');
	i.classList.add('fa-regular', 'fa-trash-can');
	button.appendChild(i);
	button.classList.add('delete-img');
	button.addEventListener('click', () => deleteFile(preview))

	while (preview.firstChild) {
		preview.removeChild(preview.lastChild);
	}
	preview.style.zIndex = 3;
	preview.appendChild(fileSpan);
	preview.appendChild(image);
	preview.appendChild(button);
}

function deleteFile(preview) {
	console.log("delete");
	while (preview.firstChild) {
		preview.removeChild(preview.lastChild);
	}
	preview.style.zIndex = 1;
	const input = document.getElementById('avatar');
	input.value = "";
}

function editProfile() {
	const hiddens = document.querySelectorAll('.hidden');
	const inputs = document.querySelectorAll('input:disabled');
	hiddens.forEach((hidden) => hidden.classList.remove('hidden'));
	inputs.forEach((input) => input.disabled = false);

	const updateButton = document.getElementById('edit');
	updateButton.classList.add('hidden');
}

function verifyPassword() {
	const password = document.getElementById('password');
	const confirm = document.getElementById('confirm-password');
	const saveButton = document.getElementById('save');
	console.log("password: " + password.value);
	console.log("confirm: " + confirm.value);
	if ((password.value === confirm.value)) {
		saveButton.disabled = false;
		return
	}
	saveButton.disabled = true;
}