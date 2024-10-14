document.addEventListener('DOMContentLoaded', function() {
    const productForm = document.getElementById('productForm');
    const productTable = document.getElementById('productTable').getElementsByTagName('tbody')[0];

    // Fonction pour charger les produits
    function loadProducts() {
        fetch('./app/api.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(products => {
                productTable.innerHTML = '';
                products.forEach(product => {
                    addProductToTable(product);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    // Fonction pour ajouter un produit au tableau
    function addProductToTable(product, prepend = false) {
        const row = productTable.insertRow(prepend ? 0 : -1);
        row.insertCell(0).textContent = product.product_aid;
        row.insertCell(1).textContent = product.product_name_en;
        row.insertCell(2).textContent = product.product_priceUSD;
        row.insertCell(3).textContent = product.product_stock_units;
        const actionsCell = row.insertCell(4);
        const editButton = document.createElement('button');
        editButton.textContent = 'Modifier';
        editButton.className = 'edit';
        editButton.onclick = () => editProduct(product);
        actionsCell.appendChild(editButton);
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Supprimer';
        deleteButton.className = 'delete';
        deleteButton.onclick = () => deleteProduct(product.product_aid);
        actionsCell.appendChild(deleteButton);
    }

    // Fonction pour valider le formulaire
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;

        if (name === '') {
            alert('Le nom du produit est requis');
            return false;
        }
        if (isNaN(price) || price <= 0) {
            alert('Le prix du produit doit être un nombre positif');
            return false;
        }
        if (isNaN(stock) || stock < 0) {
            alert('Le stock du produit doit être un nombre non négatif');
            return false;
        }
        return true;
    }

    // Fonction pour ajouter ou mettre à jour un produit
    productForm.onsubmit = function(event) {
        event.preventDefault();
        if (!validateForm()) {
            return;
        }

        const id = document.getElementById('productId').value;
        const name = document.getElementById('name').value;
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `./app/api.php/${id}` : './app/api.php';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_name_en: name, product_priceUSD: price, product_stock_units: stock })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            alert(data.message); // Afficher le message de confirmation
            if (method === 'POST') {
                addProductToTable(data.product, true); // Ajouter le nouveau produit en haut du tableau
            }
            productForm.reset();
            document.getElementById('productId').value = ''; // Effacer l'ID du produit après ajout
            if (method === 'PUT') {
                loadProducts(); // Recharger les produits après mise à jour
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    };

    // Fonction pour éditer un produit
    function editProduct(product) {
        document.getElementById('productId').value = product.product_aid || '';
        document.getElementById('name').value = product.product_name_en || '';
        document.getElementById('price').value = product.product_priceUSD || '';
        document.getElementById('stock').value = product.product_stock_units || '';
    }

    // Fonction pour supprimer un produit
    function deleteProduct(id) {
        fetch(`./app/api.php/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            alert(data.message); // Afficher le message de confirmation
            loadProducts();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    // Charger les produits au chargement de la page
    loadProducts();
});