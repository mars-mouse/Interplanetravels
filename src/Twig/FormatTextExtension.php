<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


class FormatTextExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // chaineCaracteres|format_text(longueur_maximum)
            new TwigFilter('format_text', [$this, 'formatText']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('expires_in', [$this, 'expiresIn']),
        ];
    }

    /**
     * Si la chaine de caractères dépasse la longueur donnée, elle sera coupée au dernier mot en-dessous de cette longueur,
     * puis on ajoute "... (see more)"
     */
    public function formatText(string $text, int $maxLength = 100)
    {
        $formattedText = $text;

        if (strlen($formattedText) > $maxLength) {
            // On éclate la chaîne en tableau de mots
            $wordsArray = explode(' ', $formattedText);

            // On retire du tableau les mots qui font dépasser les 100 caractères
            // initialisation des variables
            $charCount = 0;
            $i = 0;
            $newWordsArray = [];    // on met les mots dans une nouvelle array

            // on compte pour le premier mot
            $charCount += strlen($wordsArray[$i]);

            while ($charCount < $maxLength) {
                // on ne dépasse pas, on peut enregistrer le mot
                $newWordsArray[] = $wordsArray[$i];

                // on passe au mot suivant
                $i++;
                // on compte pour le mot suivant et on ajoute 1 pour l'espace entre ce mot et le précédent
                $charCount += strlen($wordsArray[$i]) + 1;
            }
            // on sort de la boucle avant d'enregistrer le mot qui fait dépasser
            // notre tableau peut maintenant être remis en string

            $formattedText = implode(" ", $newWordsArray);

            // on rajoute les points de suspension
            $formattedText .= "... (see&nbsp;more)";
        }

        return $formattedText;
    }

    /**
     * Prend un objet date, le compare avec la date actuel et renvoie la différence
     * en années, mois, jours ou heures, minutes dans une chaine de caractères.
     * Ex: "1 year 3 months 4 days"
     */
    public function expiresIn($date): string
    {
        // date() crée la date d'aujourd'hui en format string
        // date_create() crée un objet date d'une string
        $currentDate = date_create(date('Y-m-d'));

        // date_diff() renvoie la différence entre les dates sous forme d'objet
        $timeDiff = date_diff($currentDate, $date);

        // on crée donc une string qui affichera ce résultat proprement
        $expiration = '';

        // s'il y a une différence en années (peu probable)
        if ($timeDiff->y > 0) {
            $expiration .= $timeDiff->y . ' year';  // ex: "12 year"
            if ($timeDiff->y > 1) {
                $expiration .= 's'; // ex devient : "12 years"
            }
        }
        // s'il y a une différence en mois
        if ($timeDiff->m > 0) {
            // s'il y a qqch avant, on ajoute un espace pour séparer
            if ($expiration !== '') {
                $expiration .= ' ';
            }
            // même chose que pour les années
            $expiration .= $timeDiff->m . ' month';
            if ($timeDiff->m > 1) {
                $expiration .= 's';
            }
        }
        // s'il y a une différence en jours
        if ($timeDiff->d > 0) {
            if ($expiration !== '') {
                $expiration .= ' ';
            }
            $expiration .= $timeDiff->d . ' day';
            if ($timeDiff->d > 1) {
                $expiration .= 's';
            }
        }

        // Pour les heures et les minutes, on ne les affiche que quand il reste moins d'un jour
        // Pour ce cas, y, m et d sont === 0, donc $expiration est resté ''
        if ($expiration === '') {
            if ($timeDiff->h > 0) {
                $expiration .= $timeDiff->h . ' hour';
                if ($timeDiff->h > 1) {
                    $expiration .= 's';
                }
            }

            if ($timeDiff->i > 0) {
                if ($expiration !== '') {
                    $expiration .= ' ';
                }
                $expiration .= $timeDiff->i . ' minute';
                if ($timeDiff->i > 1) {
                    $expiration .= 's';
                }
            }
        }

        return $expiration;
    }
}