# Dev notes

## Phing targets

![phing targets](images/build.svg)

## Running tests

PHPUnit needs a mysql database to run tests, this is handled
by `docker compose`:

```console
phing setup qa
```
