# 📋 TODO - Améliorations Espace Étudiant LAV_SMS

## ✅ COMPLÉTÉ

### Phase 1 - Fonctionnalités Critiques

1. ✅ **Dashboard Étudiant Personnalisé**
   - ✅ Contrôleur créé (`DashboardController.php`)
   - ✅ Vue créée (`dashboard.blade.php`)
   - ✅ Route ajoutée (`/student/dashboard`)
   - ✅ Statistiques rapides (devoirs, présences, livres, messages)
   - ✅ Devoirs à venir (5 prochains)
   - ✅ Livres empruntés actuellement
   - ✅ Résumé financier
   - ✅ Statistiques de présence du mois
   - ✅ Supports pédagogiques récents
   - ✅ Notifications récentes

2. ✅ **Annulation de Demandes de Livres**
   - ✅ Méthode `cancel()` ajoutée au contrôleur
   - ✅ Route ajoutée (`/student/library/my-requests/{id}/cancel`)
   - ✅ Vue mise à jour avec bouton d'annulation
   - ✅ Vérification des permissions (`canBeCancelled()`)
   - ✅ Mise à jour du statut du livre

3. ✅ **Téléchargement de Reçus PDF**
   - ✅ Méthode `downloadReceipt()` ajoutée
   - ✅ Méthode `printReceipt()` ajoutée
   - ✅ Méthode `downloadAllReceipts()` ajoutée
   - ✅ Routes ajoutées
   - ✅ Vue PDF existante (`receipt_pdf.blade.php`)
   - ✅ Boutons de téléchargement dans la vue

---

## 🔄 EN COURS

### Phase 2 - Fonctionnalités Essentielles

4. ⏳ **Centre de Notifications**
   - [ ] Migration pour table `notifications`
   - [ ] Système de notifications Laravel
   - [ ] Badge de compteur dans le menu
   - [ ] Page de notifications
   - [ ] Marquage comme lu
   - [ ] Notifications en temps réel (Pusher)

5. ⏳ **Module Emploi du Temps**
   - [ ] Contrôleur `TimetableController`
   - [ ] Vue hebdomadaire
   - [ ] Vue journalière
   - [ ] Affichage des cours du jour dans le dashboard
   - [ ] Export iCal/Google Calendar

---

## 📝 À FAIRE

### Phase 3 - Améliorations UX

6. **Système de Favoris (Bibliothèque)**
   - [ ] Migration `book_favorites`
   - [ ] Modèle `BookFavorite`
   - [ ] Méthodes dans `LibraryController`
   - [ ] Boutons favoris dans les vues
   - [ ] Page "Mes favoris"

7. **Justification d'Absences**
   - [ ] Migration `absence_justifications`
   - [ ] Modèle `AbsenceJustification`
   - [ ] Formulaire de justification
   - [ ] Upload de certificat médical
   - [ ] Validation par l'administration

8. **Notes et Feedback sur Devoirs**
   - [ ] Affichage des notes reçues
   - [ ] Commentaires de l'enseignant
   - [ ] Historique des soumissions
   - [ ] Statistiques de performance

9. **Recherche Avancée**
   - [ ] Recherche globale dans le menu
   - [ ] Filtres avancés (bibliothèque, supports)
   - [ ] Recherche en texte intégral

10. **Statistiques de Performance**
    - [ ] Graphiques d'évolution des notes
    - [ ] Comparaison avec la moyenne de classe
    - [ ] Prédiction de la moyenne finale
    - [ ] Taux de présence par matière

11. **Export de Documents**
    - [ ] Export relevé de présences (PDF)
    - [ ] Export relevé de notes (PDF)
    - [ ] Export historique de paiements (Excel)

### Phase 4 - Fonctionnalités Avancées

12. **Paiement en Ligne**
    - [ ] Intégration M-Pesa
    - [ ] Intégration Orange Money
    - [ ] Intégration Airtel Money
    - [ ] Historique des transactions
    - [ ] Reçus automatiques

13. **Module Social/Communauté**
    - [ ] Profil étudiant public
    - [ ] Forum de discussion par classe
    - [ ] Partage de ressources
    - [ ] Groupes d'étude

14. **Système de Badges**
    - [ ] Badges d'assiduité
    - [ ] Badges de performance
    - [ ] Badges de participation
    - [ ] Page de récompenses

15. **Chat en Temps Réel**
    - [ ] Intégration WebSocket
    - [ ] Chat avec enseignants
    - [ ] Chat de groupe
    - [ ] Notifications push

16. **Application Mobile (PWA)**
    - [ ] Configuration PWA
    - [ ] Service Worker
    - [ ] Mode hors ligne
    - [ ] Notifications push
    - [ ] Installation sur mobile

17. **Module Demandes Administratives**
    - [ ] Demande de certificat de scolarité
    - [ ] Demande d'attestation
    - [ ] Demande de carte d'étudiant
    - [ ] Demande de relevé de notes
    - [ ] Suivi des demandes

---

## 🐛 BUGS À CORRIGER

- [ ] Vérifier la compatibilité des noms de colonnes dans `BookRequest`
- [ ] Tester les relations Eloquent (student, book, etc.)
- [ ] Vérifier les permissions sur toutes les routes
- [ ] Tester la génération PDF avec DomPDF
- [ ] Vérifier les traductions françaises

---

## 🔧 AMÉLIORATIONS TECHNIQUES

### Base de données
- [ ] Ajouter des index sur les colonnes fréquemment recherchées
- [ ] Optimiser les requêtes N+1
- [ ] Ajouter des contraintes de clés étrangères manquantes

### Performance
- [ ] Implémenter le cache pour le dashboard
- [ ] Lazy loading des images
- [ ] Minification des assets
- [ ] CDN pour les ressources statiques

### Sécurité
- [ ] Rate limiting sur les actions sensibles
- [ ] Validation stricte des uploads
- [ ] Protection CSRF sur tous les formulaires
- [ ] Logs d'audit pour les actions importantes

### Tests
- [ ] Tests unitaires pour les contrôleurs
- [ ] Tests d'intégration pour les flux complets
- [ ] Tests de performance
- [ ] Tests de sécurité

---

## 📊 MÉTRIQUES À SUIVRE

- [ ] Taux de connexion quotidien
- [ ] Temps passé sur la plateforme
- [ ] Pages les plus visitées
- [ ] Taux de soumission des devoirs
- [ ] Taux de consultation des supports
- [ ] Nombre de demandes de livres
- [ ] Taux de paiement à temps

---

## 📝 NOTES

### Prochaines étapes immédiates:
1. Tester le dashboard étudiant
2. Tester l'annulation de demandes de livres
3. Tester le téléchargement de reçus PDF
4. Créer le centre de notifications
5. Implémenter le module emploi du temps

### Dépendances à vérifier:
- Laravel 8.x
- DomPDF (barryvdh/laravel-dompdf)
- Pusher (pour notifications temps réel)
- Laravel Notifications

### Configuration requise:
- Configurer SMTP pour les emails
- Configurer Pusher pour les notifications
- Configurer les permissions de fichiers
- Configurer le stockage public

---

**Dernière mise à jour:** {{ date('d/m/Y H:i') }}
**Développeur:** BLACKBOXAI
**Projet:** LAV_SMS - School Management System
