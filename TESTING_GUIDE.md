# 🧪 Guide de Test Complet - Espace Étudiant LAV_SMS

## 📋 Prérequis

Avant de commencer les tests, assurez-vous que:
- ✅ Le serveur Laravel est démarré (`php artisan serve`)
- ✅ La base de données est configurée et migrée
- ✅ Vous avez un compte étudiant de test
- ✅ Des données de test existent (devoirs, livres, paiements, etc.)

---

## 🔐 Connexion

### Test 1: Se connecter en tant qu'étudiant

**Étapes:**
1. Accéder à `http://localhost:8000/login`
2. Utiliser les identifiants: `student@student.com` / `cj`
3. Cliquer sur "Se connecter"

**Résultat attendu:**
- ✅ Redirection vers le dashboard étudiant
- ✅ Menu étudiant visible
- ✅ Nom de l'étudiant affiché

**Statut:** [ ] Réussi / [ ] Échoué

**Notes:**
```
_______________________________________________________
```

---

## 📊 DASHBOARD ÉTUDIANT

### Test 2: Accéder au Dashboard

**URL:** `http://localhost:8000/student/dashboard`

**Étapes:**
1. Après connexion, vérifier la redirection automatique
2. Ou cliquer sur "Dashboard" dans le menu

**Résultat attendu:**
- ✅ Page se charge sans erreur
- ✅ Message de bienvenue avec nom de l'étudiant
- ✅ Date du jour affichée

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 3: Cartes de Statistiques

**Vérifications:**

#### Carte 1: Devoirs en attente
- [ ] Nombre affiché correctement
- [ ] Icône de livre visible
- [ ] Lien "Voir tous les devoirs" fonctionne
- [ ] Redirection vers `/student/assignments`

#### Carte 2: Taux de présence
- [ ] Pourcentage affiché (0-100%)
- [ ] Icône de checkmark visible
- [ ] Lien "Voir les présences" fonctionne
- [ ] Redirection vers `/student/attendance`

#### Carte 3: Livres empruntés
- [ ] Nombre affiché correctement
- [ ] Icône de livres visible
- [ ] Lien "Voir mes demandes" fonctionne
- [ ] Redirection vers `/student/library/my-requests`

#### Carte 4: Messages non lus
- [ ] Nombre affiché correctement
- [ ] Icône de mail visible
- [ ] Lien "Voir les messages" fonctionne
- [ ] Redirection vers `/student/messages`

**Statut:** [ ] Réussi / [ ] Échoué

**Notes:**
```
_______________________________________________________
```

---

### Test 4: Section Devoirs à Venir

**Vérifications:**
- [ ] Maximum 5 devoirs affichés
- [ ] Colonnes: Matière, Titre, Date limite, Statut, Action
- [ ] Badge "Soumis" (vert) pour devoirs déjà soumis
- [ ] Badge "En attente" (jaune) pour devoirs non soumis
- [ ] Date limite en rouge si dépassée
- [ ] Bouton "Voir" redirige vers les détails
- [ ] Message "Aucun devoir" si liste vide

**Cas de test:**
1. Avec devoirs en attente
2. Avec devoirs soumis
3. Avec devoirs en retard
4. Sans aucun devoir

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 5: Section Livres Empruntés

**Vérifications:**
- [ ] Liste des livres actuellement empruntés
- [ ] Colonnes: Livre, Auteur, Date de retour, Statut
- [ ] Date de retour affichée correctement
- [ ] Alerte "En retard!" si date dépassée (rouge)
- [ ] Temps relatif affiché (ex: "dans 5 jours")
- [ ] Badge de statut coloré correctement
- [ ] Section cachée si aucun livre emprunté

**Cas de test:**
1. Avec livres en cours d'emprunt
2. Avec livres en retard
3. Sans livres empruntés

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 6: Résumé Financier

