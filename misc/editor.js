const COLORS = ['GRAY', 'PINK', 'WHITE', 'BLUE', 'YELLOW', 'ORANGE', 'BLACK', 'RED', 'GREEN'];
const VERTICAL_MAPS = ['india', 'nordiccountries'];

let MAP = localStorage.getItem('BGA_TTR_EDITOR_MAP') ?? 'usa';
let selectedCity = null;
let selectedSpace = null;

COLORS.forEach(color => {
    addOptionToSelect(color, color, 'route-color');
    addOptionToSelect(color, color, 'new-route-color');
});

async function getFileContent(dirHandle, fileName) {
    const fileHandle = await dirHandle.getFileHandle(fileName);
    const file = await fileHandle.getFile();
    const text = await file.text();
    return text;
}

document.getElementById('map-code').value = MAP;

async function load() {
    MAP = (document.getElementById('map-code')).value;
    localStorage.setItem('BGA_TTR_EDITOR_MAP', MAP);
    const mapDiv = document.getElementById('map');
    mapDiv.style.backgroundImage = `url('../img/${MAP}/map.jpg')`;
    if (VERTICAL_MAPS.includes(MAP)) {
        mapDiv.classList.add('vertical');
    }
    const root = await (window).showDirectoryPicker();
    const modulesHandle = await root.getDirectoryHandle('modules');
    const mapsHandle = await modulesHandle.getDirectoryHandle('maps');
    const mapHandle = await mapsHandle.getDirectoryHandle(MAP);
    const citiesPhpFileText = await getFileContent(mapHandle, `cities.php`);
    const routesPhpFileText = await getFileContent(mapHandle, `routes.php`);

    parseCities(citiesPhpFileText);
    parseRoutes(routesPhpFileText);

    document.addEventListener('keydown', keydown);
    const selectedCityNameInput = document.getElementById('selected-city-name');
    selectedCityNameInput.addEventListener('change', () => {
        if (selectedCity) {
            selectedCity.dataset.name = selectedCityNameInput.value;
            selectedCity.innerText = selectedCity.dataset.name;
            this.updateCitiesExport();
        }
    });

    const selectedRouteFromInput = document.getElementById('route-from');
    selectedRouteFromInput.addEventListener('change', () => {
        if (selectedSpace) {
            getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.from = selectedRouteFromInput.value);
            this.updateRoutesExport();
        }
    });

    const selectedRouteToInput = document.getElementById('route-to');
    selectedRouteToInput.addEventListener('change', () => {
        if (selectedSpace) {
            getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.from = selectedRouteToInput.value);
            this.updateRoutesExport();
        }
    });

    const selectedRouteColorInput = document.getElementById('route-color');
    selectedRouteColorInput.addEventListener('change', () => {
        if (selectedSpace) {
            getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.color = selectedRouteColorInput.value);
            this.updateRoutesExport();
        }
    });

    const selectedRouteTunnelInput = document.getElementById('route-tunnel');
    selectedRouteTunnelInput.addEventListener('change', () => {
        if (selectedSpace) {
            getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.tunnel = selectedRouteTunnelInput.checked ? 'true' : 'false');
            this.updateRoutesExport();
        }
    });

    const selectedRouteLocomotivesInput = document.getElementById('route-locomotives');
    selectedRouteLocomotivesInput.addEventListener('change', () => {
        if (selectedSpace) {
            getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.locomotives = selectedRouteLocomotivesInput.value);
            this.updateRoutesExport();
        }
    });


    this.updateCitiesExport();
    this.updateRoutesExport();

}

function parseCities(text) {
    const matches = text.match(/(-?\d+).*'(.*)'.*(\d+).*(\d+)/g);
    matches?.forEach(lineMatch => {
        const match = lineMatch.match(/(-?\d+).*'(.*)'.*?(\d+).*?(\d+)/);
        if (match) {
            const id = Number(match[1]);
            const city = match[2];
            const x = Number(match[3]);
            const y = Number(match[4]);
            addCity(id, city, x, y);
        }
    });
}

