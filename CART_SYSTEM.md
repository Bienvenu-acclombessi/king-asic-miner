# Système de Panier - King ASIC Miner

## Vue d'ensemble

Ce système de panier professionnel gère:
- ✅ Ajout au panier avec et sans connexion (session/database)
- ✅ Gestion des options de produits (required/optional)
- ✅ Gestion des variations de produits
- ✅ Incrémentation automatique si produit existe déjà
- ✅ Quantités personnalisées
- ✅ Modificateurs de prix sur les options
- ✅ Interface utilisateur réactive avec notifications

## Architecture

### Backend (Laravel)

#### Models
- **Cart** (`App\Models\Orders\Cart`) - Le panier principal
- **CartLine** (`App\Models\Orders\CartLine`) - Ligne de panier (produit + quantité)
- **CartLineOptionValue** (`App\Models\Orders\CartLineOptionValue`) - Options sélectionnées pour une ligne

#### Controller
- **CartController** (`App\Http\Controllers\Public\CartController`)
  - `get()` - Récupère le panier actuel
  - `add()` - Ajoute un produit au panier
  - `update()` - Met à jour la quantité
  - `remove()` - Retire un produit
  - `clear()` - Vide le panier
  - `checkProduct()` - Vérifie si un produit peut être ajouté directement

#### Routes (`routes/public.php`)
```php
Route::get('/cart/get', [CartController::class, 'get']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::put('/cart/update/{lineId}', [CartController::class, 'update']);
Route::delete('/cart/remove/{lineId}', [CartController::class, 'remove']);
Route::delete('/cart/clear', [CartController::class, 'clear']);
Route::get('/cart/check-product/{productId}', [CartController::class, 'checkProduct']);
```

### Frontend (JavaScript)

#### Fichiers
1. **cart.js** (`public/client/js/cart.js`) - Gestionnaire principal du panier
2. **product-detail-cart.js** (`public/client/js/product-detail-cart.js`) - Gestion des options sur la page détail

#### Classes JavaScript

##### CartManager
```javascript
// Instance globale accessible via window.cartManager
window.cartManager.addToCart(data)
window.cartManager.updateQuantity(lineId, quantity)
window.cartManager.removeItem(lineId)
window.cartManager.clearCart()
window.cartManager.getCart()
```

##### ProductDetailCart
```javascript
// Pour les pages de détail avec options
window.productDetailCart.addToCart()
window.productDetailCart.getSelectedOptions()
window.productDetailCart.reset()
```

## Logique d'ajout au panier

### Depuis les cartes produits

1. L'utilisateur clique sur "Add to cart"
2. Le JavaScript appelle `/cart/check-product/{productId}`
3. **Si le produit n'a PAS d'options required:**
   - Ajout direct au panier
   - Notification de succès
   - Mise à jour du badge/total
4. **Si le produit a des options required:**
   - Redirection vers la page de détail
   - Notification "Ce produit nécessite des options"

### Depuis la page de détail

1. L'utilisateur sélectionne les options
2. Le bouton "Add to cart" est activé quand toutes les options required sont sélectionnées
3. Au clic, envoi à `/cart/add` avec:
   ```json
   {
     "product_id": 123,
     "variant_id": 456, // optionnel
     "quantity": 2,
     "options": [
       {
         "option_id": 1,
         "value_id": 10
       },
       {
         "option_id": 2,
         "custom_value": "Texte personnalisé"
       }
     ]
   }
   ```

## Gestion des variations

### Qu'est-ce qu'une variation ?

Une variation est une combinaison spécifique d'options qui:
- Affecte le prix (`affects_price = true`)
- Affecte le stock (`affects_stock = true`)

### Exemples

**Produit: T-Shirt**
- Options: Couleur (Rouge, Bleu), Taille (S, M, L)
- Variations:
  - Rouge + S → Variant ID 1 (Prix: $20, Stock: 10)
  - Rouge + M → Variant ID 2 (Prix: $20, Stock: 5)
  - Bleu + S → Variant ID 3 (Prix: $22, Stock: 8)
  - etc.

**Produit: ASIC Miner**
- Pas de variations car pas d'options qui affectent prix/stock
- Ajout direct au panier sans variant_id

### Détection automatique des variations

Le backend vérifie automatiquement:
1. Si `variant_id` est fourni → Utilise la variation
2. Sinon → Utilise le produit parent

## Gestion des produits existants

### Incrémentation automatique

Le système vérifie si un produit avec **exactement les mêmes options** existe déjà:
- **Oui** → Incrémente la quantité
- **Non** → Crée une nouvelle ligne

### Exemple

```
Panier actuel:
- Produit A (Couleur: Rouge, Taille: M) x1

Ajout de:
- Produit A (Couleur: Rouge, Taille: M) x2

Résultat:
- Produit A (Couleur: Rouge, Taille: M) x3 ✅

---

Ajout de:
- Produit A (Couleur: Bleu, Taille: M) x1

Résultat:
- Produit A (Couleur: Rouge, Taille: M) x3
- Produit A (Couleur: Bleu, Taille: M) x1 ✅ (nouvelle ligne)
```