**Vérifications:**
- [ ] Total à payer affiché
- [ ] Montant payé affiché (vert)
- [ ] Solde restant affiché (rouge si > 0, vert si = 0)
- [ ] Alerte "Solde impayé" si balance > 0 (jaune)
- [ ] Alerte "Paiements à jour" si balance = 0 (vert)
- [ ] Bouton "Voir les détails" fonctionne
- [ ] Montants formatés avec espaces (ex: 50 000 FC)

**Cas de test:**
1. Avec solde impayé
2. Avec paiements à jour
3. Avec paiement partiel

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 7: Statistiques de Présence

**Vérifications:**
- [ ] Nombre de présences (vert)
- [ ] Nombre d'absences (rouge)
- [ ] Nombre de retards (jaune)
- [ ] Nombre d'excusés (bleu)
- [ ] Barre de progression affichée
- [ ] Pourcentage de présence correct
- [ ] Bouton "Voir l'historique" fonctionne
- [ ] Message si aucune donnée

**Calcul à vérifier:**
```
Taux = (Présent / Total) × 100
```

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 8: Supports Pédagogiques Récents

**Vérifications:**
- [ ] Maximum 5 supports affichés
- [ ] Titre du support visible
- [ ] Description tronquée (80 caractères max)
- [ ] Badge de matière affiché
- [ ] Nom de l'enseignant affiché
- [ ] Temps relatif (ex: "il y a 2 jours")
- [ ] Clic redirige vers les détails
- [ ] Bouton "Voir tout" fonctionne
- [ ] Message si aucun support

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 9: Notifications Récentes

**Vérifications:**
- [ ] Maximum 10 notifications affichées
- [ ] Fond clair pour notifications non lues
- [ ] Icône de cloche visible
- [ ] Message de notification affiché
- [ ] Temps relatif affiché
- [ ] Scroll si plus de 10 notifications
- [ ] Message si aucune notification

**Statut:** [ ] Réussi / [ ] Échoué

---

## 📖 DEMANDES DE LIVRES

### Test 10: Accéder à Mes Demandes

**URL:** `http://localhost:8000/student/library/my-requests`

**Vérifications:**
- [ ] Page se charge sans erreur
- [ ] Titre "Mes Demandes de Livres" affiché
- [ ] Bouton "Nouvelle demande" visible
- [ ] Tableau des demandes affiché

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 11: Affichage de la Liste

**Vérifications:**
- [ ] Colonnes: #, Livre, Auteur, Date demande, Date retour, Statut, Actions
- [ ] Numérotation correcte avec pagination
- [ ] Nom du livre affiché
- [ ] Auteur affiché
- [ ] Date de demande formatée (dd/mm/yyyy)
- [ ] Temps relatif affiché
- [ ] Date de retour prévue affichée
- [ ] Alerte "En retard!" si applicable
- [ ] Badge de statut coloré:
  - [ ] Jaune pour "pending"
  - [ ] Bleu pour "approved"
  - [ ] Vert pour "borrowed"
  - [ ] Vert foncé pour "returned"
  - [ ] Rouge pour "rejected"

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 12: Bouton d'Annulation

**Vérifications:**
- [ ] Bouton visible uniquement pour statuts "pending" et "approved"
- [ ] Bouton caché pour "borrowed", "returned", "rejected"
- [ ] Icône de fermeture (X) visible
- [ ] Tooltip "Annuler la demande" au survol

**Cas de test:**
1. Demande "pending" → Bouton visible
2. Demande "approved" → Bouton visible
3. Demande "borrowed" → Bouton caché
4. Demande "returned" → Bouton caché
5. Demande "rejected" → Bouton caché

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 13: Processus d'Annulation

**Étapes:**
1. Cliquer sur le bouton d'annulation (X rouge)
2. Vérifier la popup de confirmation
3. Cliquer sur "OK" pour confirmer
4. Attendre la redirection

