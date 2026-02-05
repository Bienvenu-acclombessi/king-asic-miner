# Guide de Création de Produits - King ASIC Miner

## Vue d'ensemble

Ce guide explique comment utiliser le système de création de produits dynamique et professionnel, inspiré de WooCommerce.

## Architecture

### Modèles de Données

#### Product
Le modèle principal avec les champs:
- `product_type_id` - Type de produit (FK vers ProductType)
- `brand_id` - Marque du produit (FK vers Brand)
- `status` - Statut (draft, published, archived, out_of_stock)
- `attribute_data` - Données JSON flexibles (nom, slug, description, SEO, etc.)

#### ProductVariant
Chaque produit peut avoir plusieurs variantes avec:
- Informations d'identification (SKU, GTIN, MPN, EAN)
- Dimensions (longueur, largeur, hauteur)
- Poids et volume
- Stock et gestion d'inventaire
- État d'achat (always, in_stock, never)
- Prix par groupe de clients (relation polymorphique)

#### Relations
- **Product → Collections** (many-to-many) - Catégories/Collections
- **Product → Tags** (polymorphic many-to-many)
- **Product → CustomerGroups** (many-to-many) - Visibilité par groupe
- **Product → ProductOptions** (many-to-many) - Options pour variantes
- **ProductVariant → Prices** (polymorphic one-to-many)
- **ProductVariant → ProductOptionValues** (many-to-many)

## Fonctionnalités

### 1. Informations de Base
- Nom du produit (requis)
- Slug (auto-généré si vide)
- Description courte
- Description détaillée

### 2. Données Produit (Onglets)

#### Onglet Général
- **Type de produit** - Sélection du type (requis)
- **Marque** - Association à une marque
- **Options de produit** - Sélection des options pour créer des variantes
  - Exemple: Size (S, M, L, XL), Color (Red, Blue, Green)
  - Les combinaisons génèrent automatiquement les variantes

#### Onglet Inventaire
Géré au niveau des variantes pour un contrôle granulaire

#### Onglet Expédition
Géré au niveau des variantes (dimensions et poids par variante)

#### Onglet Attributs
- Ajout d'attributs personnalisés
- Types supportés: text, number, boolean, date
- Stockés en JSON dans `attribute_data`

#### Onglet Variantes
- **Génération automatique** basée sur les options sélectionnées
- Pour chaque variante:
  - SKU unique
  - Stock
  - État (always, in_stock, never)
  - Shippable (oui/non)
  - Dimensions (L x W x H en cm)
  - Poids (en kg)
  - **Prix par groupe de clients**
    - Prix normal
    - Prix comparatif
    - Quantité minimale

#### Onglet Avancé
- SEO Meta Title
- SEO Meta Description
- SEO Meta Keywords

### 3. Sidebar

#### Publier
- Statut du produit (draft, published, archived, out_of_stock)
- Actions: Publier ou Enregistrer comme brouillon

#### Collections
- Sélection multiple de collections/catégories
- Support hiérarchique (parent → enfant)

#### Tags
- Sélection ou création de tags
- Permet plusieurs tags par produit

#### Visibilité
- Groupes de clients ayant accès au produit
- Par défaut: tous les groupes sélectionnés

## Utilisation

### Créer un Produit Simple

1. Accédez à **Admin → Products → Add New**
2. Remplissez les informations de base (nom, description)
3. Dans l'onglet **Général**:
   - Sélectionnez le type de produit
   - Sélectionnez la marque (optionnel)
4. Dans l'onglet **Variantes**:
   - Vous verrez un message "No options selected"
   - Pour un produit simple, vous devrez créer une variante manuelle via code
5. Configurez la sidebar (status, collections, tags)
6. Cliquez sur **Publish Product**

### Créer un Produit avec Variantes

1. Accédez à **Admin → Products → Add New**
2. Remplissez les informations de base
3. Dans l'onglet **Général**:
   - Sélectionnez le type de produit
   - **Cochez les options** que vous voulez (ex: Size, Color)
4. Dans l'onglet **Variantes**:
   - Cliquez sur **Generate Variants**
   - Le système génère toutes les combinaisons automatiquement
   - Pour chaque variante, remplissez:
     - SKU
     - Stock
     - Dimensions et poids
     - Prix pour chaque groupe de clients
5. Configurez les attributs personnalisés si nécessaire
6. Configurez la sidebar
7. Cliquez sur **Publish Product**

### Exemple de Variantes

**Options sélectionnées:**
- Size: S, M, L
- Color: Red, Blue

