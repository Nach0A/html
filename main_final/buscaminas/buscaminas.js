// Selección de elementos del DOM
const board = document.getElementById("board");
const mineCountDisplay = document.getElementById("mine-count");
const timerDisplay = document.getElementById("timer");
const resetButton = document.getElementById("reset-button");
const easyBtn = document.getElementById("easy-btn");
const mediumBtn = document.getElementById("medium-btn");
const hardBtn = document.getElementById("hard-btn");

// Elementos del modal
const winModal = document.getElementById("win-modal");
const modalTitle = document.getElementById("modal-title");
const finalTimeDisplay = document.getElementById("final-time");
const modalMinesDisplay = document.getElementById("modal-mines");
const playAgainButton = document.getElementById("play-again-button");

// Variables del juego
let rows, cols, mines;
let grid = [];
let revealedCells = 0;
let gameStarted = false;
let gameOver = false;
let timerInterval;
let timeElapsed = 0;
let flagsPlaced = 0;
let currentDifficulty = "easy";

// Configuraciones de dificultad
const difficulties = {
  easy: { rows: 8, cols: 8, mines: 10, cellSize: 40, fontSize: "1.5em" },
  medium: { rows: 12, cols: 20, mines: 40, cellSize: 28, fontSize: "1.2em" },
  hard: { rows: 12, cols: 40, mines: 99, cellSize: 28, fontSize: "1.2em" },
};

// Función para establecer la dificultad
function setDifficulty(difficulty) {
  currentDifficulty = difficulty;
  const config = difficulties[difficulty];
  rows = config.rows;
  cols = config.cols;
  mines = config.mines;

  document.documentElement.style.setProperty("--cell-size", `${config.cellSize}px`);
  document.documentElement.style.setProperty("--font-size", config.fontSize);
  board.style.width = "95%";
  board.style.maxWidth = "900px";
  board.style.gridTemplateColumns = `repeat(${cols}, 1fr)`;
  board.style.gridAutoRows = "1fr";

  easyBtn.classList.remove("active");
  mediumBtn.classList.remove("active");
  hardBtn.classList.remove("active");
  document.getElementById(`${difficulty}-btn`).classList.add("active");

  board.className = "";
  board.classList.add(difficulty);

  newGame();
}

// Crear el tablero
function createBoard() {
  board.innerHTML = "";
  grid = Array(rows).fill().map(() => Array(cols).fill(0));

  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      const cell = document.createElement("div");
      cell.className = "cell hidden";
      cell.dataset.row = r;
      cell.dataset.col = c;

      cell.style.display = "flex";
      cell.style.justifyContent = "center";
      cell.style.alignItems = "center";

      cell.addEventListener("click", handleCellClick);
      cell.addEventListener("contextmenu", handleCellRightClick);
      board.appendChild(cell);
    }
  }
}

// Colocar minas
function placeMines(initialRow, initialCol) {
  let minesPlaced = 0;
  while (minesPlaced < mines) {
    const r = Math.floor(Math.random() * rows);
    const c = Math.floor(Math.random() * cols);
    if (
      grid[r][c] !== "mine" &&
      !(Math.abs(r - initialRow) <= 1 && Math.abs(c - initialCol) <= 1)
    ) {
      grid[r][c] = "mine";
      minesPlaced++;
    }
  }
}

// Calcular minas adyacentes
function calculateAdjacentMines() {
  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      if (grid[r][c] !== "mine") {
        let count = 0;
        for (let dr = -1; dr <= 1; dr++) {
          for (let dc = -1; dc <= 1; dc++) {
            const nr = r + dr;
            const nc = c + dc;
            if (
              nr >= 0 &&
              nr < rows &&
              nc >= 0 &&
              nc < cols &&
              grid[nr][nc] === "mine"
            ) {
              count++;
            }
          }
        }
        grid[r][c] = count;
      }
    }
  }
}

// Iniciar el juego
function startGame(initialRow, initialCol) {
  gameStarted = true;
  placeMines(initialRow, initialCol);
  calculateAdjacentMines();
  startTimer();
}

