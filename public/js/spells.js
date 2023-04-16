const list = document.getElementById('sorts') ?? "";
if (list !== "") {
	list.addEventListener('click', () => {
		const spellList = document.getElementById('spell-list') ?? "";
        if (spellList !== "") {
            spellList.classList.remove('hidden');
        }
        const spellDescription = document.getElementById('spell-description') ?? "";
        if (spellDescription !== "") {
            spellDescription.classList.add('hidden');
        }
	});
}

const description = document.getElementById('description') ?? "";
if (description !== "") {
    description.addEventListener('click', () => {
		const spellList = document.getElementById('spell-list') ?? "";
        if (spellList !== "") {
            spellList.classList.add('hidden');
        }
        const spellDescription = document.getElementById('spell-description') ?? "";
        if (spellDescription !== "") {
            spellDescription.classList.remove('hidden');
        }
	});
}
