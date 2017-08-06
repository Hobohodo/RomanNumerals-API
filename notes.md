#Task notes

##Thoughts

For the database I only created the one "conversions" table, and used an index to make finding rows based on the integers easy. I considered using multiple tables for this - one to act as a cache of converted integers (with the most recently converted date) and another to store the counts, but realised this would fail to show an accurate history if the same integer were converted multiple times.

I set the number of characters for the "numeral" field to 32, but did cheat a bit and find that the longest possible conversion was from 3888 with 15 characters at MMMDCCCLXXXVIII.

##What I would do differently

I would most definitely not name the conversion totals table "total" and give the column name "total". It just made naming unnecessarily complex. 

##Running commentary
This is a list of thoughts and comments done while writing the test. Imported from OneNote.

The first half is a long list of failures, with some more constructive thoughts later on.

* My available options are:
  * Host remotely, set up my git repo there and push to it. 
  	* Use a bare repo and a clone that's used to display the site?
  * Set up Vagrant box or php in windows and run locally - may be an issue with sorting out db things.

* Decided to use virtualbox and laravel\homestead to spin up a remote box.

* After creating vagrant box was unable to retrieve a response from the server, this was due to laravel not being installed in the vendor folder. (composer issue)

* Installed composer on windows due to ease of use in future, sadly not compatible with the remote interpreter in PHPStorm.

* Installed PHP 7.1 interpreter, added PHP to the PATH and enabled extensions with some edits to the php.ini

* When running composer discovered an error where Symfony's HttpKernel was not installed correctly - it exists in vendor/composer/b037c860/symfony-http-kernel-c830387/HttpKernelInterface.php but not in vendor/symfony.

* This was due to an issue I thought was solved earlier where running php artisan optimize failed (php not defined). I thought this was fixed by adding php to the PATH since retrying composer did not fail again, but evidently not. Attempted solution: restart computer.

* Solution did not work, turns out I messed up when adding php to the PATH. Composer now installs fine.

* discovered I had not yet enabled virtualization in my BIOS.

* I used the Homestead/Laravel composer component to generate a vagrantfile, and the app is accessible via SSH using localhost:2222

* _At this point I didn't realise I hadn't actually got the server running and thought the issue may have been to do with there not being a default landing page_ Currently working on which class is needed for Route, since there are many possible answers.

Here is where I gave up on Laravel/Homestead. I didn't write anything in the log on tuesday while working this out.

* Tuesday 01/08/17 21:25pm Finally got a server running that shows something! Gave up on homestead/laravel completely, tried running with php artisan serve

* Ran into an issue where everything was returning "not found" errors, found laravel.log and it was pointing to index.php then to the compiled files

```
[2017-08-0120:21:26]production.ERROR:RuntimeException:TheonlysupportedciphersareAES-128-CBCandAES-256-CBCwiththecorrectkeylengths.inD:\Documents\Projects\RomanNumerals-API\bootstrap\cache\compiled.php:13520
   Stacktrace:
   #0D:...\RomanNumerals-API\bootstrap\cache\compiled.php(7927):Illuminate\Encryption\Encrypter->__construct('','AES-256-CBC')
   #1D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1477):Illuminate\Encryption\EncryptionServiceProvider->Illuminate\Encryption\{closure}(Object(Illuminate\Foundation\Application),Array)
   #2D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1433):Illuminate\Container\Container->build(Object(Closure),Array)
   #3D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2011):Illuminate\Container\Container->make('encrypter',Array)
   #4D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1534):Illuminate\Foundation\Application->make('encrypter')
   #5D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1511):Illuminate\Container\Container->resolveClass(Object(ReflectionParameter))
   #6D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1497):Illuminate\Container\Container->getDependencies(Array,Array)
   #7D:...\RomanNumerals-API\bootstrap\cache\compiled.php(1433):Illuminate\Container\Container->build('App\\Http\\Middle...',Array)
   #8D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2011):Illuminate\Container\Container->make('App\\Http\\Middle...',Array)
   #9D:...\RomanNumerals-API\bootstrap\cache\compiled.php(2529):Illuminate\Foundation\Application->make('App\\Http\\Middle...')
   #10D:...\RomanNumerals-API\public\index.php(58):Illuminate\Foundation\Http\Kernel->terminate(Object(Illuminate\Http\Request),Object(Illuminate\Http\Response))
   #11D:...\RomanNumerals-API\server.php(21):require_once('D:\\Documents\\Pr...')
   #12{main}
```

* Did the obvious and googled the exception, discovered that this was likely due to a lack of an application key, and ran `php artisan key:generate`

* Created a home template in blade, it was refreshingly simple to do.

* Installed fractal using composer as it wasn't pre-installed. Not updated the composer.json or composer.lock files though.

* Created a numerals table using artisan migrate, I had to change the environment to work on root though (no changes required for other things).

* In implementing the Roman Numeral conversion, I would have placed the type checking of the value in the function arguments using scalar type hinting, but decided to obey the interface as given.

* I was initially going to have the Numerals table use an "index" on the integer column. This would have interfered with the requirement to view the most recently stored integers, and so was removed.

* I also initially created the "Numerals" table using `php artisan make:migration create_numerals_table --table=numerals` instead of using php `model:make model Numerals -m`. This wasn't really a mistake, just inefficient.

* Realised that effective use of Fractal resources would make it much easier to work with a `totals` table, so going to make one now.