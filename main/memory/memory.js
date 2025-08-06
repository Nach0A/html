// memory.js — Lógica de selección, temporizador y control de pares

let timerInterval;
let startTime;
let matches = 0;
let attempts = 0;
const totalPairs = 12;

// Referencias al DOM
const timerEl    = document.getElementById('timer');
const matchesEl  = document.getElementById('matches');
const attemptsEl = document.getElementById('intentos');    // ¡ID corregido!
const boardEl    = document.querySelector('.game-board');
const restartBtn = document.getElementById('restart-btn');
const winModal   = document.getElementById('win-modal');
const finalTimeEl = document.getElementById('final-time');
const playAgainBtn = document.getElementById('play-again-btn');

// Inicializa el juego
function initGame() {
  matches = 0; attempts = 0;
  matchesEl.textContent  = `Pares: ${matches}`;
  attemptsEl.textContent = `Intentos: ${attempts}`;
  resetTimer();
  startTimer();
  winModal.classList.add('hidden');

  firstCard = null; secondCard = null; lockBoard = false;

  const cards = generateDeck();
  boardEl.innerHTML = '';
  cards.forEach(data => boardEl.appendChild(createCardElement(data)));
}

// Genera 12 pares y mezcla
function generateDeck() {
  const deck = [];
  for (let i = 1; i <= totalPairs; i++) {
    deck.push({ value: i }, { value: i });
  }
  return shuffle(deck);
}

// Fisher–Yates shuffle
function shuffle(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
  return array;
}

// Crea la carta
function createCardElement({ value }) {
  const card = document.createElement('div');
  card.className = 'card';
  card.dataset.value = value;
  card.innerHTML = `
    <div class="card-inner">
      <div class="card-front"></div>
      <div class="card-back" style="background: url('./imagenes/cartas-diseño-con-el-logo/diseño-pagina-${value}.png') center/contain no-repeat;"></div>
    </div>`;
  card.addEventListener('click', onCardClick);
  return card;
}

// Selección de cartas
let firstCard = null, secondCard = null, lockBoard = false;
function onCardClick(e) {
  const clicked = e.currentTarget;
  if (lockBoard || clicked === firstCard || clicked.classList.contains('flipped')) return;
  clicked.classList.add('flipped');
  if (!firstCard) { firstCard = clicked; return; }
  secondCard = clicked; lockBoard = true;
  attempts++; attemptsEl.textContent = `Intentos: ${attempts}`;
  if (firstCard.dataset.value === secondCard.dataset.value) {
    matches++; matchesEl.textContent = `Pares: ${matches}`; disableMatchedCards();
    if (matches === totalPairs) showWinModal();
  } else unflipCards();
}

function disableMatchedCards() {
  firstCard.removeEventListener('click', onCardClick);
  secondCard.removeEventListener('click', onCardClick);
  resetTurn();
}
function unflipCards() {
  setTimeout(() => {
    firstCard.classList.remove('flipped');
    secondCard.classList.remove('flipped');
    resetTurn();
  }, 1000);
}
function resetTurn() { [firstCard, secondCard] = [null, null]; lockBoard = false; }

// Temporizador mm:ss
function startTimer() {
  startTime = Date.now();
  timerInterval = setInterval(() => {
    const elapsed = Date.now() - startTime;
    const m = String(Math.floor(elapsed / 60000)).padStart(2, '0');
    const s = String(Math.floor((elapsed % 60000) / 1000)).padStart(2, '0');
    timerEl.textContent = `Tiempo ${m}:${s}`;
  }, 500);
}
function resetTimer() { clearInterval(timerInterval); timerEl.textContent = '00:00'; }

// Modal de victoria
function showWinModal() {
  clearInterval(timerInterval);
  finalTimeEl.textContent = timerEl.textContent;
  const modalContent = document.querySelector('.modal-content');
  const prev = modalContent.querySelector('.attempts-info');
  if (prev) prev.remove();
  const p = document.createElement('p');
  p.className = 'attempts-info';
  p.textContent = `Intentos realizados: ${attempts}`;
  modalContent.insertBefore(p, playAgainBtn);
  winModal.classList.remove('hidden');
}

// Eventos
restartBtn.addEventListener('click', initGame);
playAgainBtn.addEventListener('click', initGame);
window.addEventListener('load', initGame);
