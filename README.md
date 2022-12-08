

# **CLIENT AREA** 
Ceci est l'espace client d'Azuracom. Il permettra aux clients de consulter le solde de leur carnet de maintenance, leurs projets, documents personnels, factures etc. 

Client Area est connecté avec [Azuracore API](https://github.com/azuracom/azuracore) pour accéder à base de données.

# Table des matières
<!-- vscode-markdown-toc -->
* 1. [Tableau de bord](#Tableaudebord)
	* 1.1. [Mes projets](#Mesprojets)
	* 1.2. [Carnet de maintenance](#Carnetdemaintenance)
	* 1.3. [Opération commerciale](#Oprationcommerciale)
	* 1.4. [Mon agence](#Monagence)
	* 1.5. [Actualités](#Actualits)
* 2. [Mon profil](#Monprofil)
* 3. [Mes Documents](#MesDocuments)
* 4. [Mes Projets](#MesProjets)
* 5. [Assistance](#Assistance)
* 6. [Front-end](#Front-end)
* 7. [Encore des questions ?](#Encoredesquestions)



##  1. <a name='Tableaudebord'></a>Tableau de bord

###  1.1. <a name='Mesprojets'></a>Mes projets
L'application récupère les noms des projets du client depuis AZURACORE. 

Le logo du projet et le statut n'existent pas en base de données. Pour le moment, les données sont en dur. On pourrait ajouter ces 2 attributs au projet pour que les données soient dynamiques.

La requête est asynchrone avec [AJAX](public/scripts/home.js) via l'[AjaxController](src/controller/AjaxController.php).

La requête peut aussi se lancer depuis le [HomeController](src/controller/HomeController.php), le code a seulement été commenté dans le [fichier TWIG du tableau de bord](templates/home/index.html.twig). 


###  1.2. <a name='Carnetdemaintenance'></a>Carnet de maintenance
La progressbar et toutes les informations sont dynamiques. C'est le [HomeController](src/controller/HomeController.php) qui récupère les données, et [workingSessions.js](public/scripts/workingSessions.js) qui commande l'affichage.

Le bouton "Recharger mon carnet" déclenche l'envoi d'un email à Jean-Marie. L'envoi des emails est configuré avec Mailtrap.

###  1.3. <a name='Oprationcommerciale'></a>Opération commerciale
Les données sont en dur. On pourrait envisager de créer une table pour les opérations commerciales, comportant un titre, un texte, une image et un lien.

###  1.4. <a name='Monagence'></a>Mon agence
Les données sont dynamiques. On récupère les données de l'agence avec l'id = 1.

⚠️ Attention : il n'existe pas de AgencyAPI dans le SDK. Pour que le code fonctionne, il faut l'ajouter :

```twig
<?php

namespace Azuracom\ApiSdkBundle\ApiClient;

class AgencyApi extends AbstractApiClient
{
    const IRI = '/api/agencies';
}
```
Par ailleurs, les coordonnées concernant la personne à contacter sont celles du référent technique du projet le plus récent. Seul le téléphone du référent technique est en dur car le champ n'existe pas. 

###  1.5. <a name='Actualits'></a>Actualités
Les données sont en dur. On pourrait envisager d'ajouter un attribut "content" à la table Articles pour les rendre dynamiques.


##  2. <a name='Monprofil'></a>Mon profil
Tout ce qui concerne l'affichage des infos du client ou leur modification est dans le [ClientController](src/Controller/ClientController.php).
Les données sont dynamiques. Il n'existe pas de champ "address" dans la table client, j'ai donc renseigné l'adresse dans "description". On pourrait ajouter un champ pour le téléphone et un pour l'email.

##  3. <a name='MesDocuments'></a>Mes Documents
L'application va chercher les facture dans l'API ZOHO, via [ZohoInvoicesApiManager](src/Zoho/ZohoInvoicesApiManager.php). 

⚠️ Pour le moment, l'id du client zoho est en dur dans la requête, afin de le récupérer dynamiquement, il faudrait ajouter un champ dans le client permettant d'indiquer l'id_client sur ZOHO.

▶️ Pour la configuration de l'API Zoho : voir le ReadMe d'Azuraforge qui est très bien détaillé.

##  4. <a name='MesProjets'></a>Mes Projets
Tout est en dur sauf le nom des projets. Comme indiqué plus haut, il faudrait ajouter à la table project des champs "logo", "statut", ainsi que des champs "médias", "maquettes" et "SEO", qui contiendraient des liens vers un Google Drive client. Les boutons pourraient être conditionnels en fonction du statut du projet ou si le lien est renseigné ou non. 

##  5. <a name='Assistance'></a>Assistance
La soumission du formulaire permet d'envoyer un email à support@azuracom.com, ce qui aura pour conséquence de créer un ticket sur ZohoDesk. Tout ceci est géré par le [SupportController](src/Controller/SupportController.php).

La fonction qui permettra de récupérer les tickets existants ne fonctionne pas. Je n'ai pas trouvé l'erreur. 

##  6. <a name='Front-end'></a>Front-end
L'application a été réalisée avec Bootstrap. J'ai désinstallé WebPack en cours de projet pour des soucis techniques. 

##  7. <a name='Encoredesquestions'></a>Encore des questions ?

- [Maeva POIRIER](mailto:veyrac_maeva@hotmail.com) (ou par sms 06 12 08 73 83)