// Clic izquierdo
function handleCellClick(event) {
  if (gameOver) return;
  const cell = event.target;
  const r = parseInt(cell.dataset.row);
  const c = parseInt(cell.dataset.col);

  if (!gameStarted) startGame(r, c);

  if (cell.classList.contains("revealed")) {
    if (cell.textContent !== "") {
      const adjacentMines = parseInt(cell.dataset.mines);
      if (isNaN(adjacentMines) || adjacentMines === 0) return;
      let adjacentFlags = 0;
      const neighbors = getNeighbors(r, c);
      for (const { nr, nc } of neighbors) {
        const neighborCell = board.querySelector(
          `.cell[data-row="${nr}"][data-col="${nc}"]`
        );
        if (neighborCell && neighborCell.classList.contains("flag")) {
          adjacentFlags++;
        }
      }
      if (adjacentFlags === adjacentMines) {
        for (const { nr, nc } of neighbors) {
          const neighborCell = board.querySelector(
            `.cell[data-row="${nr}"][data-col="${nc}"]`
          );
          if (
            neighborCell &&
            neighborCell.classList.contains("hidden") &&
            !neighborCell.classList.contains("flag")
          ) {
            revealCell(nr, nc);
          }
        }
      }
    }
    return;
  }

  if (cell.classList.contains("flag")) return;
  revealCell(r, c);
}

// Clic derecho (banderas)
function handleCellRightClick(event) {
  event.preventDefault();
  if (gameOver) return;

  const cell = event.target;
  if (cell.classList.contains("revealed")) return;

  if (cell.classList.contains("flag")) {
    cell.classList.remove("flag");
    cell.classList.add("question");
    cell.textContent = "?";
    flagsPlaced--;
  } else if (cell.classList.contains("question")) {
    cell.classList.remove("question");
    cell.textContent = "";
  } else if (flagsPlaced < mines) {
    cell.classList.add("flag");
    cell.textContent = "🚩";
    flagsPlaced++;
  }
  updateMineCountDisplay();
}

// Revelar celda
function revealCell(r, c) {
  if (r < 0 || r >= rows || c < 0 || c >= cols) return;

  const cell = board.querySelector(`.cell[data-row="${r}"][data-col="${c}"]`);
  if (!cell || cell.classList.contains("revealed") || cell.classList.contains("flag")) return;

  cell.classList.remove("hidden", "question");
  cell.classList.add("revealed");
  cell.textContent = "";
  revealedCells++;

  if (grid[r][c] === "mine") {
    cell.classList.add("mine", "exploded-mine");
    cell.textContent = "💣";
    endGame(false);
    return;
  } else if (grid[r][c] > 0) {
    cell.textContent = grid[r][c];
    cell.dataset.mines = grid[r][c];
  } else {
    for (let dr = -1; dr <= 1; dr++) {
      for (let dc = -1; dc <= 1; dc++) {
        if (dr !== 0 || dc !== 0) revealCell(r + dr, c + dc);
      }
    }
  }

  checkWin();
}

// Vecinos
function getNeighbors(r, c) {
  const neighbors = [];
  for (let dr = -1; dr <= 1; dr++) {
    for (let dc = -1; dc <= 1; dc++) {
      if (dr !== 0 || dc !== 0) {
        const nr = r + dr;
        const nc = c + dc;
        if (nr >= 0 && nr < rows && nc >= 0 && nc < cols) {
          neighbors.push({ nr, nc });
        }
      }
    }
  }
  return neighbors;
}

// Comprobar victoria
function checkWin() {
  if (revealedCells === rows * cols - mines) {
    endGame(true);
  }
}

