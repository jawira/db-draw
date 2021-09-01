# Diagram sizes

## Mini

```php
$dbDraw = new DbDraw($connection);
$mini   = $dbDraw->generatePuml(DbDraw::MINI); // only table names
```

![mini-diagram](images/mini.png)

## Midi

```php
$dbDraw = new DbDraw($connection);
$midi   = $dbDraw->generatePuml(DbDraw::MIDI); // like mini with columns
```

![midi-diagram](images/midi.png)

## Maxi

```php
$dbDraw = new DbDraw($connection);
$maxi   = $dbDraw->generatePuml(DbDraw::MAXI); // like midi with views
```

![maxi-diagram](images/maxi.png)
