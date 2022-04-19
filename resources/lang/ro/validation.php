<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted' => 'Trebuie acceptat :attribute.',
    'active_url' => 'Atributul :attribute nu este o adresa URL valida.',
    'after' => 'Atributul :attribute trebuie sa fie o data valida dupa :date.',
    'after_or_equal' => 'Atributul :attribute trebuie sa fie o data valida dupa sau egala cu :date.',
    'alpha' => 'Atributul :attribute poate contine doar litere.',
    'alpha_dash' => 'Atributul :attribute poate contine doar litere, numere, minus si _.',
    'alpha_num' => 'Atributul :attribute poate contine doar litere si numere.',
    'array' => 'Atributul :attribute trebuie sa fie de tip multime.',
    'before' => 'Atributul :attribute trebuie sa fie o data inainte de :date.',
    'before_or_equal' => 'Atributul :attribute trebuie sa fie o data inainte sau egala cu :date.',
    'between' => [
        'numeric' => 'Atributul :attribute trebuie sa fie intre :min si :max.',
        'file' => 'Atributul :attribute trebuie sa fie intre :min si :max kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie intre :min si :max caractere.',
        'array' => 'Atributul :attribute trebuie sa fie intre :min si :max elemente.',
    ],
    'boolean' => 'Atributul :attribute trebuie sa fie adevarat sau fals.',
    'confirmed' => 'Atributul :attribute confirmare nu corespunde.',
    'date' => 'Atributul :attribute nu este o data valida.',
    'date_equals' => 'Atributul :attribute trebuie sa fie o data egala cu :date.',
    'date_format' => 'Atributul :attribute nu corespunde formatului :format.',
    'different' => 'Atributul :attribute si :other trebuie sa fie diferite.',
    'digits' => 'Atributul :attribute trebuie sa fie :digits cifre.',
    'digits_between' => 'Atributul :attribute trebuie sa fie intre :min si :max cifre.',
    'dimensions' => 'Atributul :attribute are dimeniunile imaginii invalide.',
    'distinct' => 'Atributul :attribute are valoare duplicata.',
    'email' => 'Atributul :attribute trebuie sa fie o adresa de e-mail valida.',
    'ends_with' => 'Atributul :attribute trebuie sa se termine cu una din urmatoarele: :values.',
    'exists' => 'Atributul selectat :attribute este invalid.',
    'file' => 'Atributul :attribute trebuie sa fie fisier.',
    'filled' => 'Atributul :attribute trebuie sa contina o valoare.',
    'gt' => [
        'numeric' => 'Atributul :attribute trebuie sa fie mai mare decat :value.',
        'file' => 'Atributul :attribute trebuie sa fie mai mare decat :value kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie mai mare decat :value caractere.',
        'array' => 'Atributul :attribute trebuie sa contina mai multe elemente decat :value.',
    ],
    'gte' => [
        'numeric' => 'Atributul :attribute trebuie sa fie mai mare sau egal cu :value.',
        'file' => 'Atributul :attribute trebuie sa fie mai mare sau egal cu :value kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie mai mare sau egal cu :value caractere.',
        'array' => 'Atributul :attribute trebuie sa contina :value elemente sau mai multe.',
    ],
    'image' => 'Atributul :attribute trebuie sa fie o imagine.',
    'in' => 'Atributul selected :attribute este invalid.',
    'in_array' => 'Atributul :attribute nu exista in :other.',
    'integer' => 'Atributul :attribute trebuie sa fie numeric.',
    'ip' => 'Atributul :attribute trebuie sa fie format valid de: IP address.',
    'ipv4' => 'Atributul :attribute trebuie sa fie format valid de: IPv4 address.',
    'ipv6' => 'Atributul :attribute trebuie sa fie format valid de: IPv6 address.',
    'json' => 'Atributul :attribute trebuie sa fie format valid de: JSON string.',
    'lt' => [
        'numeric' => 'Atributul :attribute trebuie sa fie mai mic decat :value.',
        'file' => 'Atributul :attribute trebuie sa fie mai mic decat :value kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie mai mic decat :value caractere.',
        'array' => 'Atributul :attribute trebuie sa contina mai putin de :value elemente.',
    ],
    'lte' => [
        'numeric' => 'Atributul :attribute trebuie sa fie mai mic sau egal cu :value.',
        'file' => 'Atributul :attribute trebuie sa fie mai mic sau egal cu :value kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie mai mic sau egal cu :value caractere.',
        'array' => 'Atributul :attribute nu trebuie sa contina mai mult de :value elemente.',
    ],
    'max' => [
        'numeric' => 'Atributul :attribute nu trebuie sa fie mai mare decat :max.',
        'file' => 'Atributul :attribute nu trebuie sa fie mai mare decat :max kilobytes.',
        'string' => 'Atributul :attribute nu trebuie sa fie mai mare de :max caractere.',
        'array' => 'Atributul :attribute nu trebuie sa contina mai mult de :max elemente.',
    ],
    'mimes' => 'Atributul :attribute trebuie sa fie un fisier de tipul: :values.',
    'mimetypes' => 'Atributul :attribute trebuie sa fie un fisier de tipul: :values.',
    'min' => [
        'numeric' => 'Atributul :attribute trebuie sa fie minim :min.',
        'file' => 'Atributul :attribute trebuie sa fie de minim :min kilobytes.',
        'string' => 'Atributul :attribute trebuie sa contina minim :min catactere.',
        'array' => 'Atributul :attribute trebuie sa contina minim :min elemente.',
    ],
    'not_in' => 'Atributul selectat :attribute este invalid.',
    'not_regex' => 'Atributul :attribute are format invalid.',
    'numeric' => 'Atributul :attribute trebuie sa fie numar.',
    'password' => 'Atributul password este incorect.',
    'present' => 'Atributul :attribute trebuie sa fie prezent.',
    'regex' => 'Atributul :attribute are format invalid.',
    'required' => 'Atributul :attribute este obligatoriu.',
    'required_if' => 'Atributul :attribute este obligatoriu cand :other este :value.',
    'required_unless' => 'Atributul :attribute este obligatoriu doar daca :other este in :values.',
    'required_with' => 'Atributul :attribute este obligatoriu cand valorile :values sunt prezente.',
    'required_with_all' => 'Atributul :attribute este obligatoriu cand toate valorile :values sunt prezente.',
    'required_without' => 'Atributul :attribute este obligatoriu cand valorile :values nu sunt prezente.',
    'required_without_all' => 'Atributul :attribute este obligatoriu cand valorile :values nu sunt prezente.',
    'same' => 'Atributul :attribute si :other trebuie sa fie identice.',
    'size' => [
        'numeric' => 'Atributul :attribute trebuie sa fie de :size.',
        'file' => 'Atributul :attribute trebuie sa fie de :size kilobytes.',
        'string' => 'Atributul :attribute trebuie sa fie de :size caractere.',
        'array' => 'Atributul :attribute trebuie sa contina :size elemente.',
    ],
    'starts_with' => 'Atributul :attribute trebuie sa inceapa cu una din urmatoarele valori: :values.',
    'string' => 'Atributul :attribute trebuie sa fie un sir de caractere.',
    'timezone' => 'Atributul :attribute trebuie sa fie o zona valida.',
    'unique' => 'Atributul :attribute exista deja.',
    'uploaded' => 'Atributul :attribute a esuat incarcarea.',
    'url' => 'Atributul :attribute are format invalid.',
    'uuid' => 'Atributul :attribute trebuie sa fie un id tip UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
     */

    'attributes' => [
        'subject' => 'Subiect',
        'name' => 'Numele',
        'phone' => 'Telefon',
        'description' => 'Descriere',
        'categories' => 'Categorii',
        'city' => 'Localitate',
        'message' => 'Mesaj',
        'the_files' => 'Fisiere',
        'company_type' => 'forma organizare',
    ],

];
