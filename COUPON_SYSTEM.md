# Système de Coupons - Documentation

## Vue d'ensemble

Le système de coupons a été complètement implémenté avec toutes les fonctionnalités d'un système moderne comme WooCommerce.

## Fonctionnalités

### Admin (CRUD avec Modals)

**Page:** `/admin/coupons`

#### Types de réduction supportés :
- **Pourcentage** : Réduction en pourcentage du total (ex: 10%)
- **Montant fixe** : Réduction d'un montant fixe (ex: $50)
- **Livraison gratuite** : Offrir la livraison gratuite

#### Restrictions d'utilisation :
- **Montant minimum de commande** (`min_order_amount`)
- **Montant maximum de commande** (`max_order_amount`)
- **Quantité minimum d'articles** (`min_qty`)
- **Montant maximum de réduction** (`max_discount_amount`) - pour limiter les réductions en pourcentage

#### Limites d'utilisation :
- **Usage limite par coupon** (`max_uses`)
- **Usage limite par utilisateur** (`max_uses_per_user`)

#### Période de validité :
- **Date de début** (`starts_at`)
- **Date de fin** (`ends_at`)

#### Options avancées :
- **Actif/Inactif** (`is_active`)
- **Usage individuel** (`individual_use`) - ne peut pas être combiné avec d'autres coupons
- **Exclure les articles en promotion** (`exclude_sale_items`)
- **Livraison gratuite** (`free_shipping`)

#### Relations disponibles (pour extension future) :
- Produits spécifiques (via `discount_purchasables`)
- Collections spécifiques (via `collection_discount`)
- Marques spécifiques (via `brand_discount`)
- Clients spécifiques (via `customer_discount`)
- Groupes de clients (via `customer_group_discount`)

### Frontend (Application dans le panier)

**Page:** `/cart`

#### Fonctionnalités :
1. **Formulaire d'application du coupon**
   - Champ de saisie avec transformation automatique en majuscules
   - Bouton "Appliquer" avec spinner de chargement
   - Messages d'erreur en cas de problème

2. **Affichage du coupon appliqué**
   - Code du coupon visible dans le résumé
   - Montant de la réduction affiché
   - Bouton "✕" pour retirer le coupon

3. **Calcul automatique du total**
   - Sous-total
   - Réduction (si coupon appliqué)
   - Total final

## Structure de la base de données

### Table `discounts`

```sql
- id
- name (nom du coupon)
- handle (slug)
- coupon (code promo - unique)
- type (percentage|fixed|free_shipping)
- discount_value (valeur de la réduction)
- is_active (actif/inactif)
- starts_at (date de début)
- ends_at (date de fin)
- uses (nombre d'utilisations)
- max_uses (limite d'utilisations totales)
- max_uses_per_user (limite par utilisateur)
- min_order_amount (montant minimum)
- max_order_amount (montant maximum)
- min_qty (quantité minimum)
- max_discount_amount (réduction maximum)
- apply_to_shipping (appliquer à la livraison)
- exclude_sale_items (exclure articles en promo)
- individual_use (usage individuel)
- free_shipping (livraison gratuite)
- allowed_emails (emails autorisés)
- description (description admin)
```

### Table `carts`

```sql
- coupon_code (code du coupon appliqué)
```

## API Endpoints

### Admin
- `GET /admin/coupons` - Liste des coupons
- `POST /admin/coupons` - Créer un coupon
- `GET /admin/coupons/{id}/edit` - Récupérer un coupon (JSON)
- `PUT /admin/coupons/{id}` - Mettre à jour un coupon
- `DELETE /admin/coupons/{id}` - Supprimer un coupon

### Public
- `POST /cart/apply-coupon` - Appliquer un coupon
  ```json
  {
    "coupon_code": "SUMMER2024"
  }
  ```

- `DELETE /cart/remove-coupon` - Retirer le coupon

## Fichiers modifiés/créés

### Migrations
- `2026_02_08_140000_add_coupon_fields_to_discounts_table.php`

### Modèles
- `app/Models/Discounts/Discount.php` ✓ Mis à jour
- `app/Models/Orders/Cart.php` ✓ Mis à jour

### Contrôleurs
- `app/Http/Controllers/Admin/CouponController.php` ✓ Complété
- `app/Http/Controllers/Public/CartController.php` ✓ Méthodes ajoutées

### Vues Admin
- `resources/views/admin/pages/coupons/index.blade.php` ✓ Avec modals
- `resources/views/admin/pages/coupons/partials/create-modal.blade.php` ✓ Nouveau
- `resources/views/admin/pages/coupons/partials/edit-modal.blade.php` ✓ Nouveau
- `resources/views/admin/pages/coupons/partials/delete-modal.blade.php` ✓ Nouveau

### Vues Client
- `resources/views/client/pages/cart/partials/cart_body.blade.php` ✓ Mis à jour

### JavaScript
- `public/admin/js/coupons.js` ✓ Nouveau
- `public/client/js/cart.js` ✓ Méthodes ajoutées

### Routes
- `routes/admin.php` ✓ Routes existantes (resource)
- `routes/public.php` ✓ Routes ajoutées

## Utilisation

### Créer un coupon (Admin)

1. Aller sur `/admin/coupons`
2. Cliquer sur "Create Coupon"
3. Remplir le formulaire dans le modal
4. Cliquer sur "Create Coupon"

### Appliquer un coupon (Client)

1. Ajouter des produits au panier
2. Aller sur `/cart`
3. Dans la section "Code promo", entrer le code
4. Cliquer sur "Appliquer"
5. La réduction s'applique automatiquement

### Retirer un coupon

1. Cliquer sur le "✕" à côté du code du coupon dans le résumé

## Validation automatique

Le système valide automatiquement :
- ✓ Le coupon existe
- ✓ Le coupon est actif
- ✓ Le coupon n'est pas expiré
- ✓ Le coupon n'a pas atteint sa limite d'utilisation
- ✓ Le montant minimum de commande est respecté
- ✓ La quantité minimum d'articles est respectée

## Notes de développement

- Les prix sont stockés en cents dans la base de données (multiplier par 100)
- La méthode `calculateDiscount()` du modèle Discount gère le calcul de la réduction
- La méthode `validateOrderConditions()` vérifie toutes les conditions
- La méthode `isValid()` vérifie la validité globale du coupon
- Le CartManager JavaScript gère l'application et la mise à jour de l'UI

## Tests recommandés

1. Créer un coupon avec montant minimum → vérifier qu'il ne s'applique pas en dessous
2. Créer un coupon expiré → vérifier qu'il est refusé
3. Créer un coupon en pourcentage avec max discount → vérifier la limite
4. Appliquer puis retirer un coupon → vérifier que le total se met à jour
5. Créer un coupon avec limite d'usage → utiliser jusqu'à la limite

## Extensions possibles

- [ ] Ajouter la sélection de produits/collections/marques dans les modals
- [ ] Ajouter la restriction par email
- [ ] Ajouter l'historique d'utilisation des coupons
- [ ] Ajouter des statistiques sur les coupons
- [ ] Générer des codes promo en masse
- [ ] Export des données des coupons
