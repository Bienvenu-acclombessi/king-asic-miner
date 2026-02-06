# Product Options System

## Vue d'ensemble

Système flexible pour gérer 3 types de produits :
- **Simple** : Pas d'options
- **Variable** : Options qui créent des variants (SKU/stock distincts)
- **Add-ons** : Options ajoutées au panier sans créer de variants

---

## Architecture

```
Product
  └─ ProductVariant (SKU, stock, prix)
       └─ ProductOptionValue (Taille: M, Couleur: Rouge)

ProductOption (définition)
  └─ ProductOptionValue (valeurs possibles)
```

---

## Structure Tables

### Tables principales

```sql
products
├─ id
├─ product_type_id
└─ status

product_variants
├─ id
├─ product_id
├─ sku
└─ stock

product_options
├─ id
├─ name (JSON: {"en": "Size"})
├─ handle (slug: "size")
├─ display_type (select|radio|button|color|image|swatch)
├─ required (bool)
├─ affects_price (bool)
├─ affects_stock (bool)
└─ position

product_option_values
├─ id
├─ product_option_id
├─ name (JSON: {"en": "Large"})
├─ price_modifier (int: en centimes)
├─ price_type (fixed|percentage)
├─ image_path
├─ color_hex (#FF0000)
└─ position

product_option_value_product_variant (pivot)
├─ value_id
└─ variant_id
```

### Tables Panier/Commande

```sql
cart_lines
├─ purchasable_type (ProductVariant)
├─ purchasable_id
└─ quantity

cart_line_option_values
├─ cart_line_id
├─ product_option_id
├─ product_option_value_id (nullable)
├─ custom_value (text: pour champs libres)
├─ price_modifier (snapshot)
└─ price_type

order_line_option_values
├─ order_line_id
├─ option_name (JSON snapshot)
├─ value_name (JSON snapshot)
├─ custom_value
├─ price_modifier (snapshot)
└─ price_type
```

---

## Les 3 Types de Produits

### 1. Produit Simple

```
Product: "ASIC Miner Basic"
  └─ 1 Variant (SKU: MINER-001, stock: 50, prix: $2,999)
```

Aucune option définie.

---

### 2. Produit Variable

```
Product: "ASIC Miner Pro"

Options:
  Batch (affects_price: true, affects_stock: true)
    ├─ March 2025 (+$0)
    ├─ April 2025 (+$500)
    └─ May 2025 (+$1,200)

  Warranty (affects_price: true, affects_stock: false)
    ├─ 1 Year (+$0)
    └─ 2 Years (+$150)

→ Génère 6 Variants (3×2)

Variants:
  ├─ March + 1Y → SKU: MINER-MAR-1Y → $2,999 → stock: 20
  ├─ March + 2Y → SKU: MINER-MAR-2Y → $3,149 → stock: 20
  ├─ April + 1Y → SKU: MINER-APR-1Y → $3,499 → stock: 30
  └─ ...
```

**Règle** : Options avec `affects_price` ou `affects_stock` = créent des variants.

---

### 3. Produit avec Add-ons

```
Product: "ASIC Miner Basic"
  └─ 1 Variant (base)

Options (ne créent PAS de variants):
  Installation (affects_price: true, affects_stock: false)
    ├─ None (+$0)
    ├─ Basic (+$300)
    └─ Premium (+$500)

  Custom Note (display_type: text, affects_price: false)
    └─ Champ texte libre
```

**Stockage** : Dans `cart_line_option_values`, pas de nouveaux variants.

---

## Flow : Ajout au Panier

```php
// 1. Créer ligne de panier
$cartLine = CartLine::create([
    'cart_id' => $cart->id,
    'purchasable_type' => 'ProductVariant',
    'purchasable_id' => $variant->id,
    'quantity' => 1,
]);

// 2. Stocker options sélectionnées
foreach ($selectedOptions as $optionId => $valueId) {
    $optionValue = ProductOptionValue::find($valueId);

    CartLineOptionValue::create([
        'cart_line_id' => $cartLine->id,
        'product_option_id' => $optionId,
        'product_option_value_id' => $valueId,
        'price_modifier' => $optionValue->price_modifier, // Snapshot
        'price_type' => $optionValue->price_type,
    ]);
}
```

---

## Calcul du Prix

```php
class CartLine extends Model
{
    public function getTotalPriceAttribute(): int
    {
        $price = $this->purchasable->price->price;

        foreach ($this->optionValues as $option) {
            if ($option->price_type === 'fixed') {
                $price += $option->price_modifier;
            } else {
                $price += ($price * $option->price_modifier / 100);
            }
        }

        return $price * $this->quantity;
    }
}
```

---

## Flow : Panier → Commande

