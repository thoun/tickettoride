const COLORS = ['TRACKBED', 'GRAY', 'PINK', 'WHITE', 'BLUE', 'YELLOW', 'ORANGE', 'BLACK', 'RED', 'GREEN'];
const SPECIFIC_ROUTE_ARGUMENTS = {
    canPayWithAnySetOfCards: { input: 'route-can-pay-with-any-set-of-cards', defaultValue: 'null' },
    mountain: { input: 'route-mountain', defaultValue: '0' },
    stockShares: { input: 'route-stock-shares', defaultValue: '[]' },
    bulletTrainSpaceIndex: { input: 'route-bullet-train-space-index', defaultValue: 'null', checkbox: true },
    ferryWaves: { input: 'route-ferry-waves', defaultValue: '0' },
};
const DIRECTORY_DATABASE_NAME = 'BGA_TTR_EDITOR';
const DIRECTORY_STORE_NAME = 'directoryHandles';
const TTR_ROOT_HANDLE_KEY = 'ttrRoot';

let MAP = localStorage.getItem('BGA_TTR_EDITOR_MAP') ?? 'usa';
let selectedCity = null;
let selectedSpace = null;
let ttrRootHandle = null;

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

function openDirectoryDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DIRECTORY_DATABASE_NAME, 1);
        request.onupgradeneeded = () => request.result.createObjectStore(DIRECTORY_STORE_NAME);
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

async function getStoredTtrRootHandle() {
    const database = await openDirectoryDatabase();
    return new Promise((resolve, reject) => {
        const request = database.transaction(DIRECTORY_STORE_NAME).objectStore(DIRECTORY_STORE_NAME).get(TTR_ROOT_HANDLE_KEY);
        request.onsuccess = () => resolve(request.result ?? null);
        request.onerror = () => reject(request.error);
    });
}

async function storeTtrRootHandle(handle) {
    const database = await openDirectoryDatabase();
    return new Promise((resolve, reject) => {
        const transaction = database.transaction(DIRECTORY_STORE_NAME, 'readwrite');
        transaction.objectStore(DIRECTORY_STORE_NAME).put(handle, TTR_ROOT_HANDLE_KEY);
        transaction.oncomplete = resolve;
        transaction.onerror = () => reject(transaction.error);
    });
}

async function getTtrRootHandle() {
    if (ttrRootHandle) {
        const permission = await ttrRootHandle.queryPermission({mode: 'read'});
        if (permission === 'granted' || await ttrRootHandle.requestPermission({mode: 'read'}) === 'granted') {
            return ttrRootHandle;
        }
        throw new DOMException('Permission to read the TTR folder was denied.', 'NotAllowedError');
    }

    ttrRootHandle = await window.showDirectoryPicker({mode: 'read'});
    await storeTtrRootHandle(ttrRootHandle);
    document.getElementById('load-map').textContent = 'Load';
    return ttrRootHandle;
}

getStoredTtrRootHandle().then(handle => {
    if (handle) {
        ttrRootHandle = handle;
        document.getElementById('load-map').textContent = 'Load';
    }
});

document.getElementById('map-code').value = MAP;

document.addEventListener('keydown', keydown);

const selectedCityNameInput = document.getElementById('selected-city-name');
selectedCityNameInput.addEventListener('change', () => {
    if (selectedCity) {
        selectedCity.dataset.name = selectedCityNameInput.value;
        selectedCity.innerText = selectedCity.dataset.name;
        updateCitiesExport();
    }
});

const selectedRouteFromInput = document.getElementById('route-from');
selectedRouteFromInput.addEventListener('change', () => {
    if (selectedSpace) {
        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.from = selectedRouteFromInput.value);
        updateRoutesExport();
    }
});

const selectedRouteToInput = document.getElementById('route-to');
selectedRouteToInput.addEventListener('change', () => {
    if (selectedSpace) {
        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.from = selectedRouteToInput.value);
        updateRoutesExport();
    }
});

const selectedRouteColorInput = document.getElementById('route-color');
selectedRouteColorInput.addEventListener('change', () => {
    if (selectedSpace) {
        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.color = selectedRouteColorInput.value);
        updateRoutesExport();
    }
});

