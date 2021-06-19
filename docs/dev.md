# Dev notes

## Phing targets

![phing targets](images/build.png)

## Running tests

PHPUnit needs a mysql database to run tests, this is handled by `docker-composer`:

```console
phing setup qa:remote
```
