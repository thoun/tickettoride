# What is this project ? 
This project is an adaptation for BoardGameArena of game Ticket to Ride edited by Days of Wonder.
You can play here : https://boardgamearena.com

# How to install the auto-build stack

## Install builders
Intall node/npm then `npm i` on the root folder to get builders.

## Auto build JS and CSS files
In VS Code, add extension https://marketplace.visualstudio.com/items?itemName=emeraldwalk.RunOnSave and then add to config.json extension part :
```json
        "commands": [
            {
                "match": ".*\\.ts$",
                "isAsync": true,
                "cmd": "npm run build:ts"
            },
            {
                "match": ".*\\.scss$",
                "isAsync": true,
                "cmd": "npm run build:scss"
            }
        ]
    }
```
If you use it for another game, replace `tickettoride` mentions on package.json `build:scss` script and on tsconfig.json `files` property.

## Auto-upload builded files
Also add one auto-FTP upload extension (for example https://marketplace.visualstudio.com/items?itemName=lukasz-wronski.ftp-sync) and configure it. The extension will detected modified files in the workspace, including builded ones, and upload them to remote server.

## Hint
Make sure ftp-sync.json and node_modules are in .gitignore

# How to start PHP unit test
go on tests dir and start execute file, for example `php ./tickettoride.game.test-longest-path.php` / `php ./tickettoride.game.test-destination-completed.php`

# Points of rules
If there is a lot of locomotives remaining in a small set of available cards (for example 3 locomotives in 5 visible cards, no card in discard), we attempt 3 times to replace and if there is still 3 locomotives, we log and let them visible (to avoid an infinite loop).
In case the player cannot pick destination cards nor train car cards, and cannot claim a route, we let him pass.
What if he can only construct ? Does he have to ? -> probably yes, no issue here
What if he can only pick destinations ? Does he have to ? -> asked, can be a negative move for the player

Can you complete the file modules/maps/india/routes.php with all the routes represented as colored rectangles on img/india/map.jpg ?
Each colored rectangle is a RouteSpace, and multiple RouteSpace are forming a Route from one city to another.
The cities id, name and coordinates are noted in modules/maps/india/cities.php