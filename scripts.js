// Funkce pro načítání receptů
function loadRecipes() {
    const recipeList = document.getElementById('recipe-list');

    fetch('get_recipes.php') // Tento PHP soubor bude vracet seznam receptů
        .then(response => response.json())
        .then(data => {
            recipeList.innerHTML = ''; // Vyprázdnit seznam
            data.forEach(recipe => {
                const div = document.createElement('div');
                div.classList.add('recipe');
                div.innerHTML = `<h3>${recipe.title}</h3><p>${recipe.description}</p><button onclick="viewRecipe(${recipe.id})">Zobrazit</button>`;
                recipeList.appendChild(div);
            });
        })
        .catch(error => console.log('Chyba při načítání receptů:', error));
}

// Funkce pro přidání receptu
function submitRecipe(event) {
    event.preventDefault();

    const form = document.getElementById('recipe-form');
    const formData = new FormData(form);

    fetch('add_recipe.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'Success') {
            loadRecipes(); // Načíst recepty po přidání
        } else {
            console.log('Chyba při přidávání receptu');
        }
    })
    .catch(error => console.log('Chyba při přidávání:', error));
}

// Načítání receptů při načtení stránky
window.onload = function() {
    loadRecipes();
};