/*function parseRoutes(text) {
    const matches = text.match(/(\d+).*?(\d+).*?(\d+).*?(\w+).*\[((.|\n)*?)\]\W*(true|false)?,? ?(\d+)?/g);
    matches?.forEach(lineMatch => {
        const match = lineMatch.match(/(\d+).*?(\d+).*?(\d+).*?(\w+).*\[((.|\n)*?)\]\W*(true|false)?,? ?(\d+)?/);
        if (match) {
            const id = Number(match[1]);
            const from = Number(match[2]);
            const to = Number(match[3]);
            const color = match[4];
            const routesStr = match[5];
            const routes = Array.from(routesStr.match(/(\d+).*?(\d+).*?(\d+)\W*(true)?/g)).map(routeStr => routeStr.match(/(\d+).*?(\d+).*?(\d+)\W*(true)?/).slice(1,4).map(Number));
            const tunnel = match[7] === 'true';
            const locomotives = match[7] ? Number(match[8]) : 0;
            addRoute(id, from, to, color, routes, tunnel, locomotives);
        }
    });
}*/
function parseRoutes(text) {
    const lines = text.split('\n');


    let id = null;
    let from = null;
    let to = null;
    let color = null;
    let routes = null;
    let tunnel = null;
    let locomotives = null;

    lines.forEach((line, index) => {
        const routeStartMatch = line.match(/(\d+).*?(-?\d+).*?(-?\d+).*?(\w+).*\[/);
        if (routeStartMatch) {
            id = Number(routeStartMatch[1]);
            from = Number(routeStartMatch[2]);
            to = Number(routeStartMatch[3]);
            color = routeStartMatch[4];
            routes = [];
            tunnel = null;
            locomotives = null;
        } else {

            const routeSpaceMatch = line.match(/(\d+).*?(\d+).*?(-?\d+)/);
            if (routeSpaceMatch) {
                routes.push(routeSpaceMatch.slice(1,4).map(Number));
            } else if (id !== null) {
                const routeEndMatch = line.match(/\][, ]*(\w+)?[, ]*(\d+)?/);
                if (routeEndMatch) {
                    tunnel = routeEndMatch[1] === 'true';
                    locomotives = routeEndMatch[2] ? Number(routeEndMatch[2]) : 0;
                    addRoute(id, from, to, color, routes, tunnel, locomotives);
                    id = null; // end of current route
                }
            }
        }
    });
}

function addOptionToSelect(id, name, selectId) {
    document.getElementById(selectId).insertAdjacentHTML('beforeend', `
       <option value="${id}">${name}</option> 
    `);
}

function addCity(id, name, x, y) {
    document.getElementById('cities').insertAdjacentHTML('beforeend', 
        `<div id="city-${id}" class="city" data-id="${id}" data-name="${name}" data-x="${x}" data-y="${y}" style="--x: ${x}px; --y: ${y}px;">${name}</div>`
    )
    const elem = document.getElementById(`city-${id}`);
    elem.addEventListener('click', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
        cityClick(elem);
    });

    addOptionToSelect(id, name, 'route-from');
    addOptionToSelect(id, name, 'new-route-from');
    addOptionToSelect(id, name, 'route-to');
    addOptionToSelect(id, name, 'new-route-to');
}

function addRoute(id, from, to, color, spaces, tunnel, locomotives) {
    spaces.forEach((space, index) => {
        document.getElementById('route-spaces').insertAdjacentHTML('beforeend', 
            `<div id="route-spaces-route${id}-space${index}" class="route-space ${tunnel ? 'tunnel' : ''} ${index < locomotives ? 'locomotive'  :''}" data-x="${space[0]}" data-y="${space[1]}" data-a="${space[2]}" style="--x: ${space[0]}px; --y: ${space[1]}px; --a: ${space[2]}deg;" data-tunnel="${tunnel ? 'true' : 'false'}" data-locomotives="${locomotives}" data-route-id="${id}" data-space-index="${index}" data-from="${from}" data-to="${to}" data-color="${color}">${color}</div>`
        );
        const elem = document.getElementById(`route-spaces-route${id}-space${index}`);
        elem.addEventListener('click', e => {
            e.preventDefault();
            e.stopImmediatePropagation();
            spaceClick(elem);
        })
    });    
}