// === Enviar puntaje y tiempo al servidor ===
// ==== saveScore con logging y envio de cookies ====
function saveScore(puntos, tiempo) {
  const idJuego = 2; // ID del Buscaminas
  const bodyStr = `puntos=${Math.round(puntos)}&tiempo=${tiempo}&intentos=0&id_juego=${idJuego}`;

  console.log("[saveScore] Enviando ->", {
    url: "../pagina-principal/guardar_puntaje.php",
    body: bodyStr
  });

  fetch("../pagina-principal/guardar_puntaje.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    credentials: "include", // <- MUY IMPORTANTE si usás $_SESSION en PHP
    body: bodyStr,
  })
    .then((res) => {
      console.log("[saveScore] HTTP STATUS:", res.status, res.statusText);
      return res.text();
    })
    .then((data) => {
      console.log("[saveScore] Respuesta del servidor:", data);
    })
    .catch((err) => {
      console.error("[saveScore] Error al guardar puntaje (fetch):", err);
      if (typeof alert === "function") alert("Error fetch: " + err);
    });
}


// Finalizar juego
function endGame(won) {
  gameOver = true;
  clearInterval(timerInterval);

  document.querySelectorAll(".cell").forEach((cell) => {
    cell.removeEventListener("click", handleCellClick);
    cell.removeEventListener("contextmenu", handleCellRightClick);
  });

  if (won) {
    modalTitle.textContent = "¡VICTORIA!";
    finalTimeDisplay.textContent = String(timeElapsed).padStart(3, "0");
    modalMinesDisplay.textContent = mines;
    winModal.classList.remove("hidden");

    let puntos = 0;
    if (currentDifficulty === "easy") puntos = 4000 / timeElapsed;
    else if (currentDifficulty === "medium") puntos = 16000 / timeElapsed;
    else if (currentDifficulty === "hard") puntos = 64000 / timeElapsed;

    saveScore(puntos, timeElapsed);

    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        const cell = board.querySelector(`.cell[data-row="${r}"][data-col="${c}"]`);
        if (grid[r][c] === "mine" && !cell.classList.contains("flag")) {
          cell.classList.remove("hidden", "question");
          cell.classList.add("revealed", "mine");
          cell.textContent = "💣";
        }
      }
    }
    mineCountDisplay.textContent = "000";
  } else {
    modalTitle.textContent = "💣 ¡PERDISTE!";
    finalTimeDisplay.textContent = String(timeElapsed).padStart(3, "0");
    modalMinesDisplay.textContent = mines;
    winModal.classList.remove("hidden");

    for (let r = 0; r < rows; r++) {
      for (let c = 0; c < cols; c++) {
        const cell = board.querySelector(`.cell[data-row="${r}"][data-col="${c}"]`);
        if (grid[r][c] === "mine") {
          cell.classList.remove("hidden", "question");
          cell.classList.add("revealed", "mine");
          cell.textContent = "💣";
        }
      }
    }
  }
}

// Temporizador
function startTimer() {
  timeElapsed = 0;
  timerDisplay.textContent = "000";
  timerInterval = setInterval(() => {
    timeElapsed++;
    if (timeElapsed > 999) {
      timeElapsed = 999;
      clearInterval(timerInterval);
    }
    timerDisplay.textContent = String(timeElapsed).padStart(3, "0");
  }, 1000);
}

// Actualizar contador
function updateMineCountDisplay() {
  const remainingMines = mines - flagsPlaced;
  mineCountDisplay.textContent = String(Math.max(0, remainingMines)).padStart(3, "0");
}

// Nuevo juego
function newGame() {
  clearInterval(timerInterval);
  gameStarted = false;
  gameOver = false;
  timeElapsed = 0;
  revealedCells = 0;
  flagsPlaced = 0;
  resetButton.textContent = "Reiniciar";
  timerDisplay.textContent = "000";
  winModal.classList.add("hidden");
  updateMineCountDisplay();
  createBoard();
}

// Event listeners
easyBtn.addEventListener("click", () => setDifficulty("easy"));
mediumBtn.addEventListener("click", () => setDifficulty("medium"));
hardBtn.addEventListener("click", () => setDifficulty("hard"));
resetButton.addEventListener("click", newGame);
playAgainButton.addEventListener("click", newGame);

// Inicializar
document.addEventListener("DOMContentLoaded", () => {
  setDifficulty(currentDifficulty);
});