const selectedRouteTunnelInput = document.getElementById('route-tunnel');
selectedRouteTunnelInput.addEventListener('change', () => {
    if (selectedSpace) {
        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.tunnel = selectedRouteTunnelInput.checked ? 'true' : 'false');
        updateRoutesExport();
    }
});

const selectedRouteLocomotivesInput = document.getElementById('route-locomotives');
selectedRouteLocomotivesInput.addEventListener('change', () => {
    if (selectedSpace) {
        getSpacesOfRoute(Number(selectedSpace.dataset.routeId)).forEach(elem => elem.dataset.locomotives = selectedRouteLocomotivesInput.value);
        updateRouteClasses(Number(selectedSpace.dataset.routeId));
        updateRoutesExport();
    }
});

Object.entries(SPECIFIC_ROUTE_ARGUMENTS).forEach(([name, definition]) => {
    document.getElementById(definition.input).addEventListener('change', event => {
        if (!selectedSpace) {
            return;
        }

        const value = definition.checkbox
            ? (event.target.checked ? selectedSpace.dataset.spaceIndex : 'null')
            : (event.target.value || definition.defaultValue);
        setRouteAdditionalArgument(Number(selectedSpace.dataset.routeId), name, value);
        updateRouteClasses(Number(selectedSpace.dataset.routeId));
        updateRoutesExport();
    });
});

function resetEditor() {
    unselectCity();
    unselectSpace();

    const mapDiv = document.getElementById('map');
    mapDiv.style.backgroundImage = '';
    mapDiv.style.width = '';
    mapDiv.style.height = '';

    document.getElementById('cities').replaceChildren();
    document.getElementById('route-spaces').replaceChildren();
    ['route-from', 'route-to', 'new-route-from', 'new-route-to'].forEach(selectId => {
        document.getElementById(selectId).replaceChildren();
    });

    document.getElementById('cities-export').value = '';
    document.getElementById('routes-export').value = '';

    document.getElementById('new-city-id').value = '1';
    document.getElementById('new-city-name').value = '';
    document.getElementById('new-route-color').value = COLORS[0];
    document.getElementById('new-route-tunnel').checked = false;
    document.getElementById('new-route-spaces').value = '1';
    document.getElementById('new-route-locomotives').value = '0';
    document.getElementById('new-route-can-pay-with-any-set-of-cards').value = '';
    document.getElementById('new-route-mountain').value = '0';
    document.getElementById('new-route-stock-shares').value = '[]';
    document.getElementById('new-route-ferry-waves').value = '0';
    document.querySelectorAll('.specific-route-field').forEach(field => field.hidden = true);
}

async function load() {
    const mapCode = (document.getElementById('map-code')).value;
    MAP = mapCode;
    localStorage.setItem('BGA_TTR_EDITOR_MAP', MAP);
    const mapUrl = `../img/${mapCode}/map.webp`;

    // Use the actual map dimensions instead of maintaining a map-name list.
    // This also keeps the editor working for newly added maps and non-standard
    // image sizes.
    const mapImage = new Image();
    await new Promise(resolve => {
        mapImage.onload = resolve;
        mapImage.onerror = resolve;
        mapImage.src = mapUrl;
    });
    const root = await getTtrRootHandle();
    const modulesHandle = await root.getDirectoryHandle('modules');
    const mapsHandle = await modulesHandle.getDirectoryHandle('maps');
    const mapHandle = await mapsHandle.getDirectoryHandle(mapCode);
    const citiesPhpFileText = await getFileContent(mapHandle, `cities.php`);
    const routesPhpFileText = await getFileContent(mapHandle, `routes.php`);

    resetEditor();
    const mapDiv = document.getElementById('map');
    mapDiv.style.backgroundImage = `url('${mapUrl}')`;
    if (mapImage.naturalWidth > 0 && mapImage.naturalHeight > 0) {
        mapDiv.style.width = `${mapImage.naturalWidth}px`;
        mapDiv.style.height = `${mapImage.naturalHeight}px`;
    }
    parseCities(citiesPhpFileText);
    parseRoutes(routesPhpFileText);

    updateCitiesExport();
    updateRoutesExport();

}

