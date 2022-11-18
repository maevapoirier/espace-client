

async function getData(apiUrl, clientId, token) {

    let url = apiUrl + 'api/clients/' + clientId + '/projects?page=1&itemsPerPage=30';

    const options = {
        method: 'GET',
        headers: {
            'accept': 'application/json',
            'authorization' : 'Bearer ' + token
        }
    }

    try {
        const response = await fetch(url, options);
        if (response.ok) {
            const data = await response.json();
            return data;
        } else {
            console.error(`Erreur de réponse ${error}`);
        }
        
    } catch (error) {
        console.error(`Erreur de fetch ${error}`);
    } 

}


const handleUrlProjects = (apiUrl, clientId, token) => {
    console.log('fonction getProjects appelée');
    
    getData(url, token);
}

const init = async () => {
    let jsUser = document.querySelector('.js-user');
    let token = jsUser.dataset.user;
    console.log(token);

    let jsClient = document.querySelector('.js-client');
    let clientId = jsClient.dataset.client;
    console.log(clientId);

    let jsApi = document.querySelector('.js-api');
    let apiUrl = jsApi.dataset.api;
    console.log(apiUrl);

    let response = await getData(apiUrl, clientId, token);

    //decomposr mon json
    //alimenter mon tableau 
}

document.addEventListener('DOMContentLoaded', init);
