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

# TODO
hint to turn mobile when on portrait? => map is not really bigger, as playerboard will shift right, probably not necessery
translate cities names? confirm with publisher
confirm for route claim? see if publisher is OK
Zoom option : i don't know why it's slow, or strrange, but the movement is not as direct as it should be and it's "exhausting" (for a boardgamer i guess) to drag it around. A good option would be to have it a bit more "out" of the MAP zone, as it won't and will never cover a single part of it. For this map, Vancouver is a bit covered, and i would prefer it won't. Also, having it a little bit shifted out of the map will help see everything better.
Also, i would love to get a special frame displayed when zoomin in, so you never forget that you're displaying it magnified. (love the light hovering effect btw).
For very small screens, i would love a second level zoom, especially for very long mobile screens like mine, where the zooming option is not giving better option than 2 finger/built-in zoom.

fix drag on mobile
https://stackoverflow.com/questions/9251590/prevent-page-scroll-on-drag-in-ios-and-android

bug on double-click? use no-lock instead?

show tiny color cards on destination selection

allow to click on a card then claim a route?
