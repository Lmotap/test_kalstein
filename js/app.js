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
                console.log('Products:', products); // Log des produits reçus
                productTable.innerHTML = '';
                products.forEach(product => {
                    const row = productTable.insertRow();
                    row.insertCell(0).textContent = product.product_aid;
                    row.insertCell(1).textContent = product.product_name_en;
                    row.insertCell(2).textContent = product.product_priceUSD;
                    row.insertCell(3).textContent = product.product_stock_units;
                    const actionsCell = row.insertCell(4);
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Modifier';
                    editButton.onclick = () => editProduct(product);
                    actionsCell.appendChild(editButton);
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Supprimer';
                    deleteButton.onclick = () => deleteProduct(product.product_aid);
                    actionsCell.appendChild(deleteButton);
                });
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    // Fonction pour ajouter ou mettre à jour un produit
    productForm.onsubmit = function(event) {
        event.preventDefault();
        const id = document.getElementById('productId').value;
        const name = document.getElementById('name').value;
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `./app/api.php/${id}` : './app/api.php';

        console.log('Submitting product:', { id, name, price, stock }); // Log des données envoyées

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_name_en: name, product_priceUSD: price, product_stock_units: stock })
        })
        .then(response => {
            console.log('Response Status:', response.status); // Log du statut de la réponse
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response:', data); // Log de la réponse de succès
            productForm.reset();
            loadProducts();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    };

    // Fonction pour éditer un produit
    function editProduct(product) {
        console.log('Editing product:', product); // Log des données du produit à éditer
        document.getElementById('productId').value = product.product_aid || '';
        document.getElementById('name').value = product.product_name_en || '';
        document.getElementById('price').value = product.product_priceUSD || '';
        document.getElementById('stock').value = product.product_stock_units || '';
    }

    // Fonction pour supprimer un produit
    function deleteProduct(id) {
        console.log('Attempting to delete product with ID:', id); // Log de l'ID du produit à supprimer
        fetch(`./app/api.php/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            console.log('Delete Response Status:', response.status); // Log du statut de la réponse
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Utilisez response.json() pour gérer la réponse vide
        })
        .then(data => {
            console.log('Delete Success Response:', data); // Log de la réponse de succès

            // Vérifiez si la réponse est vide
            if (!data) {
                console.error('Received empty response');
                return;
            }

            // Optionnel: affichez le message de confirmation
            alert(data.message);

            loadProducts(); // Rechargez la liste des produits
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    }

    // Charger les produits au chargement de la page
    loadProducts();
});