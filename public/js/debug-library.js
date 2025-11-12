// Fichier de débogage pour la bibliothèque
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DÉBUT DU DÉBOGAGE ===');
    
    // Vérification des boutons
    const buttons = document.querySelectorAll('button[type="submit"]');
    console.log('Boutons trouvés:', buttons.length);
    
    // Vérification des formulaires
    const forms = document.querySelectorAll('form');
    console.log('Formulaires trouvés:', forms.length);
    
    // Afficher les informations sur chaque formulaire
    forms.forEach((form, index) => {
        console.log(`Formulaire ${index + 1}:`, {
            action: form.action,
            method: form.method,
            hasCSRF: form.querySelector('[name="_token"]') ? 'Oui' : 'Non'
        });
    });
    
    // Vérification de jQuery et SweetAlert
    console.log('jQuery:', typeof $ === 'function' ? 'Chargé' : 'Non chargé');
    console.log('SweetAlert:', typeof Swal === 'function' ? 'Chargé' : 'Non chargé');
    
    console.log('=== FIN DU DÉBOGAGE ===');
    
    // Afficher une alerte si tout est chargé
    alert('Le script de débogage est chargé. Veuillez vérifier la console (F12) pour les détails.');
});
