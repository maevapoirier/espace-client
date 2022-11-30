const { async } = require("regenerator-runtime");


async function getData(url, token) {

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


const showProjects = (data) => {
    for (let element of data) {

        console.log("fonction showProjects appelée");
        let myProject = document.createElement('tr');
        let myLogo = document.createElement('td');
        let myImg = document.createElement('img');
        let myName = document.createElement('td');
        let myStatus = document.createElement('td');

        myImg.src = "build/images/mini-logoipsum.jpg";
        myImg.alt = "logo client";
        myName.textContent = element.name;
        myStatus.textContent = "Statut du projet";

        myLogo.appendChild(myImg);
        myProject.appendChild(myLogo);
        myProject.appendChild(myName);
        myProject.appendChild(myStatus);

        let projectTable = document.getElementById("ProjectsTable");
        projectTable.appendChild(myProject);
    }
}

const getProjects = async (apiUrl, clientId, token) => {
    console.log('fonction getProjects appelée');

    let url = apiUrl + 'api/clients/' + clientId + '/projects?page=1&itemsPerPage=30';
    
    let data = await getData(url, token);

    console.log(data);

    showProjects(data);
}

const init = async () => {
    // let jsUser = document.querySelector('.js-user');
    // let token = jsUser.dataset.user;
    // console.log(token);

    // let jsClient = document.querySelector('.js-client');
    // let clientId = jsClient.dataset.client;
    // console.log(clientId);

    // let jsApi = document.querySelector('.js-api');
    // let apiUrl = jsApi.dataset.api;
    // console.log(apiUrl);

    // getProjects(apiUrl, clientId, token);

    try {
        const responseView = await fetch('projects-ajax-controller');

        if (responseView.ok) {
            const data = await responseView.text();
            console.log(data);
            
            let projectTable = document.getElementById("ProjectsTable");
            projectTable.innerHTML = data;

        } else {
            console.error(`Erreur de réponse ${error}`);
        }
        
    } catch (error) {
        console.error(`Erreur de fetch ${error}`);
    } 

}

document.addEventListener('DOMContentLoaded', init);
