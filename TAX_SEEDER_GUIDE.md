# Guide des Seeders du Système de Taxes

Ce guide explique comment utiliser les seeders pour peupler le système de taxes.

## 📋 Vue d'ensemble

Le système de taxes comprend 4 tables principales :

1. **tax_zones** - Zones géographiques (France, EU, US, etc.)
2. **tax_rates** - Taux de taxe spécifiques à chaque zone
3. **tax_classes** - Classes de taxe (Standard, Réduit, etc.)
4. **tax_rate_amounts** - Associations entre classes et taux avec pourcentages

## 🚀 Utilisation

### Option 1 : Seeder complet (toute la base de données)

```bash
php artisan db:seed
```

Cette commande exécute le `DatabaseSeeder` qui inclut :
- Les données utilisateurs
- Les données du système de taxes

### Option 2 : Seeder taxes uniquement (Recommandé)

```bash
php artisan db:seed --class=TaxSystemSeeder
```

Cette commande peuple uniquement les tables liées aux taxes.

### Option 3 : Seeders individuels

Si vous voulez un contrôle plus fin :

```bash
# 1. Zones de taxe (obligatoire en premier)
php artisan db:seed --class=TaxZoneSeeder

# 2. Taux de taxe (dépend de TaxZone)
php artisan db:seed --class=TaxRateSeeder

# 3. Classes de taxe (indépendant)
php artisan db:seed --class=TaxClassSeeder

# 4. Montants des taux (dépend de TaxClass et TaxRate)
php artisan db:seed --class=TaxRateAmountSeeder
```

## 📊 Données créées

### Tax Zones (7 zones)
- 🇫🇷 France (défaut)
- 🇪🇺 European Union
- 🇺🇸 United States
- 🇬🇧 United Kingdom
- 🇨🇦 Canada
- 🇨🇳 China
- 🌍 Rest of World

### Tax Rates (13 taux)
- **France** : TVA France
- **EU** : VAT EU Standard, VAT EU Reduced
- **US** : US Sales Tax, US State Tax
- **UK** : VAT UK
- **Canada** : GST Canada, HST Canada, PST Canada
- **China** : VAT China
- **ROW** : Standard Tax ROW

### Tax Classes (4 classes)
- ✅ **Standard Rate** (défaut) - 20% en France
- 📉 **Reduced Rate** - 5.5% en France
- 0️⃣ **Zero Rate** - 0%
- 🔽 **Super Reduced Rate** - 2.1% en France

### Tax Rate Amounts
Associations pré-configurées avec des taux réels :
- TVA France : 20% (Standard), 5.5% (Réduit), 2.1% (Super Réduit), 0% (Zéro)
- VAT UK : 20% (Standard), 5% (Réduit), 0% (Zéro)
- US Sales Tax : 8.5% (Standard)
- GST Canada : 5% (Standard)
- Et bien d'autres...

## 🔄 Réinitialiser et re-seeder

Si vous voulez recommencer à zéro :

```bash
# Réinitialiser toutes les tables et re-seeder
php artisan migrate:fresh --seed

# Ou juste re-seeder les taxes
php artisan migrate:fresh
php artisan db:seed --class=TaxSystemSeeder
```

## 📝 Notes importantes

1. **Ordre des seeders** : Respectez toujours l'ordre de dépendance :
   - TaxZone → TaxRate → TaxClass → TaxRateAmount

2. **Modification des données** : Vous pouvez modifier les seeders dans `database/seeders/` pour ajouter vos propres zones/taux.

3. **Production** : En production, adaptez les taux selon vos besoins spécifiques.

## 🛠️ Personnalisation

Pour ajouter vos propres zones/taux, éditez les fichiers :
- `database/seeders/TaxZoneSeeder.php`
- `database/seeders/TaxRateSeeder.php`
- `database/seeders/TaxClassSeeder.php`
- `database/seeders/TaxRateAmountSeeder.php`

## ✅ Vérification

Après avoir exécuté les seeders, vous pouvez vérifier dans l'admin :
1. Allez sur `/admin/tax-classes`
2. Cliquez sur "View" pour une classe
3. Vous verrez tous les taux assignés avec leurs pourcentages
