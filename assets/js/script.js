document.addEventListener('DOMContentLoaded', () => {
    // Fonction pour mettre à jour les compteurs
    function updateCounters(cartCount, wishlistCount) {
        const cartBadge = document.querySelector('.cart-counter');
        const wishlistBadge = document.querySelector('.wishlist-counter');
        if (cartBadge && cartCount !== null) {
            cartBadge.textContent = cartCount || 0;
        }
        if (wishlistBadge && wishlistCount !== null) {
            wishlistBadge.textContent = wishlistCount || 0;
        }
    }

    // Notification générique
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `cart-notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('show'), 10);
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Gestion de l’ajout au panier
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            if (!button.dataset.id) return;

            const productId = button.dataset.id;
            fetch(`add_to_cart.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${productId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message || 'Produit ajouté au panier !');
                        updateCounters(data.cartCount, null);
                    } else {
                        showNotification(data.message || 'Erreur lors de l’ajout au panier.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Cart Error:', error);
                    showNotification('Erreur réseau.', 'error');
                });
        });
    });

// Gestion de l’ajout/suppression de la wishlist
document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const productId = button.dataset.id;
        if (!productId) {
            showNotification('Produit non valide.', 'error');
            return;
        }

        const isInWishlist = button.classList.contains('active');
        const action = isInWishlist ? 'supprimer' : 'ajouter';

        fetch(`add_to_wishlist.php?action=${action}&id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('active');
                    updateCounters(null, data.wishlistCount);
                    showNotification(data.message);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur wishlist:', error);
                showNotification('Erreur réseau.', 'error');
            });
    });
});

function updateCounters(cartCount, wishlistCount) {
    if (wishlistCount !== null && wishlistCount !== undefined) {
        document.querySelectorAll('.wishlist-counter').forEach(counter => {
            counter.textContent = wishlistCount;
            counter.style.display = wishlistCount > 0 ? 'flex' : 'none';
        });
    }
}
    // Gestion des quantités et suppression dans le panier
    document.querySelectorAll('.panier-table').forEach(table => {
        // Quantité
        table.querySelectorAll('.quantity-controls button').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.closest('tr').dataset.id;
                const input = button.parentElement.querySelector('span');
                let quantity = parseInt(input.textContent);
                quantity = button.textContent === '+' ? quantity + 1 : Math.max(1, quantity - 1);

                fetch('update_panier.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${productId}&action=update&quantity=${quantity}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            input.textContent = quantity;
                            updateCounters(data.cartCount, null);
                            location.reload(); // Recharge pour mettre à jour le total
                        } else {
                            showNotification(data.message || 'Erreur lors de la mise à jour.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Update Cart Error:', error);
                        showNotification('Erreur réseau.', 'error');
                    });
            });
        });

        // Suppression
        table.querySelectorAll('.btn-supprimer').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.dataset.id;
                if (!productId) return;

                fetch('update_panier.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${productId}&action=supprimer`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.closest('tr').remove();
                            updateCounters(data.cartCount, null);
                            showNotification('Produit supprimé du panier !');
                            if (!table.querySelector('tr[data-id]')) {
                                location.reload();
                            }
                        } else {
                            showNotification(data.message || 'Erreur lors de la suppression.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Remove Cart Error:', error);
                        showNotification('Erreur réseau.', 'error');
                    });
            });
        });
    });

    // Animations au survol des produits
    document.querySelectorAll('.box').forEach(box => {
        box.addEventListener('mouseenter', () => {
            box.style.transform = 'scale(1.05)';
            box.style.transition = 'transform 0.3s ease';
        });
        box.addEventListener('mouseleave', () => {
            box.style.transform = 'scale(1)';
        });
    });

    // Menu responsive
    const navbar = document.querySelector('.navbar');
    const menuToggle = document.querySelector('#menu-bar');
    if (menuToggle && navbar) {
        menuToggle.addEventListener('click', () => {
            navbar.classList.toggle('active');
        });
    }

    // Défilement fluide
    document.querySelectorAll('a[href*="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href').split('#')[1];
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Parallaxe pour la section home
    document.addEventListener('scroll', () => {
        const heroImage = document.querySelector('.hero-image-container img');
        if (heroImage) {
            const scrollPosition = window.scrollY;
            heroImage.style.transform = `translateY(${scrollPosition * 0.15}px)`;
        }
    });

    // Styles des notifications
    const notificationStyle = document.createElement('style');
    notificationStyle.innerHTML = `
        .cart-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--orange);
            color: var(--white);
            padding: 1rem 2rem;
            border-radius: var(--radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 1000;
        }
        .cart-notification.show {
            opacity: 1;
            transform: translateY(0);
        }
        .cart-notification.error {
            background: var(--red);
        }
    `;
    document.head.appendChild(notificationStyle);
});
// Défilement fluide
document.querySelectorAll('a[href*="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
    e.preventDefault();
    const targetId = link.getAttribute('href').split('#')[1];
    if (targetId) {
    const target = document.getElementById(targetId);
    if (target) {
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
    // Redirection vers index.php si l’ancre n’existe pas sur la page actuelle
    window.location.href = link.getAttribute('href');
    }
    }
    });
   });