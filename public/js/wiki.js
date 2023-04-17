function append(self, div, type) {
	console.log(self)
	console.log(self.value);
	console.log(self.options[self.selectedIndex].text);

	const li = document.createElement('li');
	li.id = `${type}-${self.value}`;

	const input = document.createElement('input');
	input.type = 'hidden';
	input.name = `${type}[${self.value}]`;
	input.value = self.value;

	const p = document.createElement('p');
	p.innerHTML = self.options[self.selectedIndex].text;

	const button = document.createElement('button');
	button.type = 'button';
	button.innerHTML = '✗';
	button.addEventListener('click', () => remove(li));

	li.appendChild(input);
	li.appendChild(p);
	li.appendChild(button);

	div.appendChild(li);

	self.removeChild(self.options[self.selectedIndex]);
	self.value = "";
}

function remove(select, li) {
	const option = document.createElement('option');
	option.value = li.id.split('-')[1];
	option.innerHTML = li.children[1].innerHTML;
	select.appendChild(option);

	while (li.firstChild) {
		li.removeChild(li.firstChild);
	}
	li.remove();
}