**Vérifications:**
- [ ] Popup JavaScript s'affiche
- [ ] Message: "Êtes-vous sûr de vouloir annuler cette demande de livre?"
- [ ] Boutons "OK" et "Annuler" présents
- [ ] Si "Annuler" → Rien ne se passe
- [ ] Si "OK" → Formulaire soumis
- [ ] Redirection vers la liste
- [ ] Message de succès affiché (vert)
- [ ] Statut de la demande changé en "rejected"
- [ ] Note d'annulation ajoutée avec date/heure
- [ ] Livre redevenu disponible (vérifier dans la bibliothèque)

**Statut:** [ ] Réussi / [ ] Échoué

**Notes:**
```
_______________________________________________________
```

---

### Test 14: Annulation - Cas d'Erreur

**Cas de test:**

#### A. Annuler une demande déjà empruntée
1. Modifier manuellement le statut en "borrowed"
2. Essayer d'annuler
3. **Attendu:** Message d'erreur "Cette demande ne peut pas être annulée"

#### B. Annuler la demande d'un autre étudiant
1. Copier l'URL d'annulation
2. Se déconnecter et se connecter avec un autre étudiant
3. Coller l'URL
4. **Attendu:** Erreur 404 ou 403

#### C. Annuler avec ID invalide
1. Accéder à `/student/library/my-requests/99999/cancel`
2. **Attendu:** Erreur 404

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 15: Pagination

**Vérifications:**
- [ ] 15 demandes par page
- [ ] Liens de pagination affichés si > 15 demandes
- [ ] Numérotation continue entre les pages
- [ ] Navigation entre pages fonctionne

**Statut:** [ ] Réussi / [ ] Échoué

---

## 💰 REÇUS FINANCIERS

### Test 16: Accéder aux Reçus

**URL:** `http://localhost:8000/student/finance/receipts`

**Vérifications:**
- [ ] Page se charge sans erreur
- [ ] Titre "Mes Reçus de Paiement" affiché
- [ ] Filtres disponibles (année, mois)
- [ ] Tableau des reçus affiché

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 17: Téléchargement d'un Reçu PDF

**Étapes:**
1. Cliquer sur le bouton "Télécharger" (icône download)
2. Attendre le téléchargement
3. Ouvrir le fichier PDF

**Vérifications:**
- [ ] Fichier PDF téléchargé
- [ ] Nom du fichier: `recu_{id}_{date}.pdf`
- [ ] PDF s'ouvre correctement
- [ ] En-tête avec logo de l'école
- [ ] Titre "REÇU DE PAIEMENT"
- [ ] Référence du reçu affichée
- [ ] Date de génération affichée
- [ ] Informations de l'étudiant:
  - [ ] Nom complet
  - [ ] Matricule
  - [ ] Classe
  - [ ] Section
- [ ] Détails du paiement:
  - [ ] Libellé
  - [ ] Montant total
  - [ ] Montant payé (en gras)
  - [ ] Reste à payer
  - [ ] Méthode de paiement
  - [ ] Référence de transaction (si existe)
  - [ ] Notes (si existent)
- [ ] Ligne de signature
- [ ] Pied de page avec:
  - [ ] Nom de l'école
  - [ ] URL du site
  - [ ] Message de conservation
  - [ ] Date de génération
- [ ] Watermark en arrière-plan
- [ ] Mise en page professionnelle
- [ ] Pas d'erreurs d'affichage

**Statut:** [ ] Réussi / [ ] Échoué

**Notes:**
```
_______________________________________________________
```

---

### Test 18: Impression d'un Reçu

**Étapes:**
1. Cliquer sur le bouton "Imprimer"
2. Vérifier la vue d'impression

**Vérifications:**
- [ ] Nouvelle fenêtre/onglet s'ouvre
- [ ] Vue optimisée pour l'impression
- [ ] Format A4
- [ ] Marges appropriées
- [ ] Contenu identique au PDF
- [ ] Dialogue d'impression du navigateur s'ouvre

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 19: Téléchargement Groupé