## Gestion hors connexion vs avec connexion

### Utilisateur non connecté (Guest)
- Panier stocké en **session** (cart_id dans session)
- Panier créé automatiquement à la première action
- Persiste tant que la session est active

### Utilisateur connecté
- Panier associé au **user_id**
- Stocké en base de données
- Persiste entre les sessions
- Fusion possible avec panier guest lors de la connexion (à implémenter)

## Modificateurs de prix

Les options peuvent avoir des modificateurs de prix:

### Types de modificateurs

1. **Fixed** (montant fixe en cents)
   ```
   Option: "Garantie étendue" → +$50.00
   price_modifier: 5000
   price_type: 'fixed'
   ```

2. **Percentage** (pourcentage en basis points)
   ```
   Option: "Personnalisation" → +10%
   price_modifier: 1000 (= 10%)
   price_type: 'percentage'
   ```

### Calcul du prix final

```
Prix de base: $1000
Option 1 (Garantie): +$50 (fixed)
Option 2 (Personnalisation): +10% (percentage sur prix de base)

Prix final = $1000 + $50 + ($1000 × 0.10) = $1,150
```

## Interface utilisateur

### Badge de compteur

```html
<span class="cart-count">3</span>
```
- Affiche le nombre total d'articles
- Mis à jour automatiquement
- Masqué si panier vide

### Total du panier

```html
<span class="cart-total">$1,250.00</span>
```
- Affiche le montant total
- Formaté automatiquement

### Notifications (Toasts)

Les notifications Bootstrap sont utilisées pour:
- Succès d'ajout au panier
- Erreurs
- Informations (redirection vers page détail)

## Événements JavaScript

Le système émet des événements personnalisés:

```javascript
// Écouter les mises à jour du panier
window.addEventListener('cart:updated', (event) => {
  console.log('Cart updated:', event.detail);
  // event.detail contient les données du panier
});

// Écouter les mises à jour de l'UI
window.addEventListener('cart:ui-updated', (event) => {
  console.log('Cart UI updated:', event.detail);
});
```

## Intégration dans les vues

### Layout principal

Le fichier `resources/views/client/components/app.blade.php` inclut:
- CSRF token dans le `<head>`
- Script `cart.js` avant `</body>`
- Conteneur de toasts

### Cartes produits

Les cartes produits doivent avoir un bouton avec:
```html
<button class="add-to-cart-btn"
        data-product-id="{{ $product->id }}"
        data-quantity="1">
  Add to cart
</button>
```

### Page de détail produit

Pour les produits avec options, incluez:
```html
<div id="product-detail" data-product-id="{{ $product->id }}">
  <!-- Options du produit -->
  <div data-option-id="1" data-option-required="true">
    <input type="radio"
           class="product-option-input"
           data-option-id="1"
           value="10"
           name="option_1">
  </div>

  <!-- Bouton d'ajout -->
  <button id="add-to-cart-detail" class="btn btn-primary">
    Add to cart
  </button>
</div>

<script src="/client/js/product-detail-cart.js"></script>
```

## Sécurité

- ✅ CSRF protection sur toutes les requêtes
- ✅ Validation des données côté serveur
- ✅ Vérification du stock avant ajout
- ✅ Vérification des options required
- ✅ Isolation des paniers par utilisateur/session

## Prochaines étapes suggérées

1. **Fusion des paniers**
   - Fusionner panier guest avec panier user lors de la connexion

2. **Offcanvas panier**
   - Afficher le contenu du panier dans le sidebar
   - Permettre la modification directe

3. **Wishlist**
   - Implémenter la liste de souhaits
   - Bouton "Ajouter à la wishlist"

4. **Comparaison**
   - Système de comparaison de produits

5. **Gestion des stocks**
   - Réserver le stock lors de l'ajout au panier
   - Libérer après un délai

6. **Promotions**
   - Codes promo
   - Remises automatiques

7. **Panier abandonné**
   - Emails de rappel
   - Analytics

## Tests

### Test manuel

1. **Ajout simple**
   - Cliquer sur "Add to cart" sur une carte produit sans options
   - Vérifier le badge et le total mis à jour

2. **Ajout avec options**
   - Cliquer sur un produit avec options required
   - Vérifier la redirection vers la page détail
   - Sélectionner les options
   - Ajouter au panier
   - Vérifier que les options sont enregistrées

3. **Incrémentation**
   - Ajouter le même produit avec les mêmes options
   - Vérifier que la quantité augmente

4. **Variations**
   - Ajouter un produit avec différentes variations
   - Vérifier que chaque variation crée une ligne distincte

5. **Hors connexion**
   - Ajouter des produits sans être connecté
   - Vérifier la persistance dans la session

## Support

Pour toute question ou problème, contactez l'équipe de développement.