```php
foreach ($cart->lines as $cartLine) {
    $orderLine = OrderLine::create([
        'order_id' => $order->id,
        'purchasable_type' => $cartLine->purchasable_type,
        'purchasable_id' => $cartLine->purchasable_id,
        'quantity' => $cartLine->quantity,
        'unit_price' => $cartLine->unit_price,
        'total' => $cartLine->total_price,
    ]);

    // Snapshot des options
    foreach ($cartLine->optionValues as $cartOption) {
        OrderLineOptionValue::create([
            'order_line_id' => $orderLine->id,
            'product_option_id' => $cartOption->product_option_id,
            'product_option_value_id' => $cartOption->product_option_value_id,
            'option_name' => $cartOption->option->name, // Snapshot
            'value_name' => $cartOption->value?->name,   // Snapshot
            'custom_value' => $cartOption->custom_value,
            'price_modifier' => $cartOption->price_modifier,
            'price_type' => $cartOption->price_type,
        ]);
    }
}
```

**Important** : Les snapshots (`option_name`, `value_name`) préservent l'historique même si l'option est supprimée.

---

## Display Types

| Type | Usage | Exemple |
|------|-------|---------|
| `select` | Liste déroulante | `<select>` HTML |
| `radio` | Boutons radio | Un seul choix |
| `button` | Boutons cliquables | Design moderne |
| `color` | Pastilles couleur | `color_hex` requis |
| `image` | Sélection visuelle | `image_path` requis |
| `swatch` | Couleur + texte | Combinaison |

---

## Règles Importantes

### ✅ Quand créer des Variants ?

```
IF affects_stock = true OU affects_price = true
    → Créer des Variants
ELSE
    → Add-on (stocké dans cart_line_option_values)
```

### ✅ Prix en centimes

Tous les prix sont stockés en **centimes** (int) :
```php
$2,999.00 → 299900
$500.50  → 50050
```

### ✅ Snapshots

Au moment de l'ajout au panier et de la commande, on fait un **snapshot** du prix pour éviter les modifications rétroactives.

### ✅ JSON multilingue

Les champs `name` et `label` sont en JSON pour le multilingue :
```json
{"en": "Size", "fr": "Taille", "es": "Tamaño"}
```

---

## Exemples de Requêtes

```php
// Paniers contenant une option spécifique
CartLine::whereHas('optionValues', fn($q) =>
    $q->where('product_option_value_id', $valueId)
)->get();

// Top 10 options les plus vendues
OrderLineOptionValue::select('product_option_value_id', DB::raw('count(*) as total'))
    ->groupBy('product_option_value_id')
    ->orderByDesc('total')
    ->limit(10)
    ->get();

// Revenus par option
OrderLineOptionValue::where('product_option_id', $optionId)
    ->sum('price_modifier');
```

---

## Migrations à Ajouter

```bash
# Améliorer product_options
php artisan make:migration improve_product_options_structure

# Tables cart/order options
php artisan make:migration create_cart_line_option_values_table
php artisan make:migration create_order_line_option_values_table
```

Voir les fichiers de migration dans `/database/migrations/`.

---

## Schéma Complet

```
                    ┌─────────────┐
                    │   Product   │
                    └──────┬──────┘
                           │
                  ┌────────┴────────┐
                  │                 │
         ┌────────▼────────┐ ┌─────▼──────────┐
         │ ProductVariant  │ │ ProductOption  │
         │ (SKU, stock)    │ │ (définition)   │
         └────────┬────────┘ └─────┬──────────┘
                  │                │
                  │         ┌──────▼─────────────┐
                  │         │ ProductOptionValue │
                  │         │ (valeurs)          │
                  │         └──────┬─────────────┘
                  │                │
         ┌────────▼────────────────▼─────┐
         │ product_option_value_         │
         │    product_variant (pivot)    │
         └───────────────────────────────┘

         ┌────────────┐
         │ CartLine   │──┐
         └────────────┘  │
                         │
              ┌──────────▼─────────────────┐
              │ CartLineOptionValue        │
              │ (options sélectionnées)    │
              └────────────────────────────┘
                         │
                         │ Conversion
                         ▼
              ┌──────────────────────────┐
              │ OrderLineOptionValue     │
              │ (snapshot des options)   │
              └──────────────────────────┘
```

---

## Comparaison avec autres systèmes

| Système | Approche | Notre choix |
|---------|----------|-------------|
| Shopify | Max 3 options, 100 variants | ✅ Illimité |
| WooCommerce | Taxonomies + post_meta | ✅ Plus propre |
| Magento | Tables relationnelles | ✅ Similaire |
| PrestaShop | Customizations | ✅ Plus flexible |

---

**Système inspiré de Magento avec la flexibilité de Lunar.**