**URL:** `http://localhost:8000/student/finance/receipts/download-all?year=2025`

**Étapes:**
1. Sélectionner une année dans le filtre
2. Cliquer sur "Télécharger tous les reçus"
3. Attendre le téléchargement
4. Ouvrir le fichier PDF

**Vérifications:**
- [ ] Fichier PDF téléchargé
- [ ] Nom: `reçus_{année}_{nom_étudiant}.pdf`
- [ ] PDF contient tous les reçus de l'année
- [ ] Chaque reçu sur une page séparée
- [ ] Ordre chronologique (plus récent en premier)
- [ ] Pas d'erreur si aucun reçu

**Cas de test:**
1. Année avec plusieurs reçus
2. Année sans reçus → Message d'erreur

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 20: Caractères Spéciaux dans PDF

**Étapes:**
1. Créer un reçu avec caractères spéciaux:
   - Accents: é, è, à, ù, ç
   - Symboles: €, $, £
   - Caractères spéciaux: &, <, >
2. Télécharger le PDF
3. Vérifier l'affichage

**Vérifications:**
- [ ] Tous les caractères affichés correctement
- [ ] Pas de caractères bizarres (, ?, etc.)
- [ ] Encodage UTF-8 respecté

**Statut:** [ ] Réussi / [ ] Échoué

---

## 🔒 TESTS DE SÉCURITÉ

### Test 21: Isolation des Données

**Scénario:**
1. Se connecter comme Étudiant A
2. Noter l'ID d'une demande de livre
3. Se déconnecter
4. Se connecter comme Étudiant B
5. Essayer d'accéder à la demande de l'Étudiant A

**URLs à tester:**
- `/student/library/my-requests/{id_etudiant_A}`
- `/student/finance/receipt/{id_recu_etudiant_A}/download`
- `/student/library/my-requests/{id_etudiant_A}/cancel`

**Résultat attendu:**
- [ ] Erreur 404 (Not Found)
- [ ] Ou erreur 403 (Forbidden)
- [ ] Pas d'accès aux données d'un autre étudiant

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 22: Protection CSRF

**Vérifications:**
- [ ] Token CSRF présent dans tous les formulaires
- [ ] Champ `@csrf` dans les vues Blade
- [ ] Erreur 419 si token manquant ou invalide

**Test manuel:**
1. Inspecter le formulaire d'annulation
2. Vérifier la présence de `<input type="hidden" name="_token">`
3. Supprimer le token
4. Soumettre le formulaire
5. **Attendu:** Erreur 419

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 23: Middleware d'Authentification

**Scénario:**
1. Se déconnecter
2. Essayer d'accéder directement aux URLs

**URLs à tester:**
- `/student/dashboard`
- `/student/library/my-requests`
- `/student/finance/receipts`

**Résultat attendu:**
- [ ] Redirection vers `/login`
- [ ] Message "Veuillez vous connecter"

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 24: Middleware Student

**Scénario:**
1. Se connecter comme Admin ou Enseignant
2. Essayer d'accéder aux URLs étudiants

**Résultat attendu:**
- [ ] Erreur 403 (Forbidden)
- [ ] Ou redirection vers dashboard approprié

**Statut:** [ ] Réussi / [ ] Échoué

---

## ⚡ TESTS DE PERFORMANCE

### Test 25: Temps de Chargement du Dashboard

**Méthode:**
1. Ouvrir les DevTools (F12)
2. Onglet "Network"
3. Recharger la page
4. Noter le temps de chargement

**Critères:**
- [ ] Temps < 2 secondes (bon)
- [ ] Temps < 3 secondes (acceptable)
- [ ] Temps > 3 secondes (à optimiser)

**Temps mesuré:** _______ secondes

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 26: Requêtes N+1

