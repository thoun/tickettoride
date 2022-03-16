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
fix drag on mobile
https://stackoverflow.com/questions/9251590/prevent-page-scroll-on-drag-in-ios-and-android

- picking cards
      - the card stays on the display when it is picked and another slide over it
- train positioning
- fix dragging the cards (Portland San Francisco is particularly off)
- it happened that some cards from display are not clickable
- add card icon in the log instead of the text
- longest route - it is awarded to the shortest
- sluggish performance (chrome on macbook air)
- sounds (especially when showing accomplished and failed tickets)
- Portland / San Francisco - trains on the right were on top of the trains on the left (blue on the right and red on the left)

Add more players from the list from Adrien
