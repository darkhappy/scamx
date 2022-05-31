# ScamX

Un site vraiment amusant.

[Le lien magique!](https://420n46.jolinfo.cegep-lanaudiere.qc.ca/1927230/)

## Tester sur le serveur

Choisissez un utilisateur au choix :

```
courriel: vim@darkh.app
password: vim-my-beloved
```

```
courriel: bs@darkh.app
password: bs-my-beloved
```

## Migrations

Il suffit de faire une copie de `config/config-example.yml` et de modifier les valeurs suivantes:

Le nouveau fichier doit se nommer `config/config.yml`

```yaml
host = host.docker.internal   # si vous n'utiliser pas le docker-compose.yml, veuillez remplacer ces champs
name = test
user = root
pass = root
port = 3306
root = /
debug = true                  # mettre à faux pour le déploiement
stripe_public_key = public    # mettre votre clé publique Stripe
stripe_secret_key = secret    # mettre votre clé privée Stripe
```

Si le fichier n'existe pas, le site ne fonctionnera pas.

De plus, il n'y a pas de transactions par défaut. Il faut Stripe pour les faire.

## Auto évaluation

### Fonctionalités

* Authentification de base (1.1, 1.2 et 2.1) - 5/5
* Authentification avancée (1.3, 1.4 et 2.2) - 15/15
* CRUD produits (1.5, 4.1, 4.2 et 5.2) - 20/20
* Achat avec Stripe (3.1 et 5.1) - 10/10
* Remboursement avec Stripe (3.2 et 4.4) - 10/10
* Traiter une vente (4.3) - 5/5
* Historiques (3.3 et 4.5) - 10/10
* Traitement d'image avec GD (5.3) - 10/10
* Journaux (5.4) - 5/5
* Total - 90/90

### Code

* Qualité du code (MVC avancé, normes, performances, typage, etc.) - 40/40
    * La plupart du code est fait pour être réutilisé partout. Par exemple, les messages sont dynamiques et stockés dans
      des variables de session. Alors, on peut afficher un message dans n'importe quel page peu importe la source.
* Sécurité - 38/40
    * J'ai oublié le CSRF pour le boutton delete et logout. J'ai réalisé dernière minute.
* Tests unitaires - 10/10
* Tests d'acceptations - 10/10
* Ergonomie, accessibilité et réactivité - 10/10
    * Le seul problème est que le site marche un peu mal sur un iPhone SE (des textes qui dépasse), mais il y a eu
      beaucoup d'éffort sur le site pour le rendre plus accessible, et honnêtement la plupart des sites marchent mals
      sur un téléphone vraiment petit.
* Déploiement sur le FTP - 10/10
    * J'ai quand même laissé le directoire "src" dans le FTP. Cependant, tous marche et l'utilisateur ne saura même
      pas que ce dossier existe!
* Fichiers (migration, README, git propre, etc.) - 5/5
* Total - 123/125

### Ajouts

* Auto-évaluation réaliste et détaillée de votre projet - 5/5
* Navigation entre différents produits
    * Pour la liste des produits, on requête seulement un certain nombre de produit a la fois. On peut faire "
      suivant" et "précédent" pour naviguer dans la liste.
    * Cette liste est réutilisé d'une manière polymorphe pour les différents types de produits (produits, ventes,
      achats).

Total - 218/220