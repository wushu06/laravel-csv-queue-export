# Export

Export large data collection to csv using Laravel Queue.

## Installation

Via Composer

``` bash
$ composer require nour/export
```

## Usage

Create your export class 

````
<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Nour\Export\Interfaces\ExportQueue;

class NourUserExport implements ExportQueue
{

    public function model(): Builder
    {
        return User::query();
    }

    public function rowMapping($row): array
    {
        return [
            'ID' => $row->id,
            'Name' => $row->first_name,
        ];
    }

}
````

Then call the export class inside your controller or anywhere you want

```
\Nour\Export\Facades\Export::export(new \App\Exports\NourExport());
```

### Optionals

you can specify the name and destination of the export file

```
<?php

namespace Nour\Export\Tests\Unit;

use Illuminate\Database\Eloquent\Builder;
use Nour\Export\Interfaces\ExportQueue;

class ExampleExport implements ExportQueue
{
    ....
    public function file()
    {
        return './export.csv';
    }

}

```

you can receive notification when the job has finished or failed by passing email address

```
\Nour\Export\Facades\Export::export(new \App\Exports\NourExport(), 'example@email.com');
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author@email.com instead of using the issue tracker.

## Credits

- Noureddine Latreche

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/nour/export.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nour/export.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nour/export/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/nour/export
[link-downloads]: https://packagist.org/packages/nour/export
[link-travis]: https://travis-ci.org/nour/export
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/nour
[link-contributors]: ../../contributors
