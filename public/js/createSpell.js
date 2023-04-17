document.getElementById("item").onchange = function () {
	console.log(this);
	const input = document.createElement('input');
	input.type = 'hidden';
	input.name = `item[${this.value.split('|')[0]}]`;
	input.value = this.value.split('|')[0];
	document.getElementById('createCharacter').appendChild(input);
	const items = document.getElementById('inventory-items');
	const div = document.createElement('div');
	div.classList.add('character-inventory-item');
	let item = items.appendChild(div);
	const img = document.createElement('img');
	if (this.value.split('|')[1] == '') {
		img.src = '/images/default.jpg';
	} else {
		img.src = this.value.split('|')[1];
	}
	item.appendChild(img);
}

document.getElementById("spell").onchange = function () {
	const input = document.createElement('input');
	input.name = `spell[${this.value.split('|')[0]}]`;
	input.type = 'hidden';
	input.value = this.value.split('|')[0];
	document.getElementById('createCharacter').appendChild(input);
	const spells = document.getElementById('spells-items');
	const div = document.createElement('div');
	div.classList.add('character-spell');
	spells.appendChild(div);
	const name = document.createElement('p');
	name.classList.add('character-spell-name');
	name.innerHTML = this.value.split('|')[1];
	div.appendChild(name);
	const level = document.createElement('p');
	level.classList.add('character-spell-level');
	level.innerHTML = this.value.split('|')[2];
	div.appendChild(level);
}
