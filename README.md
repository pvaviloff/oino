# OINO.uno 

Install in your laravel project [laracasts/generators](https://github.com/laracasts/Laravel-5-Generators-Extended)
```bash
composer require --dev laracasts/generators
```

### Settings
Create in your project oino.yaml file.
```yaml
settings:
  shell-driver: 'laravel'
  db-execute: 'schema'
  db:
    driver: 'pdo_mysql'
    host: 'localhost'
    dbname: 'homestead'
    port: 3306
    user: 'homestead'
    password: 'secret'
args:
  file: ''
  tab: ''
  project: ''
  dir: ''
  filename: ''
```
db - schema parser use dbal. More details about settings: [Doctrine DBAL Configuration](https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details)

### Import example:
```bash
php oino -c import -o /path/to/settings/oino.yaml --file="path/to/exported/file.json" --tab="tab name" --project="/path/to/project"
```

### Export example:
```bash
php oino -c export -o /path/to/settings/oino.yaml --dir="path/to/export/directory"
```