function unselectCity() {
    document.querySelectorAll('.city.selected').forEach(elem => elem.classList.remove('selected'));
    selectedCity = null;
    document.getElementById('selected-city-name').value = '';
}

function unselectSpace() {
    selectedSpace?.classList.remove('selected');
    selectedSpace = null;
    document.getElementById('route-from').value = '';
    document.getElementById('route-to').value = '';
    document.getElementById('route-color').value = '';
    document.getElementById('route-tunnel').checked = false;
    document.getElementById('route-locomotives').value = '';
    document.querySelectorAll('.selected-other-route').forEach(elem => elem.classList.remove('selected-other-route'));
}

function cityClick(elem) {
    const selected = selectedCity !== elem;
    this.unselectCity();
    this.unselectSpace();
    
    if (selected) {
        selectedCity = elem;
        elem.classList.add('selected');
        document.getElementById('selected-city-name').value = elem.dataset.name;
    }
}

function spaceClick(elem) {
    const selected = selectedSpace !== elem;
    this.unselectCity();
    this.unselectSpace();
    
    if (selected) {
        selectedSpace = elem;
        elem.classList.add('selected');
        document.getElementById('route-from').value = elem.dataset.from;
        document.getElementById('route-to').value = elem.dataset.to;
        document.getElementById('route-color').value = elem.dataset.color;
        document.getElementById('route-tunnel').checked = elem.dataset.tunnel === 'true';
        document.getElementById('route-locomotives').value = elem.dataset.locomotives;

        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).filter(oe => oe != elem).forEach(oe => oe.classList.add('selected-other-route'));
        document.getElementById(`city-${elem.dataset.from}`).classList.add('selected');
        document.getElementById(`city-${elem.dataset.to}`).classList.add('selected');
    }
}

function keydown(e) {
    if (['ArrowUp', 'ArrowLeft', 'ArrowRight', 'ArrowDown'].includes(e.key)) {
        e.preventDefault();
        e.stopPropagation();

        const multiplier = e.ctrlKey ? 100 : (e.shiftKey ? 10 : 1);

        if (e.altKey) { // rotate 
            if (selectedSpace) {
                rotateSelectedSpace(e.key, multiplier);
            }
        } else { // translate
            if (selectedCity) {
                moveSelectedCity(e.key, multiplier);
            }
    
            if (selectedSpace) {
                moveSelectedSpace(e.key, multiplier);
            }
        }
    }
}

function moveElem(elem, key, multiplier) {
    let shiftX = 0;
    let shiftY = 0;
    switch (key) {
        case 'ArrowUp':
            shiftY = -1;
            break;
        case 'ArrowLeft':
            shiftX = -1;
            break;
        case 'ArrowRight':
            shiftX = 1;
            break;
        case 'ArrowDown':
            shiftY = 1;
            break;
    }

    elem.dataset.x = Number(elem.dataset.x) + shiftX * multiplier;
    elem.dataset.y = Number(elem.dataset.y) + shiftY * multiplier;
    elem.style.setProperty('--x', `${elem.dataset.x}px`);
    elem.style.setProperty('--y', `${elem.dataset.y}px`);
}

function moveSelectedCity(key, multiplier) {
    this.moveElem(selectedCity, key, multiplier),
    this.updateCitiesExport();
}

function moveSelectedSpace(key, multiplier) {
    this.moveElem(selectedSpace, key, multiplier),
    this.updateRoutesExport();
}

function rotateSelectedSpace(key, multiplier) {
    let shift = 0;
    switch (key) {
        case 'ArrowUp':
            case 'ArrowRight':
            shift = 1;
            break;
        case 'ArrowLeft':
        case 'ArrowDown':
            shift = -1;
            break;
    }

    selectedSpace.dataset.a = Number(selectedSpace.dataset.a) + shift * multiplier;
    selectedSpace.style.setProperty('--a', `${selectedSpace.dataset.a}deg`);

    this.updateRoutesExport();
}