**Variantes générées:**
1. S / Red
2. S / Blue
3. M / Red
4. M / Blue
5. L / Red
6. L / Blue

Chaque variante peut avoir son propre:
- SKU
- Stock
- Prix par groupe de clients
- Dimensions

## Architecture JavaScript

### ProductManager Class

Le fichier `/public/admin/js/product-manager.js` contient la classe principale:

```javascript
class ProductManager {
    constructor(config) {
        this.customerGroups = config.customerGroups;
        this.productOptions = config.productOptions;
        this.attributes = config.attributes;
        // ...
    }
}
```

#### Méthodes Principales

- `handleOptionChange(event)` - Gère la sélection/déselection d'options
- `generateVariants()` - Génère toutes les combinaisons de variantes
- `generateCombinations(options)` - Algorithme de combinaisons
- `renderVariants()` - Affiche les variantes en accordéon
- `addAttributeRow()` - Ajoute un attribut personnalisé
- `updateFormData()` - Met à jour les champs JSON avant soumission

### Soumission du Formulaire

Lors de la soumission:
1. `updateFormData()` est appelée
2. Les variantes sont converties en JSON → champ `variants`
3. Les attributs sont convertis en JSON → champ `attributes`
4. Le formulaire est soumis au controller

## Controller

### ProductController::store()

1. Valide les données
2. Crée le produit principal
3. Attache les relations (collections, tags, customer groups, options)
4. Crée les variantes via `createVariant()`
5. Pour chaque variante:
   - Crée l'enregistrement ProductVariant
   - Attache les ProductOptionValues
   - Crée les Prix pour chaque groupe de clients

### Structure de Données JSON

#### Variants (champ caché `variants`)
```json
[
  {
    "option_values": [1, 5],
    "sku": "ASIC-S-RED",
    "stock": 100,
    "purchasable": "always",
    "shippable": true,
    "weight_value": 2.5,
    "weight_unit": "kg",
    "length_value": 30,
    "width_value": 20,
    "height_value": 10,
    "prices": [
      {
        "customer_group_id": 1,
        "price": 1500,
        "compare_price": 2000,
        "min_quantity": 1
      }
    ]
  }
]
```

#### Attributes (champ caché `attributes`)
```json
{
  "Material": {
    "value": "Aluminum",
    "type": "text"
  },
  "Power Consumption": {
    "value": "3250",
    "type": "number"
  }
}
```

## Dépendances

### Frontend
- Bootstrap 5 (inclus dans le template)
- Select2 (pour multi-select)
- jQuery (pour Select2)

### Backend
- Laravel 10+
- Relations Eloquent
- JSON casting

## Personnalisation

### Ajouter un Nouveau Type d'Attribut

1. Modifiez le select dans `addAttributeRow()`:
```javascript
<select class="form-select attr-type">
    <option value="text">Text</option>
    <option value="number">Number</option>
    <option value="url">URL</option> <!-- Nouveau type -->
</select>
```

2. Ajoutez la validation côté serveur si nécessaire

### Ajouter des Champs aux Variantes

1. Ajoutez le champ dans `renderVariantFields()`:
```javascript
<div class="col-md-6">
    <label class="form-label">New Field</label>
    <input type="text" class="form-control variant-new-field" data-index="${index}">
</div>
```

2. Ajoutez l'event listener dans `attachVariantEventListeners()`

3. Ajoutez le champ dans `updateFormData()`

## Améliorations Futures

- [ ] Upload d'images par variante
- [ ] Import/Export CSV de variantes
- [ ] Copie de produit existant
- [ ] Variation en masse (bulk edit)
- [ ] Prévisualisation des variantes
- [ ] Génération automatique de SKU
- [ ] Gestion des stocks en temps réel
- [ ] Historique des changements de prix

## Support

Pour toute question ou problème:
1. Vérifiez les logs Laravel (`storage/logs/laravel.log`)
2. Vérifiez la console JavaScript du navigateur
3. Assurez-vous que toutes les dépendances sont installées

## Exemples d'Utilisation

### Produit ASIC Miner avec Variantes de Hash Rate

**Options:**
- Hash Rate: 100 TH/s, 110 TH/s, 120 TH/s
- Warranty: 6 months, 12 months

**Résultat:** 6 variantes avec prix différents selon le hash rate et la garantie

### Produit Accessoire Simple

**Type:** Simple (sans variantes)
- Un seul SKU
- Prix fixe
- Stock global

---

**Date de création:** 2026-02-05
**Version:** 1.0
**Auteur:** Claude Sonnet 4.5 via Claude Code
