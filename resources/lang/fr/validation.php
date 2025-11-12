<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lignes de langue de validation
    |--------------------------------------------------------------------------
    |
    | Les lignes de langue suivantes contiennent les messages d'erreur par défaut
    | utilisés par la classe de validation. Certaines de ces règles ont plusieurs
    | versions comme les règles de taille. N'hésitez pas à ajuster chacun de ces
    | messages ici.
    |
    */

    'accepted'             => 'Le champ :attribute doit être accepté.',
    'active_url'           => 'Le champ :attribute n\'est pas une URL valide.',
    'after'                => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_or_equal'       => 'Le champ :attribute doit être une date postérieure ou égale au :date.',
    'alpha'                => 'Le champ :attribute ne peut contenir que des lettres.',
    'alpha_dash'           => 'Le champ :attribute ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
    'alpha_num'            => 'Le champ :attribute ne peut contenir que des lettres et des chiffres.',
    'array'                => 'Le champ :attribute doit être un tableau.',
    'before'               => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'      => 'Le champ :attribute doit être une date antérieure ou égale au :date.',
    'between'              => [
        'numeric' => 'Le champ :attribute doit être compris entre :min et :max.',
        'file'    => 'Le champ :attribute doit être compris entre :min et :max kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir entre :min et :max caractères.',
        'array'   => 'Le champ :attribute doit contenir entre :min et :max éléments.',
    ],
    'boolean'              => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'            => 'La confirmation du champ :attribute ne correspond pas.',
    'date'                 => 'Le champ :attribute n\'est pas une date valide.',
    'date_format'          => 'Le champ :attribute ne correspond pas au format :format.',
    'different'            => 'Les champs :attribute et :other doivent être différents.',
    'digits'               => 'Le champ :attribute doit contenir :digits chiffres.',
    'digits_between'       => 'Le champ :attribute doit contenir entre :min et :max chiffres.',
    'dimensions'           => 'Le champ :attribute a des dimensions d\'image non valides.',
    'distinct'             => 'Le champ :attribute a une valeur en double.',
    'email'                => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists'               => 'Le champ :attribute sélectionné n\'est pas valide.',
    'file'                 => 'Le champ :attribute doit être un fichier.',
    'filled'               => 'Le champ :attribute doit avoir une valeur.',
    'gt'                   => [
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'file'    => 'Le champ :attribute doit être supérieur à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir plus de :value caractères.',
        'array'   => 'Le champ :attribute doit contenir plus de :value éléments.',
    ],
    'gte'                  => [
        'numeric' => 'Le champ :attribute doit être supérieur ou égal à :value.',
        'file'    => 'Le champ :attribute doit être supérieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au moins :value caractères.',
        'array'   => 'Le champ :attribute doit contenir au moins :value éléments.',
    ],
    'image'                => 'Le champ :attribute doit être une image.',
    'in'                   => 'Le champ :attribute sélectionné n\'est pas valide.',
    'in_array'             => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'              => 'Le champ :attribute doit être un entier.',
    'ip'                   => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'                 => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'                 => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'                 => 'Le champ :attribute doit être une chaîne JSON valide.',
    'lt'                   => [
        'numeric' => 'Le champ :attribute doit être inférieur à :value.',
        'file'    => 'Le champ :attribute doit être inférieur à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir moins de :value caractères.',
        'array'   => 'Le champ :attribute doit contenir moins de :value éléments.',
    ],
    'lte'                  => [
        'numeric' => 'Le champ :attribute doit être inférieur ou égal à :value.',
        'file'    => 'Le champ :attribute doit être inférieur ou égal à :value kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au maximum :value caractères.',
        'array'   => 'Le champ :attribute ne doit pas contenir plus de :value éléments.',
    ],
    'max'                  => [
        'numeric' => 'Le champ :attribute ne peut pas être supérieur à :max.',
        'file'    => 'Le champ :attribute ne peut pas être supérieur à :max kilo-octets.',
        'string'  => 'Le champ :attribute ne peut pas contenir plus de :max caractères.',
        'array'   => 'Le champ :attribute ne peut pas contenir plus de :max éléments.',
    ],
    'mimes'                => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'            => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'                  => [
        'numeric' => 'Le champ :attribute doit être au moins :min.',
        'file'    => 'Le champ :attribute doit être au moins :min kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le champ :attribute doit contenir au moins :min éléments.',
    ],
    'not_in'               => 'Le champ :attribute sélectionné n\'est pas valide.',
    'not_regex'            => 'Le format du champ :attribute n\'est pas valide.',
    'numeric'              => 'Le champ :attribute doit être un nombre.',
    'present'              => 'Le champ :attribute doit être présent.',
    'regex'                => 'Le format du champ :attribute n\'est pas valide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_if'          => 'Le champ :attribute est obligatoire quand :other est :value.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire quand :values sont présents.',
    'required_without'     => 'Le champ :attribute est obligatoire quand :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est obligatoire quand aucun des :values n\'est présent.',
    'same'                 => 'Les champs :attribute et :other doivent correspondre.',
    'size'                 => [
        'numeric' => 'Le champ :attribute doit être :size.',
        'file'    => 'Le champ :attribute doit être :size kilo-octets.',
        'string'  => 'Le champ :attribute doit contenir :size caractères.',
        'array'   => 'Le champ :attribute doit contenir :size éléments.',
    ],
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'             => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'               => 'Le champ :attribute a déjà été pris.',
    'uploaded'             => 'Le champ :attribute n\'a pas pu être téléchargé.',
    'url'                  => 'Le format du champ :attribute n\'est pas valide.',

    /*
    |--------------------------------------------------------------------------
    | Lignes de langue de validation personnalisées
    |--------------------------------------------------------------------------
    |
    | Ici, vous pouvez spécifier des messages de validation personnalisés pour
    | les attributs en utilisant la convention "attribute.rule" pour nommer les
    | lignes. Cela permet de spécifier rapidement une ligne de langue personnalisée
    | spécifique pour une règle d'attribut donnée.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'message-personnalisé',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Attributs de validation personnalisés
    |--------------------------------------------------------------------------
    |
    | Les lignes de langue suivantes sont utilisées pour échanger notre
    | espace réservé d'attribut avec quelque chose de plus convivial pour
    | le lecteur comme "Adresse E-Mail" au lieu de "email". Cela nous aide
    | simplement à rendre notre message plus expressif.
    |
    */

    'attributes' => [
        'name' => 'nom',
        'username' => 'nom d\'utilisateur',
        'email' => 'adresse e-mail',
        'first_name' => 'prénom',
        'last_name' => 'nom de famille',
        'password' => 'mot de passe',
        'password_confirmation' => 'confirmation du mot de passe',
        'city' => 'ville',
        'country' => 'pays',
        'address' => 'adresse',
        'phone' => 'téléphone',
        'mobile' => 'portable',
        'age' => 'âge',
        'sex' => 'sexe',
        'gender' => 'genre',
        'day' => 'jour',
        'month' => 'mois',
        'year' => 'année',
        'hour' => 'heure',
        'minute' => 'minute',
        'second' => 'seconde',
        'title' => 'titre',
        'content' => 'contenu',
        'description' => 'description',
        'excerpt' => 'extrait',
        'date' => 'date',
        'time' => 'heure',
        'available' => 'disponible',
        'size' => 'taille',
        'file' => 'fichier',
        'image' => 'image',
        'photo' => 'photo',
        'avatar' => 'avatar',
        'subject' => 'matière',
        'message' => 'message',
        'terms' => 'conditions',
        'privacy' => 'confidentialité',
    ],

];