function parseCities(text) {
    extractConstructorCalls(text, 'City').forEach(({prefix, argumentsText}) => {
        const idMatch = prefix.match(/(-?\d+)\s*=>\s*$/);
        if (!idMatch) {
            return;
        }

        const argumentsList = splitTopLevelArguments(argumentsText);
        if (argumentsList.length < 3) {
            return;
        }

        const city = parsePhpString(argumentsList[0]);
        const x = Number(argumentsList[1]);
        const y = Number(argumentsList[2]);
        if (city === null || !Number.isFinite(x) || !Number.isFinite(y)) {
            return;
        }

        addCity(Number(idMatch[1]), city, x, y, argumentsList.slice(3));
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
function splitTopLevelArguments(text) {
    const result = [];
    let currentArgument = '';
    let depth = 0;
    let quote = null;
    let escaped = false;

    for (const character of text) {
        if (quote !== null) {
            currentArgument += character;
            if (escaped) {
                escaped = false;
            } else if (character === '\\') {
                escaped = true;
            } else if (character === quote) {
                quote = null;
            }
        } else if (character === '"' || character === "'") {
            quote = character;
            currentArgument += character;
        } else if (['[', '(', '{'].includes(character)) {
            depth++;
            currentArgument += character;
        } else if (character === ']' || character === ')' || character === '}') {
            depth--;
            currentArgument += character;
        } else if (character === ',' && depth === 0) {
            if (currentArgument.trim()) {
                result.push(currentArgument.trim());
            }
            currentArgument = '';
        } else {
            currentArgument += character;
        }
    }

    if (currentArgument.trim()) {
        result.push(currentArgument.trim());
    }

    return result;
}

function extractConstructorCalls(text, constructorName) {
    const calls = [];
    const constructorPattern = new RegExp(`new\\s+${constructorName}\\s*\\(`, 'g');
    let match;

    while ((match = constructorPattern.exec(text)) !== null) {
        const openingParenthesis = text.indexOf('(', match.index);
        let depth = 1;
        let quote = null;
        let escaped = false;
        let closingParenthesis = openingParenthesis + 1;

        for (; closingParenthesis < text.length && depth > 0; closingParenthesis++) {
            const character = text[closingParenthesis];
            if (quote !== null) {
                if (escaped) {
                    escaped = false;
                } else if (character === '\\') {
                    escaped = true;
                } else if (character === quote) {
                    quote = null;
                }
            } else if (character === '"' || character === "'") {
                quote = character;
            } else if (character === '(') {
                depth++;
            } else if (character === ')') {
                depth--;
            }
        }

        if (depth !== 0) {
            continue;
        }

        const prefix = text.slice(Math.max(0, text.lastIndexOf('\n', match.index) + 1), match.index);
        calls.push({prefix, argumentsText: text.slice(openingParenthesis + 1, closingParenthesis - 1)});
        constructorPattern.lastIndex = closingParenthesis;
    }

    return calls;
}

function parsePhpString(value) {
    const trimmedValue = value.trim();
    if (trimmedValue.length < 2) {
        return null;
    }

    const quote = trimmedValue[0];
    if ((quote !== "'" && quote !== '"') || trimmedValue[trimmedValue.length - 1] !== quote) {
        return null;
    }

    return trimmedValue.slice(1, -1).replace(new RegExp(`\\\\${quote}`, 'g'), quote).replace(/\\\\/g, '\\');
}

function parseRouteArguments(text) {
    const routeArgumentNames = ['tunnel', 'locomotives', ...Object.keys(SPECIFIC_ROUTE_ARGUMENTS)];
    const result = {
        tunnel: false,
        locomotives: 0,
        additionalArguments: [],
    };
    let positionalArgumentIndex = 0;
    const normalizedText = text.trim().replace(/^,\s*/, '');

    splitTopLevelArguments(normalizedText).forEach(argument => {
        const namedArgumentMatch = argument.match(/^([a-zA-Z_]\w*)\s*:\s*([\s\S]*)$/);
        const name = namedArgumentMatch?.[1] ?? routeArgumentNames[positionalArgumentIndex++];
        const value = namedArgumentMatch?.[2] ?? argument;

        switch (name) {
            case 'tunnel':
                result.tunnel = value.trim() === 'true';
                break;
            case 'locomotives':
                result.locomotives = Number(value);
                break;
            default:
                // Specific and unknown parameters stay together so addRoute
                // does not need a fixed parameter for every map variant.
                result.additionalArguments.push(namedArgumentMatch ? argument : `${name}: ${value}`);
        }
    });

    return result;
}

function parseRoutes(text) {
    const lines = text.split('\n');

    let id = null;
    let from = null;
    let to = null;
    let color = null;
    let routes = null;

    lines.forEach(line => {
        const routeStartMatch = line.match(/^\s*(-?\d+)\s*=>\s*new Route\(\s*(-?\d+)\s*,\s*(-?\d+)\s*,\s*(\w+)\s*,\s*\[\s*$/);
        if (routeStartMatch) {
            id = Number(routeStartMatch[1]);
            from = Number(routeStartMatch[2]);
            to = Number(routeStartMatch[3]);
            color = routeStartMatch[4];
            routes = [];
            return;
        }

        if (id === null) {
            return;
        }

        const routeSpaceMatch = line.match(/^\s*new RouteSpace\(\s*(-?\d+)\s*,\s*(-?\d+)\s*,\s*(-?\d+)/);
        if (routeSpaceMatch) {
            routes.push(routeSpaceMatch.slice(1, 4).map(Number));
            return;
        }

        const routeEndMatch = line.match(/^\s*\](.*)\),?\s*(?:\/\/.*)?$/);
        if (routeEndMatch) {
            const routeArguments = parseRouteArguments(routeEndMatch[1]);
            addRoute(
                id,
                from,
                to,
                color,
                routes,
                routeArguments.tunnel,
                routeArguments.locomotives,
                routeArguments.additionalArguments,
            );
            id = null;
        }
    });
}

function addOptionToSelect(id, name, selectId) {
    document.getElementById(selectId).insertAdjacentHTML('beforeend', `
       <option value="${id}">${name}</option> 
    `);
}

function addCity(id, name, x, y, additionalArguments = []) {
    const isVirtual = Number(id) < 0;
    document.getElementById('cities').insertAdjacentHTML('beforeend', 
        `<div id="city-${id}" class="city ${isVirtual ? 'virtual-city' : ''}" data-id="${id}" data-name="${name}" data-x="${x}" data-y="${y}" style="--x: ${x}px; --y: ${y}px;">${name}</div>`
    )
    const elem = document.getElementById(`city-${id}`);
    elem.dataset.additionalArguments = JSON.stringify(additionalArguments);
    elem.addEventListener('click', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
        cityClick(elem);
    });

    if (!isVirtual) {
        addOptionToSelect(id, name, 'route-from');
        addOptionToSelect(id, name, 'new-route-from');
        addOptionToSelect(id, name, 'route-to');
        addOptionToSelect(id, name, 'new-route-to');
    }
}

function getAdditionalArgument(additionalArguments, name) {
    const argument = additionalArguments.find(value => value.match(new RegExp(`^${name}\\s*:`)));
    return argument?.replace(new RegExp(`^${name}\\s*:\\s*`), '') ?? null;
}

function setRouteAdditionalArgument(routeId, name, value) {
    getSpacesOfRoute(routeId).forEach(space => {
        const additionalArguments = JSON.parse(space.dataset.additionalArguments ?? '[]');
        const newArgument = `${name}: ${value}`;
        const argumentIndex = additionalArguments.findIndex(argument => argument.match(new RegExp(`^${name}\\s*:`)));
        if (argumentIndex === -1) {
            additionalArguments.push(newArgument);
        } else {
            additionalArguments[argumentIndex] = newArgument;
        }
        space.dataset.additionalArguments = JSON.stringify(additionalArguments);
    });
}

function showSpecificRouteArguments(additionalArguments) {
    Object.keys(SPECIFIC_ROUTE_ARGUMENTS).forEach(name => {
        if (getAdditionalArgument(additionalArguments, name) !== null) {
            document.querySelectorAll(`[data-route-argument="${name}"]`).forEach(field => field.hidden = false);
        }
    });
}

function addRoute(id, from, to, color, spaces, tunnel, locomotives, additionalArguments = []) {
    const mountain = Number(getAdditionalArgument(additionalArguments, 'mountain') ?? 0);
    showSpecificRouteArguments(additionalArguments);
    spaces.forEach((space, index) => {
        document.getElementById('route-spaces').insertAdjacentHTML('beforeend', 
            `<div id="route-spaces-route${id}-space${index}" class="route-space ${tunnel ? 'tunnel' : ''} ${index < locomotives ? 'locomotive' : ''} ${index < mountain ? 'mountain' : ''}" data-x="${space[0]}" data-y="${space[1]}" data-a="${space[2]}" style="--x: ${space[0]}px; --y: ${space[1]}px; --a: ${space[2]}deg;" data-tunnel="${tunnel ? 'true' : 'false'}" data-locomotives="${locomotives}" data-route-id="${id}" data-space-index="${index}" data-from="${from}" data-to="${to}" data-color="${color}">${color}</div>`
        );
        const elem = document.getElementById(`route-spaces-route${id}-space${index}`);
        elem.dataset.additionalArguments = JSON.stringify(additionalArguments);
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
    Object.values(SPECIFIC_ROUTE_ARGUMENTS).forEach(definition => {
        const input = document.getElementById(definition.input);
        if (definition.checkbox) {
            input.checked = false;
        } else {
            input.value = '';
        }
    });
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
        const additionalArguments = JSON.parse(elem.dataset.additionalArguments ?? '[]');
        Object.entries(SPECIFIC_ROUTE_ARGUMENTS).forEach(([name, definition]) => {
            const input = document.getElementById(definition.input);
            const value = getAdditionalArgument(additionalArguments, name) ?? definition.defaultValue;
            if (definition.checkbox) {
                input.checked = value !== 'null' && Number(value) === Number(elem.dataset.spaceIndex);
            } else {
                input.value = value === 'null' ? '' : value;
            }
        });

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
        `    ${elem.dataset.id} => new City('${elem.dataset.name}', ${elem.dataset.x}, ${elem.dataset.y}${JSON.parse(elem.dataset.additionalArguments ?? '[]').map(argument => `, ${argument}`).join('')}),`
    ).join('\n');
    document.getElementById('cities-export').value = php;
}

function getSpacesOfRoute(routeId) {
    return Array.from(document.querySelectorAll(`[id^="route-spaces-route${routeId}-space"]`));
}

function updateRouteClasses(routeId) {
    getSpacesOfRoute(routeId).forEach((space, index) => {
        const additionalArguments = JSON.parse(space.dataset.additionalArguments ?? '[]');
        const mountain = Number(getAdditionalArgument(additionalArguments, 'mountain') ?? 0);
        space.classList.toggle('locomotive', index < Number(space.dataset.locomotives));
        space.classList.toggle('mountain', index < mountain);
    });
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
            const additionalArguments = JSON.parse(firstSpace.dataset.additionalArguments ?? '[]');
            const routeArguments = [];
            if (firstSpace.dataset.tunnel === 'true') {
                routeArguments.push('tunnel: true');
            }
            if (locomotives > 0) {
                routeArguments.push(`locomotives: ${locomotives}`);
            }
            routeArguments.push(...additionalArguments);
            php += `    ${id} => new Route(${from}, ${to}, ${firstSpace.dataset.color}, [\n`;
            spaces.forEach(space => php += `        new RouteSpace(${space.dataset.x}, ${space.dataset.y}, ${space.dataset.a}),\n`),
            php += `    ]${routeArguments.map(argument => `, ${argument}`).join('')}),\n`;
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

    const additionalArguments = [];
    Object.entries(SPECIFIC_ROUTE_ARGUMENTS).forEach(([name, definition]) => {
        if (definition.checkbox || document.querySelector(`[data-route-argument="${name}"]`).hidden) {
            return;
        }
        const value = document.getElementById(`new-${definition.input}`).value || definition.defaultValue;
        additionalArguments.push(`${name}: ${value}`);
    });

    addRoute(id, selectedRouteFromInput.value, selectedRouteToInput.value, selectedRouteColorInput.value, routes, selectedRouteTunnelInput.checked, selectedRouteLocomotivesInput.value, additionalArguments);
    this.updateRoutesExport();
}
