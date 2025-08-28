# Gruppi di acquisto solidali 
Ordini collettivi con BuddyPress, WooCommerce, Wallet e Dokan per gruppi di acquisto solidali.

- Contributors: marcogiustini  
- Tags: buddypress, woocommerce, dokan, wallet, group orders  
- Requires at least: 6.0  
- Tested up to: 6.8  
- Requires PHP: 7.4  
- Stable tag: 1.0.0  
- License: GPLv2 or later  
- License URI: https://www.gnu.org/licenses/gpl-2.0.html

# Description 

Questo plugin consente ai gruppi BuddyPress di organizzare ordini collettivi solidali con prodotti WooCommerce, gestire wallet condivisi, confermare ritiri e smistare automaticamente gli ordini ai venditori Dokan.

Funzionalità principali:
* Partecipazione all’ordine collettivo direttamente dal gruppo BuddyPress
* Aggregazione automatica degli ordini e smistamento ai venditori
* Gestione punti di ritiro (globali e per gruppo)
* Conferma ritiro da parte degli utenti con badge visivo
* Storico ritiri e promemoria automatici
* Wallet virtuale per utenti e gruppi
* Email riepilogative ai venditori Dokan
* Dashboard venditore con ordini collettivi assegnati

# Installation 

1. Carica la cartella del plugin in `wp-content/plugins/`
2. Attiva il plugin da “Plugin” → “Attiva”
3. Assicurati che WooCommerce, BuddyPress e Dokan siano attivi

# Frequently Asked Questions 

= È compatibile con WPML o Loco Translate? =  
Sì, il plugin include il file `.pot` ed è completamente internazionalizzabile.

= Posso usare il plugin senza Dokan? =  
Sì, ma le funzionalità di smistamento venditori saranno disattivate.

= Dove si imposta il punto di ritiro? =  
Nel backend del gruppo BuddyPress o globalmente tramite le impostazioni del plugin.

# Changelog 

= 1.0.0 =
* Versione iniziale con supporto per ordini collettivi, wallet, punti di ritiro e compatibilità Dokan.

# Upgrade Notice 

Aggiorna alla versione 1.0.0 per abilitare il supporto completo a WooCommerce 6.8 e BuddyPress 12.x.
