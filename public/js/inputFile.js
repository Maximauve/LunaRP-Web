function imgUpload(e) {
	const image = document.createElement('img');
	image.src = URL.createObjectURL(e.target.files[0]);
	const preview = document.getElementById('img-holder');

	const label = document.getElementById('avatar-label');
	label.innerHTML = "Changer d'image";

	const fileName = e.target.files[0].name;
	const fileSpan = document.createElement('span');
	fileSpan.innerHTML = fileName;
	fileSpan.classList.add('file-name');

	const button = document.createElement('button');
	const i = document.createElement('i');
	i.classList.add('fa-regular', 'fa-trash-can');
	button.appendChild(i);
	button.classList.add('delete-img');
	button.addEventListener('click', () => deleteFile(preview, label))

	while (preview.firstChild) {
		preview.removeChild(preview.lastChild);
	}
	preview.appendChild(fileSpan);
	preview.appendChild(image);
	preview.appendChild(button);
}

function deleteFile(preview, label) {
	console.log("delete");
	while (preview.firstChild) {
		preview.removeChild(preview.lastChild);
	}
	const input = document.getElementById('avatar');
	input.value = "";

	label.innerHTML = "Choisir une image";
}