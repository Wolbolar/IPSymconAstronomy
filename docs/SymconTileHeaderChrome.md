# Symcon Tile Header Chrome

Dieser Workaround hinterlegt den von Symcon gerenderten Kachel-Header optisch, ohne den Header selbst zu stylen.

## Zweck

- heller Hintergrund hinter dem Symcon-Titel links
- heller Kreis hinter dem Symcon-Umschalt-Icon rechts
- freier oberer Bereich, damit die eigene Navigation nicht mit dem Symcon-Header kollidiert

## Verwendete Maße

- oberes Reservieren im Tile-Layout: `padding-top: 56px`
- Title-Pill: `28px` hoch, Breite dynamisch aus dem Instanznamen
- Icon-Kreis: `28x28`
- Positionierung des Chrome-Containers: `top: 14px`, `left/right: 12px`
- rechter Innenversatz für das Symcon-Icon: `right: 4px`

## Dynamische Titelbreite

Die Breite des hellen Hintergrunds hinter dem Symcon-Titel wird aus dem Instanznamen angenähert:

```js
const instanceName = state.instanceName || state.title || "";
const headerTitleWidth = Math.max(116, Math.min(300, Math.round(instanceName.length * 9.4 + 30)));
```

Das ist kein exaktes Messen des Symcon-Headers, sondern ein pragmatischer Näherungswert für die feste Symcon-Schriftgröße.

## HTML

```html
<section class="myTile">
    <div class="myTile__chrome">
        <div class="myTile__chromeTitle" style="width: 172px"></div>
        <div class="myTile__chromeIcon"></div>
    </div>
    <div class="myTile__shell">
        <!-- eigener Tile-Inhalt -->
    </div>
</section>
```

## CSS

```css
.myTile {
    position: relative;
}

.myTile__chrome {
    inset: 14px 12px auto 12px;
    pointer-events: none;
    position: absolute;
    z-index: 3;
}

.myTile__chromeTitle,
.myTile__chromeIcon {
    background: rgba(248, 244, 236, 0.94);
    border: 1px solid rgba(58, 68, 80, 0.12);
    box-shadow: 0 4px 14px rgba(7, 16, 28, 0.12);
    position: absolute;
}

.myTile__chromeTitle {
    border-radius: 999px;
    height: 28px;
    left: 0;
    top: 0;
}

.myTile__chromeIcon {
    border-radius: 999px;
    height: 28px;
    right: 4px;
    top: 0;
    width: 28px;
}

.myTile__shell {
    padding-top: 56px;
    position: relative;
    z-index: 1;
}
```

## Hinweis

Das ist bewusst ein visueller Workaround. Der eigentliche Symcon-Header liegt außerhalb des eigenen HTML-SDK-Inhalts und kann dadurch nicht zuverlässig direkt gestylt werden.
