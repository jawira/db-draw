# Filters

When generating database diagrams it can happen to work with very large
databases, in such case displaying all the tables at once is not convenient.
This is when the filters can be useful. `DbDraw` has two filter mechanisms:
`include` and `exclude`.

## Using filters

You can pass the tables to include and exclude as parameters to
`\Jawira\DbDraw\DbDraw::generatePuml` method.

```php
$puml = $drawer->generatePuml(size: Size::Mini, theme: 'plain', include: [], exclude: []);
```

The `include` parameters is an array containing the names to tables to include
in the diagram. Important, when the `include` parameter is an empty array all
the tables are printed.

Examples:

```php
// Generate a diagram with all tables
$puml = $drawer->generatePuml(Size::Mini, 'plain', [], []);

// Diagram will contain two tables
$puml = $drawer->generatePuml(Size::Mini, 'plain', ['Course', 'Student'], []);
```

The `exclude` parameter is an array containing the names of the tables to remove
in a diagram. When the array is empty no table is removed.

Examples:

```php
// Two tables are removed from the diagram
$puml = $drawer->generatePuml(Size::Mini, 'plain', [], ['CreditCard', 'Inscription']);
```

Important, `include` and `exclude` can be used at the same time.

```php
// Diagram will only contain the Teacher table
$puml = $drawer->generatePuml(Size::Mini, 'plain', ['Faculty', 'Teacher'], ['Faculty']);
```

## Wildcards

Use wildcards instead of declaring table names one by one.

* Use `?` to match a single characters.
* Use `*` to match multiple characters.

Examples:

```php
// Display all tables starting with "billing."
$puml = $drawer->generatePuml(Size::Mini, 'plain', ['billing.*'], []);

// Remove tables like "report_2020", "report_2021", etc.
$puml = $drawer->generatePuml(Size::Mini, 'plain', [], ['report_202?']);
```
