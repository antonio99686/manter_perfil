const sideMenu = document.querySelector("aside");
const menuBtn = document.getElementById("menu-btn");
const closeBtn = document.getElementById("close-btn");

const darkMode = document.querySelector(".dark-mode");

// Verifica se há uma preferência de tema armazenada
const isDarkMode = localStorage.getItem("darkMode") === "true";

// Aplica a classe dark-mode-variables se necessário
if (isDarkMode) {
  document.body.classList.add("dark-mode-variables");
  darkMode.querySelector("span:nth-child(1)").classList.add("active");
  darkMode.querySelector("span:nth-child(2)").classList.add("active");
}

menuBtn.addEventListener("click", () => {
  sideMenu.style.display = "block";
});

closeBtn.addEventListener("click", () => {
  sideMenu.style.display = "none";
});

darkMode.addEventListener("click", () => {
  // Alterna entre os modos claro e escuro
  document.body.classList.toggle("dark-mode-variables");
  darkMode.querySelector("span:nth-child(1)").classList.toggle("active");
  darkMode.querySelector("span:nth-child(2)").classList.toggle("active");

  // Salva a preferência de tema no localStorage
  const isDarkModeEnabled = document.body.classList.contains(
    "dark-mode-variables"
  );
  localStorage.setItem("darkMode", isDarkModeEnabled ? "true" : "false");
});

// Função para adicionar linhas de pedidos à tabela
function addOrderRows() {
  const tbody = document.querySelector("table tbody");

  Orders.forEach((order) => {
    const tr = document.createElement("tr");
    const trContent = `
            <td>${order.productName}</td>
            <td>${order.productNumber}</td>
            <td>${order.paymentStatus}</td>
            <td class="${
              order.status === "Declined"
                ? "danger"
                : order.status === "Pending"
                ? "warning"
                : "primary"
            }">${order.status}</td>
            <td class="primary">Details</td>
        `;
    tr.innerHTML = trContent;
    tbody.appendChild(tr);
  });
}

// Adiciona linhas de pedidos à tabela quando o DOM estiver pronto
document.addEventListener("DOMContentLoaded", addOrderRows);