**Méthode:**
1. Installer Laravel Debugbar: `composer require barryvdh/laravel-debugbar --dev`
2. Activer dans `.env`: `DEBUGBAR_ENABLED=true`
3. Recharger le dashboard
4. Vérifier l'onglet "Queries"

**Vérifications:**
- [ ] Nombre de requêtes < 20 (bon)
- [ ] Nombre de requêtes < 30 (acceptable)
- [ ] Pas de requêtes en boucle (N+1)
- [ ] Utilisation de `with()` pour eager loading

**Nombre de requêtes:** _______

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 27: Génération PDF

**Méthode:**
1. Chronométrer le téléchargement d'un reçu
2. Chronométrer le téléchargement groupé (10 reçus)

**Critères:**
- [ ] 1 reçu < 3 secondes
- [ ] 10 reçus < 10 secondes

**Temps mesurés:**
- 1 reçu: _______ secondes
- 10 reçus: _______ secondes

**Statut:** [ ] Réussi / [ ] Échoué

---

## 📱 TESTS RESPONSIVE

### Test 28: Affichage Mobile

**Appareils à tester:**
- [ ] iPhone (375px)
- [ ] iPad (768px)
- [ ] Desktop (1920px)

**Vérifications:**
- [ ] Dashboard lisible sur mobile
- [ ] Cartes empilées verticalement
- [ ] Tableaux scrollables horizontalement
- [ ] Boutons accessibles
- [ ] Texte lisible (taille appropriée)
- [ ] Images/icônes bien dimensionnées

**Statut:** [ ] Réussi / [ ] Échoué

---

## 🐛 TESTS DE CAS LIMITES

### Test 29: Étudiant Sans Données

**Scénario:**
Créer un nouvel étudiant sans:
- Devoirs
- Livres empruntés
- Paiements
- Présences
- Supports pédagogiques

**Vérifications:**
- [ ] Dashboard se charge sans erreur
- [ ] Messages appropriés affichés:
  - [ ] "Aucun devoir en attente"
  - [ ] "Aucun livre emprunté"
  - [ ] "Aucune donnée de présence"
  - [ ] "Aucun support pédagogique"
- [ ] Pas d'erreur PHP
- [ ] Pas d'erreur JavaScript

**Statut:** [ ] Réussi / [ ] Échoué

---

### Test 30: Données Volumineuses

**Scénario:**
Créer un étudiant avec:
- 100+ devoirs
- 50+ demandes de livres
- 200+ reçus

**Vérifications:**
- [ ] Pagination fonctionne correctement
- [ ] Pas de timeout
- [ ] Performance acceptable
- [ ] Pas d'erreur de mémoire

**Statut:** [ ] Réussi / [ ] Échoué

---

## 📊 RÉSUMÉ DES TESTS

### Statistiques

**Total de tests:** 30

**Tests réussis:** _____ / 30
**Tests échoués:** _____ / 30
**Tests non effectués:** _____ / 30

**Taux de réussite:** _____ %

---

### Bugs Trouvés

| # | Description | Sévérité | Page/Fonction | Statut |
|---|-------------|----------|---------------|--------|
| 1 |             | Critique/Majeur/Mineur |  | Ouvert/Résolu |
| 2 |             |          |               |        |
| 3 |             |          |               |        |

---

### Améliorations Suggérées

1. _______________________________________________________
2. _______________________________________________________
3. _______________________________________________________

---

## ✅ VALIDATION FINALE

- [ ] Tous les tests critiques passent
- [ ] Aucun bug bloquant
- [ ] Performance acceptable
- [ ] Sécurité vérifiée
- [ ] Documentation à jour

**Validé par:** _______________________
**Date:** _______________________
**Signature:** _______________________

---

**🎉 Fin des Tests**

Pour toute question ou problème, contacter:
- **Email:** tshitshob@gmail.com
- **Téléphone:** +243812380589
