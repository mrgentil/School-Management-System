<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyClass;
use App\Models\Subject;

class GuideUtilisationInterfaceSeeder extends Seeder
{
    public function run(): void
    {
        echo "📋 GUIDE D'UTILISATION DE L'INTERFACE COTES PAR MATIÈRE\n\n";
        
        echo "🎯 ÉTAPES À SUIVRE:\n\n";
        
        echo "1️⃣ ACCÈS À L'INTERFACE:\n";
        echo "   ├─ 🌐 URL: http://localhost:8000/subject-grades-config\n";
        echo "   ├─ 📚 Menu: Académique → 🧮 Cotes par Matière (RDC)\n";
        echo "   └─ 🔐 Connexion: Super Admin requis\n\n";
        
        echo "2️⃣ SÉLECTION DE CLASSE:\n";
        echo "   ├─ 📋 Vous verrez un dropdown 'Sélectionner une Classe'\n";
        echo "   ├─ 🏫 Classes disponibles:\n";
        
        $classes = MyClass::all();
        foreach ($classes as $class) {
            $subjectCount = Subject::where('my_class_id', $class->id)->count();
            echo "   │  ├─ " . ($class->full_name ?: $class->name) . " ({$subjectCount} matières)\n";
        }
        
        echo "   └─ 🖱️ Cliquez sur une classe pour la sélectionner\n\n";
        
        echo "3️⃣ CE QUI VA SE PASSER APRÈS SÉLECTION:\n";
        echo "   ├─ 🔄 La page se recharge automatiquement\n";
        echo "   ├─ 📊 Un tableau apparaît avec toutes les matières de la classe\n";
        echo "   ├─ 📋 Chaque ligne = une matière avec ses cotes\n";
        echo "   └─ 🎯 Vous pourrez configurer:\n";
        echo "      ├─ Cote Période (ex: 20 points)\n";
        echo "      ├─ Cote Examen (ex: 40 points)\n";
        echo "      └─ Ratio automatique (ex: 1:2.0)\n\n";
        
        echo "4️⃣ EXEMPLE CONCRET - CLASSE '6ème Sec A Electronique':\n";
        
        $testClass = MyClass::first();
        if ($testClass) {
            $subjects = Subject::where('my_class_id', $testClass->id)->get();
            echo "   ├─ 📚 Matières disponibles: " . $subjects->count() . "\n";
            
            foreach ($subjects->take(5) as $index => $subject) {
                echo "   │  ├─ " . ($index + 1) . ". {$subject->name}\n";
            }
            
            if ($subjects->count() > 5) {
                echo "   │  └─ ... et " . ($subjects->count() - 5) . " autres\n";
            }
        }
        
        echo "   └─ 💡 Configuration suggérée:\n";
        echo "      ├─ Mathématiques: Période 20pts, Examen 40pts\n";
        echo "      ├─ Français: Période 25pts, Examen 50pts\n";
        echo "      ├─ Anglais: Période 20pts, Examen 40pts\n";
        echo "      └─ Électronique: Période 30pts, Examen 60pts\n\n";
        
        echo "5️⃣ BOUTONS ET ACTIONS DISPONIBLES:\n";
        echo "   ├─ 🟢 'Initialiser par Défaut': Crée config 20/40 pour toutes\n";
        echo "   ├─ 🔵 'Dupliquer depuis une autre classe': Copie config existante\n";
        echo "   ├─ 🟡 'Réinitialiser Tout': Remet tout à 20/40\n";
        echo "   ├─ 🔄 Boutons individuels de réinitialisation par matière\n";
        echo "   └─ 💾 'Sauvegarder la Configuration': Enregistre tout\n\n";
        
        echo "6️⃣ FONCTIONNALITÉS INTELLIGENTES:\n";
        echo "   ├─ 📊 Calcul automatique des ratios (Examen/Période)\n";
        echo "   ├─ 🎨 Couleurs des badges selon le ratio:\n";
        echo "   │  ├─ 🟡 Jaune: ratio < 1.5 (examen trop faible)\n";
        echo "   │  ├─ 🔵 Bleu: ratio 1.5-3.0 (équilibré)\n";
        echo "   │  └─ 🔴 Rouge: ratio > 3.0 (examen trop fort)\n";
        echo "   ├─ ✅ Validation en temps réel\n";
        echo "   └─ 💾 Sauvegarde sécurisée\n\n";
        
        echo "7️⃣ APRÈS SAUVEGARDE:\n";
        echo "   ├─ ✅ Message de confirmation\n";
        echo "   ├─ 📊 Affichage des configurations actuelles\n";
        echo "   ├─ 🔄 Possibilité de modifier à nouveau\n";
        echo "   └─ 📋 Prêt pour une autre classe\n\n";
        
        echo "🚨 SI VOUS NE VOYEZ RIEN APRÈS SÉLECTION:\n";
        echo "   ├─ ❓ Vérifiez que la classe a des matières assignées\n";
        echo "   ├─ 🔄 Rafraîchissez la page (F5)\n";
        echo "   ├─ 🔐 Vérifiez que vous êtes connecté en Super Admin\n";
        echo "   └─ 📞 Contactez le support si le problème persiste\n\n";
        
        echo "💡 CONSEILS D'UTILISATION:\n";
        echo "   ├─ 🎯 Commencez par 'Initialiser par Défaut'\n";
        echo "   ├─ ⚖️ Utilisez des ratios cohérents (1:2 recommandé)\n";
        echo "   ├─ 📊 Adaptez selon l'importance de la matière\n";
        echo "   ├─ 💾 Sauvegardez régulièrement\n";
        echo "   └─ 🔄 Dupliquez pour classes similaires\n\n";
        
        echo "🎉 VOUS ÊTES PRÊT!\n";
        echo "Allez sur l'interface et suivez ce guide étape par étape!\n";
        echo "URL: http://localhost:8000/subject-grades-config\n";
    }
}
