## **School-Management-System** 

**School-Management-System** est une application développée pour les établissements d’enseignement tels que les écoles et les collèges.
Elle est construite sur le framework Laravel 8 et offre une plateforme complète de gestion académique.

**SCREENSHOTS** 

**Tableau de bord**
<img src="https://i.ibb.co/D4T0z6T/dashboard.png" alt="dashboard" border="0">

**Connexion (Login)**
<img src="https://i.ibb.co/Rh1Bfwk/login.png" alt="login" border="0">

**Bulletin de notes (Marksheet)**
<img src="https://i.ibb.co/GCgv5ZR/marksheet.png" alt="marksheet" border="0">

**Paramètres du système**
<img src="https://i.ibb.co/Kmrhw69/system-settings.png" alt="system-settings" border="0">

**Impression du bulletin**
<div style="clear: both"> </div>
<img src="https://i.ibb.co/5c1GHCj/capture-20210530-115521-crop.png" alt="print-marksheet">

**Impression de la feuille de tabulation et du bulletin**
<img src="https://i.ibb.co/QmscPfn/capture-20210530-115802.png" alt="tabulation-sheet" border="0">

<hr />  

L’application gère 7 types de comptes utilisateurs, à savoir :
 
- Super Administrateur
- Administrateur
- Bibliothécaire
- Comptable
- Enseignant
- Élève
- Parent

**Prérequis** 

Check Laravel 8 Requirements https://laravel.com/docs/8.x

**Installation**
- Install dependencies (composer install)
- Set Database Credentials & App Settings in dotenv file (.env)
- Migrate Database (php artisan migrate)
- Database seed (php artisan db:seed)

**Identifiants de connexion (par défaut)**
près avoir exécuté le seeder, les identifiants de connexion sont les suivants :

| Account Type  | Username | Email | Password |
| ------------- | -------- | ----- | -------- |
| Super Admin | cj | cj@cj.com | cj |
|  Admin | admin | admin@admin.com | cj |
|  Teacher | teacher | teacher@teacher.com | cj |
|  Parent | parent | parent@parent.com | cj |
|  Accountant | accountant | accountant@accountant.com | cj |
|  Student | student | student@student.com | cj |

#### **Fonctionnalités par rôle utilisateur** 

**-- 🧑‍💼 Super Administrateur**
- Seul le Super Admin peut supprimer n’importe quel enregistrement
- Peut créer n’importe quel compte utilisateur
 
**-- 🏫 Administrateurs (Super Admin & Admin)**

- Gérer les élèves, classes et sections

- Consulter les bulletins de notes des élèves

- Créer, modifier et gérer tous les comptes utilisateurs

- Créer, modifier et gérer les examens et les notes

- Créer, modifier et gérer les matières

- Gérer le tableau d’affichage (notices, annonces, événements)

- Modifier les paramètres du système

- Gérer les paiements et les frais scolaires

💰 Comptable

- Gérer les paiements et frais scolaires

- Imprimer les reçus de paiement

📚 Bibliothécaire

- Gérer les livres et ressources de la bibliothèque

👨‍🏫 Enseignant

- Gérer sa propre classe et ses sections

- Gérer les résultats des examens pour ses matières

- Gérer son emploi du temps (si assigné comme professeur principal)

- Gérer son profil

- Télécharger des supports pédagogiques

👨‍🎓 Élève

- Consulter le profil des enseignants

- Voir ses matières et horaires de cours

- Consulter ses notes et bulletins

- Voir ses paiements et l’état de ses livres à la bibliothèque

- Voir le tableau d’affichage et le calendrier des événements

- Gérer son profil personnel

👨‍👩‍👦 Parent

- Consulter le profil des enseignants

- Consulter et imprimer le bulletin de son enfant (PDF)

- Voir les paiements de son enfant

- Consulter le tableau d’affichage et le calendrier des événements

- Gérer son profil personnel
### **Contribution**

Les contributions et suggestions sont les bienvenues.
Veuillez soumettre vos propositions via une Pull Request sur le dépôt du projet.

### **Vulnérabilités de sécurité**

Si vous découvrez une faille de sécurité dans LAVSMS, veuillez envoyer un e-mail à Bédi Tshitsho à l’adresse : tshitshob@gmail.com. Toutes les vulnérabilités signalées seront corrigées rapidement.

***⚠️ Remarque importante*** Veuillez noter que certaines sections de ce projet sont encore en cours de développement et seront mises à jour prochainement.
Les parties concernées incluent :

- Le tableau d’affichage et le calendrier dans la zone du tableau de bord

- Les pages utilisateur du bibliothécaire et du comptable

- Le module de gestion des ressources de la bibliothèque et le téléchargement des supports pédagogiques pour les élèves

### **Contact [Bédi]**
- Téléphone : +243812380589
