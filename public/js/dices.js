const throwButton = document.getElementById('throw');

const dice4 = document.getElementById('dice-4');
const dice6 = document.getElementById('dice-6');
const dice8 = document.getElementById('dice-8');
const dice10 = document.getElementById('dice-10');
const dice12 = document.getElementById('dice-12');
const dice20 = document.getElementById('dice-20');
const dice100 = document.getElementById('dice-100');
const diceArr = [
	dice4,
	dice6,
	dice8,
	dice10,
	dice12,
	dice20,
	dice100,
];

const mainDiv = document.getElementById('dices');

const drawer = document.getElementById('dice-drawer');

throwButton.addEventListener('click', () => {
	const mainDice = document.querySelector('.main-button');
	const nb = mainDice.id.split('-')[1];
	const result = Math.floor(Math.random() * nb) + 1;
	console.log(result);
});

diceArr.forEach((dice) => {
	dice.addEventListener('click', () => {
		if (dice.classList.contains('main-button')) {
			drawer.classList.toggle('dice-hidden');
		} else {
			const mainDice = document.querySelector('.main-button');
			mainDice.classList.remove('main-button');
			mainDiv.insertBefore(dice, drawer)
			// drawer.removeChild(dice)
			drawer.appendChild(mainDice)
			dice.classList.add('main-button');
			drawer.classList.add('dice-hidden');
			sortDrawer();
		}
	});
});

function sortDrawer() {
	const diceArr = Array.from(drawer.children);
	diceArr.sort((a, b) => {
		const aNb = a.id.split('-')[1];
		const bNb = b.id.split('-')[1];
		return aNb - bNb;
	}).reverse();
	diceArr.forEach((dice) => {
		drawer.appendChild(dice);
	});
}