function updateCitiesExport() {
    let php = Array.from(document.querySelectorAll('.city')).map(elem => 
        `    ${elem.dataset.id} => new City('${elem.dataset.name}', ${elem.dataset.x}, ${elem.dataset.y}),`
    ).join('\n');
    document.getElementById('cities-export').value = php;
}

function getSpacesOfRoute(routeId) {
    return Array.from(document.querySelectorAll(`[id^="route-spaces-route${routeId}-space"]`));
}

function updateRoutesExport() {
    let php = ``;
    for (let id = 0; id <= 200; id++) {
        const spaces = getSpacesOfRoute(id);
        if (spaces.length) {
            const firstSpace = spaces[0];
            const from = Math.min(Number(firstSpace.dataset.from), Number(firstSpace.dataset.to));
            const to = Math.max(Number(firstSpace.dataset.from), Number(firstSpace.dataset.to));
            const locomotives = Number(firstSpace.dataset.locomotives);
            php += `    ${id} => new Route(${from}, ${to}, ${firstSpace.dataset.color}, [\n`;
            spaces.forEach(space => php += `      new RouteSpace(${space.dataset.x}, ${space.dataset.y}, ${space.dataset.a}),\n`),
            php += `    ]${firstSpace.dataset.tunnel === 'true' || locomotives > 0 ? `, ${firstSpace.dataset.tunnel}` : ''}${locomotives > 0 ? `, ${locomotives}` : '' }),\n`;
        }
    }
    document.getElementById('routes-export').value = php;
}

function eraseAllRoutes() {
    document.querySelectorAll(`.route-space`).forEach(elem => elem?.remove());
}

const DEFAULT_PX_BETWEEN_SPACES = 69;

function createNewCity() {
    const cityIdInput = document.getElementById('new-city-id');
    const cityNameInput = document.getElementById('new-city-name');

    this.addCity(cityIdInput.value, cityNameInput.value, 0, 0);
}

function createNewRoute() {
    let id = 1;
    for (; id <= 200; id++) {
        const spaces = getSpacesOfRoute(id);
        if (!spaces.length) {
            break;
        }
    }

    const selectedRouteFromInput = document.getElementById('new-route-from');
    const fromDiv = document.getElementById(`city-${selectedRouteFromInput.value}`);
    const fromX = Number(fromDiv.dataset.x);
    const fromY = Number(fromDiv.dataset.y);

    const selectedRouteToInput = document.getElementById('new-route-to');
    const toDiv = document.getElementById(`city-${selectedRouteToInput.value}`);
    const toX = Number(toDiv.dataset.x);
    const toY = Number(toDiv.dataset.y);

    const selectedRouteColorInput = document.getElementById('new-route-color');

    const selectedRouteTunnelInput = document.getElementById('new-route-tunnel');

    const selectedRouteSpacesInput = document.getElementById('new-route-spaces');

    const selectedRouteLocomotivesInput = document.getElementById('new-route-locomotives');

    const angle = Math.floor(Math.atan2(toY - fromY, toX - fromX) * 180 / Math.PI);
    const routeCenterX = (toX + fromX) / 2;
    const routeCenterY = (toY + fromY) / 2;

    const centerIndex = (selectedRouteSpacesInput.value - 1) / 2;
    const routes = [];
    for (i = 0; i < selectedRouteSpacesInput.value; i++) {
        const distance = (i - centerIndex) * DEFAULT_PX_BETWEEN_SPACES;
        const x = Math.round(Math.cos(angle * Math.PI / 180) * distance + routeCenterX);
        const y = Math.round(Math.sin(angle * Math.PI / 180) * distance + routeCenterY);
        routes.push([x, y, angle]);
    }

    addRoute(id, selectedRouteFromInput.value, selectedRouteToInput.value, selectedRouteColorInput.value, routes, selectedRouteTunnelInput.checked, selectedRouteLocomotivesInput.value);    
    this.updateRoutesExport();
}