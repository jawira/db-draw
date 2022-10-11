# Diagram sizes

## Mini

Mini diagrams are composed by table names only.

```php
$dbDraw = new DbDraw($connection);
$mini   = $dbDraw->generatePuml(DbDraw::MINI);
```

![mini-diagram](images/mini.png)

## Midi

Midi diagrams displays columns names and type.

```php
$dbDraw = new DbDraw($connection);
$midi   = $dbDraw->generatePuml(DbDraw::MIDI);
```

![midi-diagram](images/midi.png)

## Maxi

Same as Midi, but Views are also displayed.

```php
$dbDraw = new DbDraw($connection);
$maxi   = $dbDraw->generatePuml(DbDraw::MAXI);
```

![maxi-diagram](images/maxi.png)
